<?php
require_once __DIR__ . "/admin.php";
require_once __DIR__ . "/weather.php";
$results = refresh_weather($conn);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>API | The Liam Forecast</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="The Liam Forecast" />
    <link rel="manifest" href="/site.webmanifest" />
    <link rel="stylesheet" href="/css/oasis.css" />
</head>

<body>
    <main class="oasis">
        <div class="wrap">
            <?php
            if (count($results) === 0) {
                echo "<div class='error'><span><strong>No gigs are within the next 2 weeks</strong></span></div>";
            }
            foreach ($results as $result) {
                if ($result["error"] === null) {
                    echo "<div class='success'><span><svg class='w-6 h-6 text-gray-800 dark:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24'><path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg></span> <span><strong>Weather updated successfully</strong></span><span>";
                } else {
                    echo "<div class='error'><span><svg class='w-6 h-6 text-gray-800 dark:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24'><path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg></span> <span><strong>Error updating weather:</strong> " .
                        e($result["error"]) .
                        "</span><span>";
                }
                echo e($result["name"]) .
                    ", " .
                    e($result["date"]) .
                    " - Temperature: " .
                    e($result["temperature"]) .
                    ", Weather Code: " .
                    e($result["code"]) .
                    ", ID: " .
                    e($result["dateID"]) .
                    "</span></div>";
            }
            ?>
            <a href="index.php" class="button"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"></path>
                </svg>
                Back to list</a>
        </div>


    </main>
</body>

</html>
