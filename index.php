<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300&display=swap" rel="stylesheet">
    <title>Fun with Apis</title>
</head>
<body>
    <!-- getting news from api -->
    <?php
        $date = new DateTime();
        $date = $date->format("y:m:d h:i:s");
        $logtext = "[".$date."]";
        $input = "";
        if(isset($_GET["search"])) {
            $input = $_GET["search"];
            $logtext.=" search input = ".$input;
        } else {
            $logtext.=" search input=default";
        }
        // encode input so to avoid errors
        $input = rawurlencode($input);
        $url = "https://content.guardianapis.com/search?q=".$input."&api-key=d09d67a8-2c3e-461f-be77-788f360a0160";
        $defaults = array(
            CURLOPT_URL             => $url,
            CURLOPT_POST            => false,
            CURLOPT_HEADER          => false,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYHOST  => false,
        );
        $curl = curl_init();
        curl_setopt_array($curl, $defaults);
        $curl_response = curl_exec($curl);
        $json_obj_news = json_decode($curl_response, true);
    ?>

    <!-- getting weather for windsor  -->
    <?php
        //windsor = 2391585
        $location = 2391585;
        $url = "https://www.metaweather.com/api/location/".$location."/";
        $defaults = array(
            CURLOPT_URL             => $url,
            CURLOPT_POST            => false,
            CURLOPT_HEADER          => false,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYHOST  => false,
        );
        curl_setopt_array($curl, $defaults);
        $curl_response = curl_exec($curl);
        $json_obj_weather = json_decode($curl_response, true);
    ?>

    <!-- container  -->
    <div class="container">
        <!-- navbar -->
        <div class="navbar">
            <div class="title">
                <a href="http://localhost/fun-with-apis/index.php"> Fun with apis </a>
            </div>
            <div>
                <a href="https://open-platform.theguardian.com/documentation/" class="nav-link"> News API </a>
                &nbsp;
                &nbsp;
                <a href="https://www.metaweather.com/api/" class="nav-link"> Weather API </a>
            </div>
        </div>

        <!-- body -->
        <div class="card-container">
            <!-- news card  -->
            <div class="card">
                <!-- title -->
                <h2>
                    News
                </h2>
                <!-- content  -->
                <p>
                    <form method="get" action="http://localhost/fun-with-apis/index.php">
                        <input name="search" type="text" placeholder="search" class="input-text"/>
                        <input type="submit" class="btn" value="Go">
                    </form>         
                    <h3>
                        Top News
                    </h3>
                    <?php
                        if(sizeof($json_obj_news['response']['results']) >= 4) {
                            for($i = 0; $i < 4; $i++) {
                                $j = $i + 1;
                                echo "<div class='news'> <a class='news-link' href=".$json_obj_news['response']['results'][$i]['webUrl'].">".$j.". ".$json_obj_news['response']['results'][$i]['webTitle']."</a></div>";
                            }
                        } else {
                            echo "Sorry, couldn't find any results";
                        }
                    ?>
                    
                </p>
            </div>

            <!-- weather card  -->
            <div class="card weather-card">
                <h2>
                    Weather
                </h2>
                <!-- weather information -->
                <div class="weather">
                    <div class="weather-info">
                        <?php
                            echo "<b>Min Temp: </b>".$json_obj_weather['consolidated_weather'][0]['min_temp']." C<br>";
                            echo "<b>Max Temp: </b>".$json_obj_weather['consolidated_weather'][0]['max_temp']." C<br>";
                            echo "<b>Temp: </b>".$json_obj_weather['consolidated_weather'][0]['the_temp']." C<br>";
                            echo "<b>Wind Speed: </b>".$json_obj_weather['consolidated_weather'][0]['wind_speed']." MPH<br>";
                            echo "<b>Wind Direction: </b>".$json_obj_weather['consolidated_weather'][0]['wind_direction']."<br>";
                            echo "<b>Weather State: </b>".$json_obj_weather['consolidated_weather'][0]['weather_state_name']."<br>";
                            echo "<b>Air Pressure: </b>".$json_obj_weather['consolidated_weather'][0]['air_pressure']." mbar<br>";
                            echo "<b>Visibility: </b>".$json_obj_weather['consolidated_weather'][0]['visibility']." Miles<br>";
                            echo "<b>Humidity: </b>".$json_obj_weather['consolidated_weather'][0]['humidity']." %<br>";
                            echo "<b>Location: </b>: Windsor, CA";
                            $icon = $json_obj_weather['consolidated_weather'][0]['weather_state_abbr'];
                            $img_url = "https://www.metaweather.com/static/img/weather/png/64/".$icon.".png";
                        ?>
                    </div>
                    <!-- weather image -->
                    <div class="weather-icon">
                        <?php echo "<img width=90 src=".$img_url." />" ?> 
                    </div>
                </div>
            </div> 
        </div>

        <!-- footer  -->
        <div class="footer">
            <span>
                Copyright&copy; 2020 | Group 7
            </span>
        </div>
    </div>

    <?php
        $logfile = fopen("transaction.log", "a");
        $logtext.= " | fetching weather info for location = ".$location;
        fwrite($logfile, $logtext."\n");
    ?>
</body>
</html>