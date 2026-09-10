<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            // Block login for accounts an admin has deactivated
            if (isset($user['status']) && $user['status'] === 'inactive') {
                header("Location: index.php?error=inactive");
                exit();
            }

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            header("Location: " . $user['role'] . "/dashboard.php");
            exit();
        }
    }

    header("Location: index.php?error=1");
    exit();
}
?>
