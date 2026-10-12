<?php
require_once __DIR__ . "/admin.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_check();
    $conn->close();
    end_session();
}
header("Location: index.php");
exit();
