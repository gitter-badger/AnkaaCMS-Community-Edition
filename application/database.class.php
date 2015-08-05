<?php
/*
 * This is de database class which can be used to create a database connection.
 * This class is extended by the extender and can be called by using the
 * extender class as extension.
 *
 * @author Dempsey van Wissen
 * @vendor DienstKoning
 *
 */
class database{

    private $db;
    public $return;
    public $fetchMethod = PDO::FETCH_ASSOC;
    public $query;
    public $transaction = FALSE;
    private $runningTransaction = FALSE;

    // Whenever the database class is called the database connection will be
    // created instantly
    public function __construct(){
        try{
            $dsn = 'mysql:host='.system::settings('database','hostname').';port='.system::settings('database','port').';dbname='.system::settings('database','database');
            $this->db = new PDO($dsn, system::settings('database','username'), system::settings('database','password'));
        } catch(PDOException $e){
            echo $e->getMessage();
            //header('Location: /install/');
        }
    }
    function array_to_xml($input, &$xml) {
      foreach($input as $key => $value) {
        if(is_array($value)) {
            $key = is_numeric($key) ? "item$key" : $key;
            $subnode = $xml->addChild("$key");
            $this->array_to_xml($value, $subnode);
        }
        else {
            $key = is_numeric($key) ? "item$key" : $key;
            $xml->addChild("$key","$value");
        }
      }
      return $xml;
    }


    public function schema($returnType='array'){
      $this->queryData('SHOW TABLES;');
  		$tables = $this->return;
  		foreach($tables as $table){
  			$db_table = $table['Tables_in_ankaacms'];
  			$this->queryData('SHOW COLUMNS FROM '.$db_table);
  			$columns = $this->return;
        foreach($columns as $column){
          $db_columns[$column['Field']] = $column;
          unset($db_columns[$column['Field']]['Field']);
        }
  			$dbSchema[$db_table] = $db_columns;
        $db_columns = array();
  		}
      switch($returnType){
        case "array":
        default:
          $return = $dbSchema;
          break;
        case "json":
          $return = json_encode($dbSchema);
          break;
        case "xml":
        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><database></database>");
        $this->array_to_xml($dbSchema, $xml);
        $return = $xml->asXML();

      }
      return $return;
    }
    /*
     * To receive data from the database in multiple rows you can use this
     * function to query data:
     * ->queryData('SELECT column FROM table WHERE id = :id', array(':id'=>1));
     * Data is then put it ->return using the fetchmethod set in ->fetchMethod
     * variable.
     */
    public function queryData($query, $values = array()){
        $sql = $this->db->prepare($query);
        $sql->execute($values);
        $fullQ = $query;
        foreach($values as $key=>$var){
            $fullQ = str_replace($key, '"'.addslashes($var).'"', $fullQ);
        }
        $this->query = $fullQ;
        $this->return = $sql->fetchAll($this->fetchMethod);
        $this->fetchMethod = PDO::FETCH_ASSOC;
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
    public function insertData($table, $data, $execute = FALSE){
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
        if($this->transaction == FALSE){
            try{
                $sql->execute($input);
                return $this->db->lastInsertId();
            } catch(PDOException $e){
                echo $e->getMessage();
                return FALSE;
            }
        } else {
            if($execute == FALSE && $this->runningTransaction == FALSE){
                $this->db->beginTransaction();
                $this->runningTransaction = TRUE;
            } elseif($execute == FALSE) {
                try{
                    $sql->execute($input);
                    return $this->db->lastInsertId();
                } catch(PDOException $e){
                    echo $e->getMessage();
                    return FALSE;
                }
            } elseif($execute == TRUE){
                try{
                    $sql->execute($input);
                    $this->db->commit();
                    $this->db->transaction = FALSE;
                    $this->db->runningTransaction = FALSE;
                    return $this->db->lastInsertId();
                } catch(PDOException $e){
                    echo $e->getMessage();
                    return FALSE;
                }
            }
        }
    }
    public function updateTable($table, $search, $data, $execute = FALSE){
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
        foreach($search as $searching=>$val){
            $i++;
            $query .= $searching.'=:search_'.$searching;
            if($i < $searches){
                $query .= ' AND ';
            }
            $input[':search_'.$searching] = $val;
        }
        $sql = $this->db->prepare($query);

        if($this->transaction == FALSE){
            try{
                $sql->execute($input);
                return $this->db->lastInsertId();
            } catch(PDOException $e){
                echo $e->getMessage();
                return FALSE;
            }
        } else {
            if($execute == FALSE && $this->runningTransaction == FALSE){
                $this->db->beginTransaction();
                $sql->execute($input);
                $this->runningTransaction = TRUE;
            } elseif($execute == FALSE) {
                try{
                    $sql->execute($input);
                    return $this->db->lastInsertId();
                } catch(PDOException $e){
                    echo $e->getMessage();
                    return FALSE;
                }
            } elseif($execute == TRUE){
                try{
                    $sql->execute($input);
                    $this->db->commit();
                    $this->db->transaction = FALSE;
                    $this->db->runningTransaction = FALSE;
                    return $this->db->lastInsertId();
                } catch(PDOException $e){
                    echo $e->getMessage();
                    return FALSE;
                }
            }
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
