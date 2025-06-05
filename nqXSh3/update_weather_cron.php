<?php

$code = $_GET["code"];
$temperature = $_GET["temperature"];
$dateID = $_GET["date"];

require_once "../config.php";

$sql =
    "UPDATE dates SET code=" .
    $code .
    ", temperature='" .
    $temperature .
    "' WHERE dateID=" .
    $dateID;

if ($conn->query($sql) === true) {
    echo "Weather updated successfully";
} else {
    echo "Error updating weather: " . $conn->error . "";
}
