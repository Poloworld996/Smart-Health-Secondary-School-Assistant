<?php
require '../includes/auth.php';
require_role('admin');
include '../config/db.php';
$page_title = "User Management";
$error = "";

// Add new user (student, teacher, or admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $class_form = mysqli_real_escape_string($conn, $_POST['class_form']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT user_id FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username already exists.";
    } else {
        mysqli_query($conn, "INSERT INTO users (full_name, username, email, password, role, class_form)
                              VALUES ('$full_name', '$username', '$email', '$password', '$role', '$class_form')");
    }
}


if (isset($_GET['deactivate'])) {
    $id = intval($_GET['deactivate']);
    // Prevent admin from deactivating themselves
    if ($id != $_SESSION['user_id']) {
        mysqli_query($conn, "UPDATE users SET status='inactive' WHERE user_id = $id");
    }
}

// Reactivate a previously deactivated user
if (isset($_GET['reactivate'])) {
    $id = intval($_GET['reactivate']);
    mysqli_query($conn, "UPDATE users SET status='active' WHERE user_id = $id");
}

include '../includes/header.php';
?>

<div class="card">
    <h2>Add New User</h2>
    <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" id="full_name" required>

        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required minlength="6">

        <label for="role">Role</label>
        <select name="role" id="role" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Admin</option>
        </select>

        <label for="class_form">Class/Form (for students only)</label>
        <input type="text" name="class_form" id="class_form" placeholder="e.g. Form 1A">

        <button type="submit" name="add_user">Add User</button>
    </form>
</div>

<div class="card">
    <h3>All Users</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Class</th><th>Status</th><th>Action</th></tr>
        <?php
        $users = mysqli_query($conn, "SELECT * FROM users ORDER BY role, full_name");
        while ($row = mysqli_fetch_assoc($users)) {
            $status = $row['status'] ?? 'active';
            $badge = $status === 'active' ? 'badge-done' : 'badge-pending';
            echo "<tr>
                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                    <td>" . htmlspecialchars($row['username']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['role']) . "</td>
                    <td>" . htmlspecialchars($row['class_form'] ?? '-') . "</td>
                    <td><span class='badge $badge'>" . htmlspecialchars($status) . "</span></td>
                    <td>";
            if ($row['user_id'] != $_SESSION['user_id']) {
                if ($status === 'active') {
                    echo "<a href='?deactivate=" . $row['user_id'] . "' onclick=\"return confirmDelete('Deactivate this user? Their health records are kept, and you can reactivate them anytime.')\">Deactivate</a>";
                } else {
                    echo "<a href='?reactivate=" . $row['user_id'] . "'>Reactivate</a>";
                }
            } else {
                echo "(You)";
            }
            echo "</td></tr>";
        }
        ?>
    </table>
    </div>
    <p style="margin-top:10px; color:#666; font-size:13px;">
        Deactivating a user blocks their login but keeps all their BMI records, symptom checks, and reminders intact nothing is deleted.
    </p>
</div>

<?php include '../includes/footer.php'; ?>
