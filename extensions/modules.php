<?php


class modules extends extender {

  public $output;

  public function __construct(){
    parent::__construct();
    if(!isset(system::request()[3]) || empty(system::request()[3])){
      $this->current = 'dashboard';
    } else {
      // do something
    }
    $this->loadMenu();
    $this->output['current_template'] = './adminpanel_modules_'.$this->current.'.tpl';
  }

  public function loadMenu(){
    $this->db->queryData('SELECT * FROM modules WHERE status = 1');
    $data = $this->db->return;
    $starter[0]['title'] = 'All Modules';
    $starter[0]['name'] = '';
    $this->output['leftmenu'] = array_merge($starter,$data);
  }

  public function __destruct(){

  }

}


?>
