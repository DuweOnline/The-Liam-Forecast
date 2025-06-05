<?php

require_once "../config.php";
$sql = "SELECT dates.dateID, locations.name, locations.country, locations.latitude, locations.longitude, dates.date, dates.code, dates.temperature
FROM locations
INNER JOIN dates ON locations.locationID = dates.locationID
WHERE dates.date < DATE_ADD(CURDATE(), INTERVAL 52 WEEK) ORDER BY dates.date ASC";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $date = date_create($row["date"]);
        echo "<tr><td>" .
                        $row["dateID"] .
                        "</td><td>" .
                        date_format($date, "jS F, Y") .
                        "</td><td>" .
                        $row["name"] .
                        "</td><td>" .
                        $row["country"] .
                        "</td><td>" .
                        $row["latitude"] .
                        "</td><td>" .
                        $row["longitude"] .
                        "</td><td>" .
                        $row["code"] .
                        "</td><td>" .
                        $row["temperature"] .
                        "</td><td><a href='update.php?id=" .
                        $row["dateID"] .
                        "'><svg aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24'><path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z'/></svg></a></td><td><a href='delete.php?id=" .
                        $row["dateID"] .
                        "'><svg class='w-6 h-6 text-gray-800 dark:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24'><path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z'/></svg></a></td></tr>";
    }
} else {
    echo "<div class='dropdown-disabled'>No gigs are within the next 2 weeks - please try later</div>";
}
mysqli_close($conn);
