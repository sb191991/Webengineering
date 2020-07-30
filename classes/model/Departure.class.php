<?php
    /*
    Models a single departure from one stop
    */
    class Departure{
        private $line;
        private $display;
        private $time;

        function __construct($line, $display, $time){
            $this->line = $line;
            $this->display = $display;
            $this->time = $time;
        }

        //The line id
        function getLine(){
            return $this->line;
        }
        //The display value of the line
        function getDisplay(){
            return $this->display;
        }
        //The time of the departure
        function getTime(){
            return $this->time;
        }
    }
?>
