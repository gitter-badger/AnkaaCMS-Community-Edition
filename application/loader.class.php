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
    protected $cwd;
    public $outputAssign = array();
    public $settings;
    
    public function __construct(){
        try{
            $this->cwd = $_SERVER['DOCUMENT_ROOT'];
            require system::settings('directory','libraries').DIRECTORY_SEPARATOR.'autoload.php';
            spl_autoload_register(array($this, 'extLoader'), true);
            spl_autoload_register(array($this, 'widgetLoader'), true);
            $this->loadExtensions();
            $this->loadWidgets();
        } catch(error $e){
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
            $this->$data['class'] = new $data['class'];
            $this->output($data['class'], $this->$data['class']->output);
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

    public function output($key, $value){
        $this->outputAssign[$key] = $value;
    }

    public function __destruct(){

    }
}
