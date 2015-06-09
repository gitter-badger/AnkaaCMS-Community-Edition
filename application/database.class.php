<?php

class database{
    
    private $db;
    public $return;
    public $fetchMethod = PDO::FETCH_ASSOC;
    public $query;

    public function __construct(){
        try{
            $dsn = 'mysql:host='.system::settings('database','hostname').';port='.system::settings('database','port').';dbname='.system::settings('database','database');
            $this->db = new PDO($dsn, system::settings('database','username'), system::settings('database','password'));
        } catch(PDOException $e){
            echo $e->getMessage();
            //header('Location: /install/');
        }
    }
    
    public function queryData($query, $values = array()){
        $sql = $this->db->prepare($query);
        $sql->execute($values);
        $fullQ = $query;
        foreach($values as $key=>$var){
            $fullQ = str_replace($key, '"'.addslashes($var).'"', $fullQ);
        }
        $this->query = $fullQ;
        $this->return = $sql->fetchAll($this->fetchMethod);
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
        $this->return = $sql->fetch($this->fetchMethod);
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

    public function updateTable($table, $search, $data){
        $query = 'UPDATE '.$table.' SET ';
        $columns = count($data);
        $i=0;
        foreach($data as $column=>$value){
            $i++;
            $query .= $column.'=:data_'.$column;
            if($i < $columns){
                $query .= ', ';
            }
            $input[':data_'.$column] = $value;
        }
        $query .= ' WHERE ';
        $searches = count($search);
        $i = 0;
        foreach($data as $search=>$value){
            $i++;
            $query .= $column.'=:search_'.$column;
            if($i < $searches){
                $query .= ' AND ';
            }
            $input[':search_'.$column] = $value;
        }
        $sql = $this->db->prepare($query);
        try{
            $sql->execute($input);
        } catch(PDOException $e){
            echo $e->getMessage();
        } 
    }
    
    public function removeData($table, $data){
        $query   = 'DELETE FROM '.$table.' WHERE ';
        $columns = count($data);
        $i=0;
        foreach($data as $column=>$value){
            $i++;
            $query .= $column.'=:'.$column;
            if($i < $columns){
                $query .= ' AND ';
            }
            $input[':'.$column] = $value;
        }
        $sql = $this->db->prepare($query);
        try{
            $sql->execute($input);
        } catch(PDOException $e){
            echo $e->getMessage();
        } 
    }
}

?>