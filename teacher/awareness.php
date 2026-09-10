<?php
require '../includes/auth.php';
require_role('teacher');
include '../config/db.php';
$page_title = "Health Awareness Management";
$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_material'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    mysqli_query($conn, "INSERT INTO health_materials (title, content, category, posted_by) VALUES ('$title', '$content', '$category', $user_id)");
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM health_materials WHERE material_id = $id");
}

include '../includes/header.php';
?>

<div class="card">
    <h2>Post a New Health Awareness Article</h2>
    <form method="POST">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" required>

        <label for="category">Category</label>
        <input type="text" name="category" id="category" placeholder="e.g. Hygiene, Nutrition, Mental Health" required>

        <label for="content">Content</label>
        <textarea name="content" id="content" required></textarea>

        <button type="submit" name="add_material">Publish</button>
    </form>
</div>

<div class="card">
    <h3>Existing Materials</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Title</th><th>Category</th><th>Date Posted</th><th>Action</th></tr>
        <?php
        $materials = mysqli_query($conn, "SELECT * FROM health_materials ORDER BY date_posted DESC");
        while ($row = mysqli_fetch_assoc($materials)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars($row['category']) . "</td>
                    <td>" . htmlspecialchars($row['date_posted']) . "</td>
                    <td><a href='?delete=" . $row['material_id'] . "' onclick=\"return confirmDelete('Delete this article?')\">Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
