<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/connect.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['cat'] ?? '') !== 'users') {
    http_response_code(400);
    echo json_encode(['message' => 'Solicitação inválida.']);
    exit;
}

$action = $_POST['act'] ?? '';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$fields = ['username', 'token', 'atk', 'saldo', 'valorapostado', 'valordebitado', 'valorganho', 'rtp', 'agentid'];
$data = [];

foreach ($fields as $field) {
    $data[$field] = trim((string) ($_POST[$field] ?? ''));
}

$data['is_influencer'] = filter_var($_POST['isinfluencer'] ?? false, FILTER_VALIDATE_BOOLEAN);
$data['agentid'] = (int) $data['agentid'];

if ($data['username'] === '' || !$data['agentid']) {
    http_response_code(422);
    echo json_encode(['message' => 'Usuário e agente são obrigatórios.']);
    exit;
}

if ($data['agentid'] !== (int) $_SESSION['admin_id']) {
    http_response_code(403);
    echo json_encode(['message' => 'Acesso não autorizado.']);
    exit;
}

try {
    if ($action === 'add') {
        $columns = implode(', ', $fields);
        $columns .= ', is_influencer';
        $placeholders = implode(', ', array_map(fn ($field) => ':' . $field, $fields));
        $statement = $conn->prepare("INSERT INTO users ($columns) VALUES ($placeholders, :is_influencer)");
        $statement->execute($data);
    } elseif ($action === 'edit' && $id) {
        $assignments = implode(', ', array_map(fn ($field) => "$field = :$field", $fields));
        $data['id'] = $id;
        $statement = $conn->prepare("UPDATE users SET $assignments, is_influencer = :is_influencer WHERE id = :id");
        $statement->execute($data);
    } elseif ($action === 'delete' && $id) {
        $statement = $conn->prepare('DELETE FROM users WHERE id = :id AND agentid = :agentid');
        $statement->execute(['id' => $id, 'agentid' => (int) $_SESSION['admin_id']]);
    } else {
        throw new InvalidArgumentException('Ação inválida.');
    }

    echo json_encode(['message' => 'Dados atualizados com sucesso.', 'redirect' => './users.php']);
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    http_response_code(400);
    echo json_encode(['message' => 'Não foi possível atualizar os dados.']);
}
