<?php
require_once __DIR__ . "/session.php";
require_once __DIR__ . "/../config.php";

// Brute-force protection: failed logins are recorded and limited per IP address and per username
const LOGIN_WINDOW_MINUTES = 15;
const LOGIN_MAX_PER_IP = 5;
const LOGIN_MAX_PER_USERNAME = 20;

function login_fail(string $error): void
{
    $_SESSION["login_error"] = $error;
    header("Location: index.php");
    exit();
}

$username = (string) ($_POST["username"] ?? "");
$password = (string) ($_POST["password"] ?? "");
if ($username === "" || $password === "") {
    login_fail("Please fill in both the username and password fields.");
}
$ip = (string) ($_SERVER["REMOTE_ADDR"] ?? "");

$throttling = true;
try {
    $conn->query(
        "CREATE TABLE IF NOT EXISTS login_attempts (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            ip VARCHAR(45) NOT NULL,
            username VARCHAR(255) NOT NULL,
            attempted_at DATETIME NOT NULL,
            KEY ip_time (ip, attempted_at),
            KEY username_time (username, attempted_at)
        ) CHARACTER SET utf8mb4"
    );
    $conn->query("DELETE FROM login_attempts WHERE attempted_at < NOW() - INTERVAL 1 DAY");

    $stmt = $conn->prepare(
        "SELECT COALESCE(SUM(ip = ?), 0), COALESCE(SUM(username = ?), 0)
        FROM login_attempts
        WHERE attempted_at > NOW() - INTERVAL " . LOGIN_WINDOW_MINUTES . " MINUTE"
    );
    $stmt->bind_param("ss", $ip, $username);
    $stmt->execute();
    $stmt->bind_result($ipFailures, $usernameFailures);
    $stmt->fetch();
    $stmt->close();

    if ((int) $ipFailures >= LOGIN_MAX_PER_IP || (int) $usernameFailures >= LOGIN_MAX_PER_USERNAME) {
        login_fail("Too many failed attempts. Please wait " . LOGIN_WINDOW_MINUTES . " minutes and try again.");
    }
} catch (mysqli_sql_exception $e) {
    // Don't lock everyone out if the table can't be created - log it so it can be fixed
    error_log("Login throttling unavailable: " . $e->getMessage());
    $throttling = false;
}

$valid = false;
$stmt = $conn->prepare("SELECT id, password FROM accounts WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hash);
    $stmt->fetch();
    $valid = password_verify($password, $hash);
}
$stmt->close();

if (!$valid) {
    if ($throttling) {
        try {
            $stmt = $conn->prepare("INSERT INTO login_attempts (ip, username, attempted_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $ip, $username);
            $stmt->execute();
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            error_log("Could not record failed login: " . $e->getMessage());
        }
    }
    login_fail("Incorrect username and/or password.");
}

if ($throttling) {
    try {
        $stmt = $conn->prepare("DELETE FROM login_attempts WHERE ip = ? OR username = ?");
        $stmt->bind_param("ss", $ip, $username);
        $stmt->execute();
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        error_log("Could not clear failed logins: " . $e->getMessage());
    }
}
$conn->close();

// Regenerate the session ID to prevent session fixation attacks
session_regenerate_id(true);
unset($_SESSION["login_error"]);
$_SESSION["account_loggedin"] = true;
$_SESSION["account_name"] = $username;
$_SESSION["account_id"] = $id;
header("Location: list.php");
exit();
