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
                        "https://theliamforecast.com/nqXSh3/update_weather_cron.php?code=" .
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
                        $dateID . "<br>";
                }
            }
            $conn->close();


?>
