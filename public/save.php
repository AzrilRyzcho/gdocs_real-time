<?php
// Ultra-fast save endpoint — bypass Laravel untuk kecepatan maksimal

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

$input = json_decode(file_get_contents('php://input'), true);
$docId = $_GET['id'] ?? null;

if (!$docId || !is_numeric($docId) || !$input) {
    echo json_encode(['error' => 'invalid']);
    exit;
}

$host = '127.0.0.1';
$db   = 'gdocs';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'db']);
    exit;
}

$stmt = $pdo->prepare('UPDATE documents SET content=?, title=?, last_editor_name=?, last_editor_color=?, last_edited_at=NOW(), updated_at=NOW() WHERE id=?');
$stmt->execute([
    $input['content'] ?? '',
    $input['title'] ?? 'Tanpa judul',
    $input['editor_name'] ?? 'Anonim',
    $input['color'] ?? '#6366f1',
    $docId
]);

echo json_encode(['status' => 'ok']);
