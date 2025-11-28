<!DOCTYPE html>
<html lang="en-GB">
<head>
    <!-- Google Tag Manager -->
    <script>
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-TBGDRCG2');
    </script>
    <!-- End Google Tag Manager -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#a1caf2">
    <title>The Liam Forecast | Find the weather for your Oasis gig</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="The Liam Forecast" />
    <link rel="manifest" href="/site.webmanifest" />
    <meta name="author" content="https://duwe.co.uk">
    <meta name="description" content="So you've bagged yourself a ticket to the Oasis reunion tour this summer? MEGA. But will it rain or sheeeeiiine? Built by https://duwe.co.uk" />
    <meta property="og:description" content="So you've bagged yourself a ticket to the Oasis reunion tour this summer? MEGA. But will it rain or sheeeeiiine? Built by https://duwe.co.uk" />
    <meta property="og:locale" content="en_GB" />
    <meta property="og:site_name" content="The Liam Forecast" />
    <meta property="og:title" content="The Liam Forecast" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://http://theliamforecast.com/" />
    <meta property="og:image" content="https://theliamforecast.com/img/liam.png" />
    <link rel="canonical" href="https://theliamforecast.com" />
    <link rel="stylesheet" href="css/oasis.css">
    <script type="module" src="js/oasis.js"></script>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TBGDRCG2" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <main class="oasis">

        <h1>The Liam Forecast</h1>

        <div class="row">

            <div class="col animations">
                <button id="back">BACK</button>
                <canvas id="weatherBg" class="hide"></canvas>
                <div class="item img">
                    <img srcset="img/liam-400w.webp 400w, img/liam-600w.webp 600w, img/liam-800w.webp 800w, img/liam-1000w.webp 1000w, img/liam-1200w.webp 1200w" sizes="(max-width: 400px) 400px, (max-width: 600px) 600px, (max-width: 800px) 800px, (max-width: 1000px) 1000px, (min-width: 1001px) 1200px" src="img/liam.png" alt="The Liam Forecast" width="1228" height="1904" fetchpriority="high">
                    <div class="lyric"></div>
                </div>
            </div>

            <div class="col">
                <div class="item">
                    <img src="img/title.jpg" alt="The Liam Forecast" class="title" />
                    <h2 class="intro">So you've bagged yourself a ticket to the Oasis reunion tour this summer? <br>MEGA. But will it rain or sheeeeiiine? Will you need your parka or your shades?</h2>
                    <p><strong>Pick yer gig, RKID, and Liam will let you know. <br>Oh, and make sure you’ve got your sound on.</strong></p>
                    <div class="dropdown">
                        <div class="dropdown-toggle" id="dropdown" tabindex="0">
                            <span>Please select your date</span>
                            <svg class="arrow" role="button" alt="Please select your date" name="Please select your date" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-menu" id="dropdownMenu" tabindex="0">
                            <?php
                            require_once "config.php";
                            $sql = "SELECT locations.name, locations.country, locations.latitude, locations.longitude, dates.date, dates.code, dates.temperature
						FROM locations
						INNER JOIN dates ON locations.locationID = dates.locationID
						WHERE dates.date < DATE_ADD(CURDATE(), INTERVAL 2 WEEK) ORDER BY dates.date ASC";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $code = $row["code"];
                                    switch ($code) {
                                        case 0:
                                            $conditions = "Clear Sky";
                                            $bg = "sunshine";
                                            $lyric =
                                                "Sunsheeeine. It’s warm outside!";
                                            $sound = "audio/sunshine.mp3";
                                            break;
                                        case 1:
                                        case 2:
                                        case 3:
                                            $conditions =
                                                "Mainly Clear<br>Partly Cloudy";
                                            $bg = "cloudy";
                                            $lyric = "The weaaather is calm";
                                            $sound = "audio/calm.mp3";
                                            break;
                                        case 45:
                                        case 48:
                                            $conditions = "Fog";
                                            $bg = "fog";
                                            $lyric = "Fog!";
                                            $sound = "audio/fog.mp3";
                                            break;
                                        case 51:
                                        case 53:
                                        case 55:
                                            $conditions = "Light Drizzle";
                                            $bg = "rain";
                                            $lyric = "Raiiin!";
                                            $sound = "audio/rain.mp3";
                                            break;
                                        case 56:
                                        case 57:
                                            $conditions = "Freezing Drizzle";
                                            $bg = "rain";
                                            $lyric = "Cooold and wind and rain";
                                            $sound = "audio/coldwindrain.mp3";
                                            break;
                                        case 61:
                                        case 63:
                                        case 65:
                                        case 66:
                                        case 67:
                                            $conditions = "Rain";
                                            $bg = "rain";
                                            $lyric =
                                                "Raiiin! I don’t see no shine";
                                            $sound = "audio/rainnoshine.mp3";
                                            break;
                                        case 71:
                                        case 73:
                                        case 75:
                                        case 77:
                                            $conditions = "Snow Fall";
                                            $bg = "snow";
                                            $lyric = "Snowstooorm";
                                            $sound = "audio/snow.mp3";
                                            break;
                                        case 80:
                                        case 81:
                                        case 82:
                                        case 85:
                                        case 86:
                                            $conditions = "Rain Showers";
                                            $bg = "rain";
                                            $lyric = "Raiiin! It soaks you to the bone";
                                            $sound = "audio/rainasitsoaksyoutothebone.mp3";
                                            break;
                                        case 95:
                                        case 96:
                                        case 99:
                                            $conditions = "Thunderstorm";
                                            $bg = "thunder";
                                            $lyric = "A storm!";
                                            $sound = "audio/storm.mp3";
                                            break;
                                    }
                                    $date = date_create($row["date"]);
                                    echo "<div class='dropdown-item' 
                                        data-sound='" .
                                        $sound .
                                        "' 
                                        data-lyric='" .
                                        $lyric .
                                        "' data-bg='" .
                                        $bg .
                                        "' data-day='" .
                                        date_format($date, "l") .
                                        "' data-date='" .
                                        date_format($date, "jS F, Y") .
                                        "' data-location='" .
                                        $row["name"] .
                                        ", " .
                                        $row["country"] .
                                        "' data-code='" .
                                        $conditions .
                                        "' data-temperature='" .
                                        $row["temperature"] .
                                        "'>" .
                                        $row["country"] .
                                        ": " .
                                        $row["name"] .
                                        " - " .
                                        date_format($date, "jS F Y") . "</div>";
                                }
                            } else {
                                echo "<div class='dropdown-disabled'>No gigs are within the next 2 weeks - please try later</div>";
                            }
                            mysqli_close($conn);
                            ?>
                        </div>
                    </div>

                    <p class="mb-1 md:mb-4"><small>Weather predictions only stretch to two weeks ahead. <br>So, if your date doesn't show yet, don't lose your marbles. <br>Check back in a bit.</small></p>
                    <p class="mb-0"><small class="hidden md:block "><a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#104;&#101;&#108;&#108;&#111;&#64;&#116;&#104;&#101;&#108;&#105;&#97;&#109;&#102;&#111;&#114;&#101;&#99;&#97;&#115;&#116;&#46;&#99;&#111;&#109;">&#104;&#101;&#108;&#108;&#111;&#64;&#116;&#104;&#101;&#108;&#105;&#97;&#109;&#102;&#111;&#114;&#101;&#99;&#97;&#115;&#116;&#46;&#99;&#111;&#109;</a> <span class="hidden">Built by <a href="https://duwe.co.uk">duwe.co.uk</a></span></small></p>
                </div>

                <div class="weather">

                </div>

                <div class="allsounds">
                    <button id="reverse">Back</button>
                    <div class="buttons">
                        <button class="allSoundButton" data-audio="audio/sunshine.mp3" data-bg="sunshine" data-lyric="Sunsheeeine. It’s warm outside!">Clear Sky</button>
                        <button class="allSoundButton" data-audio="audio/calm.mp3" data-bg="cloudy" data-lyric="The weaaather is calm">Mainly Clear<br>Partly Cloudy</button>
                        <button class="allSoundButton" data-audio="audio/fog.mp3" data-bg="fog" data-lyric="Fog!">Fog</button>
                        <button class="allSoundButton" data-audio="audio/rain.mp3" data-bg="rain" data-lyric="Raiiin!">Light Drizzle</button>
                        <button class="allSoundButton" data-audio="audio/coldwindrain.mp3" data-bg="rain" data-lyric="Cooold and wind and rain">Freezing Drizzle</button>
                        <button class="allSoundButton" data-audio="audio/rainnoshine.mp3" data-bg="rain" data-lyric="Raiiin! I don’t see no shine">Rain</button>
                        <button class="allSoundButton" data-audio="audio/snow.mp3" data-bg="snow" data-lyric="Snowstooorm">Snow Fall</button>
                        <button class="allSoundButton" data-audio="audio/rain.mp3" data-bg="rain" data-lyric="Raiiin!">Rain Showers</button>
                        <button class="allSoundButton" data-audio="audio/storm.mp3" data-bg="thunder" data-lyric="A storm!">Thunderstorm</button>
                    </div>
                </div>

            </div>

        </div>
        <audio id="snippets" autoplay="autoplay"></audio>
    </main>
</body>

</html>