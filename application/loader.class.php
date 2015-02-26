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
            spl_autoload_register(array($this, 'modLoader'), true);
            spl_autoload_register(array($this, 'libLoader'), true);
            $this->loadModules();
        } catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function modLoader($classname){
        if(file_exists('./modules/'.$classname.'.php')){
            include_once('./modules/'.$classname.'.php');
            spl_autoload($classname);
        }
    }
    public function libLoader($classname){
        if(file_exists($this->cwd.'/libraries/'.$classname.'/'.$classname.'.class.php')){
            include_once($this->cwd.'/libraries/'.$classname.'/'.$classname.'.class.php');
            spl_autoload($classname);
        }
    }
    private function loadModules(){
        $aModules = array('post');
        foreach($aModules as $module){
            $$module = new $module;
            $this->output($module, $$module->output);
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
        $output->display('default\index.tpl');
    }
}
