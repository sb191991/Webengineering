<?php
    require_once("model/PathNode.class.php");
    require_once("Getter.class.php");

    // provides functionality for a dijkstra implementation
    // the end of the file contains utility functions
    //
    // execute dijkstras algorithm for the given graph, startNode and starting at the given time
    function dijkstra($graph, $startNode, $startTime){
        $getter = new Getter();
        //initial node is reachable in 0 minutes
        $startNode->cost = 0;
        //setup processing queue
        $process = array($startNode); //queue of nodes to be processed

        //process nodes
        while(count($process) > 0){
            //get minimal node to process
            $currentNode = extractMinimum($process);
            //mark node as visited; costs are now final and cannot be lowered anymore
            $currentNode->visited = true;
            //get departure times for the point in time, in which we arrive at this stop
            $arrivalTime = addTime($startTime, $currentNode->cost);
            $departures = $getter->getDepartures($currentNode->getId(), $arrivalTime);

            //process all successors
            foreach($currentNode->getEdges() as $edge){
                $nextNode = $edge->getEndNode();
                //if the node has already been marked as visited, continue
                if($nextNode->visited) continue;
                //get the delay due to departure times, the delay is zero, if we do not have to switch lines
                $delay = 0;
                $preLine = $currentNode->preLine; //previous lines
                if($preLine != null && $preLine->getId() != $edge->getLine()->getId()){
                    //line is changing, so we need to get the delay amount, as we have to wait until the next departure
                    $delay = $departures->getDelay($edge->getLine()->getId(), $arrivalTime);
                }
                //if there is no more connection via this line today, skip this edge
                if($delay === -1) continue;

                if($nextNode->cost === INF){
                    array_unshift($process, $nextNode);
                }
                if($nextNode->cost > $currentNode->cost+$delay+$edge->getCost()){
                    $nextNode->cost = $currentNode->cost + $delay + $edge->getCost();
                    $nextNode->preNode = $currentNode;
                    $nextNode->preLine = $edge->getLine();
                }
            }
        }
    }

    //given a cost array and a final node, construct the path from start to goal
    function getPath($endNode){
        $currentPathNode = null;
        //catch cases, where no node can be found
        if($endNode->cost === INF){
            return null;
        }
        //we start at the last node
        $cnode = $endNode;
        $result = array();
        //construct the initial pathNode
        $currentPathNode = new PathNode($cnode->getId(), $cnode->cost, new Line(-1, "", -1));
        // prepend current node to the beginning of the array
        array_unshift($result, $currentPathNode);
        //follow the links to the initial node
        while ($cnode->preNode != null){
            $currentPathNode = new PathNode($cnode->preNode->getId(), $cnode->cost, $cnode->preLine);
            array_unshift($result, $currentPathNode);
            $cnode = $cnode->preNode;
        }
        return $result;
    }

    //find the dijkstra node with the minimal cost in the given array and remove it
    function extractMinimum(&$process){
        $minElement = null;
        $minIndex = -1;
        foreach($process as $index => $element){
            if($minElement == null || $minElement->cost > $element->cost){
                $minElement = $element;
                $minIndex = $index;
            }
        }
        array_splice($process, $minIndex, 1); // cut out the min element
        return $minElement;
    }

    // add the given amount of time to the original time, without modifying the original time object
    function addTime($original, $cost){
        if ($cost != null) {
            $copy = clone $original;
            return $copy->add(new DateInterval("PT".$cost."M"));
            // P duration designator, T time designator, M minutes
        } else
            return $original;
    }
?>
