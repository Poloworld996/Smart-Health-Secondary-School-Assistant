<?php
require '../includes/auth.php';
require_role('student');
include '../config/db.php';

$page_title = "BMI Calculator";
$bmi = null;
$category = null;
$css_class = "";


$bmi_info = [
    "Underweight" => [
        "effects" => [
            "Weakened immune system, making you more prone to infections",
            "Low energy levels and frequent fatigue",
            "Nutrient deficiencies (e.g. iron, calcium, vitamins)",
            "Delayed growth and puberty in teenagers",
            "Thinning hair, dry skin, and brittle nails"
        ],
        "solutions" => [
            "Eat more frequently, adding healthy snacks between main meals",
            "Increase intake of nutrient-dense foods: whole grains, nuts, dairy, lean protein",
            "Add healthy calorie-dense foods like avocado, peanut butter, and olive oil",
            "Do light strength exercises to build muscle, not just add fat",
            "Consult the school nurse or a doctor if the underweight condition persists"
        ]
    ],
    "Normal weight" => [
        "effects" => [
            "Lower risk of heart disease, diabetes, and other weight-related illnesses",
            "Better energy levels, concentration, and physical performance",
            "Healthy immune function"
        ],
        "solutions" => [
            "Maintain a balanced diet with fruits, vegetables, protein, and whole grains",
            "Keep up regular physical activity (at least 30 minutes most days)",
            "Get enough sleep and stay hydrated",
            "Recheck your BMI regularly to make sure you stay in this healthy range"
        ]
    ],
    "Overweight" => [
        "effects" => [
            "Increased risk of high blood pressure and type 2 diabetes",
            "Joint stress, especially on knees and lower back",
            "Reduced stamina and higher tiredness during physical activity",
            "Higher risk of developing heart disease later in life"
        ],
        "solutions" => [
            "Reduce portion sizes and limit sugary drinks and fast food",
            "Increase physical activity: aim for 30-60 minutes of exercise daily",
            "Add more fiber-rich foods like vegetables, fruits, and whole grains",
            "Avoid late-night snacking and skipping breakfast",
            "Track progress gradually — aim for slow, steady weight loss, not crash diets"
        ]
    ],
    "Obese" => [
        "effects" => [
            "High risk of type 2 diabetes, hypertension, and heart disease",
            "Joint and mobility problems due to excess weight",
            "Sleep problems, including sleep apnea",
            "Emotional effects such as low self-esteem or social anxiety"
        ],
        "solutions" => [
            "Seek guidance from the school nurse or a doctor before starting any weight-loss plan",
            "Adopt a structured, balanced diet — reduce processed and sugary foods",
            "Start with low-impact exercises like walking or swimming, then increase intensity gradually",
            "Set realistic, gradual weight-loss goals rather than extreme diets",
            "Get regular check-ups to monitor blood pressure and sugar levels"
        ]
    ]
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $height = floatval($_POST['height']); // in cm
    $weight = floatval($_POST['weight']); // in kg

    if ($height > 0 && $weight > 0) {
        $height_m = $height / 100;
        $bmi = $weight / ($height_m * $height_m);
        $bmi = round($bmi, 1);

       
        if ($bmi < 18.5) {
            $category = "Underweight";
            $css_class = "cat-under";
        } elseif ($bmi < 25) {
            $category = "Normal weight";
            $css_class = "cat-normal";
        } elseif ($bmi < 30) {
            $category = "Overweight";
            $css_class = "cat-over";
        } else {
            $category = "Obese";
            $css_class = "cat-obese";
        }

       
        $user_id = $_SESSION['user_id'];
        $sql = "INSERT INTO bmi_records (user_id, height_cm, weight_kg, bmi_value, bmi_category)
                VALUES ($user_id, $height, $weight, $bmi, '$category')";
        mysqli_query($conn, $sql);
    }
}

include '../includes/header.php';
?>

<div class="card">
    <h2>BMI Calculator</h2>
    <p>Enter your height and weight to calculate your Body Mass Index (BMI).</p>

    <form method="POST">
        <label for="height">Height (cm)</label>
        <input type="number" step="0.1" name="height" id="height" required>

        <label for="weight">Weight (kg)</label>
        <input type="number" step="0.1" name="weight" id="weight" required>

        <small id="live-bmi-preview" style="color:#0d7a63;"></small>

        <button type="submit">Calculate BMI</button>
    </form>

    <?php if ($bmi !== null): ?>
        <div class="result-box <?php echo $css_class; ?>">
            Your BMI is <?php echo $bmi; ?> — <?php echo $category; ?>
        </div>

        <?php if ($category !== null && isset($bmi_info[$category])): ?>
            <div class="bmi-advice">
                <div class="bmi-advice-col">
                    <h4>Possible Effects</h4>
                    <ul>
                        <?php foreach ($bmi_info[$category]['effects'] as $effect): ?>
                            <li><?php echo htmlspecialchars($effect); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="bmi-advice-col">
                    <h4><?php echo $category === 'Normal weight' ? 'How to Stay Healthy' : 'Solutions to Reach Normal Weight'; ?></h4>
                    <ul>
                        <?php foreach ($bmi_info[$category]['solutions'] as $solution): ?>
                            <li><?php echo htmlspecialchars($solution); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Your BMI History</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Date</th><th>Height (cm)</th><th>Weight (kg)</th><th>BMI</th><th>Category</th></tr>
        <?php
        $user_id = $_SESSION['user_id'];
        $history = mysqli_query($conn, "SELECT * FROM bmi_records WHERE user_id = $user_id ORDER BY date_recorded DESC");
        while ($row = mysqli_fetch_assoc($history)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['date_recorded']) . "</td>
                    <td>" . htmlspecialchars($row['height_cm']) . "</td>
                    <td>" . htmlspecialchars($row['weight_kg']) . "</td>
                    <td>" . htmlspecialchars($row['bmi_value']) . "</td>
                    <td>" . htmlspecialchars($row['bmi_category']) . "</td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
