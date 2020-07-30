<?php
    //models a bus line
    class Line{
        private $id;
        private $display;
        private $heading;
        //constucts a new instance
        function __construct($id, $display, $heading){
            $this->id = $id;
            $this->display=$display;
            $this->heading=$heading;
        }
        //the line id
        function getId(){
            return $this->id;
        }
        //the display value of the line
        function getDisplay(){
            return $this->display;
        }
        //where the line is headed
        function getHeading(){
            return $this->heading;
        }
    }
?>