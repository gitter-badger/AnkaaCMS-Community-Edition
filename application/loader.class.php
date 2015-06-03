<?php

/**
 * loader of modules and libraries within the application
 *
 * loader loads the modules and libraries that are needed by the application when needed.
 *
 * @version 1.0
 * @author DienstKoning
 */
class loader{
    private $cwd;
    public $outputAssign = array();
    
    public function __construct(){
        try{
            $this->cwd = $_SERVER['DOCUMENT_ROOT'];
          //  spl_autoload_register(array($this, 'libLoader'), true);
            require 'libraries'.DIRECTORY_SEPARATOR.'autoload.php';
            spl_autoload_register(array($this, 'extLoader'), true);
            spl_autoload_register(array($this, 'widgetLoader'), true);
            $this->loadExtensions();
            $this->loadWidgets();
        } catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function extLoader($classname){
        if(file_exists('./extensions/'.$classname.'.php')){
            include_once('./extensions/'.$classname.'.php');
            spl_autoload($classname);
        }
    }

    public function widgetLoader($classname){
        if(file_exists($this->cwd.'/widgets/'.$classname.'/index.php')){
            include_once($this->cwd.'/widgets/'.$classname.'/index.php');
            spl_autoload($classname);
        }
    }
    private function loadExtensions(){
        $aExtensions = $this->getExtensions('enabled');
        foreach($aExtensions as $data){
            $$data['class'] = new $data['class'];
            $this->output($data['class'], $$data['class']->output);
        }
    }

    private function loadWidgets(){
        $aWidgets = $this->getWidgets('enabled');
        foreach($aWidgets as $data){
            $$data['folder'] = new $data['folder'];
            $this->output($data['folder'], $$data['folder']->output);
        }
    }
    
    private function getExtensions($type=0){
        $db = new database();
        $db->fetchMethod = PDO::FETCH_ASSOC;
        $db->queryData('SELECT * FROM extensions');
        switch($type){
            case "list":
                return $db->return;
                break;
            case "enabled":
                foreach($db->return as $row=>$data){
                    if($data['enabled'] == 1){
                        $return[$row] = $data;
                        }
                }
                return $return;
                break;
            default:
                return $db->return;
                break;
        }
    }

    private function getWidgets($type=0){
        $db = new database();
        $db->fetchMethod = PDO::FETCH_ASSOC;
        $db->queryData('SELECT * FROM widgets');
        switch($type){
            case "list":
                return $db->return;
                break;
            case "enabled":
                foreach($db->return as $row=>$data){
                    if($data['status'] == 1){
                        $return[$row] = $data;
                        }
                }
                return $return;
                break;
            default:
                return $db->return;
                break;
        }
    }

    public function array_to_xml(&$array, &$xml) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                }
                else{
                    $subnode = $xml->addChild("item$key");
                    $this->array_to_xml($value, $subnode);
                }
            }
            else {
                $xml->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }

    public function outputType(){
        switch($_SERVER['HTTP_ACCEPT']){
            case "application/json":
                return 'json';
                break;
            case "application/xml":
                return 'xml';
                break;
            default:
                return 'display';
                break;

        }
    }
    public function output($key, $value){
        $this->outputAssign[$key] = $value;
    }
    public function __destruct(){
        if(isset($_SERVER['REDIRECT_URL'])){
            $path       = explode('/', $_SERVER['REDIRECT_URL']);
        } else {
            $path       = array('');
        }
        unset($path[0]);
        $request    = implode('/',$path);
        $aRequest   = explode('/', $request);
        $output = new Smarty();
        foreach($this->outputAssign as $key=>$value){
            $output->assign($key, $value);
        }
        $output->setTemplateDir(system::settings('directory', 'templates'));
        $output->setCompileDir(system::settings('directory', 'compile'));
        $output->setCacheDir(system::settings('directory', 'cache'));
        switch($this->outputType()){
            case "display":
                $output->display('a-vision\index.tpl');
                break;
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
        }
        
    }
}
