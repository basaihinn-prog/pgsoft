#!/usr/bin/env bash
set -Eeuo pipefail

APP_ROOT="${APP_ROOT:-/var/www/pgplay}"
API_PORT="${API_PORT:-3010}"
DB_SCHEMA="${DB_SCHEMA:-pgplay}"
ENABLE_TLS="${ENABLE_TLS:-0}"
TLS_EMAIL="${TLS_EMAIL:-}"
REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

if [[ "$(id -u)" -ne 0 ]]; then
  echo "Run this script as root." >&2
  exit 1
fi

: "${DATABASE_URL:?Set DATABASE_URL to the application PostgreSQL URL, including ?schema=${DB_SCHEMA}}"
: "${DATABASE_ADMIN_URL:?Set DATABASE_ADMIN_URL to an admin PostgreSQL URL for schema creation}"

if [[ ! "$DB_SCHEMA" =~ ^[A-Za-z_][A-Za-z0-9_]*$ ]]; then
  echo "DB_SCHEMA must be a valid PostgreSQL identifier." >&2
  exit 1
fi

if [[ ! -d "$REPO_ROOT/api" || ! -d "$REPO_ROOT/painel" ]]; then
  echo "Run this script from the project repository." >&2
  exit 1
fi

export DEBIAN_FRONTEND=noninteractive
apt-get update
apt-get install -y nginx git nodejs npm postgresql-client php-fpm php-pgsql php-mbstring php-xml php-curl certbot python3-certbot-nginx rsync

id -u pgplay >/dev/null 2>&1 || useradd --system --home "$APP_ROOT" --shell /usr/sbin/nologin pgplay
mkdir -p "$APP_ROOT" /etc/pgplay
rsync -a --exclude='.git' --exclude='node_modules' --exclude='dist' "$REPO_ROOT/" "$APP_ROOT/"
chown -R pgplay:pgplay "$APP_ROOT"

psql "$DATABASE_ADMIN_URL" -v ON_ERROR_STOP=1 -c "CREATE SCHEMA IF NOT EXISTS \"$DB_SCHEMA\";"

cat > /etc/pgplay/api.env <<EOF
NODE_ENV=production
PORT=$API_PORT
DATABASE_URL=$DATABASE_URL
PANEL_ORIGIN=https://panel.pgplay.online
GAMES_ORIGIN=https://games.pgplay.online
EOF
chown root:pgplay /etc/pgplay/api.env
chmod 640 /etc/pgplay/api.env

cd "$APP_ROOT/api"
runuser -u pgplay -- npm ci
runuser -u pgplay -- npm run build

if [[ "${APPLY_PRISMA_SCHEMA:-1}" == "1" ]]; then
  runuser -u pgplay -- env DATABASE_URL="$DATABASE_URL" npx prisma db push
fi

PHP_FPM_SOCKET="$(find /run/php -maxdepth 1 -type s -name 'php*-fpm.sock' -print -quit)"
if [[ -z "$PHP_FPM_SOCKET" ]]; then
  echo "No PHP-FPM socket found." >&2
  exit 1
fi
sed "s#unix:/run/php/php-fpm.sock#unix:$PHP_FPM_SOCKET#g; s#127.0.0.1:3010#127.0.0.1:$API_PORT#g" \
  "$APP_ROOT/deploy/nginx-pgplay.conf" > /etc/nginx/sites-available/pgplay.conf
ln -sfn /etc/nginx/sites-available/pgplay.conf /etc/nginx/sites-enabled/pgplay.conf
rm -f /etc/nginx/sites-enabled/default

cp "$APP_ROOT/deploy/pgplay-api.service" /etc/systemd/system/pgplay-api.service
sed -i "s/^EnvironmentFile=.*/EnvironmentFile=\/etc\/pgplay\/api.env/" /etc/systemd/system/pgplay-api.service
systemctl daemon-reload
systemctl enable --now pgplay-api
nginx -t
systemctl reload nginx

if [[ "$ENABLE_TLS" == "1" ]]; then
  : "${TLS_EMAIL:?Set TLS_EMAIL when ENABLE_TLS=1}"
  certbot --nginx --non-interactive --agree-tos --email "$TLS_EMAIL" \
    -d pgplay.online -d www.pgplay.online -d panel.pgplay.online \
    -d games.pgplay.online -d api.pgplay.online --redirect
fi

systemctl --no-pager --full status pgplay-api
curl --fail --silent "http://127.0.0.1:$API_PORT/health"
printf '\nDeployment completed.\n'
