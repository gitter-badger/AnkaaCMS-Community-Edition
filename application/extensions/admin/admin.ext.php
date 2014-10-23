<?php


class AdminExt extends AuthExt{
    
    public function __construct(){
       parent::__construct();
       if(parent::userLogin() == TRUE){
           $this->showToolbar();
       } else {
            $this->AdminExtAssign['toolbar'] = FALSE;
       }
    }
    
    private function showToolbar(){
        $this->AdminExtAssign['toolbar'] = TRUE;
        $this->AdminExtAssign['subMenuPages'] = $this->getPages();
    }
    
    private function getPages(){
        $pages = $this->q("SELECT id, slug, parent, status FROM ext_page WHERE siteprofile_id = :siteid", 'ASSOC', array(':siteid'=>$this->getRequest('siteprofile')));
        $pageModules = $this->q("SELECT pm.id, module_id, page_id, active, m.name  FROM ext_page_module AS pm INNER JOIN modules AS m ON pm.module_id = m.id");
        foreach($pages as $rows => $cols){
            foreach($pageModules as $key=>$val){
                if($val['page_id'] == $cols['id']){
                    $pages[$rows]['modules'][$key] = $pageModules[$key]; 
                }
            }
            
        }
        return $pages;
    }
}



?>