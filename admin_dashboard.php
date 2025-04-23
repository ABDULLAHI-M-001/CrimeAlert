<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch reports
$stmt = $pdo->query("SELECT * FROM reports ORDER BY created_at DESC");
$reports = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #343a40; color: white; }
        .export-btn {
            margin-top: 10px;
            background: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .map-container {
            height: 400px;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
</head>
<body>
<h2>Admin Dashboard - All Crime Reports</h2>

<button class="export-btn" onclick="window.location.href='export_csv.php'">Export as CSV</button>

<table>
    <tr>
        <th>Title</th>
        <th>Type</th>
        <th>Location</th>
        <th>Date</th>
        <th>Anonymous?</th>
        <th>File</th>
    </tr>
    <?php foreach ($reports as $report): ?>
    <tr>
        <td><?= htmlspecialchars($report['title']) ?></td>
        <td><?= htmlspecialchars($report['crime_type']) ?></td>
        <td><?= htmlspecialchars($report['location']) ?></td>
        <td><?= $report['created_at'] ?></td>
        <td><?= $report['anonymous'] ? 'Yes' : 'No' ?></td>
        <td>
            <?php if ($report['evidence_file']): ?>
                <a href="<?= $report['evidence_file'] ?>" target="_blank">View</a>
            <?php else: ?>
                None
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<div class="map-container" id="map"></div>

<script>
function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 5,
        center: { lat: 9.0820, lng: 8.6753 } // Centered on Nigeria
    });

    var markers = [
        <?php foreach ($reports as $report):
            if (!empty($report['latitude']) && !empty($report['longitude'])):
        ?>
        {
            position: { lat: <?= $report['latitude'] ?>, lng: <?= $report['longitude'] ?> },
            title: "<?= htmlspecialchars($report['title']) ?>"
        },
        <?php endif; endforeach; ?>
    ];

    markers.forEach(function(markerData) {
        var marker = new google.maps.Marker({
            position: markerData.position,
            map: map,
            title: markerData.title
        });
    });
}

window.onload = initMap;
</script>
</body>
</html>
