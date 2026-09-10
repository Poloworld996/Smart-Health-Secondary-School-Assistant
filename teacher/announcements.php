<?php
require '../includes/auth.php';
require_role('teacher');
include '../config/db.php';
$page_title = "Announcement Management";
$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_announcement'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $res = mysqli_query($conn, "INSERT INTO announcements (title, message, posted_by) VALUES ('$title', '$message', $user_id)");
    if ($res) {
        $_SESSION['flash'] = 'Announcement posted successfully.';
    } else {
        $_SESSION['flash'] = 'Failed to post announcement.';
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = mysqli_query($conn, "DELETE FROM announcements WHERE announcement_id = $id");
    $_SESSION['flash'] = $res ? 'Announcement deleted.' : 'Failed to delete announcement.';
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

include '../includes/header.php';
?>

<?php if (isset($_SESSION['flash'])): ?>
    <div class="card">
        <p><?php echo htmlspecialchars($_SESSION['flash']); ?></p>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="card">
    <h2>Post a New Announcement</h2>
    <form method="POST">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" required>

        <label for="message">Message</label>
        <textarea name="message" id="message" required></textarea>

        <button type="submit" name="add_announcement">Post Announcement</button>
    </form>
</div>

<div class="card">
    <h3>All Announcements</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Title</th><th>Message</th><th>Date</th><th>Action</th></tr>
        <?php
        $list = mysqli_query($conn, "SELECT * FROM announcements ORDER BY date_posted DESC");
        while ($row = mysqli_fetch_assoc($list)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars($row['message']) . "</td>
                    <td>" . htmlspecialchars($row['date_posted']) . "</td>
                    <td><a href='?delete=" . $row['announcement_id'] . "' onclick=\"return confirmDelete('Delete this announcement?')\">Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
