<?php
    require_once("classes/Getter.class.php");
    require_once("classes/Dijkstra.php");
    require_once("classes/model/Node.class.php");
    require_once("classes/RouteFormatter.php");

    //attempt to find an optimal path in terms of time from a to b

    header("content-type: application/json");
    if((!isset($_GET['s'])) && (!isset($_GET['e']))){
        //no valid parameters passed
        echo json_encode(array("type" => "error", "msg" => "Keine Start und Endhaltestelle angegeben"));
        die();
    } else{
        $s = $_GET['s'];
        $e = $_GET['e'];
        $a = new Getter();
        $graph = $a->getGraph();
        $start = $graph->findNode($s);
        $end = $graph->findNode($e);

        //validate start and end point
        if($start === null){
            echo json_encode(array("type" => "error", "msg" => "Die Starthaltestelle \"" . $start . "\" konnte nicht gefunden werden"));
        }
        else if($end === null){
            echo json_encode(array("type" => "error", "msg" => "Die Endhaltestelle \"" . $end . "\" konnte nicht gefunden werden"));
        }
        else{
            //obtain current point of time as a reference
            $startTime = new DateTime();
	    $startTime->setTime(15, 00); // for better comparison, use 15:00

            //get costs via dijkstra
            $costs = dijkstra($graph, $start, $startTime);
            // $s = serialize($graph);
            // file_put_contents('graph', $s);

            //obtain the optimal path from the costs
            $path = getPath($end);

            $formattedPath = formatRoute($path, $start, $end, $startTime);

            if($formattedPath === null){
                echo json_encode(array("type" => "error", "msg" => "Kein Pfad von ".$start->getId()." nach ".$end->getId()." gefunden."));
                die();
            }
            //return success
            echo json_encode(array("type" => "success", "msg" => $formattedPath));
        }
    }

//{"type":"success","msg":{"start":93,"end":70,"startTime":"15:00","route":[{"line":3,"display":"8","heading":161,"trip":[{"time":"15:02","stop":93},{"time":"15:09","stop":46}]},{"line":11,"display":"2","heading":116,"trip":[{"time":"15:10","stop":25},{"time":"15:12","stop":199},{"time":"15:13","stop":13},{"time":"15:13","stop":70}]}]}}
    ?>
