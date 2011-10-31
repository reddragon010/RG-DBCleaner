<?php

class Database {
    private $connection;
    private $config;
    
    public $queue;
    
    public function __construct($config) {
        $this->config = $config;
        $this->connection = new PDO($this->config['dsn'],$this->config['user'],$this->config['pass']);
        if(empty($this->connection)) 
                throw new Exception ('Cant connect to MySQL-Server');
        $this->queue = new QueryQueue($this->connection);
    }
    
    public function query($sql,$values=array()){
        $ps = $this->connection->prepare($sql);
        if(!$ps->execute($values)){
            $error = $ps->errorInfo();
            throw new Exception($error[2]);
        }
        return $ps;
    }
    
    public function query_and_fetch_all($sql,$values=array()){
        $ps = $this->query($sql, $values);
        return $ps->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
