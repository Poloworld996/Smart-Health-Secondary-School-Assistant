<?php
require '../includes/auth.php';
require_role('student');
$page_title = "Student Dashboard";
include '../config/db.php';
include '../includes/header.php';
?>

<div class="card welcome-card">
    <div class="welcome-avatar"><?php echo initials($_SESSION['full_name']); ?></div>
    <div class="welcome-text">
        <span class="welcome-eyebrow"><?php echo time_greeting(); ?></span>
        <h2><?php echo htmlspecialchars($_SESSION['full_name']); ?> <span class="wave">👋</span></h2>
        <p>Use the menu below to check your health, learn helpful tips, and stay safe.</p>
    </div>
</div>

<div class="menu-grid">
    <a class="menu-item mi-blue" href="bmi_calculator.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="8" width="18" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 8v3M11 8v2M15 8v3M19 8v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
        <span class="menu-label">BMI Calculator</span>
        <span class="menu-desc">Track your weight & height</span>
    </a>
    <a class="menu-item mi-coral" href="symptom_checker.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 14.8V4.5a2 2 0 1 0-4 0V14.8a4 4 0 1 0 4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 3.5h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
        <span class="menu-label">Symptom Checker</span>
        <span class="menu-desc">Get quick health guidance</span>
    </a>
    <a class="menu-item mi-purple" href="health_education.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5V5.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M4 20.5A2.5 2.5 0 0 1 6.5 18H20" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
        <span class="menu-label">Health Education</span>
        <span class="menu-desc">Learn helpful health tips</span>
    </a>
    <a class="menu-item mi-amber" href="reminders.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <span class="menu-label">Health Reminders</span>
        <span class="menu-desc">Never miss a check-up</span>
    </a>
    <a class="menu-item mi-red" href="emergency_contacts.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22 16.9v2a2 2 0 0 1-2.2 2 19.6 19.6 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6 19.6 19.6 0 0 1-3-8.6A2 2 0 0 1 4.2 1h2a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.4 2.1L7.1 8.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2.1Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <span class="menu-label">Emergency Contacts</span>
        <span class="menu-desc">Get help fast</span>
    </a>
</div>

<?php
// Latest announcement as a single, compact card
$latest = mysqli_query($conn, "SELECT * FROM announcements ORDER BY date_posted DESC LIMIT 1");
if ($latest && mysqli_num_rows($latest) > 0) {
    $row = mysqli_fetch_assoc($latest);
    echo '<div class="announcement-card">';
    echo '  <div class="announcement-card-content">';
    echo '    <div class="announcement-card-label">LATEST ANNOUNCEMENT</div>';
    echo '    <h3>' . htmlspecialchars($row['title']) . '</h3>';
    echo '    <p class="announcement-card-text">' . htmlspecialchars(substr($row['message'], 0, 200)) . (strlen($row['message']) > 200 ? '...' : '') . '</p>';
    echo '    <a href="announcements.php" class="announcement-card-link">View all announcements</a>';
    echo '  </div>';
    echo '</div>';
}
?>

<?php include '../includes/footer.php'; ?>
