<?php
error_reporting(E_ALL);

class Init{
    private   $ini         = '';
    private   $cwd         = '';
    private   $cwdArray    = array();
    private   $current     = '';
    private   $iniLocation = array();
    private   $uri         = '';
    protected $pathDiv     = '';

    function __construct(){
        if(php_uname('s') == 'Linux'){
        	$this->pathDiv = '/';
        } else {
            $this->pathDiv = '\\';
        }
        //echo'<pre>';print_r($_SERVER);echo'</pre>';
        $this->cwd = getcwd();
        $this->cwdArray = explode($this->pathDiv, $this->cwd);
        unset($this->cwdArray[0]);
        foreach( $this->cwdArray as $num=>$dir ){
            if(!isset($this->ini['site'])){
                $current = $this->pathDiv.implode($this->pathDiv, $this->cwdArray);
                array_pop($this->cwdArray);
                if(file_exists($current.$this->pathDiv.'cms-data')){
                    $this->dataFolder = $current.$this->pathDiv.'cms-data/';
                    if(file_exists($this->dataFolder.'system'.$this->pathDiv.'config.ini')){
                        $this->iniLocation[] = $this->dataFolder.'system'.$this->pathDiv.'config.ini';
                        $this->ini = parse_ini_file($this->iniLocation[0], true);
    	                if(isset($this->ini['site']['installed'])){
                            if(!$this->ini['site']['installed'] == 1){
                                header($this::fullUri());
                            }
                        } else {
                            header('Location: '.$this::fullUri().'../install/');
                        }
                        if(isset($this->ini['site']['debug'])){
                        	$df = ini_get('disable_functions');
                            if($this->ini['site']['debug'] == true){
                                error_reporting(E_ALL);
                                if(strpos($df, 'ini_set') < 0){
                                	ini_set("display_errors", 1);
                                }
                            } else {
                                error_reporting(0);
                                if(strpos($df, 'ini_set') < 0){
                                	ini_set("display_errors", 0);
                                }
                            }
		        }
                    }
                } 
            }
        } 
    }

    public function getIni($type){
        switch($type){
            case "database";
                return $this->ini['database'];
                break;
            case "site";
                return $this->ini['site'];
                break;
            case "path";
                return $this->ini['path'];
                break;
        }
    }

    protected function fullUri($boolean){
        $s = &$_SERVER;
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host = isset($s['HTTP_X_FORWARDED_HOST']) ? 
			$s['HTTP_X_FORWARDED_HOST'] : 
				isset($s['HTTP_HOST']) ? 
					$s['HTTP_HOST'] : 
						$s['SERVER_NAME'];
        return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
    }

    protected function getCwd(){
        return $this->cwd;
    }

    

}

?>
