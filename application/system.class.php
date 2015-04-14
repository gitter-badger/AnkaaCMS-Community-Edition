<?php

/**
 * System autoloader and initializer
 *
 * system is the core class of this application which gets and sets
 * the configuration and connects to the database, etc.
 *
 * @version 1.0
 * @author DienstKoning
 */

class system{
    
    private $webdata;
    protected $request;
    private $ini;
    private $db;
    
    public function __construct(){
        $methods = get_class_methods($this);
        foreach($methods as $method){
            if($method != '__construct' and $method != '__destruct' and $method != 'settings'){
                $this->$method();
            }
        }
    }
    public static function settings($section, $setting){
        $sCWD = $_SERVER['DOCUMENT_ROOT'];
        $aCWD = explode(DIRECTORY_SEPARATOR, $sCWD);
        for($i=0;$i<count($aCWD);$i++){
            $path = implode($aCWD, DIRECTORY_SEPARATOR);
            if(file_exists($path.DIRECTORY_SEPARATOR.'webdata')){
                $webdata = $path.DIRECTORY_SEPARATOR.'webdata';
                break;
            } else { 
                array_pop($aCWD);
            }
        }
        try{
            $iniPath = $webdata.DIRECTORY_SEPARATOR.'config.ini';
            if(file_exists($iniPath)){
                $parsedIni = parse_ini_file($iniPath, true);
                $parsedIni;
                return $parsedIni[$section][$setting];
            } else {
                throw new Exception(_('No config file present'));
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function __destruct(){
        
    }
}