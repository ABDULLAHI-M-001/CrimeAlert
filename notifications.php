<?php
// emergency_alert.php - Emergency Alert System
session_start();
require 'config.php';

// Fetch Latest Emergency Alerts
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['fetch_alerts'])) {
    $stmt = $pdo->query("SELECT * FROM notifications WHERE message LIKE 'EMERGENCY:%' ORDER BY created_at DESC LIMIT 5");
    $alerts = $stmt->fetchAll();
    
    foreach ($alerts as $alert) {
        echo "<p><strong>ALERT:</strong> " . htmlspecialchars($alert['message']) . " <em>" . $alert['created_at'] . "</em></p>";
    }
}

// Send Emergency Alert (Admin Only)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_alert'])) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        die("Access denied.");
    }
    $message = "EMERGENCY: " . htmlspecialchars($_POST['alert_message']);
    
    $stmt = $pdo->prepare("INSERT INTO notifications (message, created_at) VALUES (?, NOW())");
    if ($stmt->execute([$message])) {
        echo "Emergency alert sent successfully!";
    } else {
        echo "Error sending emergency alert.";
    }
}
?>
