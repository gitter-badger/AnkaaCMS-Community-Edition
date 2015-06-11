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
				$this->output['form']['user-form-login'] = $this->userForm('user-form-login');
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
			$this->output['form']['user-form-login'] = $this->userForm('user-form-login');
		}
		$this->output['loggedin'] = $this->loggedin;
		if(isset(system::request()[1])){
			$current = system::request()[1];
		} else {
			$current = '';
		}
		if($this->loggedin){
			$this->setAction();
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
				$this->output['message']['type'] = 'warning';
				$this->output['message']['text'] = _('Session Timed out');
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
			$this->output['message']['type'] = 'error';
			$this->output['message']['text'] = _('Username or password incorrect.');
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
		$update['lastaction'] = date("Y-m-d H:i:s");
		$this->db->updateTable('user_session', $search, $update);
	}

	private function userForm($name = ''){
		$this->db->queryRow('SELECT * FROM user_forms WHERE name = :name', array(':name'=>$name));
		$form = $this->db->return;
		switch($form['method']){
			case 1:
				$form['method'] = 'POST';
				break;
			case 2:
				$form['method'] = 'GET';
				break;
			default:
				$form['method'] = '';
				break;
		}
		$this->db->queryData('SELECT * FROM user_form_fields WHERE user_form_id = :formid', array(':formid'=>$form['id']));
		$fields = $this->db->return;
		foreach($fields as $row=>$data){
			if($data['tag'] == 'select'){
				$fields[$row]['value'] = unserialize($data['value']);
			}
		}
		$form['fields'] = $fields;
		return $form;
	}

	public function loadAdmin(){
		if($this->hasRights() == TRUE){
			$this->output['admin']['leftmenu'][0]['name']     = 'create';
			$this->output['admin']['leftmenu'][0]['function'] = 'create';
			$this->loadCurrentAdmin();
		}
	}

	public function loadCurrentAdmin(){
		if(isset(system::request()[2])){
			$current_function = system::request()[2];
		} else {
			$current_function = 'dashboard';
		}
		$this->output['admin']['current_template'] = './adminpanel_user_'.$current_function.'.tpl';
		$this->output['admin']['current_function'] = $current_function;
		if(method_exists($this, $current_function)){
			$this->$current_function();
		}
	}

	public function dashboard(){
		$this->db->queryData('SELECT 	u.id, u.username, u.status, u.created,
										p.firstname, p.lastname, p.gender, p.email,
										(SELECT lastaction FROM user_session WHERE user_id = u.id ORDER BY id DESC LIMIT 1) as lastaction
										FROM user u INNER JOIN user_profile p ON u.id = p.user_id');
		$return = $this->db->return;
		foreach($return as $num=>$row){
			foreach($row as $column=>$data){
				$columns[]    = $column;
				if($column == 'status'){
					switch($data){
						case 1:
							$rows[$num][] = _('Enabled');
							break;
						case 0:
							$rows[$num][] = _('Disabled');
							break;
					}
				} elseif($column == 'gender'){
					$rows[$num][] = system::gender($data);
				} else {
					$rows[$num][] = $data;
				}
			}
		}
		$this->output['admin']['userlist']['columns'] = $columns;
		$this->output['admin']['userlist']['rows']    = $rows;
	}

	public function create(){
		if(isset($_POST['action']) && $_POST['action'] == 'createuser'){
			if($_POST['password-first'] !== $_POST['password-second']){
				$this->output['message']['type'] = 'error';
				$this->output['message']['text'] = _('Passwords do not match.');
			}
		}
		$this->output['form']['user-form-create'] = $this->userForm('user-form-create');
	}

}


?>
