<?php




class Site extends Main{

	
	protected $attributes;

	public function __construct(){
		parent::__construct();
		$this->loadAttributes();
        $this->loadExt(); 
		$this->loadFrontpage();
        $this->loadTranslations();
	}
	
	private function loadAttributes(){
		$this->siteDomain = $_SERVER['SERVER_NAME'];
		$replace = array(':domain'=>$this->siteDomain,':lang'=>$this->getRequest("lang"));
		$this->attributes = $this->q("SELECT * 
							FROM sys_settings AS s
							LEFT JOIN sys_siteprofile_settings AS k
								ON s.id = k.settings_id
							LEFT JOIN sys_siteprofile AS p
								ON k.siteprofile_id = p.id
							WHERE p.domain = :domain
								AND p.language = :lang
							",'ASSOC',$replace);
		foreach($this->attributes as $row){
			$this->SiteAssign[$row['attribute']] = $row['string'];
		}
	}
	
	
	
	private function loadFrontpage(){
		if($this->getRequest("page") == FALSE){
			$frontpage = $this->q("	SELECT string 
							FROM sys_settings AS s
							LEFT JOIN sys_siteprofile_settings AS k
								ON s.id = k.settings_id
							LEFT JOIN sys_siteprofile AS p
								ON k.siteprofile_id = p.id
							WHERE attribute = 'site_frontpage'
							");
			header("Location: /".$frontpage[0]['string']);
		}
	}
    private function loadTranslations(){
        $this->SiteAssign['lang'] = $this->getRequest('lang');
        $this->SiteAssign['back'] = _("Go Back");
        $this->SiteAssign['save'] = _("Save");
        $this->SiteAssign['login'] = _("Login");
        $this->SiteAssign['username'] = _("Username");
        $this->SiteAssign['password'] = _("Password");
        $this->SiteAssign['forgotpassword'] = _("Forgot your password?");
        $this->SiteAssign['send'] = _("Send");
        $this->SiteAssign['edit'] = _("Edit");
        $this->SiteAssign['remove'] = _("Remove");
        $this->SiteAssign['delete'] = _("Delete");
        $this->SiteAssign['add'] = _("Add");
        $this->SiteAssign['new'] = _("New");
        $this->SiteAssign['create'] = _("Create");
        $this->SiteAssign['options'] = _("Options");
        $this->SiteAssign['settings'] = _("Settings");
        $this->SiteAssign['title'] = _("Title");
        $this->SiteAssign['administration'] = _("Administration");
        $this->SiteAssign['username'] = _("Username");
        $this->SiteAssign['password'] = _("Password");
        $this->SiteAssign['forgotpassword'] = _("Forgot your password?");
        $this->SiteAssign['pages'] = _("Pages");
        
    }
    
    public function __destruct(){
        parent::__destruct();        
        $this->assign[] = $this->SiteAssign;

		foreach($this->assign as $row){
			foreach($row as $name => $value){
				$this->assign[$name] = $value;
			}
			foreach($this->assign as $name => $value){
				$this->Smarty->assign($name,$value);
			}
		}
		$theme = $this->q("	SELECT string 
							FROM sys_settings AS s
							LEFT JOIN sys_siteprofile_settings AS k
								ON s.id = k.settings_id
							LEFT JOIN sys_siteprofile AS p
								ON k.siteprofile_id = p.id
							WHERE attribute = 'site_theme'
							");
		$theme = $theme[0]['string'];
        $path = $this->getIni('path');
        $this->Smarty->setCompileDir($this->dataFolder.'compile/')
                     ->setCacheDir($this->dataFolder.'cache/')
                     ->setTemplateDir($path['themes'].$theme);
        $template = $this->template.'.tpl';
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Content-Type: text/html');
        $this->Smarty->display($template);
    }        
}




?>