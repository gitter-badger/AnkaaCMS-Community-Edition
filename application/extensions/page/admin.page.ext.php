<?php


class pageAdminExt extends Main{
    
    public $page;
    
    public function __construct($function='', $values=''){
        $this->function = $function;
        $this->values = $values;
        parent::__construct();
        if($function == 'edit'){
            $this->editPages($values);
        } else {
            $this->getPages($this->getRequest('siteprofile'));
        }
    }
    
    private function getPages($id){
        $replace = array(':sid' => $id);
        $pages = $this->q('SELECT * FROM ext_page WHERE siteprofile_id = :sid','ASSOC',$replace);
        $this->page = $pages;
    }
    
    private function editPages($values){
        $replace = array(':sid' => $this->getRequest('siteprofile'), ':id' => $values[0]);
        $pages = $this->q('SELECT * FROM ext_page_module as pm INNER JOIN ext_page AS p ON p.id = pm.page_id INNER JOIN ext_page_module_attributes AS pma ON pm.id = pma.pagemodule_id INNER JOIN modules AS m ON pm.module_id = m.id WHERE p.siteprofile_id = :sid AND p.id = :id ORDER BY pm.location_id','ASSOC',$replace);
        foreach($pages as $key=>$page){
            $page['edit'] = getcwd().'/modules/'.$pages[$key]['folder'].'/edit-page.tpl';
            include_once(getcwd().'/modules/'.$pages[$key]['folder'].'/admin.inc.php');
            $moduleadmin = $pages[$key]['name'].'Admin';
            $$moduleadmin = new $moduleadmin('edit', $page['string']);
            $this->moduleAssign[$key]['name']    = $pages[$key]['name'];
            $this->moduleAssign[$key]['content'] = $$moduleadmin->$pages[$key]['name'];
            $pages[$key] = $page;
        }

        $this->page = $pages;




    }
}


?>