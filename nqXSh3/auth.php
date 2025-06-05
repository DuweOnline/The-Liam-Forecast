<?php
// Start the session
session_start();
// Change the below variables to reflect your MySQL database details
$DATABASE_HOST = "sdb-60.hosting.stackcp.net";
$DATABASE_USER = "oasisweather-3530323502e9";
$DATABASE_PASS = "N0@x£*E5B[q~";
$DATABASE_NAME = "oasisweather-3530323502e9";
// Try and connect using the info above
$con = mysqli_connect(
    $DATABASE_HOST,
    $DATABASE_USER,
    $DATABASE_PASS,
    $DATABASE_NAME
);
// Check for connection errors
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error
    exit("Failed to connect to MySQL: " . mysqli_connect_error());
}
if (!isset($_POST["username"], $_POST["password"])) {
    // Could not get the data that should have been sent
    exit("Please fill both the username and password fields!");
}
if (
    $stmt = $con->prepare(
        "SELECT id, password FROM accounts WHERE username = ?"
    )
) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param("s", $_POST["username"]);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Account exists, so bind the results to variables
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Note: remember to use password_hash in your registration file to store the hashed passwords
        if (password_verify($_POST["password"], $password)) {
            // Password is correct! User has logged in!
            // Regenerate the session ID to prevent session fixation attacks
            session_regenerate_id();
            // Declare session variables (they basically act like cookies but the data is remembered on the server)
            $_SESSION["account_loggedin"] = true;
            $_SESSION["account_name"] = $_POST["username"];
            $_SESSION["account_id"] = $id;
            // Output success message
            echo "Welcome back, " .
                htmlspecialchars($_SESSION["account_name"], ENT_QUOTES) .
                "!";
            header("Location: list.php");
            exit();
        } else {
            // Incorrect password
            echo "Incorrect username and/or password!";
        }
    } else {
        // Incorrect username
        echo "Incorrect username and/or password!";
    }

    // Close the prepared statement
    $stmt->close();
}
?>
