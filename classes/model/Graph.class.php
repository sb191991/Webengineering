<?php
    require_once("Node.class.php");
    require_once("Edge.class.php");
    //models a graph
    //in the given context, the nodes of a graph represent bus stops and
    //the edges bus line connections between stops
    class Graph{
        private $nodes;
        //constructs an empty graph
        function __construct(){
            $this->nodes = array();
        }
        //adds a new node with the given id
        function addNode($id){
            array_push($this->nodes, new Node($id));
        }
        //adds a new node with given cost and associated line between nodes
        //with id startID and endID
        function addEdge($startId, $endId, $cost, $line){
            $startNode = $this->findNode($startId);
            $endNode = $this->findNode($endId);
            if($startNode != null && $endNode != null){
                $e = new Edge($endNode, $cost, $line);
                $startNode->addEdge($e);
            }
        }
        //finds the node instance associated with the given id
        function findNode($id){
            foreach($this->nodes as $node){
                if($node->getId() === intval($id))
                    return $node;
            }
            return null;
        }

        // Hilfsfunktion um Graphen mit IDs darzustellen. Zum Debugging.
        public function print(){
          foreach($this->nodes as $node){
            echo $node->getId() . " --> ";
            foreach($node->getEdges() as $edge){
              echo $edge->getEndNode()->getId() . " ";
            }
            echo "<br>";
          }
        }
    }
?>
