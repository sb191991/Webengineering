<?php
    require_once("model/Graph.class.php");
    require_once("model/Node.class.php");
    require_once("model/Edge.class.php");
    require_once("model/PathNode.class.php");

    //find any path in the given graph from start to end
    function dfsearch($graph, $startId, $endId){
        $startNode = $graph->findNode($startId);
        $endNode = $graph->findNode($endId);
        $visited = array();
        $path = dfsearchRec($graph, $startNode, $endNode, $visited);
        if ($path != -1)
            $path = convertToPathNodes($path);
        return $path;
    }
    //recursive path finding implementation
    //additionally is passed an array containing already visited things
    function dfsearchRec($graph, $currentNode, $endNode, &$visited){
        //mark the node as visited
        array_push($visited, $currentNode);
        //if we have reached the end point, we have found a path
        if($currentNode === $endNode){
            $result = array();
            array_push($result, $endNode);
            return $result;
        }
        //otherwise, follow all outgoing edges and recurse through them
        foreach($currentNode->getEdges() as $edge){
            //skip nodes we have already seen
	  if(in_array($edge->getEndNode(), $visited, true)) // true -> strict: consider also the type
                continue;
            //get recursive result
            $rec = dfsearchRec($graph, $edge->getEndNode(), $endNode, $visited);
            //if that edge leads to the goal somehow, the current node is also
            //part of the path to the end node
            if($rec !== -1){ // array is non-empty
                // add node to the beginning of the array
                array_unshift($rec, $currentNode);
                return $rec;
            }
        }
        //no path found
        return -1;
    }

    //given an array with nodes from start to end node, construct an array of
    //pathNode objects
    function convertToPathNodes($path){
        $result = array();
        for($i = 0; $i < count($path)-1; $i++){
            $edge = $path[$i]->getEdge($path[$i+1]);
            $pathNode = new PathNode($path[$i]->getId(), $edge->getCost(), $edge->getLine());
            array_push($result, $pathNode);
        }
        return $result;
    }
?>
