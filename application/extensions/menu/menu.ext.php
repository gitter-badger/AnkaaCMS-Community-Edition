<?php


class MenuExt extends Main{
	
    function __construct(){
        parent::__construct();
        $this->getItems(1);
    }

    private function getItems($menu){
        $replace = array(":menuid"=>$menu,":siteprofile_id"=>$this->getRequest('siteprofile'));
		
        $items = $this->q(" SELECT slug,link, url, type, typeid, target
                            FROM ext_menu AS m
                                LEFT JOIN ext_page AS p
                                    ON m.typeid = p.id
                            WHERE m.menuid= :menuid AND m.siteprofile_id = :siteprofile_id
                                ORDER BY itemid",'ASSOC',$replace);
        $this->MenuExtAssign['menu'] = $this->getType($items);
    }

    private function getType($items){
        /* Types of menulinks
        1   =   Page
        2   =   Website
        */

        foreach($items as $key => $item){
            switch($item['type']){
                case 1: // Page link
                    if(empty($items[$key]['typeid'])){
                        unset($items[$key]);
                    } else {
                        $items[$key]['slug'] = '/'.$this->getRequest('lang').'/'.$items[$key]['slug'];
                    }
                break;
                case 2: // Website link
                    $items[$key]['slug'] = $items[$key]['url'];
                break;
            }
        }
        return $items;

    }

}

?>