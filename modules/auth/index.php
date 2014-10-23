<?php


class Auth extends Main{	
    public $AuthTemplate;
    
	function __construct($attributes=0){
		parent::__construct();
		if(!is_array($attributes)){
			print_r($attributes);
		}
        $this->attributes = $attributes;
        $this->showAdmin();
	    
	}
    
    private function showAdmin(){
        if(isset($_SESSION['auth']) == TRUE){
            $this->path = $this->getIni('path');

            $module   = $this->getRequest('values')[0];
            $values   = $this->getRequest('values');
            unset($values[0]);
            if(isset($this->getRequest('values')[1])){
            	$function = $this->getRequest('values')[1];
            	unset($values[1]);
            	$values = array_values($values);
            } else {
            	$function = '';
            }
            $modules = $this->q("SELECT * FROM modules",'ASSOC');
            $extensions = $this->q("SELECT * FROM extensions WHERE status = 1","ASSOC");
            $this->module = $module;
            $this->function = $function;
            $this->values = $values;
            if(empty($this->function)){
                $this->function = '';
                $this->fTemplate = 'admin';
            } else {
                $this->fTemplate = $this->function;
            }
            if($this->module == 'dashboard'){
            	$this->showDashboard();
            } else {
                $output = false;
            	foreach($modules as $module){
            	   if($this->module == $module['name']){
            	     //  $this->AuthAssign[0] = $module['name'];
                     $this->AuthAssign[$this->attributes['location']]['template'] = getcwd().'/modules/'.$module['folder'].'/'.$this->fTemplate.'.tpl';
                     include_once(getcwd().'/modules/'.$module['folder'].'/admin.inc.php');
                     $moduleadmin = $module['name'].'Admin';
                     $$moduleadmin = new $moduleadmin($this->function, $this->values);
                     $this->AuthAssign[$this->attributes['location']][$module['name']] = $$moduleadmin->$module['name'];
                     $output = true;
            	   }  else {

            	   }
            	}
                foreach($extensions as $extension){
            	   if($this->module == $extension['name']){
            	     //  $this->AuthAssign[0] = $extension['name'];
                     $this->AuthAssign[$this->attributes['location']]['template'] = getcwd().'/application/extensions/'.$extension['folder'].'/admin.'.$extension['name'].'.'.$this->fTemplate.'.tpl';
                     include_once(getcwd().'/application/extensions/'.$extension['folder'].'/admin.'.$extension['name'].'.ext.php');
                     $extensionadmin = $extension['name'].'AdminExt';
                     $$extensionadmin = new $extensionadmin($this->function, $this->values);
                     $this->AuthAssign[$this->attributes['location']][$extension['name']] = $$extensionadmin->$extension['name'];
                     $output = true;
            	   }  else {

            	   }
            	}
                if($output == false){
                    $this->showDashboard();
                }
            }
        } else {
            $this->showLogin();
        }
   }

   
   
   private function showDashboard(){
		$this->AuthAssign[$this->attributes['location']]['template'] = getcwd().'/modules/auth/dashboard.tpl';
		$this->AuthAssign[$this->attributes['location']]['module'] = 'Auth';
		$this->AuthAssign[$this->attributes['location']]['dashboard'] = TRUE;
		$this->AuthAssign[$this->attributes['location']]['categories'] = $this->loadDashButtons();
   }

    private function showLogin(){
        $this->path = $this->getIni('path');
        $this->AuthAssign[$this->attributes['location']]['template'] = getcwd().'/modules/auth/login.tpl';
        $this->AuthAssign[$this->attributes['location']]['lang']  = $this->getRequest('lang');
    }

    private function loadDashButtons(){
    	$arr[0]['buttons'][0]['name'] = _('Article');
    	$arr[0]['buttons'][0]['description'] = _('Create an article');
        $arr[0]['buttons'][0]['link'] = $this->getRequest('lang').'/Admin/Article';
        $arr[0]['buttons'][0]['icon'] = 'fa-newspaper-o';
        $arr[1]['buttons'][0]['name'] = _('Page');
    	$arr[1]['buttons'][0]['description'] = _('Create a page');
        $arr[1]['buttons'][0]['link'] = $this->getRequest('lang').'/Admin/page';
        $arr[1]['buttons'][0]['icon'] = 'fa-file-o';
    	return $arr;
    }

    private function loadSettings($item){

    }
}


?>