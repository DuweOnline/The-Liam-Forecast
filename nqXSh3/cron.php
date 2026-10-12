<?php
// Refreshes the stored forecast for gigs in the next two weeks.
// Run from the command line, e.g: php /home/.../public_html/nqXSh3/cron.php
if (PHP_SAPI !== "cli") {
    http_response_code(404);
    exit();
}

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/weather.php";

$failures = 0;
foreach (refresh_weather($conn) as $result) {
    if ($result["error"] === null) {
        echo "Weather updated successfully:" . PHP_EOL;
    } else {
        $failures++;
        echo "Error updating weather: " . $result["error"] . PHP_EOL;
    }
    echo "Location: " . $result["name"] . ", " . $result["country"] . PHP_EOL .
        "Date: " . $result["date"] . PHP_EOL .
        "Temperature: " . $result["temperature"] . PHP_EOL .
        "Weather Code: " . $result["code"] . PHP_EOL .
        "ID: " . $result["dateID"] . PHP_EOL .
        PHP_EOL;
}
$conn->close();

exit($failures > 0 ? 1 : 0);
