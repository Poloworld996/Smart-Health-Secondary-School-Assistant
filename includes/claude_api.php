<?php
/*
    Helper: ask_claude_for_symptom_check()
    ---------------------------------------
    Sends the student's selected symptoms to the Claude API and asks for
    a short, non-diagnostic "possible condition" + "advice" pair, returned
    as JSON so it drops straight into the existing database columns.

    Returns an array: ['condition' => ..., 'advice' => ...]
    Returns null on any failure (caller should fall back to local rules).
*/

require_once __DIR__ . '/../config/api.php';

function ask_claude_for_symptom_check($symptom_labels) {
    // $symptom_labels is a plain array of human-readable symptom names,
    // e.g. ["Fever", "Headache", "Fatigue / Tiredness"]

    if (empty($symptom_labels)) {
        return null;
    }

    $symptoms_text = implode(", ", $symptom_labels);

    $system_prompt =
        "You are a school health assistant helping a secondary school student " .
        "understand mild symptoms. You are NOT a doctor and must never give a " .
        "definitive diagnosis. Rules:\n" .
        "- Always phrase the condition as a possibility (e.g. 'Possible Common Cold'), never a certainty.\n" .
        "- If the symptoms could indicate anything serious or an emergency " .
        "(e.g. difficulty breathing, chest pain, severe symptoms), say so clearly " .
        "and advise seeing the school nurse or emergency help immediately.\n" .
        "- Keep advice short, practical, and appropriate for a teenager (rest, fluids, " .
        "when to see the nurse, basic self-care).\n" .
        "- Always end by reminding the student to inform the school nurse or a trusted adult " .
        "if symptoms continue or worsen.\n" .
        "- Respond ONLY with valid JSON in this exact format, nothing else, no markdown fences:\n" .
        '{"condition": "Possible X", "advice": "short advice text"}';

    $user_message = "Student's selected symptoms: " . $symptoms_text;

    $payload = [
        "model" => ANTHROPIC_MODEL,
        "max_tokens" => 300,
        "system" => $system_prompt,
        "messages" => [
            ["role" => "user", "content" => $user_message]
        ]
    ];

    $ch = curl_init(ANTHROPIC_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "x-api-key: " . ANTHROPIC_API_KEY,
        "anthropic-version: 2023-06-01"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); // don't let a slow API call hang the page

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error || $http_code !== 200 || !$response) {
        error_log("Claude API symptom check failed: HTTP $http_code, cURL error: $curl_error");
        return null;
    }

    $data = json_decode($response, true);
    if (!isset($data['content'][0]['text'])) {
        error_log("Claude API symptom check: unexpected response shape: " . $response);
        return null;
    }

    $text = trim($data['content'][0]['text']);
    // Strip markdown fences just in case the model adds them
    $text = preg_replace('/^```json\s*|\s*```$/m', '', $text);

    $parsed = json_decode($text, true);
    if (!isset($parsed['condition']) || !isset($parsed['advice'])) {
        error_log("Claude API symptom check: could not parse JSON: " . $text);
        return null;
    }

    return [
        'condition' => $parsed['condition'],
        'advice' => $parsed['advice']
    ];
}
?>
