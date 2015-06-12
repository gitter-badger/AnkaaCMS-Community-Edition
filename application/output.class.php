<?php

class output extends loader{
  
  public function __construct(){
    parent::__construct();
    $this->db = new database;
    if(empty(system::request()[0])){
      system::redirectDefault();
    }
  }


  private function supportedOutput(){
    $this->db->queryData('SELECT * FROM output_supported WHERE enabled = 1');
    $output_supported = $this->db->return;
    return $output_supported;
  }

  private function loadOutput($classname){
    include_once($this->cwd.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'output_'.$classname.'.php');
    if(file_exists($this->cwd.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'output_'.$classname.'.php')){
        include_once($this->cwd.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'output_'.$classname.'.php');
    }
    $class = 'output_'.$classname;
    $this->getMessage();
    $this->output = new $class($this->outputAssign);
  }
  
  public function getMessage(){
    $this->outputAssign['message'] = array();
    $this->outputAssign['request'] = implode('/', system::request());
    $this->outputAssign['forms'] = array();
    foreach($this->outputAssign as $output){
      if(is_array($output)){
        if(array_key_exists('message', $output)){
          $this->outputAssign['messages'][] = $output['message'];
        }
        if(array_key_exists('forms', $output)){
          $this->outputAssign['forms'] = $this->outputAssign['forms'] + $output['forms'];
        }
      }
    }
  }

  public function loadType(){
    foreach($this->supportedOutput() as $supported){
      if(strpos(system::server('HTTP_ACCEPT'), $supported['type']) !== FALSE){
        $http_accept = $supported['extension_class'];
      } else{
        $http_accept = 'html';
      }
    }
    $this->loadOutput($http_accept);
  }

  static function getSiteSettings($name){
    $db = new database;
    $db->queryRow('SELECT * FROM settings WHERE settings_name = :name', array(':name'=>$name));
    return $db->return['settings_value'];
  }

  public function __destruct(){
        if(system::server('REDIRECT_URL') !== FALSE){
            $path       = explode('/', system::server('REDIRECT_URL'));
        } else {
            $path       = array('');
        }
        unset($path[0]);
        $request    = implode('/',$path);
        $aRequest   = explode('/', $request);
        
        $this->loadType();
            /*
            case "json":
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Content-type: application/json');
                echo json_encode($this->outputAssign);
                break;
            case "xml":
                if(isset($aRequest[0]) && !empty($aRequest[0])){
                    $array = $this->outputAssign[$aRequest[0]];
                } else {
                    $array = $this->outputAssign;
                }
                header('Content-Type: text/xml');
                $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><response></response>");
                $this->array_to_xml($array,$xml);
                print $xml->asXML();
                break;
            case "array":
                print_r($this->outputAssign);
                break;
            default:
                $output->display($this->templatename.DIRECTORY_SEPARATOR.'index.tpl');
                break;
            */
  }
 

}



?>