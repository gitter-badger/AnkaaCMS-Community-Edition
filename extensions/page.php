<?php

/**
 * page short summary.
 *
 * page description.
 *
 * @version 1.0
 * @author Dempsey
 */
class page extends extender{
    private $modules;
    public $output;
    
    public function __construct(){
        parent::__construct();
        $this->db = new database();
        spl_autoload_register(array($this, 'modLoader'), true);
        $this->modules = $this->getModules();
        $this->loadPage();
    }
    
    public function loadPage(){
        $blocks = $this->getBlocks();
        foreach($blocks as $block){
            $this->getContent($block['location']);
        }
    }
    
    public function requestMethod(){
        $this->db->queryData('SELECT settings_value FROM page_settings WHERE settings_name = :value', array(':value'=>'method'));
        return $this->db->return[0]['settings_value'];
    }
    
    private function getBlocks(){
        $this->db->queryData('SELECT * FROM pages_blocks WHERE status = 1');
        return $this->db->return;
    }
    
    private function getContent($location){
        if(!empty($this->request)){
            $aRequest = explode('/', $this->request);
            if(is_array($aRequest)){
                $this->db->queryData('SELECT * FROM pages_content c INNER JOIN pages p ON p.id = c.pageid WHERE p.title = :title',array(':title'=>'Home'));
            } else {
                $this->db->queryData('SELECT * FROM pages_content c INNER JOIN pages p ON p.id = c.pageid WHERE p.title = :title)',array(':title'=>$aRequest));
            }
            $contents = $this->db->return;
        } else {
            $this->db->queryData('SELECT * FROM pages_content c INNER JOIN pages p ON p.id = c.pageid WHERE p.default = 1 LIMIT 1');
            $contents = $this->db->return;
        }
        foreach($this->modules as $module){
            foreach($contents as $content){
                $return['title'] = $content['title'];
                $return['subtitle'] = $content['subtitle'];
                $return['author']   = $content['author'];
                foreach($this->modules as $module){
                    if($module['id'] == $content['module']){
                        $$module['name'] = new $module['name']($content['data']);
                        $return['content'] = $$module['name']->output;
                    }
                }
                $output[$location] = $return;
            }
            $this->output = $output;
        }
        
    }
    
    private function getModules(){
        $this->db->queryData('SELECT * FROM modules WHERE status = 1');
        return $this->db->return;
    }
    
    private function modLoader($classname){
        if(file_exists($this->cwd.'/modules/'.$classname.'/index.php')){
            include_once($this->cwd.'/modules/'.$classname.'/index.php');
            spl_autoload($classname);
        }
    }
    
}
