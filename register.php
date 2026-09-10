<?php
$page_title = "Register";
$body_class = "auth-page";
include 'includes/header.php';
?>

<div class="auth-box">
    <h2>Student Registration</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form action="register_process.php" method="POST">
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" id="full_name" required>

        <label for="class_form">Class / Form</label>
        <input type="text" name="class_form" id="class_form" placeholder="e.g. Form 2B" required>

        <label for="email">Email(Optional)</label>
        <input type="email" name="email" id="email" >

        <label for="username">Choose a Username</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Choose a Password</label>
        <input type="password" name="password" id="password" required minlength="6">

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="index.php">Login here</a></p>
</div>

<?php include 'includes/footer.php'; ?>
