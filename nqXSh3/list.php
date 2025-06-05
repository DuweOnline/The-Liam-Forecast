<?php
session_start();
if (!isset($_SESSION["account_loggedin"])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LIST | The Liam Forecast</title>
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
            <table class="buttonTable">
                <tbody>
                    <tr>
                        <td>
                            <a href="add_date.php" class="button"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M4 9.05H3v2h1v-2Zm16 2h1v-2h-1v2ZM10 14a1 1 0 1 0 0 2v-2Zm4 2a1 1 0 1 0 0-2v2Zm-3 1a1 1 0 1 0 2 0h-2Zm2-4a1 1 0 1 0-2 0h2Zm-2-5.95a1 1 0 1 0 2 0h-2Zm2-3a1 1 0 1 0-2 0h2Zm-7 3a1 1 0 0 0 2 0H6Zm2-3a1 1 0 1 0-2 0h2Zm8 3a1 1 0 1 0 2 0h-2Zm2-3a1 1 0 1 0-2 0h2Zm-13 3h14v-2H5v2Zm14 0v12h2v-12h-2Zm0 12H5v2h14v-2Zm-14 0v-12H3v12h2Zm0 0H3a2 2 0 0 0 2 2v-2Zm14 0v2a2 2 0 0 0 2-2h-2Zm0-12h2a2 2 0 0 0-2-2v2Zm-14-2a2 2 0 0 0-2 2h2v-2Zm-1 6h16v-2H4v2ZM10 16h4v-2h-4v2Zm3 1v-4h-2v4h2Zm0-9.95v-3h-2v3h2Zm-5 0v-3H6v3h2Zm10 0v-3h-2v3h2Z" />
                                </svg> New Date</a>
                        </td>
                        <td>
                            <a href="add_location.php" class="button"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z" />
                                </svg> New Location
                            </a>
                        </td>
                        <td>
                            <a href="link_date.php" class="button"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961" />
                                </svg> Link Date to Location</a>
                        </td>
                        <td>
                            <a id="api" href="api.php" class="button"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.3333 14.6528c.7073 0 1.3856-.2101 1.8857-.7101.5-.5001.781-1.2493.781-1.9565 0-.7073-.281-1.3856-.781-1.8857-.5001-.50007-1.1784-.78102-1.8857-.78102h-.0222c.0133-.14755.0222-.296.0222-.44444-.0033-1.17924-.4328-2.31753-1.2092-3.20508-.7765-.88756-1.8476-1.46455-3.0159-1.62465-1.1683-.1601-2.3551.10749-3.34174.75344-.98658.64596-1.70644 1.62675-2.0269 2.76162-.06223-.00355-.12089-.01866-.184-.01866-.943 0-1.91009.36598-2.57689 1.03277C4.31188 9.24128 4 10.1543 4 11.0973c0 .943.3746 1.8473 1.0414 2.5141.45292.4529 1.01546.7711 1.62527.9285M12 14v3m0 0v3m0-3-2.12134-2.1212M12 17l2.1213 2.1214M12 17H9m3 0h3m-3 0-2.12134 2.1213M12 17l2.1213-2.1213M6 18h.01M18 18h.01" />
                                </svg>

                                Recheck Weather</a>

                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="apiCheck"></div>
        </div>
        <div class="wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Country</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Code</th>
                        <th>&deg;C</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
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
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>