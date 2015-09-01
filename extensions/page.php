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
        if(explode('/',$this->request)[0] == 'page'){
            $this->loadPage();
        }
        if(system::request()[0] == 'admin' && isset(system::request()[1]) && system::request()[1] == 'page'){
			$this->loadAdmin();
        }
    }
    public function loadPage(){
        $blocks = $this->getBlocks();
        foreach($blocks as $block){
            $this->getContent($block['id'], $block['location']);
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
    private function getContent($location, $block){
        if(!empty($this->request)){
            $aRequest = explode('/', $this->request);
            if(is_array($aRequest) && count($aRequest) > 1){
                $this->db->queryData('SELECT * FROM pages_content c INNER JOIN pages p ON p.id = c.pageid WHERE p.title = :title AND c.blockid = :blockid',array(':title'=>$aRequest[1], ':blockid'=>$location));
            } else {
                $this->db->queryData('SELECT * FROM pages_content c INNER JOIN pages p ON p.id = c.pageid WHERE p.id = :id and c.blockid = :blockid)',array(':id'=>1, ':blockid'=>$location));
            }
            $contents = $this->db->return;
        } else {
            $this->db->queryData('SELECT * FROM pages_content c INNER JOIN pages p ON p.id = c.pageid WHERE p.default = 1 and c.blockid = :blockid LIMIT 1', array(':blockid'=>$location));
            $contents = $this->db->return;
        }
        foreach($contents as $content){
            $return['title'] = $content['title'];
            $return['subtitle'] = $content['subtitle'];
            $return['author']   = $content['author'];
            foreach($this->modules as $module){
                if($module['id'] == $content['module']){
                    $$module['name'] = new $module['name']($content['data']);
                    $return['content'] = $$module['name']->output;
                    $this->output[$block] = $return;
                }
            }

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
    public function loadAdmin(){
  		if($this->hasRights() == TRUE){
  			$this->output['leftmenu'][0]['name']     = _('Dashboard');
  			$this->output['leftmenu'][0]['function'] = '';
  			$this->output['leftmenu'][1]['name']     = _('Create Newsletter');
  			$this->output['leftmenu'][1]['function'] = 'create';
  			$this->output['leftmenu'][2]['name']     = _('Edit Newsletter');
  			$this->output['leftmenu'][2]['function'] = 'edit';
  			$this->output['leftmenu'][3]['name']     = _('Create Mail');
  			$this->output['leftmenu'][3]['function'] = 'createmail';
  			$this->output['leftmenu'][4]['name']     = _('Edit unsend Mail');
  			$this->output['leftmenu'][4]['function'] = 'editmail';
  			$this->loadCurrentAdmin();
  		}
	  }
    public function loadCurrentAdmin(){
  		if(isset(system::request()[2]) && !empty(system::request()[2])){
  			$current_function = system::request()[2];
  		} else {
  			$current_function = 'dashboard';
  		}
  		$this->output['current_template'] = './adminpanel_page_'.$current_function.'.tpl';
  		$this->output['current_function'] = $current_function;
  		if(method_exists($this, $current_function)){
  			$this->$current_function();
  		}
  	}
    public function dashboard(){
        $this->db->queryData('	SELECT 
                                        *,
                                        (SELECT count(*) FROM newsletter_mail m
                                            WHERE m.newsletter_id = n.id) as mails,
                                        (SELECT count(*) FROM newsletter_subscriber s
                                            WHERE s.newsletter_id = n.id) as subscribers
                                FROM newsletter n');
        $return  = $this->db->return;
        $columns = array();
        $rows    = array();
        foreach($return as $num=>$row){
                foreach($row as $column=>$data){
                        if(!in_array($column, $columns)){
                                if($column == 'template_name'){
                                        $column = 'template';
                                }
                                if($column == 'last_send'){
                                        $column = 'last send';
                                }
                                $columns[]    = $column;
                        }
                        $rows[$num][$column] = $data;
                }
        }
        $this->output['list']['columns'] = $columns;
        $this->output['list']['rows'] = $rows;
    }

}
