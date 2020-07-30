<?php

require_once("Departures.class.php");
require_once("model/Line.class.php");
require_once("model/Graph.class.php");
require_once("model/Departure.class.php");
require_once("model/Node.class.php");

//Provides backend data access functions
class Getter {
  private $basicLink = "http://morgal.informatik.uni-ulm.de:8000/line/";

  //initializes a new instance
  function __construct(){
  }
  //helper function, which requests data from the given url
  //result is returned as decoded json object
  private function requestData($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly
    $resp = curl_exec($curl);
    return json_decode($resp, true); // true to get an associative array
  }
  //returns the list of stops from the server
  function getStopList(){
      $json = $this -> requestData($this->basicLink."stop/");
      $stops = $json["stops"];
      asort($stops); // sort associative array by value (keys here are the stop index)
      return $stops;
  }
  //reads and assembles the stop and line graph from the server
  function getGraph(){
      $json = $this->requestData($this->basicLink."data/");
      $graph = new Graph();
      foreach($json["stops"] as $index=>$name){
          $graph->addNode($index);
      }
      foreach($json["lines"] as $line){
          $prev = null;
          foreach($line["trip"] as $stop){
              if($prev !== null){
                  $graph->addEdge($prev, $stop["stop"], $stop["time"], new Line($line["id"], $line["display"], $line["heading"]));
              }
              $prev = $stop["stop"];
          }
      }
      return $graph;
  }
  //returns the departures (number restricted by the backend) for the given stop, starting at the given timepoint
  function getDepartures($stopId, $time){
      $req = $this->basicLink."stop/".$stopId."/departure/?start=".$time->format('H:i');
      $json = $this->requestData($req);
      $departureResult = array();
      foreach($json as $entry){
          $d = new Departure($entry["line"], $entry["display"], new DateTime($entry["time"]));
          array_push($departureResult, $d);
      }
      return new Departures($departureResult);
  }
}


?>
