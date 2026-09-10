<?php
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $class_form = mysqli_real_escape_string($conn, $_POST['class_form']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT user_id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        header("Location: register.php?error=" . urlencode("Username already taken. Please choose another."));
        exit();
    }

    $sql = "INSERT INTO users (full_name, username, email, password, role, class_form)
            VALUES ('$full_name', '$username', '$email', '$password', 'student', '$class_form')";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?registered=1");
        exit();
    } else {
        header("Location: register.php?error=" . urlencode("Registration failed. Please try again."));
        exit();
    }
}
?>
