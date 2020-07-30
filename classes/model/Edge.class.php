<?php
    require_once("Node.class.php");

    //Represents an edge in the graph
    //An edge is annotated with the line represented by it
    class Edge{
        private $endNode;
        private $cost;
        private $line;
        //Constructs a new instance
        function __construct($endNode, $cost, $line){
            $this->endNode = $endNode;
            $this->cost = $cost;
            $this->line = $line;
        }
        //The end node this edge points to
        function getEndNode(){
            return $this->endNode;
        }
        //The cost of using this edge
        function getCost(){
            return $this->cost;
        }
        //The line associated with the edge
        function getLine(){
            return $this->line;
        }
    }
?>
