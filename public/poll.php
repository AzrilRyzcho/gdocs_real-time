<?php
// Ultra-fast polling endpoint — bypass Laravel untuk kecepatan maksimal
// Langsung query MySQL tanpa framework overhead

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$docId = $_GET['id'] ?? null;
if (!$docId || !is_numeric($docId)) {
    echo json_encode(['error' => 'no id']);
    exit;
}

// Koneksi database langsung
$host = '127.0.0.1';
$db   = 'gdocs';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'db']);
    exit;
}

$stmt = $pdo->prepare('SELECT content, title, updated_at, last_editor_name, last_editor_color FROM documents WHERE id = ? LIMIT 1');
$stmt->execute([$docId]);
$doc = $stmt->fetch();

if (!$doc) {
    echo json_encode(['error' => 'not found']);
    exit;
}

echo json_encode([
    'content'          => $doc['content'],
    'title'            => $doc['title'],
    'updated_at'       => strtotime($doc['updated_at']),
    'last_editor_name' => $doc['last_editor_name'],
    'last_editor_color'=> $doc['last_editor_color'],
]);
