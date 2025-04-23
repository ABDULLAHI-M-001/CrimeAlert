<?php
// crime_analytics.php - Crime Reports Analytics Dashboard
session_start();
require 'config.php';

// Ensure only admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access denied.");
}

// Fetch Crime Report Statistics
$stmtTotal = $pdo->query("SELECT COUNT(*) AS total_reports FROM reports");
$totalReports = $stmtTotal->fetch()['total_reports'];

$stmtResolved = $pdo->query("SELECT COUNT(*) AS resolved_reports FROM reports WHERE status = 'Resolved'");
$resolvedReports = $stmtResolved->fetch()['resolved_reports'];

$stmtPending = $pdo->query("SELECT COUNT(*) AS pending_reports FROM reports WHERE status = 'Pending'");
$pendingReports = $stmtPending->fetch()['pending_reports'];

$stmtUnderInvestigation = $pdo->query("SELECT COUNT(*) AS under_investigation FROM reports WHERE status = 'Under Investigation'");
$underInvestigation = $stmtUnderInvestigation->fetch()['under_investigation'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crime Analytics Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Crime Analytics Dashboard</h2>
    <canvas id="crimeChart" width="400" height="200"></canvas>
    <script>
        var ctx = document.getElementById('crimeChart').getContext('2d');
        var crimeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Reports', 'Resolved Cases', 'Pending Cases', 'Under Investigation'],
                datasets: [{
                    label: 'Crime Report Statistics',
                    data: [<?= $totalReports ?>, <?= $resolvedReports ?>, <?= $pendingReports ?>, <?= $underInvestigation ?>],
                    backgroundColor: ['blue', 'green', 'orange', 'red']
                }]
            },
        });
    </script>
</body>
</html>
