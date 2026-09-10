<?php
require '../includes/auth.php';
require_role('student');
include '../config/db.php';
require_once '../includes/claude_api.php';
require_once '../includes/gemini_api.php';

$page_title = "Symptom Checker";
$result_condition = null;
$result_advice = null;

// List of symptoms shown as checkboxes
$symptom_list = [
    "fever" => "Fever",
    "headache" => "Headache",
    "cough" => "Cough",
    "sore_throat" => "Sore Throat",
    "runny_nose" => "Runny Nose",
    "stomach_ache" => "Stomach Ache",
    "vomiting" => "Vomiting",
    "diarrhea" => "Diarrhea",
    "body_aches" => "Body Aches",
    "fatigue" => "Fatigue / Tiredness",
    "skin_rash" => "Skin Rash",
    "difficulty_breathing" => "Difficulty Breathing",
];

$extra_symptoms = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!empty($_POST['symptoms']) || trim($_POST['extra_symptoms'] ?? '') !== '')) {
    $selected = $_POST['symptoms'] ?? []; // array of selected symptom keys
    $extra_symptoms = trim($_POST['extra_symptoms'] ?? '');
    // Keep the free-text response short so it can't be used to send an
    // oversized or abusive payload to the API.
    $extra_symptoms = mb_substr($extra_symptoms, 0, 500);

   
    $selected_labels = array_map(function($k) use ($symptom_list) {
        return $symptom_list[$k] ?? $k;
    }, $selected);

    $api_result = ask_gemini_for_symptom_check($selected_labels, $extra_symptoms);

 
    if (isset($_GET['debug'])) {
        echo "<pre style='background:#f4f4f4;padding:10px;border:1px solid #ccc;'>";
        echo "curl enabled: " . (function_exists('curl_init') ? 'yes' : 'NO - THIS IS YOUR PROBLEM') . "\n";
        echo "API key set: " . (GEMINI_API_KEY !== 'PASTE_YOUR_NEW_ROTATED_KEY_HERE' ? 'yes' : 'NO - still placeholder') . "\n";
        echo "API result: ";
        var_dump($api_result);
        echo "</pre>";
    }
   


    if ($api_result !== null) {
        $result_condition = $api_result['condition'];
        $result_advice = $api_result['advice'];
    } elseif (in_array("difficulty_breathing", $selected)) {
        $result_condition = "Possible Serious Condition";
        $result_advice = "Difficulty breathing can be serious. Please see the school nurse or seek emergency help immediately.";
    } elseif (in_array("fever", $selected) && in_array("body_aches", $selected) && in_array("fatigue", $selected)) {
        $result_condition = "Possible Flu";
        $result_advice = "Rest, drink plenty of fluids, and inform the school nurse. See a doctor if symptoms worsen or last more than 3 days.";
    } elseif (in_array("vomiting", $selected) && in_array("diarrhea", $selected)) {
        $result_condition = "Possible Food Poisoning / Stomach Infection";
        $result_advice = "Drink plenty of clean water (oral rehydration), avoid solid foods temporarily, and visit the school nurse.";
    } elseif (in_array("cough", $selected) && in_array("sore_throat", $selected) && in_array("runny_nose", $selected)) {
        $result_condition = "Possible Common Cold";
        $result_advice = "Rest, drink warm fluids, and keep warm. See the nurse if symptoms don't improve after a few days.";
    } elseif (in_array("headache", $selected) && count($selected) == 1) {
        $result_condition = "Possible Mild Headache";
        $result_advice = "Rest in a quiet place, drink water, and avoid too much screen time. See the nurse if it becomes severe.";
    } elseif (in_array("skin_rash", $selected)) {
        $result_condition = "Possible Skin Irritation / Allergy";
        $result_advice = "Avoid scratching the area and report it to the school nurse for proper checking.";
    } else {
        $result_condition = "General Mild Symptoms";
        $result_advice = "Monitor your symptoms, rest well, and report to the school nurse if they continue or get worse.";
    }

    // Save this check into the database
    $user_id = (int) $_SESSION['user_id'];
    $symptoms_for_db = implode(", ", $selected_labels);
    if ($extra_symptoms !== '') {
        $symptoms_for_db .= ($symptoms_for_db !== '' ? " | " : "") . "Description: " . $extra_symptoms;
    }
    $symptoms_text = mysqli_real_escape_string($conn, $symptoms_for_db);
    $cond_escaped = mysqli_real_escape_string($conn, $result_condition);
    $advice_escaped = mysqli_real_escape_string($conn, $result_advice);

    $sql = "INSERT INTO symptom_checks (user_id, symptoms_selected, possible_condition, advice_given)
            VALUES ($user_id, '$symptoms_text', '$cond_escaped', '$advice_escaped')";
    mysqli_query($conn, $sql);
}

include '../includes/header.php';
?>

<div class="card">
    <h2>Symptom Checker</h2>
    <div class="alert alert-info">
        This tool gives general guidance only and is <strong>not</strong> a medical diagnosis.
        Always inform the school nurse or a trusted adult if you feel unwell.
    </div>

    <form id="symptom-form" method="POST">
        <label>Select the symptoms you are experiencing:</label>
        <div class="checkbox-group">
            <?php foreach ($symptom_list as $key => $label): ?>
                <label>
                    <input type="checkbox" name="symptoms[]" value="<?php echo $key; ?>">
                    <?php echo $label; ?>
                </label>
            <?php endforeach; ?>
        </div>

        <label for="extra_symptoms" style="margin-top: 10px;">
            Describe how you feel in your own words (optional):
        </label>
        <textarea
            id="extra_symptoms"
            name="extra_symptoms"
            maxlength="500"
            placeholder="E.g. My stomach has been hurting since this morning and I feel dizzy when I stand up..."
        ><?php echo isset($extra_symptoms) ? htmlspecialchars($extra_symptoms) : ''; ?></textarea>

        <button type="submit">Check Symptoms</button>
    </form>

    <?php if ($result_condition): ?>
        <div class="result-box cat-over">
            <?php echo htmlspecialchars($result_condition); ?><br>
            <small style="font-weight:normal; display:block; margin-top:8px;">
                <?php echo htmlspecialchars($result_advice); ?>
            </small>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Your Past Checks</h3>
    <div class="table-responsive">
    <table>
        <tr><th>Date</th><th>Symptoms</th><th>Possible Condition</th></tr>
        <?php
        $user_id = $_SESSION['user_id'];
        $history = mysqli_query($conn, "SELECT * FROM symptom_checks WHERE user_id = $user_id ORDER BY date_checked DESC");
        while ($row = mysqli_fetch_assoc($history)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['date_checked']) . "</td>
                    <td>" . htmlspecialchars($row['symptoms_selected']) . "</td>
                    <td>" . htmlspecialchars($row['possible_condition']) . "</td>
                  </tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
