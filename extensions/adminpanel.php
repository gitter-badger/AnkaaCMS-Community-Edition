<?php

class adminpanel extends extender{

	public $output;
	private $current;

	public function __construct(){
		parent::__construct();
		$this->current = $this::getCurrent();
		$this->loadMenu();
		$this->output['current'] = $this->current;
		if(system::request()[0] == 'admin' && system::request()[1] = 'adminpanel'){
				$this->output['admin']['leftmenu'] = '';//$this->adminMenu();
				if(isset(system::request()[2]) && !empty(system::request()[2])){
						if(method_exists($this, system::request()[2])){
								$method = system::request()[2];
								$this->$method();
						} else {
								//$this->dashboard();
						}
				} else {
						//$this->dashboard();
				}
		}
		if(empty($this->current) || $this->current == 'adminpanel'){
			$this->output['current_template'] = './adminpanel.tpl';
		} else {
			$this->output['current_template'] = './adminpanel_'.$this->current.'.tpl';
		}
	}

	private function dbschema(){
		if(isset(system::request()[3])){
			$req = system::request()[3];
			if($req == 'export'){
				new Download($this->db->schema('xml'), 'database.xml', 'text/xml');
			}
		}
		if((isset($req) && $req !== 'export') || !isset(system::request()[3])){
			system::prePrintArray('This function is not yet supported');
		}
	}

	private function loadMenu(){
		$this->db->queryData('SELECT name, class FROM extensions WHERE enabled = 1');
		$return = $this->db->return;
		$this->output['menu']['top'] = $return;
		foreach($this->output['menu']['top'] as $key => $item){
			if($this->current == $item['class']){
				$this->output['menu']['top'][$key]['current'] = TRUE;
			} else {
				$this->output['menu']['top'][$key]['current'] = FALSE;
			}
		}
	}


	private static function getCurrent(){
		if(isset(system::request()[1])){
			return system::request()[1];
		} else {
			return '';
		}
	}
}

?>
