<?php
require '../includes/auth.php';
require_role('admin');
include '../config/db.php';
$page_title = "Report Generation";

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] === 'bmi') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="bmi_report.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Student', 'Class', 'Height(cm)', 'Weight(kg)', 'BMI', 'Category', 'Date']);

    $sql = "SELECT u.full_name, u.class_form, b.* FROM bmi_records b
            JOIN users u ON b.user_id = u.user_id ORDER BY b.date_recorded DESC";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [$row['full_name'], $row['class_form'], $row['height_cm'], $row['weight_kg'], $row['bmi_value'], $row['bmi_category'], $row['date_recorded']]);
    }
    fclose($output);
    exit();
}

include '../includes/header.php';

// Summary counts for the report page
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='student'"))['c'];
$underweight = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bmi_records WHERE bmi_category='Underweight'"))['c'];
$normal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bmi_records WHERE bmi_category='Normal weight'"))['c'];
$overweight = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bmi_records WHERE bmi_category='Overweight'"))['c'];
$obese = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bmi_records WHERE bmi_category='Obese'"))['c'];
?>

<div class="card">
    <h2>School Health Report Summary</h2>
    <div class="stat-grid">
        <div class="stat-box"><div class="number"><?php echo $total_students; ?></div>Total Students</div>
        <div class="stat-box"><div class="number"><?php echo $underweight; ?></div>Underweight</div>
        <div class="stat-box"><div class="number"><?php echo $normal; ?></div>Normal Weight</div>
        <div class="stat-box"><div class="number"><?php echo $overweight; ?></div>Overweight</div>
        <div class="stat-box"><div class="number"><?php echo $obese; ?></div>Obese</div>
    </div>
</div>

<div class="card">
    <h3>Export Reports</h3>
    <a class="btn" href="?export=bmi">⬇️ Download BMI Report (CSV)</a>
</div>

<?php include '../includes/footer.php'; ?>
