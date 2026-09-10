<?php
require '../includes/auth.php';
require_role('student');
include '../config/db.php';
$page_title = "Announcements";
include '../includes/header.php';
?>

<div class="card">
    <h2>
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Announcements
    </h2>
    <p style="color:#5a7d85; font-size:13.5px; margin-top:-8px; margin-bottom:18px;">
        Health notices and updates from the school nurse and staff.
    </p>
    <?php
    $list = mysqli_query($conn, "SELECT * FROM announcements ORDER BY date_posted DESC");
    if (mysqli_num_rows($list) > 0) {
        echo '<ul class="announcements-list">';
        while ($row = mysqli_fetch_assoc($list)) {
            echo '<li>';
            echo '<strong>' . htmlspecialchars($row['title']) . '</strong>';
            echo '<span class="muted">' . htmlspecialchars($row['date_posted']) . '</span>';
            echo '<div>' . nl2br(htmlspecialchars($row['message'])) . '</div>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<div class="announcements-empty">';
        echo '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        echo '<p>No announcements at the moment. Check back soon.</p>';
        echo '</div>';
    }
    ?>
</div>

<?php include '../includes/footer.php'; ?>
