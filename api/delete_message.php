<?php
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Yetkisiz erişim.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek yöntemi.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$messageId = $input['id'] ?? null;

if (!$messageId) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Mesaj ID\'si eksik.']);
    exit;
}

$messagesFilePath = __DIR__ . '/../data/messages.json';
$messages = [];

if (file_exists($messagesFilePath)) {
    $fileContent = file_get_contents($messagesFilePath);
    $messages = json_decode($fileContent, true) ?: [];
}

$initialCount = count($messages);
$messages = array_filter($messages, function ($message) use ($messageId) {
    return $message['id'] !== $messageId;
});
$messages = array_values($messages);

if (count($messages) < $initialCount) {
    file_put_contents($messagesFilePath, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['status' => 'success', 'message' => 'Mesaj başarıyla silindi.']);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Mesaj bulunamadı.']);
}
?>