<?php
require '../includes/auth.php';
require_role('student');
include '../config/db.php';
$page_title = "Emergency Contacts";
include '../includes/header.php';

$contacts = mysqli_query($conn, "SELECT * FROM emergency_contacts ORDER BY name ASC");
?>

<div class="card">
    <h2>Emergency Contacts</h2>
    <p>Save or note down these important contacts for emergencies.</p>
    <div class="table-responsive">
    <table>
        <tr><th>Name</th><th>Role</th><th>Phone</th><th>Email</th></tr>
        <?php while ($row = mysqli_fetch_assoc($contacts)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_role']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['email'] ?? '-'); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
