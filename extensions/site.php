<?php

/**
 * The Site Module makes sure there is actually a website to view
 * from the browser.
 * 
 *
 * @version 1.0
 * @author Dempsey van Wissen
 */
class site{
    
    
    public $output;


    public function __construct(){
        $this->db = new database();
        $this->getSettings();
        if(system::request()[0] == 'admin' && system::request()[1] = 'site'){
            $this->dashboard();
        }
    }
    
    private function getSettings(){
        $this->db->fetchMethod = PDO::FETCH_NUM;
        $this->db->queryData('SELECT * FROM settings s3 INNER JOIN site_settings s2 ON s3.settings_id = s2.settings_id INNER JOIN site s1 ON s1.id = s2.site_id WHERE s1.domain = :site_domain ',
                            array(':site_domain'=>$_SERVER['HTTP_HOST']));

        foreach($this->db->return as $setting){
            $settings[$setting[1]] = $setting[2];
        }
        $this->output['settings'] = $settings;
    }
    
    public function setSettings($name, $value){
        $data['settings_name']  = $name;
        $data['settings_value'] = $value;
        $newset['settings_id']  = $this->db->insertData('settings', $data);
        //$newset['site_id']      = ''; 
        //$this->db->insertData('');
    }

    public function delSettings($name, $value){

    }

    public function updateSettings($name, $newvalue){

    }

    public function dashboard(){
        $this->output['admin']['leftmenu'] = array();
        $this->output['admin']['current_function'] = 'Settings';
        }
    
    
}
