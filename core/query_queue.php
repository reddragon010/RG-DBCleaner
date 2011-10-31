<?php

class QueryQueue {
    private $queue = array();
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function add($query, $values = array()){
        $key = count($this->queue) . '-' . $this->hash_query($query, $values);
        $this->queue[$key] = array('sql' => $query, 'values' => $values);
    }
    
    public function add_range($array){
        foreach($array as $item){
            call_user_func_array(array($this, 'add'), (array)$item);
        }
    }
    
    public function get($query, $values = array()){
        $key = $this->hash_query($query, $values);
        if(isset($this->queue[$key])){
            return $this->queue[$key];
        } else {
            return false;
        }
    } 
    
    public function remove($query, $values = array()){
        $key = $this->hash_query($query, $values);
        if(isset($this->queue[$key])){
            unset($this->queue[$key]);
            return true;
        } else {
            return false;
        }
    }
    
    public function execute(){
        foreach($this->queue as $query){
            echo "... fireing query '{$query['sql']}' ...";
            //FIRE!!
            echo " SUCCESS! <br />";
        }
    }
    
    private function hash_query($query, $values){
        return md5($query . json_encode($values));
    }
}
