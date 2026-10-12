<?php
// Shared setup for logged-in admin pages
require_once __DIR__ . "/session.php";
if (!isset($_SESSION["account_loggedin"])) {
    header("Location: index.php");
    exit();
}
require_once __DIR__ . "/../config.php";

function csrf_field(): string
{
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf" value="' . e($_SESSION["csrf"]) . '">';
}

function csrf_check(): void
{
    if (empty($_SESSION["csrf"]) || !hash_equals($_SESSION["csrf"], (string) ($_POST["csrf"] ?? ""))) {
        http_response_code(400);
        exit("Invalid form submission - please go back, refresh and try again.");
    }
}

function valid_date(string $date): bool
{
    $parsed = DateTime::createFromFormat("Y-m-d", $date);
    return $parsed !== false && $parsed->format("Y-m-d") === $date;
}

// aria attributes that tie a form field to its error message
function error_attrs(string $error, string $messageId): string
{
    return $error === "" ? "" : ' aria-invalid="true" aria-describedby="' . e($messageId) . '"';
}
