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
            if( $method == 'request'){
                $this->$method();
            }
        }
    }
    
    public static function redirectDefault(){
        header('Location: /'.output::getSiteSettings('default_module_name').'/'.output::getSiteSettings('default_module_value'));
    }

    public static function settings($section, $setting){
        $sCWD = system::server('DOCUMENT_ROOT');
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
                if(isset($parsedIni[$section][$setting])){
                    return $parsedIni[$section][$setting];
                } else {
                    return '';
                }
                
            } else {
                throw new Exception(_('No config file present'));
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public static function request(){
        $request = explode('/',system::server('REQUEST_URI'));
        unset($request[0]);
        $implode = implode('/', $request);
        return explode('/', $implode);
    }

    private function loadAdmin(){
           new admin();
    }

    public static function getSession($name){
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        } else {
            return FALSE;
        }
    }
    public static function setSession($name, $value=0){
        $_SESSION[$name] = $value;
        return $_SESSION[$name];
    }

    public static function server($name){
        if(isset($_SERVER[$name])){
            return $_SERVER[$name];
        } else {
            return FALSE;
        }
    }

    public static function gender($int){
        if($int == 1){
            return _('Male');
        }
        if($int == 0){
            return _('Female');
        }
    }

    public static function generateRandomkey($bytes=32){
        return openssl_random_pseudo_bytes($bytes);
    }

    public static function crypto($switch=0, $input){
        //SWITCH VAR: 0 = Encrypt & 1 = Decrypt
        switch($switch){
            case 0:
                $key = system::settings('misc','key');
                if(!empty($key)){
                    $key = system::settings('misc','key');
                } else {
                    return FALSE;
                }
                $civ = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
                $enc =  mcrypt_encrypt(MCRYPT_RIJNDAEL_256, hex2bin($key), $input, MCRYPT_MODE_CBC, $civ);
                return array('c'=>bin2hex($civ), 'e'=>bin2hex($enc));
                break;
            case 1:
                if(is_array($input)){
                    $key = system::settings('misc','key');
                    if(!empty($key)){
                        $key = system::settings('misc','key');
                        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, hex2bin($key), hex2bin($input['e']), MCRYPT_MODE_CBC, hex2bin($input['c']));
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
                break;
        }
    }

    public static function prePrintArray($arr){
        echo '<pre>'.print_r($arr, true).'</pre>';
    }

    public static function validate($value, $type){
        switch($type){
            case "EMAIL":
                // FILTER_VALIDATE_EMAIL regex is unreliable and does not work with some
                // valid e-mailaddresses. Should not be user. Better solution?
                //if(filter_var($value, FILTER_VALIDATE_EMAIL) === TRUE){
                    return TRUE;
                //} else {
                //    return FALSE;
                //}
                break;
            default:
                return FALSE;
                break;
        }
    }

    public function __destruct(){
        
    }
}