<?php
require 'config.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="crime_reports.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['Title', 'Type', 'Location', 'Date', 'Anonymous']);

$stmt = $pdo->query("SELECT title, crime_type, location, created_at, anonymous FROM reports ORDER BY created_at DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $row['anonymous'] = $row['anonymous'] ? 'Yes' : 'No';
    fputcsv($output, $row);
}

fclose($output);
