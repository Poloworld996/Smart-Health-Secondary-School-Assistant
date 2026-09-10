<?php
require '../includes/auth.php';
require_role('admin');
include '../config/db.php';
$page_title = "System Monitoring";

$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];
$total_bmi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bmi_records"))['c'];
$total_symptoms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM symptom_checks"))['c'];
$total_materials = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM health_materials"))['c'];
$total_announcements = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM announcements"))['c'];
$total_reminders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM health_reminders"))['c'];

include '../includes/header.php';
?>

<div class="card">
    <h2>System Monitoring</h2>
    <p>Overview of overall system activity and health.</p>
    <div class="stat-grid">
        <div class="stat-box"><div class="number"><?php echo $total_users; ?></div>Total Users</div>
        <div class="stat-box"><div class="number"><?php echo $total_bmi; ?></div>BMI Records</div>
        <div class="stat-box"><div class="number"><?php echo $total_symptoms; ?></div>Symptom Checks</div>
        <div class="stat-box"><div class="number"><?php echo $total_materials; ?></div>Health Materials</div>
        <div class="stat-box"><div class="number"><?php echo $total_announcements; ?></div>Announcements</div>
        <div class="stat-box"><div class="number"><?php echo $total_reminders; ?></div>Reminders Set</div>
    </div>
</div>

<div class="card">
    <h3>Server Information</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Item</th><th>Value</th></tr>
        <tr><td>PHP Version</td><td><?php echo phpversion(); ?></td></tr>
        <tr><td>Server Software</td><td><?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'N/A'); ?></td></tr>
        <tr><td>Current Server Time</td><td><?php echo date("Y-m-d H:i:s"); ?></td></tr>
        <tr><td>Database</td><td>MySQL (health_assistant)</td></tr>
          <tr><td>API</td><td>Gemini_API</td></tr>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
