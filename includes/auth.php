<?php

session_start();


function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /health_assistant/index.php");
        exit();
    }
}


function require_role($role) {
    require_login();
    if ($_SESSION['role'] !== $role) {
        header("Location: /health_assistant/index.php");
        exit();
    }
}


function initials($name) {
    $parts = preg_split('/\s+/', trim($name));
    $init = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        if ($p !== '') {
            $init .= mb_strtoupper(mb_substr($p, 0, 1));
        }
    }
    return $init !== '' ? $init : '?';
}


function time_greeting() {
    $hour = (int) date('G');
    if ($hour < 12) return 'Good morning';
    if ($hour < 17) return 'Good afternoon';
    return 'Good evening';
}
?>
