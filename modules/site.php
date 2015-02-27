<?php

/**
 * post short summary.
 *
 * post description.
 *
 * @version 1.0
 * @author Dempsey
 */
class site{
    
    public $output;
    
    public function __construct(){
        $this->db = new database();
        $this->getSettings();
        // $this->setSettings('error_display', 'false');
    }
    
    private function getSettings(){
        $this->db->fetchMethod = PDO::FETCH_NUM;
        $this->db->queryData('SELECT * FROM settings');
        foreach($this->db->return as $setting){
            $settings[$setting[1]] = $setting[2];
        }
        $this->output['settings'] = $settings;
    }
    
    public function setSettings($name, $value){
        $data['settings_name']  = $name;
        $data['settings_value'] = $value;
        $this->db->insertData('settings', $data);
    }
    
}
