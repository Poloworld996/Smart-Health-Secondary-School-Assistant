<?php
require '../includes/auth.php';
require_role('teacher');
$page_title = "Teacher Dashboard";
include '../includes/header.php';
?>

<div class="card welcome-card">
    <div class="welcome-avatar"><?php echo initials($_SESSION['full_name']); ?></div>
    <div class="welcome-text">
        <span class="welcome-eyebrow"><?php echo time_greeting(); ?></span>
        <h2><?php echo htmlspecialchars($_SESSION['full_name']); ?> <span class="wave">👋</span></h2>
        <p>Manage student health awareness and monitor reports from here.</p>
    </div>
</div>

<div class="menu-grid">
    <a class="menu-item mi-blue" href="student_reports.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 20V10M10 20V4M16 20v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 20h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
        <span class="menu-label">Student Health Reports</span>
        <span class="menu-desc">View BMI & symptom checks</span>
    </a>
    <a class="menu-item mi-purple" href="awareness.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5V5.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M4 20.5A2.5 2.5 0 0 1 6.5 18H20" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
        <span class="menu-label">Health Awareness Management</span>
        <span class="menu-desc">Post & manage articles</span>
    </a>
    <a class="menu-item mi-teal" href="announcements.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 10.5v3a1.7 1.7 0 0 0 1.7 1.7h1L9.5 20V5.3L5.7 9H4.7A1.7 1.7 0 0 0 3 10.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round"/><path d="M13 8v8M17 6v12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
        <span class="menu-label">Announcement Management</span>
        <span class="menu-desc">Post school announcements</span>
    </a>
</div>

<?php include '../includes/footer.php'; ?>
