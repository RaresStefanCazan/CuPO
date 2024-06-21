<?php
if (isset($_COOKIE['user_email'])) {
    $username = urldecode($_COOKIE['user_email']);
    echo "Cookie value: " . htmlspecialchars($username);
} else {
    echo "Cookie 'user_email' is not set.";
}
?>