<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Line Overview</title>
    </head>
    <body>
        <?php
            function cmp($line1, $line2) {
                return $line1["display"] <=> $line2["display"];
            }

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "http://morgal.informatik.uni-ulm.de:8000/line/data/");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            $resp = curl_exec($curl);
            $data = json_decode($resp, true);
            $stops = $data["stops"];
            usort($data["lines"], "cmp");
            foreach($data["lines"] as $line){
                echo "<strong>Line " . $line["display"] . " (ID " . $line["id"] . ") to " . $stops[$line["heading"]] . ": </strong><br>";
                foreach($line["trip"] as $stop){
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $stops[$stop["stop"]] . " (ID " . $stop["stop"] . ") <br>";
                }
                echo "<br><br>";
            }
        ?>
    </body>
</html>
