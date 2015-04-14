<?php

/**
 * extend short summary.
 *
 * extend description.
 *
 * @version 1.0
 * @author Dempsey
 */
class extender{
    protected $db;
    protected $request = array();
    protected $cwd;

    public function __construct(){
        $this->cwd = $_SERVER['DOCUMENT_ROOT'];
        $this->db = new database();
        if(isset($_SERVER['PATH_INFO'])){
            $path       = explode('/', $_SERVER['PATH_INFO']);
        } else {
            $path       = array('');
        }
        unset($path[0]);
        $this->request    = implode('/',$path);
    }
}
