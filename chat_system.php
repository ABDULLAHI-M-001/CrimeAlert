<?php
// chat_system.php - Live Chat for Users and Law Enforcement
session_start();
require 'config.php';

// Fetch Chat Messages
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['fetch_messages'])) {
    $stmt = $pdo->query("SELECT chat_messages.*, users.username FROM chat_messages JOIN users ON chat_messages.user_id = users.id ORDER BY created_at DESC LIMIT 20");
    $messages = $stmt->fetchAll();
    
    foreach ($messages as $msg) {
        echo "<p><strong>" . htmlspecialchars($msg['username']) . "</strong>: " . htmlspecialchars($msg['message']) . " <em>" . $msg['created_at'] . "</em></p>";
    }
}

// Send Chat Message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $user_id = $_SESSION['user_id'];
    $message = htmlspecialchars($_POST['message']);
    
    $stmt = $pdo->prepare("INSERT INTO chat_messages (user_id, message, created_at) VALUES (?, ?, NOW())");
    if ($stmt->execute([$user_id, $message])) {
        echo "Message sent successfully!";
    } else {
        echo "Error sending message.";
    }
}
?>
