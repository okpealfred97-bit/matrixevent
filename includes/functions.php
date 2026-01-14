<?php
function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
