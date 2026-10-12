<?php
// Shared weather refresh, used by cron.php (command line) and api.php (admin "Recheck Weather").
if (realpath($_SERVER["SCRIPT_FILENAME"] ?? "") === __FILE__) {
    http_response_code(404);
    exit();
}

// Open-Meteo only forecasts about two weeks ahead, so only gigs from today up to then are refreshed
const WEATHER_WINDOW_SQL = "dates.date >= CURDATE() AND dates.date < DATE_ADD(CURDATE(), INTERVAL 2 WEEK)";

function fetch_forecast(string $latitude, string $longitude, string $date): array
{
    $url =
        "https://api.open-meteo.com/v1/forecast?" .
        http_build_query([
            "latitude" => $latitude,
            "longitude" => $longitude,
            "daily" => "weather_code,apparent_temperature_max",
            "timezone" => "auto",
            "start_date" => $date,
            "end_date" => $date,
        ]);

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 20,
    ]);
    $output = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);

    if ($output === false) {
        throw new RuntimeException("Request to Open-Meteo failed: " . $error);
    }
    $data = json_decode($output, true);
    if ($status !== 200 || !is_array($data)) {
        $reason = is_array($data) && isset($data["reason"]) ? ": " . $data["reason"] : "";
        throw new RuntimeException("Open-Meteo returned HTTP " . $status . $reason);
    }

    $code = $data["daily"]["weather_code"][0] ?? null;
    $temperature = $data["daily"]["apparent_temperature_max"][0] ?? null;
    if (!is_numeric($code) || !is_numeric($temperature)) {
        throw new RuntimeException("No forecast available for " . $date);
    }
    return [(int) $code, (string) $temperature];
}

function refresh_weather(mysqli $conn): array
{
    $rows = fetch_rows(
        $conn->query(
            "SELECT locations.name, locations.country, locations.latitude, locations.longitude, dates.date, dates.dateID
            FROM locations
            INNER JOIN dates ON locations.locationID = dates.locationID
            WHERE " . WEATHER_WINDOW_SQL . " ORDER BY dates.date ASC"
        )
    );

    $update = $conn->prepare("UPDATE dates SET code = ?, temperature = ? WHERE dateID = ?");
    $results = [];
    foreach ($rows as $row) {
        $result = [
            "name" => $row["name"],
            "country" => $row["country"],
            "date" => $row["date"],
            "dateID" => (int) $row["dateID"],
            "code" => null,
            "temperature" => null,
            "error" => null,
        ];
        try {
            [$code, $temperature] = fetch_forecast($row["latitude"], $row["longitude"], $row["date"]);
            $dateID = $result["dateID"];
            $update->bind_param("isi", $code, $temperature, $dateID);
            $update->execute();
            $result["code"] = $code;
            $result["temperature"] = $temperature;
        } catch (RuntimeException | mysqli_sql_exception $e) {
            $result["error"] = $e->getMessage();
        }
        $results[] = $result;
    }
    $update->close();
    return $results;
}
