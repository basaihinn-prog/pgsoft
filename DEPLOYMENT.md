# Production VPS deployment

This guide deploys the project on an Ubuntu 22.04 or 24.04 VPS using Nginx, PHP-FPM, Node.js 20, PostgreSQL, and systemd.

## Application layout

The project has three production services:

| Hostname | Purpose | Source directory |
| --- | --- | --- |
| `pgplay.online`, `www.pgplay.online`, `panel.pgplay.online` | PHP panel | `painel/` |
| `games.pgplay.online` | Static game assets | `api/public/` |
| `api.pgplay.online` | NestJS API | `api/` |

The PHP panel reads game folders from `../api/public/`. Keep both folders under the same parent directory:

```text
/var/www/pgplay/
├── api/
│   ├── dist/
│   ├── prisma/
│   └── public/
└── painel/
```

## Before deployment

1. Point A/AAAA DNS records for all four hostnames to the VPS.
2. Ensure inbound ports `80` and `443` are allowed by the provider firewall and UFW. Do not expose port `3010` publicly.
3. Provision PostgreSQL before starting the app. The repository contains a Prisma schema but no committed Prisma migrations, so import or create the intended database schema through an approved, tested database-provisioning process before going live.
4. Keep secrets out of Git. Do not deploy the database test utilities `painel/test-db.php`, `painel/setup-test-agent.php`, or `painel/create_test_agent.php`.

## 1. Install system packages

```bash
sudo apt update
sudo apt upgrade -y
sudo apt install -y nginx postgresql-client php-fpm php-pgsql certbot python3-certbot-nginx curl ca-certificates gnupg
```

Install Node.js 20 LTS using the distribution package source approved for your environment, then verify:

```bash
node --version
npm --version
php --version
php -m | grep -E 'PDO|pdo_pgsql'
nginx -v
```

The `pdo_pgsql` extension is required for the PHP panel.

## 2. Create the deployment user and application directory

Use a non-root account that owns the application files and runs the Node service:

```bash
sudo adduser --disabled-password --gecos "" pgplay
sudo mkdir -p /var/www/pgplay
sudo chown pgplay:www-data /var/www/pgplay
sudo chmod 750 /var/www/pgplay
```

Deploy the repository into `/var/www/pgplay` so that `/var/www/pgplay/api` and `/var/www/pgplay/painel` exist. The PHP-FPM worker must be able to traverse the directory:

```bash
sudo chown -R pgplay:www-data /var/www/pgplay
sudo find /var/www/pgplay -type d -exec chmod 750 {} \;
sudo find /var/www/pgplay -type f -exec chmod 640 {} \;
```

Do not make the repository or its environment files world-readable.

## 3. Configure production environment variables

Create a root-owned environment file for the API and PHP-FPM. Replace every placeholder with the production value:

```bash
sudo install -d -m 750 /etc/pgplay
sudo tee /etc/pgplay/production.env > /dev/null <<'EOF'
PORT=3010
DATABASE_URL=postgresql://APP_USER:APP_PASSWORD@DB_HOST:5432/DB_NAME?schema=pgplay&sslmode=require
PANEL_ORIGIN=https://panel.pgplay.online
GAMES_ORIGIN=https://games.pgplay.online
EOF
sudo chown root:pgplay /etc/pgplay/production.env
sudo chmod 640 /etc/pgplay/production.env
```

`PORT` must remain `3010`: the Nginx API proxy uses `127.0.0.1:3010`. The sample configuration is in `deploy/production.env.example`.

For a database hosted on the VPS, use `127.0.0.1` as `DB_HOST`; do not expose PostgreSQL to the internet. For a managed PostgreSQL database, keep `sslmode=require` when the provider requires TLS.

## 4. Build the Node API

Run dependency installation and the build as the deployment user:

```bash
sudo -u pgplay -H bash
cd /var/www/pgplay/api
npm install
npx prisma generate
npm run build
exit
```

The project has a `yarn.lock` but no populated npm lockfile. Standardize and commit a tested lockfile before using `npm ci` for reproducible releases. Until then, use the package manager selected by the release process and validate the resulting build.

## 5. Create the systemd API service

Create `/etc/systemd/system/pgplay-api.service`:

```ini
[Unit]
Description=pgplay NestJS API
After=network-online.target
Wants=network-online.target

[Service]
Type=simple
User=pgplay
Group=pgplay
WorkingDirectory=/var/www/pgplay/api
EnvironmentFile=/etc/pgplay/production.env
ExecStart=/usr/bin/node /var/www/pgplay/api/dist/main.js
Restart=on-failure
RestartSec=5
NoNewPrivileges=true
PrivateTmp=true
ProtectHome=true

[Install]
WantedBy=multi-user.target
```

