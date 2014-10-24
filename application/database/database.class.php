<?php


class Database extends Init{

    private $cn = array();

    function __construct(){
        parent::__construct();
	$this->cn = parent::getIni('database');
        try{
			$this->db = new PDO(	'mysql:'.
    				'host='.	$this->cn['hostname'].';'.
				'dbname='.	$this->cn['database'].';charset=latin1', 
						$this->cn['username'], 
						$this->cn['password']);
		}
		catch(PDOException $Exception){
			print_r($Exception->getMessage());
		}
    }
    
    public function q($q,$r='ASSOC',$d=FALSE){
	 $p = $this->db->prepare($q);
        if(!$d){
            $p->execute();
        } else {
            try{
                foreach($d as $key=>$value){
                    if($r != 'HTMLINPUT'){
                        $d[$key] = htmlentities($value);
                    }
                }
                $p->execute($d);
                
            } catch( PDOException $Exception) {
                  echo $Exception->getMessage();
                  $Exception->getCode();
            }
        }
        switch($r){
            case "ASSOC":
                $f = $p->fetchAll(PDO::FETCH_ASSOC);
                break;
            case "DEBUG":
                $f = $this->debug($q, $d);
                break;
            default:
                $f = $p->fetchAll(PDO::FETCH_ASSOC);
                break;
        }

        return $f;
    }

    private function debug($q, $d){
    
        $backtrace = debug_backtrace();
    
        foreach($d as $k=>$v){
            $q = str_replace($k, '"'.$v.'"', $q);
        }
        $file = $this->dataFolder.'logs/db-debug.log';
        $log = file_get_contents($file);
        //file_put_contents($file, "[ ".date("d-m-Y H:i:s")." ][".$backtrace[1]['file']." on line ".$backtrace[1]['line']."] - ".$q."\n");
        file_put_contents($file, $q."\n");
        return $q;
    }

    public function updateData(){
    }

    public function removeData(){
    }



}


?>
