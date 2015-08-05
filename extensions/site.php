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
        $this->getSiteID();
        $this->getSiteStatus($this->getSiteID());
        if(system::request()[0] == 'admin' && system::request()[1] = 'site'){
            $this->output['admin']['leftmenu'] = $this->adminMenu();
            if(isset(system::request()[2]) && !empty(system::request()[2])){
                if(method_exists($this, system::request()[2])){
                    $method = system::request()[2];
                    $this->$method();
                } else {
                    $this->dashboard();
                }
            } else {
                $this->dashboard();
            }
        }
    }
    private function getSettings($site_id=''){
        $settings_full = array();
        $settings = array();
        $query = 'SELECT * FROM settings s3
                           INNER JOIN site_settings s2
                               ON s3.settings_id = s2.settings_id
                           INNER JOIN site s1
                               ON s1.id = s2.site_id ';
        if(empty($site_id)){
            $query .='WHERE s1.domain = :site_domain ';
            $site_search = array(':site_domain'=>$_SERVER['HTTP_HOST']);
        } else {
            $query .='WHERE s1.id = :site_id ';
            $site_search = array(':site_id'=>intval($site_id));
        }
        $this->db->fetchMethod = PDO::FETCH_NUM;
        $this->db->queryData(
                    $query,
                    $site_search);
        $return = $this->db->return;
        foreach($return as $setting){
            $settings_full[$setting[0]]['id'] = $setting[0];
            $settings_full[$setting[0]]['name'] = $setting[1];
            $settings_full[$setting[0]]['value'] = $setting[2];
            $settings_full[$setting[0]]['name'] = $setting[1];
            $settings[$setting[1]] = $setting[2];
        }
        if(empty($site_id)){
            $this->output['settings'] = $settings;
            $this->output['settings_full'] = $settings_full;
        } else {
            $this->output['settings_full'] = $settings_full;
        }
    }
    public function setSettings($name=0, $value=0){
        if(isset(system::request()[3]) && !empty(system::request()[3])){
            $this->output['current_function'] = 'Save Settings';
            switch(system::request()[3]){
                case "saveall":
                    $num = count($_POST);
                    $i = 1;
                    foreach($_POST as $setting=>$value){
                        $search['settings_id']  = $setting;
                        $data['settings_value'] = $value;
                        if(!is_numeric($search['settings_id'])){
                          if($i == $num){
                            $new_SettingID = $this->db->insertData('settings',
                                                    array('settings_name'=>$setting
                                                        , 'settings_value'=>$value
                                                        ));
                          } else {
                            $new_SettingID = $this->db->insertData('settings',
                                                    array('settings_name'=>$setting
                                                        , 'settings_value'=>$value
                                                        ));
                          }

                          if(isset($new_SettingID)){
                            if(isset(system::request()[4])){
                              $this->siteID = intval(system::request()[4]);
                            }

                            $this->db->insertData('site_settings', array(
                              'site_id'      => $this->siteID
                              ,'settings_id' => $new_SettingID
                            ));
                          }

                        } else {
                          if($i == $num){
                              $this->db->updateTable('settings', $search, $data, true);
                          } else {
                              $this->db->updateTable('settings', $search, $data);
                          }
                        }
                        $i++;
                    }
            }
        } else {
            $data['settings_name']  = $name;
            $data['settings_value'] = $value;
            $newset['settings_id']  = $this->db->insertData('settings', $data);
        }

        //$newset['site_id']      = '';
        //$this->db->insertData('');
    }
    public function delSettings($name, $value){
      // nothing yet
    }
    public function updateSettings($name, $newvalue){
      // Nothing yet
    }
    protected function getSiteID(){
      if(!isset($this->siteID) OR $this->siteID == 0){
        $this->db->queryRow('SELECT * FROM site WHERE domain=:domain',
                            array(':domain'=>$_SERVER['HTTP_HOST']));
        return $this->db->return['id'];
      } else {
        return $this->siteID;
      }
    }
    protected function getSiteStatus($id){
      $this->db->queryRow('SELECT status FROM site WHERE id = :id', array(':id'=>$id));
      $status = $this->db->return;
      $this->output['status'] = $status['status'];
      return $status['status'];
    }
    public function adminMenu(){
        $this->db->queryData('SELECT id, domain, status FROM site');
        $sites = $this->db->return;
        return $sites;
    }
    public function view(){
        $this->output['admin']['current_function'] = _('View');
        //system::prePrintArray($this->getSettings(2));
        $this->getSiteStatus(system::request()[3]);
        $this->getSettings(system::request()[3]);
    }
    public function dashboard(){
        $this->output['admin']['current_function'] = 'Settings';
        }


}
