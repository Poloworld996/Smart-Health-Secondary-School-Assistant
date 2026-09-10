<?php
require '../includes/auth.php';
require_role('admin');
include '../config/db.php';
$page_title = "Health Data Management";



if (isset($_GET['delete_bmi'])) {
    mysqli_query($conn, "DELETE FROM bmi_records WHERE bmi_id = " . intval($_GET['delete_bmi']));
}
if (isset($_GET['delete_symptom'])) {
    mysqli_query($conn, "DELETE FROM symptom_checks WHERE check_id = " . intval($_GET['delete_symptom']));
}
if (isset($_GET['delete_reminder'])) {
    mysqli_query($conn, "DELETE FROM health_reminders WHERE reminder_id = " . intval($_GET['delete_reminder']));
}

include '../includes/header.php';
?>

<div class="card">
    <h2>Health Data Management</h2>
    <p>View and permanently delete individual BMI records, symptom checks, and reminders. Deleting a record here does not deactivate or affect the student's account.</p>
</div>

<div class="card">
    <h3>BMI Records</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Student</th><th>Class</th><th>Height (cm)</th><th>Weight (kg)</th><th>BMI</th><th>Category</th><th>Date</th><th>Action</th></tr>
        <?php
        $bmi = mysqli_query($conn, "SELECT b.*, u.full_name, u.class_form FROM bmi_records b
                                     JOIN users u ON b.user_id = u.user_id
                                     ORDER BY b.date_recorded DESC");
        while ($row = mysqli_fetch_assoc($bmi)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                    <td>" . htmlspecialchars($row['class_form'] ?? '-') . "</td>
                    <td>" . htmlspecialchars($row['height_cm']) . "</td>
                    <td>" . htmlspecialchars($row['weight_kg']) . "</td>
                    <td>" . htmlspecialchars($row['bmi_value']) . "</td>
                    <td>" . htmlspecialchars($row['bmi_category']) . "</td>
                    <td>" . htmlspecialchars($row['date_recorded']) . "</td>
                    <td><a href='?delete_bmi=" . $row['bmi_id'] . "' onclick=\"return confirmDelete('Delete this BMI record?')\">Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<div class="card">
    <h3>Symptom Checks</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Student</th><th>Class</th><th>Symptoms</th><th>Possible Condition</th><th>Date</th><th>Action</th></tr>
        <?php
        $symptoms = mysqli_query($conn, "SELECT s.*, u.full_name, u.class_form FROM symptom_checks s
                                          JOIN users u ON s.user_id = u.user_id
                                          ORDER BY s.date_checked DESC");
        while ($row = mysqli_fetch_assoc($symptoms)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                    <td>" . htmlspecialchars($row['class_form'] ?? '-') . "</td>
                    <td>" . htmlspecialchars($row['symptoms_selected']) . "</td>
                    <td>" . htmlspecialchars($row['possible_condition']) . "</td>
                    <td>" . htmlspecialchars($row['date_checked']) . "</td>
                    <td><a href='?delete_symptom=" . $row['check_id'] . "' onclick=\"return confirmDelete('Delete this symptom check?')\">Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<div class="card">
    <h3>Reminders</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Student</th><th>Class</th><th>Title</th><th>Date</th><th>Status</th><th>Action</th></tr>
        <?php
        $reminders = mysqli_query($conn, "SELECT r.*, u.full_name, u.class_form FROM health_reminders r
                                           JOIN users u ON r.user_id = u.user_id
                                           ORDER BY r.reminder_date DESC");
        while ($row = mysqli_fetch_assoc($reminders)) {
            $badge = $row['status'] === 'done' ? 'badge-done' : 'badge-pending';
            echo "<tr>
                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                    <td>" . htmlspecialchars($row['class_form'] ?? '-') . "</td>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars($row['reminder_date']) . "</td>
                    <td><span class='badge $badge'>" . htmlspecialchars($row['status']) . "</span></td>
                    <td><a href='?delete_reminder=" . $row['reminder_id'] . "' onclick=\"return confirmDelete('Delete this reminder?')\">Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
