<?php


class AuthExt extends Main{

    private $userId;

    function __construct(){
        parent::__construct();
        $site = $this::getIni('site');
        $this->userLogin();               
    }
    
    public function userLogin(){
        if(isset($_POST['AuthSubmit'])){
            $uname = $_POST['AuthUsername'];
            $pword = $_POST['AuthPassword'];
            if($this->checkLogin($uname,$pword) == TRUE){
                $this->setSession();
                return TRUE;
            }
        } 
        if(isset($_SESSION['auth'])){
            if($this->checkSession($_SESSION['auth']) == TRUE){
                $this->LoggedIn = TRUE;
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }
    
    private function checkLogin($uname,$pword){
        $getSaltA = array(':u'=>$uname);
        $getSalt = $this->q("SELECT salt FROM sys_users WHERE username = :u",'ASSOC', $getSaltA);
        foreach($getSalt as $salt){
            $encPass = crypt($pword, '$6$rounds=99999$'.$salt['salt']);
        }
        if(!isset($encPass)){
            return FALSE;
        } else {
            $getUserA = array(':u'=>$uname, ':p'=>$encPass);
            $getUser = $this->q("SELECT count(*),id FROM sys_users WHERE username = :u AND password = :p",'ASSOC', $getUserA);
            if($getUser[0]['count(*)'] == 1){
                $this->userId = $getUser[0]['id'];
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
    }  
    private function createUser($uname,$pword){
        $salt = hash(sha512, time().rand(99999,99999999999999).md5(rand(99999,99999999999999)));
        $encPass = crypt($pword, '$6$rounds=99999$'.$salt);
        $countUsername = $this->q("SELECT count(*) FROM sys_users WHERE username = :u", 'ASSOC',array(':u'=>$uname)); 
        if($countUsername[0]['count(*)'] == 0){
            $this->q("INSERT INTO sys_users (username, password, salt) VALUES (:u, :p, :s)", 'ASSOC', array(':u'=>$uname,':p'=>$encPass,':s'=>$salt));
            echo _('user created');
        } else {
            echo _('username already exists');
        }
    }
    private function setSession(){
        $sessionKey = hash('sha512', time().rand(99999,99999999999999).md5(rand(99999,99999999999999)));
        $userSession = array('id'=>$this->userId, 'time'=>time()+86400, 'ip'=>$_SERVER['REMOTE_ADDR'], 'session'=>$sessionKey);
        $this->q('INSERT INTO sys_sessions (user_id, timestamp, ip, session) VALUES (:id, :t, :ip, :s)', 'ASSOC', array(':id'=>$userSession['id'], ':t'=>$userSession['time'], ':ip'=>$userSession['ip'], ':s'=>$userSession['session']));
        $_SESSION['auth'] = $sessionKey;
    }
    private function checkSession($session){
        if(isset($_GET['logout'])){
            session_destroy();
            header('Location: /');
            return FALSE;
        }
        $getSession = $this->q('SELECT count(*) FROM sys_sessions WHERE session=:s AND ip=:ip AND timestamp > :t', 'ASSOC', array(':s'=>$session, ':ip'=>$_SERVER['REMOTE_ADDR'], ':t'=>time()));
        if($getSession[0]['count(*)'] == 0){
            session_destroy();
            return FALSE;
        } elseif($getSession[0]['count(*)'] == 1) {
            return TRUE;
        }
    }
}

?>
