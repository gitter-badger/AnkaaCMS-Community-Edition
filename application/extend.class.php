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
        $this->db->fetchMethod = PDO::FETCH_ASSOC;
        if(isset($_SERVER['REDIRECT_URL'])){
            $path       = explode('/', $_SERVER['REDIRECT_URL']);
        } else {
            $path       = array('');
        }
        unset($path[0]);
        $this->request    = implode('/',$path);
    }

    public function hasRights(){
        return TRUE;
    }
}
