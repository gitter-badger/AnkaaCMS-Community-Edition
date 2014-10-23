<?php


class Parser extends Main{

    function __construct(){
        parent::__construct();
        $this->parse();
    }

    function parse(){
        foreach($this->assign as $name => $value){
            $this->Smarty->assign($name,$value);
        }
   //     $this->Smarty->display('./themes/default/index.tpl');
    }

}


?>