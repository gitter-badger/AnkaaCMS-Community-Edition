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
        $top[0] = array(
                        'href'  => '/Home',
                        'title' => 'Homepage',
                        'name'  => 'Home',
                        'class' => 'current'
                        );
        $top[1] = array(
                        'href'  => '/About',
                        'title' => 'About Us',
                        'name'  => 'About',
                        'class' => ''
                        );
        $this->output['top'] = $top;
    }
}