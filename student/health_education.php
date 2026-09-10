<?php
require '../includes/auth.php';
require_role('student');
include '../config/db.php';
$page_title = "Health Education";
include '../includes/header.php';

$materials = mysqli_query($conn, "SELECT * FROM health_materials ORDER BY date_posted DESC");
?>

<div class="card">
    <h2>Health Education Materials</h2>
    <p>Read these health tips shared by your teachers and admin.</p>
</div>

<?php while ($row = mysqli_fetch_assoc($materials)): ?>
    <div class="card">
        <h3><?php echo htmlspecialchars($row['title']); ?> <small style="color:#0f9a7d;">[<?php echo htmlspecialchars($row['category']); ?>]</small></h3>
        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        <small style="color:#888;">Posted on <?php echo htmlspecialchars($row['date_posted']); ?></small>
    </div>
<?php endwhile; ?>

<?php include '../includes/footer.php'; ?>
