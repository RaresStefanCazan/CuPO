<?php

$db_server = "127.0.0.1";  // Folosește același host ca în configurarea phpMyAdmin
$db_user = "root";
$db_pass = "";  // Lasă-l gol dacă nu ai setat o parolă pentru root
$db_name = "users";  // Asigură-te că baza de date există

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
if (empty($db_pass)) {
    echo "Parola este goală";
} else {
    echo "Parola NU este goală: $db_pass";
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . " - " . mysqli_connect_errno());
}

echo "Connected successfully";

?>
