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
    echo "<div class='success'><span><svg class='w-6 h-6 text-gray-800 dark:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24'><path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg></span> <span><strong>Weather updated successfully</strong></span><span>";
} else {
    echo "<div class='error'><span><svg class='w-6 h-6 text-gray-800 dark:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24'><path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg></span> <span><strong>Error updating weather:</strong> " .
        $conn->error .
        "";
}
