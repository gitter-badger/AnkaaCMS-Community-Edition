<?php


class PageExt extends Main{
    var $PageExtAssign;
    
    function __construct(){
        parent::__construct();
		$replace = array( ':slug'=>$this->getRequest('page'),
                          ':siteprofile_id'=>$this->getRequest('siteprofile')
                          );
        $page 	 = $this->q('SELECT * FROM ext_page WHERE slug = :slug AND (siteprofile_id = :siteprofile_id OR siteprofile_id = 0) ','ASSOC',$replace);
		$pageid  = array(':pageid'=>$page[0]['id']);
		$modules = $this->q('SELECT pm.* FROM ext_page_module AS pm INNER JOIN modules AS m ON pm.module_id = m.id WHERE page_id = :pageid AND active = 1','ASSOC',$pageid);
		
		foreach($modules as $row){
			$moduleid = array(':id'=>$row['id']);
			$attributes = $this->q("SELECT * FROM ext_page_module_attributes WHERE pagemodule_id = :id",'ASSOC',$moduleid);
			
			foreach($attributes as $attributeRows){
				$attribute[$attributeRows['attribute']] = $attributeRows['string'];
			}
            
			$attribute['location'] = $row['location_id'];
			$this->loadModule($row['module_id'],$attribute);
			if(isset($this->ModuleAssign)){
                foreach($this->ModuleAssign as $row){
    				foreach($row as $col => $val){
    				$this->PageExtAssign['page'][$col] = $val;
    				}
    			}
			}
		}
        if(is_array($this->PageExtAssign)){
            ksort($this->PageExtAssign['page'], SORT_NUMERIC);
        }
		//$this->loadModule($page[0]['moduleid']);


	}
}


?>
