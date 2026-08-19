<?php
$databaseUrl = getenv('DATABASE_URL');

if (!$databaseUrl) {
    throw new RuntimeException('DATABASE_URL is not configured.');
}

$database = parse_url($databaseUrl);
if (!$database || empty($database['host']) || empty($database['path'])) {
    throw new RuntimeException('DATABASE_URL must be a valid PostgreSQL connection URL.');
}

$port = $database['port'] ?? 5432;
$dbname = ltrim($database['path'], '/');
$dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s', $database['host'], $port, $dbname);
$options = [];

if (!empty($database['query'])) {
    parse_str($database['query'], $options);
    if (($options['sslmode'] ?? '') === 'require') {
        $dsn .= ';sslmode=require';
    }
}

$conn = new PDO($dsn, urldecode($database['user'] ?? ''), urldecode($database['pass'] ?? ''), [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

if (!empty($options['schema'])) {
    $schema = $options['schema'];
    if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $schema)) {
        throw new RuntimeException('Invalid PostgreSQL schema name.');
    }
    $conn->exec('SET search_path TO "' . $schema . '"');
}
