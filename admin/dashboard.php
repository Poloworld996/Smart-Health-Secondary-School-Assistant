<?php
require '../includes/auth.php';
require_role('admin');
include '../config/db.php';
$page_title = "Admin Dashboard";

// Quick stats
$students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='student'"))['c'];
$teachers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='teacher'"))['c'];
$bmi_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bmi_records"))['c'];
$symptom_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM symptom_checks"))['c'];

include '../includes/header.php';
?>

<div class="card welcome-card">
    <div class="welcome-avatar"><?php echo initials($_SESSION['full_name']); ?></div>
    <div class="welcome-text">
        <span class="welcome-eyebrow"><?php echo time_greeting(); ?></span>
        <h2><?php echo htmlspecialchars($_SESSION['full_name']); ?> <span class="wave">👋</span></h2>
        <p>Manage the whole Health Assistant System from here.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-box sb-blue">
        <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3 2 8l10 5 8-4v6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round"/><path d="M6 10.5V16c0 1.4 2.7 3 6 3s6-1.6 6-3v-5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <div class="number"><?php echo $students; ?></div>
        <span class="stat-label">Students</span>
    </div>
    <div class="stat-box sb-purple">
        <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.8"/><path d="M4.5 20c.9-4 3.6-6.2 7.5-6.2s6.6 2.2 7.5 6.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
        <div class="number"><?php echo $teachers; ?></div>
        <span class="stat-label">Teachers</span>
    </div>
    <div class="stat-box sb-teal">
        <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="8" width="18" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 8v3M11 8v2M15 8v3M19 8v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
        <div class="number"><?php echo $bmi_count; ?></div>
        <span class="stat-label">BMI Records</span>
    </div>
    <div class="stat-box sb-amber">
        <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 14.8V4.5a2 2 0 1 0-4 0V14.8a4 4 0 1 0 4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 3.5h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
        <div class="number"><?php echo $symptom_count; ?></div>
        <span class="stat-label">Symptom Checks</span>
    </div>
</div>

<div class="menu-grid">
    <a class="menu-item mi-blue" href="users.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="9" cy="8" r="3.2" stroke="currentColor" stroke-width="1.8"/><path d="M2.5 20c.7-3.4 3.4-5.5 6.5-5.5s5.8 2.1 6.5 5.5M16 8.2a3 3 0 1 1 3.6 2.95M17.5 14.6c2.6.4 4.4 2.1 5 4.9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <span class="menu-label">User Management</span>
        <span class="menu-desc">Add & remove accounts</span>
    </a>
    <a class="menu-item mi-purple" href="content.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5V5.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M4 20.5A2.5 2.5 0 0 1 6.5 18H20" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
        <span class="menu-label">Health Content Management</span>
        <span class="menu-desc">Materials & contacts</span>
    </a>
    <a class="menu-item mi-coral" href="health_data.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 7.5A1.5 1.5 0 0 1 4.5 6h4l2 2.5h9A1.5 1.5 0 0 1 21 10v7.5A1.5 1.5 0 0 1 19.5 19h-15A1.5 1.5 0 0 1 3 17.5v-10Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
        <span class="menu-label">Health Data Management</span>
        <span class="menu-desc">BMI, symptoms & reminders</span>
    </a>
    <a class="menu-item mi-teal" href="reports.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 20V10M10 20V4M16 20v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 20h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
        <span class="menu-label">Report Generation</span>
        <span class="menu-desc">Stats & CSV export</span>
    </a>
    <a class="menu-item mi-amber" href="monitoring.php">
        <span class="menu-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="4" width="18" height="12" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M8 20h8M12 16v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
        <span class="menu-label">System Monitoring</span>
        <span class="menu-desc">Usage & server info</span>
    </a>
</div>

<?php include '../includes/footer.php'; ?>
