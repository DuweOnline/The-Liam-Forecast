<?php
// Session setup shared by every admin page
if (realpath($_SERVER["SCRIPT_FILENAME"] ?? "") === __FILE__) {
    http_response_code(404);
    exit();
}

// Admin pages shouldn't appear in search results
header("X-Robots-Tag: noindex, nofollow");

// Only accept session IDs the server created, only from cookies, and keep the cookie
// HTTPS-only, hidden from JavaScript, scoped to the admin and never sent cross-site
ini_set("session.use_strict_mode", "1");
ini_set("session.use_only_cookies", "1");
session_set_cookie_params([
    "lifetime" => 0,
    "path" => "/nqXSh3/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Strict",
]);
session_start();

// Log out after two hours without activity
const SESSION_IDLE_SECONDS = 7200;
if (isset($_SESSION["last_activity"]) && time() - $_SESSION["last_activity"] > SESSION_IDLE_SECONDS) {
    $_SESSION = [];
    session_regenerate_id(true);
}
$_SESSION["last_activity"] = time();

function end_session(): void
{
    $_SESSION = [];
    $params = session_get_cookie_params();
    setcookie(session_name(), "", [
        "expires" => time() - 3600,
        "path" => $params["path"],
        "secure" => $params["secure"],
        "httponly" => $params["httponly"],
        "samesite" => $params["samesite"],
    ]);
    session_destroy();
}
