<?php
    //represents a single step in a path from a to b
    class PathNode{
        private $id;
        private $cost;
        private $line;

        //constructs a new instance
        function __construct($id, $cost, $line){
            $this->id = $id;
            $this->cost = $cost;
            $this->line = $line;
        }
        //the id of the represented node and stop
        function getId(){
            return $this->id;
        }
        //the cost of reaching this stop
        function getCost(){
            return $this->cost;
        }
        //the line which leads to the next step
        function getLine(){
            return $this->line;
        }
    }
?>
