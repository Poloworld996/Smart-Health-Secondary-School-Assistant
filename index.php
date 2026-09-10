<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: " . $_SESSION['role'] . "/dashboard.php");
    exit();
}
$page_title = "Login";
$body_class = "auth-page";
include 'includes/header.php';
?>

<div class="auth-box">
    <h2>Login</h2>

    <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success">Registration successful! You can now log in.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'inactive'): ?>
        <div class="alert alert-error">This account has been deactivated by an admin. Please contact your school admin.</div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-error">Invalid username or password. Please try again.</div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register as a Student</a></p>
</div>

<?php include 'includes/footer.php'; ?>
