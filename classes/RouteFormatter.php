<?php

require_once("model/Node.class.php");

function formatRoute($path, $start, $end, $startTime){
    if($path == null){
        return null;
    }
    //assemble output object, order by line taken from point to point
    //this is to alleviate some pressure from the frontend
    $root = array();
    $current = null;
    $cline = -1;
    foreach($path as $pnode){
        //if we are at the first stop, create the initial sub-array
        if($current === null){
            $cline = $pnode->getLine()->getId();
            $current = array(
                "line" => $pnode->getLine()->getId(),
                "display" => $pnode->getLine()->getDisplay(),
                "heading" => $pnode->getLine()->getHeading(),
                "trip" => array()
            );
        }
        //push current stop onto the current trip
        array_push($current["trip"], array("time" => addTime($startTime, $pnode->getCost())->format("H:i"), "stop" => $pnode->getId()));
        //if we need to take a different line to the next stop, create a new trip
        if($cline != $pnode->getLine()->getId()){
            if($current != null){
                array_push($root, $current);
            }
            $cline = $pnode->getLine()->getId();
            $current = array(
                "line" => $pnode->getLine()->getId(),
                "display" => $pnode->getLine()->getDisplay(),
                "heading" => $pnode->getLine()->getHeading(),
                "trip" => array());
        }
    }
    //wrap everything in an object with some meta information
    $parentObject = array("start" => $start->getId(), "end" => $end->getId(), "startTime"=>$startTime->format("H:i"),"route"=>$root);
    //return success
    return $parentObject;
    //echo json_encode(array("type" => "success", "msg" => $parentObject));
}
?>
