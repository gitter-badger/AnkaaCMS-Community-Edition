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
            spl_autoload_register(array($this, 'libLoader'), true);
            spl_autoload_register(array($this, 'extLoader'), true);
            $this->loadExtensions();
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
    public function libLoader($classname){
        if(file_exists($this->cwd.'/libraries/'.$classname.'/'.$classname.'.class.php')){
            include_once($this->cwd.'/libraries/'.$classname.'/'.$classname.'.class.php');
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
    
    public function output($key, $value){
        $this->outputAssign[$key] = $value;
    }
    public function __destruct(){
        $output = new Smarty();
        foreach($this->outputAssign as $key=>$value){
            $output->assign($key, $value);
        }
        $output->setTemplateDir(system::settings('directory', 'templates'));
        $output->setCompileDir(system::settings('directory', 'compile'));
        $output->setCacheDir(system::settings('directory', 'cache'));
        $output->display('acalia\index.tpl');
    }
}
