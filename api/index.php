<?php
header('Content-Type: application/json');

// Allow requests from any origin. In a production environment, you might want to restrict this.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle pre-flight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload.']);
    exit;
}

// Basic validation
$name = $input['name'] ?? '';
$email = $input['email'] ?? '';
$subject = $input['subject'] ?? '';
$message = $input['message'] ?? '';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit;
}

// Sanitize input to prevent XSS
$sanitized_name = htmlspecialchars(strip_tags($name));
$sanitized_email = htmlspecialchars(strip_tags($email));
$sanitized_subject = htmlspecialchars(strip_tags($subject));
$sanitized_message = htmlspecialchars(strip_tags($message));

$newMessage = [
    'id' => uniqid(),
    'name' => $sanitized_name,
    'email' => $sanitized_email,
    'subject' => $sanitized_subject,
    'message' => $sanitized_message,
    'timestamp' => date('Y-m-d H:i:s')
];

$messagesFilePath = __DIR__ . '/../data/messages.json';

$messages = [];
if (file_exists($messagesFilePath)) {
    $fileContent = file_get_contents($messagesFilePath);
    if ($fileContent) {
        $messages = json_decode($fileContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Handle JSON decode error, maybe the file is corrupt
            $messages = [];
        }
    }
}

// Add the new message to the beginning of the array
array_unshift($messages, $newMessage);

if (file_put_contents($messagesFilePath, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully!']);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Failed to save the message.']);
}

?>