Confirm the Node executable path with `command -v node`, then enable and start the service:

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now pgplay-api
sudo systemctl status pgplay-api --no-pager
curl http://127.0.0.1:3010/health
```

A successful health response is:

```json
{"status":"ok"}
```

Use `sudo journalctl -u pgplay-api -f` to inspect API logs.

## 6. Make environment variables available to PHP-FPM

PHP-FPM does not automatically inherit variables from an SSH shell. Add the production environment file to the active pool configuration. Determine the installed pool path, such as `/etc/php/8.3/fpm/pool.d/www.conf`, and add:

```ini
env[DATABASE_URL] = "postgresql://APP_USER:APP_PASSWORD@DB_HOST:5432/DB_NAME?schema=pgplay&sslmode=require"
env[GAMES_ORIGIN] = "https://games.pgplay.online"
```

Use the same `DATABASE_URL` as `/etc/pgplay/production.env`. Restrict the pool configuration file so that it is readable only by root and the service account, then restart the installed PHP-FPM service. For example:

```bash
sudo systemctl restart php8.3-fpm
sudo systemctl status php8.3-fpm --no-pager
```

Adjust `php8.3-fpm` to match the PHP version installed on the VPS.

In production PHP configuration, set `display_errors = Off` and `log_errors = On`. The application should log errors without returning database details or paths to visitors.

## 7. Configure Nginx

Copy `deploy/nginx-pgplay.conf` to `/etc/nginx/sites-available/pgplay` and replace the PHP-FPM socket with the socket on the VPS. For example, Ubuntu with PHP 8.3 normally uses `/run/php/php8.3-fpm.sock`.

Before enabling the site, add the following protections to both PHP-panel server blocks, ahead of the generic PHP location:

```nginx
location ~ /\. {
    deny all;
}

location ~* \.(?:env|log|sql|bak)$ {
    deny all;
}
```

Add a cache policy to the static game server:

```nginx
location ~* \.(?:css|js|png|jpg|jpeg|gif|svg|webp|woff|woff2)$ {
    expires 7d;
    add_header Cache-Control "public";
    try_files $uri =404;
}
```

Enable and validate the configuration:

```bash
sudo ln -s /etc/nginx/sites-available/pgplay /etc/nginx/sites-enabled/pgplay
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

Confirm Nginx can only reach the API locally. The systemd service listens on `3010`; no Nginx configuration or firewall rule should expose that port directly.

## 8. Enable HTTPS

Request certificates after all DNS records resolve to the VPS:

```bash
sudo certbot --nginx \
  -d pgplay.online \
  -d www.pgplay.online \
  -d panel.pgplay.online \
  -d games.pgplay.online \
  -d api.pgplay.online
```

Choose the redirect option so all HTTP requests are redirected to HTTPS. Test renewal:

```bash
sudo certbot renew --dry-run
```

## 9. Configure the firewall

Allow only SSH, HTTP, and HTTPS. Keep the SSH rule in place before enabling UFW:

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
sudo ufw status verbose
```

## 10. Post-deployment checks

Run these checks from the VPS after TLS is active:

```bash
curl -fsS https://api.pgplay.online/health
curl -I https://games.pgplay.online/125/index.html
curl -I https://panel.pgplay.online/
sudo nginx -t
sudo systemctl is-active pgplay-api
sudo systemctl is-active nginx
```

Then verify in a browser:

1. The panel login is served over HTTPS.
2. A valid login creates a session and reaches the protected panel.
3. The game list loads and opens a game from `https://games.pgplay.online`.
4. The API health endpoint returns `{"status":"ok"}`.
5. The blocked test utility paths return `404`.

## Releases and rollback

For each release:

1. Back up the PostgreSQL database and `/etc/pgplay/production.env` securely before changing code or schema.
2. Update the repository in `/var/www/pgplay` using the approved release process.
3. Run `npm install`, `npx prisma generate`, and `npm run build` in `api/` as `pgplay`.
4. Run the tested database migration or schema update, if the release includes one.
5. Restart the API with `sudo systemctl restart pgplay-api`.
6. Run the post-deployment checks above.

Keep the previous release available until validation completes. Roll back by restoring the previous code release and database backup only when the corresponding migration rollback has been tested.

## Operations checklist

- Monitor `pgplay-api` with systemd and collect `journalctl` logs.
- Monitor certificate renewal and HTTPS expiry.
- Back up PostgreSQL regularly and test restoration.
- Rotate database credentials if they were ever exposed.
- Keep the OS, Node.js, PHP-FPM, Nginx, and dependencies patched.
- Review `painel/adm/` before exposing it: it contains older connection and authentication code that should not share production credentials unless it has been audited.
