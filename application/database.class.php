<?php

class database{
    
    private $db;
    public $return;
    public $fetchMethod;
    public $query;

    public function __construct(){
        try{
            $dsn = 'mysql:host='.system::settings('database','hostname').';dbname='.system::settings('database','database');
            $this->db = new PDO($dsn, system::settings('database','username'), system::settings('database','password'));
        } catch(PDOException $e){
            header('Location: /install/');
        }
    }
    
    public function queryData($query, $values = array()){
        $sql = $this->db->prepare($query);
        $sql->setFetchMode = $this->fetchMethod;
        $sql->execute($values);
        $fullQ = $query;
        foreach($values as $key=>$var){
            $fullQ = str_replace($key, '"'.addslashes($var).'"', $fullQ);
        }
        $this->query = $fullQ;
        $this->return = $sql->fetchAll();
    }
    
    public function queryRow($query, $values = array()){
        $sql = $this->db->prepare($query);
        try{
            $sql->execute($values);
        } catch(PDOException $e){
            echo $e->getMessage();
        }
        $fullQ = $query;
        foreach($values as $key=>$var){
            $fullQ = str_replace($key, '"'.addslashes($var).'"', $fullQ);
        }
        $this->query = $fullQ;
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