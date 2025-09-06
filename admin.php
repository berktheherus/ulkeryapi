<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$messagesFilePath = __DIR__ . '/data/messages.json';
$messages = [];
if (file_exists($messagesFilePath)) {
    $fileContent = file_get_contents($messagesFilePath);
    if ($fileContent) {
        $messages = json_decode($fileContent, true);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Contact Messages</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <div class="nav-brand">
                    <h1>Ülker Yapı - Admin</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="admin.php?logout=true" class="nav-link">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="admin-main">
        <div class="container">
            <div class="messages-header">
                <h2>Contact Form Messages</h2>
                <p>Here are the messages submitted through the contact form.</p>
            </div>

            <?php if (empty($messages)): ?>
                <div class="no-messages">
                    <p>There are currently no messages.</p>
                </div>
            <?php else: ?>
                <div class="messages-container">
                    <?php foreach ($messages as $message): ?>
                        <div class="message-card">
                            <div class="message-header">
                                <div class="message-sender">
                                    <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                                    &lt;<?php echo htmlspecialchars($message['email']); ?>&gt;
                                </div>
                                <div class="message-timestamp">
                                    <?php echo htmlspecialchars($message['timestamp']); ?>
                                </div>
                            </div>
                            <div class="message-subject">
                                <h4><?php echo htmlspecialchars($message['subject']); ?></h4>
                            </div>
                            <div class="message-body">
                                <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
