<?php

class database{
    
    private $db;
    public $return;
    public $fetchMethod;

    public function __construct(){
        $dsn = 'mysql:host='.system::settings('database','hostname').';dbname='.system::settings('database','database');
        $this->db = new PDO($dsn, system::settings('database','username'), system::settings('database','password'));
    }
    
    public function queryData($query, $values = array()){
        $sql = $this->db->prepare($query);
        $sql->setFetchMode = $this->fetchMethod;
        $sql->execute($values);
        $this->return = $sql->fetchAll();
    }
    
    public function queryRow($query, $values){
        $sql = $this->db->prepare($query);
        $sql->execute($values);
        $this->return = $sql->fetch();
    }
    
    public function insertData($table, $data){
        $query   = 'INSERT INTO '.$table.' ';
        $columns = '(';
        $values  = '(';
        foreach($data as $column => $value){
            $columns.=$column.', ';
            $values .=':'.$column.', ';
            $input[':'.$column] = $value;
        }
        $query .= substr($columns, 0, -2).') VALUES '.substr($values, 0, -2).')';
        $sql = $this->db->prepare($query);
        try{
            $sql->execute($input);
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}

?>