<!DOCTYPE html>
<?php
    //generate your own google maps API key and insert it here
    $mapsAPIKey = "AIzaSyDeWgMveeugg1FtSRqy-bhons0uiXGAZ0M";
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>WebEng 2019 - Haltestellen</title>
        <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="style.css">
        <script src="js/util.js" /></script>
        <script src="js/stops.js" /></script>
    </head>
    <body onLoad="initStopPage()">
        <?php
            //initialize stop data
            require_once("classes/Getter.class.php");
            $a = new Getter();
            $stopsList = $a -> getStopList();
        ?>

        <header>
            <h1>Web Engineering Routenplaner</h1>
            <nav>
              <ul>
                  <li><a href="index.php">Home</a></li>
                  <li><a class="active" href="stops.php">Stops</a></li>
              </ul>
            </nav>
        </header>
        <main class="stop-content">
            <aside class="map-sidebar">
                <div id="map">
                </div>
            </aside>
            <section>
                <input type="text" id="filterBox" oninput="updateFilter()" placeholder="Filter"/>
                <!--Generate list of stops as options-->
                <?php foreach($stopsList as $index => $name){ ?>
                <div class="stopentry" onclick="stopSelected(<?=$index?>)" id="stop-<?=$index ?>">
                    <span class="stop-caption"><?=$name ?></span>
                    <input type="checkbox" class="favinput" id="fav-<?=$index ?>" onclick="updateFavorite(<?=$index?>)"/>
                </div>
                <?php } ?>
            </section>
        </main>
        <footer>
                Copyright WebEng 2019, Maybe rights are reserved
        </footer>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=<?=$mapsAPIKey?>&callback=initMap">
        </script>
    </body>
</html>
