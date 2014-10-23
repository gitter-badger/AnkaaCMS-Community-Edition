<?php


class LangExt extends Main{


    function __construct(){
        parent::__construct();
        $lang = $this->getRequest('lang');
        $language = $this->q('SELECT file FROM languages WHERE code=:code','ASSOC',array(':code'=>$lang));
        $this->setLang($language[0]['file']);
    }

    private function setLang($file){
    	putenv('LC_ALL='.$file);
		setlocale(LC_ALL, $file);
		bindtextdomain("system", getcwd()."/application/languages");
		bind_textdomain_codeset("system", 'UTF-8');
		textdomain("system");
    }
}


?>
