<?php
require '../includes/auth.php';
require_role('teacher');
include '../config/db.php';
$page_title = "Student Health Reports";
include '../includes/header.php';
?>

<div class="card">
    <h2>Student BMI Records</h2>
    <div class="table-responsive">
    <table>
        <tr><th>Student</th><th>Class</th><th>Height</th><th>Weight</th><th>BMI</th><th>Category</th><th>Date</th></tr>
        <?php
        $sql = "SELECT u.full_name, u.class_form, b.* FROM bmi_records b
                JOIN users u ON b.user_id = u.user_id
                ORDER BY b.date_recorded DESC LIMIT 100";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                    <td>" . htmlspecialchars($row['class_form']) . "</td>
                    <td>" . htmlspecialchars($row['height_cm']) . " cm</td>
                    <td>" . htmlspecialchars($row['weight_kg']) . " kg</td>
                    <td>" . htmlspecialchars($row['bmi_value']) . "</td>
                    <td>" . htmlspecialchars($row['bmi_category']) . "</td>
                    <td>" . htmlspecialchars($row['date_recorded']) . "</td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<div class="card">
    <h2>Student Symptom Checks</h2>
    <div class="table-responsive">
    <table>
        <tr><th>Student</th><th>Class</th><th>Symptoms</th><th>Possible Condition</th><th>Date</th></tr>
        <?php
        $sql2 = "SELECT u.full_name, u.class_form, s.* FROM symptom_checks s
                 JOIN users u ON s.user_id = u.user_id
                 ORDER BY s.date_checked DESC LIMIT 100";
        $result2 = mysqli_query($conn, $sql2);
        while ($row = mysqli_fetch_assoc($result2)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                    <td>" . htmlspecialchars($row['class_form']) . "</td>
                    <td>" . htmlspecialchars($row['symptoms_selected']) . "</td>
                    <td>" . htmlspecialchars($row['possible_condition']) . "</td>
                    <td>" . htmlspecialchars($row['date_checked']) . "</td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
