<?php
require '../includes/auth.php';
require_role('admin');
include '../config/db.php';
$page_title = "Health Content Management";
$user_id = $_SESSION['user_id'];

// Add health material
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_material'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    mysqli_query($conn, "INSERT INTO health_materials (title, content, category, posted_by) VALUES ('$title', '$content', '$category', $user_id)");
}

// Add emergency contact
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_contact'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $role = mysqli_real_escape_string($conn, $_POST['contact_role']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    mysqli_query($conn, "INSERT INTO emergency_contacts (name, contact_role, phone, email) VALUES ('$name', '$role', '$phone', '$email')");
}

// Delete handlers
if (isset($_GET['delete_material'])) {
    mysqli_query($conn, "DELETE FROM health_materials WHERE material_id = " . intval($_GET['delete_material']));
}
if (isset($_GET['delete_contact'])) {
    mysqli_query($conn, "DELETE FROM emergency_contacts WHERE contact_id = " . intval($_GET['delete_contact']));
}

include '../includes/header.php';
?>

<div class="card">
    <h2>Add Health Education Material</h2>
    <form method="POST">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" required>
        <label for="category">Category</label>
        <input type="text" name="category" id="category" required>
        <label for="content">Content</label>
        <textarea name="content" id="content" required></textarea>
        <button type="submit" name="add_material">Publish Material</button>
    </form>
</div>

<div class="card">
    <h3>Existing Materials</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Title</th><th>Category</th><th>Action</th></tr>
        <?php
        $materials = mysqli_query($conn, "SELECT * FROM health_materials ORDER BY date_posted DESC");
        while ($row = mysqli_fetch_assoc($materials)) {
            echo "<tr><td>" . htmlspecialchars($row['title']) . "</td><td>" . htmlspecialchars($row['category']) . "</td>
                  <td><a href='?delete_material=" . $row['material_id'] . "' onclick=\"return confirmDelete('Delete this material?')\">Delete</a></td></tr>";
        }
        ?>
    </table>
    </div>
</div>

<div class="card">
    <h2>Add Emergency Contact</h2>
    <form method="POST">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" required>
        <label for="contact_role">Role</label>
        <input type="text" name="contact_role" id="contact_role" placeholder="e.g. School Nurse" required>
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone" required>
        <label for="email">Email (optional)</label>
        <input type="email" name="email" id="email">
        <button type="submit" name="add_contact">Add Contact</button>
    </form>
</div>

<div class="card">
    <h3>Existing Emergency Contacts</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Name</th><th>Role</th><th>Phone</th><th>Action</th></tr>
        <?php
        $contacts = mysqli_query($conn, "SELECT * FROM emergency_contacts ORDER BY name");
        while ($row = mysqli_fetch_assoc($contacts)) {
            echo "<tr><td>" . htmlspecialchars($row['name']) . "</td><td>" . htmlspecialchars($row['contact_role']) . "</td><td>" . htmlspecialchars($row['phone']) . "</td>
                  <td><a href='?delete_contact=" . $row['contact_id'] . "' onclick=\"return confirmDelete('Delete this contact?')\">Delete</a></td></tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
