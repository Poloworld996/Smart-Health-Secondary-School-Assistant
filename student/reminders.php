<?php
require '../includes/auth.php';
require_role('student');
include '../config/db.php';
$page_title = "Health Reminders";
$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reminder'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $date = mysqli_real_escape_string($conn, $_POST['reminder_date']);
    mysqli_query($conn, "INSERT INTO health_reminders (user_id, title, message, reminder_date) VALUES ($user_id, '$title', '$message', '$date')");
}


if (isset($_GET['done'])) {
    $id = intval($_GET['done']);
    mysqli_query($conn, "UPDATE health_reminders SET status='done' WHERE reminder_id=$id AND user_id=$user_id");
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM health_reminders WHERE reminder_id=$id AND user_id=$user_id");
}

include '../includes/header.php';
?>

<div class="card">
    <h2>Add a Health Reminder</h2>
    <form method="POST">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" required>

        <label for="message">Message</label>
        <textarea name="message" id="message" required></textarea>

        <label for="reminder_date">Reminder Date</label>
        <input type="date" name="reminder_date" id="reminder_date" required>

        <button type="submit" name="add_reminder">Add Reminder</button>
    </form>
</div>

<div class="card">
    <h3>My Reminders</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Title</th><th>Message</th><th>Date</th><th>Status</th><th>Actions</th></tr>
        <?php
        $reminders = mysqli_query($conn, "SELECT * FROM health_reminders WHERE user_id=$user_id ORDER BY reminder_date ASC");
        while ($row = mysqli_fetch_assoc($reminders)) {
            $badge = $row['status'] === 'done' ? 'badge-done' : 'badge-pending';
            echo "<tr>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars($row['message']) . "</td>
                    <td>" . htmlspecialchars($row['reminder_date']) . "</td>
                    <td><span class='badge $badge'>" . htmlspecialchars($row['status']) . "</span></td>
                    <td>";
            if ($row['status'] !== 'done') {
                echo "<a href='?done=" . $row['reminder_id'] . "'>Mark Done</a> | ";
            }
            echo "<a href='?delete=" . $row['reminder_id'] . "' onclick=\"return confirmDelete('Delete this reminder?')\">Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
