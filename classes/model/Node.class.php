<?php
    //models a node in the graph
    //in our setting, this node represents a stop
    class Node{
        private $id;
        private $edges;

        public $cost;
        public $preNode;
        public $preLine;
        public $visited;

        //constructs a new instance
        function __construct($id){
            $this->id = $id;
            $this->edges = array();
            $this->cost = INF;
            $this->preNode = null;
            $this->preLine = null;
            $this->visited = false;
        }

        //adds an outgoing edge to the node
        function addEdge($edge){
            array_push($this->edges, $edge);
        }
        //the id of the node and the stop it represents
        function getId(){
            return $this->id;
        }
        //outgoing edges
        function getEdges(){
            return $this->edges;
        }
        // returns the edge for the given end node 
        // or null if no edge to the given node exists
        function getEdge($endNode) {
            foreach ($this->getEdges() as $edge) {
                if ($edge->getEndNode() === $endNode)
                    return $edge;
            }
        }
    }
?>
