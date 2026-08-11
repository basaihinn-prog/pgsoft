<?php
function panelTable(string $table): string {
    $tables = ['agents', 'calls', 'jsons', 'users'];

    if (!in_array($table, $tables, true)) {
        throw new InvalidArgumentException('Invalid table.');
    }

    return $table;
}

function counting($table, $what) {
    global $conn;
    $statement = $conn->query('SELECT COUNT(1) FROM ' . panelTable($table));
    return (int) $statement->fetchColumn();
}

function getById($table, $id) {
    global $conn;
    $statement = $conn->prepare('SELECT * FROM ' . panelTable($table) . ' WHERE id = :id');
    $statement->execute(['id' => (int) $id]);
    return $statement->fetch();
}

function getByAg($table) {
    global $conn;
    $statement = $conn->prepare('SELECT * FROM ' . panelTable($table) . ' WHERE agentid = :agentid');
    $statement->execute(['agentid' => (int) $_SESSION['admin_id']]);
    return $statement->fetch();
}

function getAll($table) {
    global $conn;
    $statement = $conn->prepare('SELECT * FROM ' . panelTable($table) . ' WHERE agentid = :agentid');
    $statement->execute(['agentid' => (int) $_SESSION['admin_id']]);
    return $statement->fetchAll();
}

function getAG($table) {
    global $conn;
    $statement = $conn->prepare('SELECT * FROM ' . panelTable($table) . ' WHERE id = :id');
    $statement->execute(['id' => (int) $_SESSION['admin_id']]);
    return $statement->fetchAll();
}
