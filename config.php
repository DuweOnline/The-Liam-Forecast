<?php
require_once __DIR__ . "/config.local.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
} catch (mysqli_sql_exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    exit("Connection failed" . (PHP_SAPI === "cli" ? ": " . $e->getMessage() . PHP_EOL : ""));
}
$conn->set_charset("utf8mb4");

// Read every row from a result. mysqli_result::fetch_all() needs mysqlnd on PHP 8.0, which the host may not have.
function fetch_rows(mysqli_result $result): array
{
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $result->free();
    return $rows;
}

// Escape a value for HTML output
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}
