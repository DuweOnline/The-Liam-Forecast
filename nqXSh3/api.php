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
            $servername = "sdb-60.hosting.stackcp.net";
            $username = "oasisweather-3530323502e9";
            $password = "N0@x£*E5B[q~";
            $conn = mysqli_connect(
                $servername,
                $username,
                $password,
                $username
            );
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $conn->set_charset("utf8mb4");
            $sql = "SELECT locations.latitude, locations.longitude, dates.date, dates.dateID
        FROM locations
        INNER JOIN dates ON locations.locationID = dates.locationID
        WHERE dates.date < DATE_ADD(CURDATE(), INTERVAL 2 WEEK) ORDER BY dates.date ASC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $curl = curl_init();
                    curl_setopt(
                        $curl,
                        CURLOPT_URL,
                        "https://api.open-meteo.com/v1/forecast?latitude=" .
                            $row["latitude"] .
                            "&longitude=" .
                            $row["longitude"] .
                            "&daily=weather_code,apparent_temperature_max&start_date=" .
                            $row["date"] .
                            "&end_date=" .
                            $row["date"]
                    );
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($curl);
                    $writeable = json_decode($output, true);
                    curl_close($curl);
                    $code = $writeable["daily"]["weather_code"][0];
                    $temperature =
                        $writeable["daily"]["apparent_temperature_max"][0];
                    $dateID = $row["dateID"];
                    $cURLConnection = curl_init();

                    curl_setopt(
                        $cURLConnection,
                        CURLOPT_URL,
                        "https://theliamforecast.com/nqXSh3/update_weather.php?code=" .
                            $code .
                            "&temperature=" .
                            $temperature .
                            "&date=" .
                            $dateID
                    );
                    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

                    $phoneList = curl_exec($cURLConnection);
                    curl_close($cURLConnection);
                    echo $phoneList .
                        "Temperature: " .
                        $temperature .
                        ", Weather Code: " .
                        $code .
                        ", ID: " .
                        $dateID .
                        "</div>";
                }
            }
            $conn->close();
            ?>
            <a href="index.php" class="button"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"></path>
                </svg>
                Back to list</a>
        </div>


    </main>
</body>

</html>