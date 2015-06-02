<?php

/**
 * menu short summary.
 *
 * menu description.
 *
 * @version 1.0
 * @author Dempsey
 */
class menu extends extender{
    public $output;

    public function __construct(){
        parent::__construct();
        $top[0] = array(
                        'href'  => '/Home',
                        'title' => 'Homepage',
                        'name'  => 'Home',
                        );
        $top[1] = array(
                        'href'  => '/About',
                        'title' => 'About Us',
                        'name'  => 'About',
                        );
        foreach($top as $row=>$item){
            if($this->getCurrentItem($item['name'])){
                $top[$row]['class'] = 'current';
            } else {
                $top[$row]['class'] = '';
            }
        }
        $this->output['top'] = $top;
    }

    private function getCurrentItem($item){
        $aRequest = explode('/', $this->request);
        if($item == $aRequest[0]){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}