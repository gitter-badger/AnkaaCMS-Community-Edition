<?php


class MenuExt extends Main{
	
    function __construct(){
        parent::__construct();
        $this->getItems(1);
    }

    private function getItems($menu){
        $replace = array(":menuid"=>$menu,":siteprofile_id"=>$this->getRequest('siteprofile'));
		
        $items = $this->q(" SELECT * 
                            FROM ext_menu AS m
                                LEFT JOIN ext_page AS p
                                    ON m.itemid = p.id
                            WHERE m.menuid= :menuid AND m.siteprofile_id = :siteprofile_id
                                ORDER BY itemid",'ASSOC',$replace);
        $this->MenuExtAssign['menu'] = $items;
    }

}

?>