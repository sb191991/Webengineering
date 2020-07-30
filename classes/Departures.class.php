<?php
    //agglomerates a set of departures for a given stop, starting at a specific timepoint
    //serves as an encapsulated way of calculating the "delay" occurring at a stop due to waiting
    //for the next departure
    class Departures{
        private $departures;
        //constructs a new instance for the given departures
        function __construct($departures){
            $this->departures = $departures;
        }
        function getDepartures() {
            return $this->departures;
        }
        //obtains the next departure of the given line after timepoint time
        function getNext($line, $time){
            foreach($this->departures as $departure){
                if($departure->getLine() == $line && $departure->getTime() > $time){
                    return $departure;
                }
            }
            return null;
        }
        //calculates the delay occurring due to waiting for the line
        function getDelay($line, $time){
            $smallest = $this->getNext($line, $time);
            if($smallest === null){
                return -1;
            }
            return $smallest->getTime()->diff($time)->i; // -> i: get minutes
        }
    }
?>
