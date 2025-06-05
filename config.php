<?php
$servername = "sdb-60.hosting.stackcp.net";
$username = "oasisweather-3530323502e9";
$password = "N0@x£*E5B[q~";
$conn = mysqli_connect($servername, $username, $password, $username);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$conn->set_charset("utf8mb4");
?>
