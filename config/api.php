<?php

define('ANTHROPIC_API_KEY', 'YOUR_API_KEY_HERE');
define('ANTHROPIC_API_URL', 'https://api.anthropic.com/v1/messages');
define('ANTHROPIC_MODEL', 'claude-haiku-4-5-20251001'); 

// --- Gemini (optional alternative) ---
// Replace with your own API key in your local environment. Do not commit real secrets.
define('GEMINI_API_KEY', 'YOUR_GEMINI_API_KEY_HERE');
define('GEMINI_MODEL', 'gemini-3.5-flash');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/' . GEMINI_MODEL . ':generateContent');
?>
