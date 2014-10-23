<?php


class Main extends Database{
    
    Public    $Smarty;
	Public    $ExtensionArr;
	Public    $ModuleAssign;
    Public    $assign = array();
    Public    $ModuleTemplate;
    Protected $ModuleTemplates;
    public    $template = 'index';

    function __construct(){
        parent::__construct();
		
        if($this->loadLib('Smarty')){
            $this->Smarty = new Smarty();
            $this->Smarty->setCompileDir(getcwd().'/templates_c');
        }
                         
    }

    function loadExtension($name, $file, $folder){
        if(file_exists('./application/extensions/'.$folder.'/'.$file)){
            include_once('./application/extensions/'.$folder.'/'.$file);
            $$name = new $name();
            return $$name;
        } else {
            return FALSE;
        }
    }

    protected function loadExt(){
        $this->ExtensionArr = $this->q("SELECT * FROM extensions WHERE status = 1");
        foreach($this->ExtensionArr as $row){
			$$row['name'] = $this->loadExtension($row['class'], $row['file'], $row['folder']);
			$ExtensionAssign = $row['class'].'Assign';
			if(isset($$row['name']->$ExtensionAssign)){
				$assign[] = $$row['name']->$ExtensionAssign;
			} 
		}
        $this->assign = $assign;	

            
    }


    function getRequest($type){
        $array = explode('/',$_SERVER['REQUEST_URI']);
        unset($array[0]);
        if( isset($array[1]) && (strlen($array[1]) < 4) ){
            $lang = $array[1];
			$page = 0;
			$values = 0;
        } 
        elseif( isset($array[1]) && (strlen($array[1]) > 3) ){
            $lang = $this->getIni('site');
            header('Location: /'.$lang['language'].$_SERVER['REQUEST_URI']);
            $lang = $lang['language'];
            $page = $array[1];
			$values = 0;
        }
        if( empty($array[1]) ){
            $lang = $this->getIni('site');
            $lang = $lang['language'];
            $page = $array[1];
        }
        if( isset($array[2]) ){
            $page = $array[2];
        }
		if( isset($array[3])){
			$lang = $array[1];
			$page = $array[2];
            unset($array[1]);
			unset($array[2]);
			$values = array_values($array);
		}
		if((!isset($array[1])) && (!isset($values))){
			$lang = 0;
			$page = 0;
			$values = 0;
		}
        $siteDomain = array(':domain'=>$_SERVER['SERVER_NAME'],':lang'=>$lang);
		$siteProfile = $this->q("SELECT * FROM sys_siteprofile WHERE domain = :domain AND language = :lang",'ASSOC',$siteDomain);
        switch($type){
            case "lang";
				
				foreach($siteProfile as $row){
					if($lang == $row['language']){
						$language = $lang;
					}
				}
				if(isset($language)){
					return $language;
				} else {
					$lang = $this->getIni('site');
					$lang = $lang['language'];
					return $lang;
				}
                
                break;
            case "page";
                return $page;
                break;
            case "action";
                return $action;
                break;
            case "values";
                return $values;
                break;
            case "siteprofile";
                $profileId = $siteProfile[0]['id'];
                return $profileId;
                break;
        }
    }

    public function loadLib($libn){
        if(file_exists('libraries/'.$libn.'/'.$libn.'.class.php')){
            include_once('./libraries/'.$libn.'/'.$libn.'.class.php');
            return TRUE;
        } else {
            return FALSE;
        }
    }

	public function loadModule($moduleid,$attributes=0){
		$moduleid = array(':id'=>$moduleid);
		$module = $this->q('SELECT * FROM modules WHERE id = :id','ASSOC',$moduleid);
		foreach($module as $row){
			include_once('./modules/'.$row['folder'].'/index.php');
			if($attributes == 0){
                $$row['name'] = new $row['name']();
			}
			else {
                $$row['name'] = new $row['name']($attributes);
			}
			$ModuleAssign = $row['name'].'Assign';                       
			if(isset($$row['name']->$ModuleAssign)){
				$assign[] = $$row['name']->$ModuleAssign;
			}
            $ModuleTemplate = $row['name'].'Template';
            if(isset($$row['name']->$ModuleTemplate)){
                $template[] = $$row['name']->$ModuleTemplate;
            } 
		}
		$this->ModuleAssign    = $assign;
        //$this->ModuleTemplates = $template;        
	}
    
    public function __destruct(){        
             
        //$this->assign[] = $assign;	
        //print_r($this->assign);        
            
    }                


    
    
    


}


?>
