<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/connect.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['cat'] ?? '') !== 'agents') {
    http_response_code(400);
    echo json_encode(['message' => 'Solicitação inválida.']);
    exit;
}

$action = $_POST['act'] ?? '';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$fields = [
    'agentcode', 'senha', 'saldo', 'agentToken', 'secretKey', 'probganho', 'probbonus',
    'probganhortp', 'probganhoinfluencer', 'probbonusinfluencer', 'probganhosaldo',
    'probganhoaposta', 'callbackurl', 'limitadorchicky',
];
$data = [];

foreach ($fields as $field) {
    $data[$field] = trim((string) ($_POST[$field] ?? ''));
}

if ($data['agentcode'] === '' || $data['senha'] === '') {
    http_response_code(422);
    echo json_encode(['message' => 'Agent Code e senha são obrigatórios.']);
    exit;
}

try {
    if (in_array($action, ['edit', 'delete'], true) && $id !== (int) $_SESSION['admin_id']) {
        throw new InvalidArgumentException('Ação inválida.');
    }

    if ($action === 'add') {
        $columns = implode(', ', array_map(fn ($field) => '"' . $field . '"', $fields));
        $placeholders = implode(', ', array_map(fn ($field) => ':' . $field, $fields));
        $statement = $conn->prepare("INSERT INTO agents ($columns) VALUES ($placeholders)");
        $statement->execute($data);
    } elseif ($action === 'edit' && $id) {
        $assignments = implode(', ', array_map(fn ($field) => '"' . $field . '" = :' . $field, $fields));
        $data['id'] = $id;
        $statement = $conn->prepare("UPDATE agents SET $assignments WHERE id = :id");
        $statement->execute($data);
    } elseif ($action === 'delete' && $id) {
        $statement = $conn->prepare('DELETE FROM agents WHERE id = :id');
        $statement->execute(['id' => $id]);
    } else {
        throw new InvalidArgumentException('Ação inválida.');
    }

    echo json_encode(['message' => 'Dados atualizados com sucesso.', 'redirect' => './agents.php']);
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    http_response_code(400);
    echo json_encode(['message' => 'Não foi possível atualizar os dados.']);
}
