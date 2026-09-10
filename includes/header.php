<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? $page_title . " - Health Assistant" : "Smart Health Assistant"; ?></title>
<link rel="stylesheet" href="/health_assistant/assets/css/style.css">
</head>
<body <?php echo isset($body_class) ? 'class="' . $body_class . '"' : ''; ?>>

<?php if (isset($body_class) && $body_class === 'auth-page'): ?>
<div class="background-slideshow" aria-hidden="true">
    <span class="bg-slide bg-slide-1"></span>
    <span class="bg-slide bg-slide-2"></span>
    <span class="bg-slide bg-slide-3"></span>
    <span class="bg-slide bg-slide-4"></span>
</div>
<?php endif; ?>

<header class="topbar">
    <div class="logo">
        <span class="logo-mark" aria-hidden="true">
            <img src="/health_assistant/assets/images/logo.png" alt="Smart Health Assistant logo">
        </span>
        <span class="logo-text">
            <span class="logo-title">Smart Health</span>
            <span class="logo-subtitle">Secondary School Assistant</span>
        </span>
    </div>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar">
        <?php if ($_SESSION['role'] === 'student'): ?>
            <a href="/health_assistant/student/dashboard.php">Dashboard</a>
            <a href="/health_assistant/student/bmi_calculator.php">BMI Calculator</a>
            <a href="/health_assistant/student/symptom_checker.php">Symptom Checker</a>
            <a href="/health_assistant/student/health_education.php">Health Tips</a>
            <a href="/health_assistant/student/reminders.php">Reminders</a>
            <a href="/health_assistant/student/announcements.php">Announcements</a>
            <a href="/health_assistant/student/emergency_contacts.php">Emergency</a>
        <?php elseif ($_SESSION['role'] === 'teacher'): ?>
            <a href="/health_assistant/teacher/dashboard.php">Dashboard</a>
            <a href="/health_assistant/teacher/student_reports.php">Student Reports</a>
            <a href="/health_assistant/teacher/awareness.php">Health Awareness</a>
            <a href="/health_assistant/teacher/announcements.php">Announcements</a>
        <?php elseif ($_SESSION['role'] === 'admin'): ?>
            <a href="/health_assistant/admin/dashboard.php">Dashboard</a>
            <a href="/health_assistant/admin/users.php">User Management</a>
            <a href="/health_assistant/admin/content.php">Content Management</a>
            <a href="/health_assistant/admin/health_data.php">Health Data</a>
            <a href="/health_assistant/admin/reports.php">Reports</a>
            <a href="/health_assistant/admin/monitoring.php">System Monitor</a>
        <?php endif; ?>
        <span class="welcome-text">Hi, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
        <a href="/health_assistant/logout.php" class="logout-link">Logout</a>
    </nav>
    <?php endif; ?>
</header>

<main class="container">
