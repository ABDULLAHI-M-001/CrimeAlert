<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['report_crime'])) {
    $title = htmlspecialchars($_POST['title']);
    $crime_type = htmlspecialchars($_POST['crime_type']);
    $description = htmlspecialchars($_POST['description']);
    $location = htmlspecialchars($_POST['location']);
    $crime_time = htmlspecialchars($_POST['crime_time']);
    $has_suspect = isset($_POST['has_suspect']) ? 1 : 0;
    $suspect_armed = isset($_POST['suspect_armed']) ? 1 : 0;
    $anonymous = isset($_POST['anonymous']) ? 1 : 0;
    $user_id = $anonymous ? null : $_SESSION['user_id'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $evidence_path = null;
    if (!empty($_FILES['evidence_file']['name'])) {
        $target_dir = "uploads/";
        $evidence_path = $target_dir . basename($_FILES["evidence_file"]["name"]);
        move_uploaded_file($_FILES["evidence_file"]["tmp_name"], $evidence_path);
    }

    $stmt = $pdo->prepare("INSERT INTO reports (user_id, title, crime_type, description, location, latitude, longitude, anonymous, evidence_file, crime_time, has_suspect, suspect_armed, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    if ($stmt->execute([$user_id, $title, $crime_type, $description, $location, $latitude, $longitude, $anonymous, $evidence_path, $crime_time, $has_suspect, $suspect_armed])) {
        echo "<p style='color: green;'>Crime report submitted successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: Could not submit report.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Crime</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; display: flex; justify-content: center; align-items: center; height: auto; padding: 20px; }
        .form-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h2 { text-align: center; }
        input, select, textarea, button { width: 100%; padding: 7px 5px; margin: 8px 0px; border-radius: 5px; border: 1.5px solid  #ccc; ; }
        button { background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
        label { font-size: 14px; }
    </style>
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                });
            }
        }
        window.onload = getLocation;
    </script>
</head>
<body>
<div class="form-box">
    <h2>Report a Crime</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Crime Title">
        <select name="crime_type" required>
            <option value="">Select Crime Type</option>
            <option value="Theft">Theft</option>
            <option value="Assault">Assault</option>
            <option value="Fraud">Fraud</option>
            <option value="Murder">Murder</option>
            <option value="Kidnapping">Kidnapping</option>
            <option value="Others">Others</option>
        </select>
        <textarea name="description" rows="4" placeholder="Describe the incident..." required></textarea>
        <input type="text" name="location" placeholder="Address of Event (or landmark)" required>
        <select name="crime_time" required>
            <option value="">Is the crime happening now?</option>
            <option value="Live">Yes, it is happening now</option>
            <option value="Past">No, it already happened</option>
        </select>
        <select name="crime_time" required>
            <option value="">Is there a suspect?</option>
            <option value="True">Yes, there is a suspect</option>
            <option value="False">No, there is no suspect</option>
        </select>
        <select name="crime_time" required>
            <option value="">Is the suspect armed? </option>
            <option value="True">Yes </option>
            <option value="False">No </option>
        </select>
        
        <input type="file" name="evidence_file" accept="image/*,video/*">
        <label><input type="checkbox" name="anonymous"> Report Anonymously</label>
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <button type="submit" name="report_crime">Submit Report</button>
    </form>
</div>
</body>
</html>
