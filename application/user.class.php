<?php


class user extends extender{
	public $output;
	public $loggedin = FALSE;
	private $hash;

	public function __construct(){
		parent::__construct();
		$this->loggedin = FALSE;
		$this->output = array('login'=>array(), 'message'=>'', 'loggedin'=>$this->loggedin);
		$this->checkUser();

		if($this->loggedin === FALSE){
			if(isset($_POST['username'])){
				$this->loggedin = $this->login();
			} else {
				$this->userForm();
			}
		}
		if($this->loggedin === TRUE){
			if(system::request()[0] == 'user'){
				if(empty(system::request()[1])){
					header('Location: /');
				} else {
					switch(system::request()[1]){
						case "logout":
							$this->logout();
							break;
						default:
							$this->logout();
							break;
					}
				}
			}
		} else {
			$this->userForm();
		}
		$this->output['loggedin'] = $this->loggedin;
		if(isset(system::request()[1])){
			$current = system::request()[1];
		} else {
			$current = '';
		}
		if($this->loggedin == TRUE && $current == 'user'){
			$this->loadAdmin();
		}
	}

	public function checkUser(){
		if($this->checkSession() !== FALSE){
			if($this->checkDBEntry() === TRUE){
				$this->loggedin = TRUE;
			} else {
				$this->loggedin = FALSE;
			}
		} else {
				$this->loggedin = FALSE;
		}
	}

	public function checkSession(){
		if(system::getSession('user') !== FALSE){
			$this->hash = system::getSession('user');
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function delSession(){
		$session['hash'] = $this->hash;
		$session['ip']   = system::server('REMOTE_ADDR');
		$this->db->removeData('user_session', $session);
		session_destroy();
	}

	public function checkDBEntry(){
		$this->db->queryRow('SELECT count(*) as count, UNIX_TIMESTAMP(user_session.lastaction) as last FROM user_session WHERE hash=:hash AND ip=:ip ORDER BY id DESC',
							array(':hash'=>$this->hash, 'ip'=>system::server('REMOTE_ADDR')));
		$return = $this->db->return;
		if($return['count'] == 1){
			$diff = time() - $return['last'];
			if($diff > output::getSiteSettings('user_timeout')){
				$this->output['message'] = _('Session Timed out');
				$this->delSession();
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	private static function createSalt(){

		return hash('sha512', time().rand(99999,99999999999999).md5(rand(99999,99999999999999)));
	}

	private function getSalt($username){
		$this->db->queryRow('SELECT value FROM user_secval v INNER JOIN user u ON u.id = v.uid WHERE u.username = :username', array(':username'=>$username));
		if(empty($this->db->return['value'])){
			return 0;
		} else {
			return $this->db->return['value'];
		}
	}

	private function login(){
		$username = $_POST['username'];
		$salt = $this->getSalt($username);
		$password = crypt($_POST['password'], '$2y$10$'.$salt);
		$this->db->queryRow('SELECT count(*) as row, id FROM user WHERE username = :username AND password = :password', array(':username'=>$username, ':password'=>$password));
		if($this->db->return['row'] == 1){
			$return = $this->db->return;
			$data['user_id'] = $return['id'];
			$data['hash'] = hash('sha256', time().rand(99999,99999999999999).md5(rand(99999,99999999999999)));
			$_SESSION['user'] = $data['hash'];
			$data['ip']   = system::server('REMOTE_ADDR');
			$this->db->insertData('user_session', $data);
			return TRUE;
		} else {
			$this->output['message'] = _('Username or password incorrect.');
			return FALSE;
		}
	}

	private function logout(){
		$this->delSession();
		header('Location: /');
	}

	public function setAction(){
		$search['hash'] = $this->hash;
		$search['ip']   = system::server('REMOTE_ADDR');
		$update['lastaction'] = time();
		$this->db->updateTable('user_session', $search, $update);
	}

	public function userForm(){
		$form['class']  = 'form-signin';
		$form['action'] = '';
		$form['method'] = 'POST';
		$form['name']   = 'form-signin';
 
 		$fields[3]['tag']			= 'h2';
 		$fields[3]['value']			= _('Please Login');
 		$fields[3]['type']			= 'html';
 		$fields[3]['class']			= 'form-signin-heading';

		$fields[0]['labelclass']	= 'sr-only';
		$fields[0]['label']			= 'Username';
		$fields[0]['tag']			= 'input';
		$fields[0]['type']			= 'text';
		$fields[0]['id']			= 'inputUsername';
		$fields[0]['name']			= 'username';
		$fields[0]['class']			= 'form-control';
		$fields[0]['placeholder']	= 'Username';
		$fields[0]['value']			= '';
		$fields[0]['required']		= TRUE;
		$fields[0]['autofocus']		= TRUE;

		$fields[1]['labelclass']	= 'sr-only';
		$fields[1]['label']			= 'Password';
		$fields[1]['tag']			= 'input';
		$fields[1]['type']			= 'password';
		$fields[1]['id']			= 'inputPassword';
		$fields[1]['name']			= 'password';
		$fields[1]['class']			= 'form-control';
		$fields[1]['placeholder']	= 'Password';
		$fields[1]['value']			= '';
		$fields[1]['required']		= TRUE;
		$fields[1]['autofocus']		= FALSE;

		/*
		$fields[4]['labelclass']	= '';
		$fields[4]['label']			= 'Remember Me';
		$fields[4]['tag']			= 'input';
		$fields[4]['type']			= 'checkbox';
		$fields[4]['id']			= '';
		$fields[4]['name']			= 'remember';
		$fields[4]['class']			= '';
		$fields[4]['placeholder']	= '';
		$fields[4]['value']			= 'remember-me';
		$fields[4]['required']		= FALSE;
		$fields[4]['autofocus']		= FALSE;
*/
		$fields[2]['labelclass']	= '';
		$fields[2]['label']			= '';
		$fields[2]['tag']			= 'input';
		$fields[2]['type']			= 'submit';
		$fields[2]['id']			= '';
		$fields[2]['name']			= 'remember';
		$fields[2]['class']			= 'btn btn-lg btn-primary btn-block';
		$fields[2]['placeholder']	= '';
		$fields[2]['value']			= 'Sign In';
		$fields[2]['required']		= FALSE;
		$fields[2]['autofocus']		= FALSE;


		$form['fields'] = $fields;
		$this->output['login']['form'] = $form;
	}

	public function loadAdmin(){
		if($this->hasRights() == TRUE){
			$this->output['admin']['leftmenu'][0]['name']     = 'create';
			$this->output['admin']['leftmenu'][0]['function'] = 'create';

			$this->output['admin']['current_template'] = './adminpanel_user_create.tpl';
			$this->output['admin']['current_function'] = 'Create';
		}
	}
}


?>
