<?php


class user extends extender{
	public $output;
	public $loggedin = FALSE;
	private $hash;

	public function __construct(){
		parent::__construct();
		$this->loggedin = FALSE;
		if(isset(system::request()[2])){
			if(system::request()[2] == 'activate'){
				$this->logout(0);
				if(isset(system::request()[3])){
					$this->activate(system::request()[3]);
				}
				
			}
		}
		$this->output = array('login'=>array(), 'message'=>'', 'loggedin'=>$this->loggedin);
		$this->checkUser();
		if($this->loggedin === FALSE){
			if(isset($_POST['action']) && $_POST['action'] === 'loginuser'){
				$this->loggedin = $this->login();
			} else {
				$this->output['forms']['user-form-login'] = $this->createForm('user-form-login');
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
			$this->output['forms']['user-form-login'] = $this->createForm('user-form-login');
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
		$this->db->queryRow('SELECT salt FROM user_secval v INNER JOIN user u ON u.id = v.user_id WHERE u.username = :username', array(':username'=>$username));
		if(empty($this->db->return['salt'])){
			return 0;
		} else {
			return $this->db->return['salt'];
		}
	}

	private function password($pass, $salt){
		$crypt = crypt($pass, '$2y$10$'.$salt);
		return $crypt;
	}

	private function login(){
		$username = $_POST['username'];
		$salt = $this->getSalt($username);
		$password = $this->password($_POST['password'], $salt);
		$this->db->queryRow('SELECT count(*) as row, id, status FROM user WHERE username = :username AND password = :password', array(':username'=>$username, ':password'=>$password));
		if($this->db->return['row'] == 1){
			$return = $this->db->return;
			if($return['status'] == 1){
				$data['user_id'] = $return['id'];
				$data['hash'] = hash('sha256', time().rand(99999,99999999999999).md5(rand(99999,99999999999999)));
				$_SESSION['user'] = $data['hash'];
				$data['ip']   = system::server('REMOTE_ADDR');
				$this->db->insertData('user_session', $data);
				return TRUE;
			} else {
				$this->output['message']['type'] = 'error';
				$this->output['message']['text'] = _('Account is disabled.');
				return FALSE;
			}
		} else {
			$this->output['message']['type'] = 'error';
			$this->output['message']['text'] = _('Username or password incorrect.');
			return FALSE;
		}
	}

	private function logout($set=1){
		$this->delSession();
		if($set==1){
			header('Location: /');
		} else {
			// do nothing
		}	
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
		$this->db->queryData('SELECT * FROM form_field WHERE form_id = :formid', array(':formid'=>$form['id']));
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
			$this->output['admin']['leftmenu'][0]['name']     = _('Dashboard');
			$this->output['admin']['leftmenu'][0]['function'] = '';
			$this->output['admin']['leftmenu'][1]['name']     = _('Create');
			$this->output['admin']['leftmenu'][1]['function'] = 'create';
			$this->output['admin']['leftmenu'][2]['name']     = _('Edit');
			$this->output['admin']['leftmenu'][2]['function'] = 'edit';
			$this->loadCurrentAdmin();
		}
	}

	public function loadCurrentAdmin(){
		if(isset(system::request()[2]) && !empty(system::request()[2])){
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

	public function getUserList($get = 'raw'){
		$this->db->queryData('SELECT 	
			u.id, u.username, u.status, u.created,
										p.firstname, p.lastname, p.gender, p.email,
										(SELECT lastaction FROM user_session WHERE user_id = u.id ORDER BY id DESC LIMIT 1) as lastaction
										FROM user u INNER JOIN user_profile p ON u.id = p.user_id');
		$return = $this->db->return;
		$columns = array();
		foreach($return as $num=>$row){
			foreach($row as $column=>$data){
				if(!in_array($column, $columns)){
					$columns[]    = $column;
				}
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
		switch($get){
			case "raw":
				return $return;
			case "separate":
				return array('columns'=>$columns, 'rows'=>$rows);
		}
	}

	public function dashboard(){
		$userlist = $this->getUserlist('separate');
		$this->output['admin']['userlist']['columns'] = $userlist['columns'];
		$this->output['admin']['userlist']['rows']    = $userlist['rows'];
	}

	public function activate($hash){
		$this->db->queryRow('SELECT count(*) as num, username FROM user WHERE hash = :hash AND status = 0', array(':hash'=>$hash));
		$prev = $this->db->return;
		if($prev['num'] == 1){
			$search['hash'] = $hash;
			$data['status'] = 1;
			$data['hash'] = '';
			$this->db->updateTable('user', $search, $data);
			$this->db->queryRow('SELECT count(*) as num, username FROM user WHERE username = :username AND status = 1', array(':username'=>$prev['username']));
			if($this->db->return['num'] == 1){
				$this->output['message']['type'] = 'success';
				$this->output['message']['text'] = _('User has been activated. You may now login.');
			}
		} else {
			$this->output['message']['type'] = 'error';
			$this->output['message']['text'] = _('User could not be activated.');
		}		
	}

	public function create(){
		if(isset($_POST['action']) && $_POST['action'] == 'createuser'){
			if($_POST['password-first'] !== $_POST['password-second']){
				$this->output['message']['type'] = 'error';
				$this->output['message']['text'] = _('Passwords do not match.');
			} elseif(	(system::validate($_POST['emailaddress'], 'EMAIL') === TRUE)
					){
				$salt = $this->createSalt();
				$user['username'] = $_POST['username'];
				$user['password'] = $this->password($_POST['password-first'], $salt);
				$user['status']   = 0;
				$user['hash']     = hash('sha256', $salt);
				$user_id          = $this->db->insertData('user', $user);

				if($user_id !== FALSE AND intval($user_id)){
					$profile['user_id']   = $user_id;
					$profile['firstname'] = $_POST['firstname'];
					$profile['lastname']  = $_POST['lastname'];
					$profile['gender']    = $_POST['gender'];
					$profile['email']     = $_POST['emailaddress'];
					$this->db->insertData('user_profile', $profile);
					$sec['user_id']      = $user_id;
					$sec['salt']         = $salt;

					$this->db->insertData('user_secval', $sec);
					$maildata = $this->mailData('user_activate', 'en');
					$data     = $maildata['data'];
					$subject  = $maildata['subject'];
					$data['link']['href'] = output::getSiteSettings('site_url').'admin/user/activate/'.$user['hash'];
					$data['introduction'] = str_replace('[[SITE_URL]]',
														output::getSiteSettings('site_url'),
														$data['introduction']);
					$to['to'][] = $profile['email'];
					
			        $this->mailTemplate($to, $subject, 'user_activate', $data);
				} else {
				}
			}
		}
		$this->output['forms']['user-form-create'] = $this->createForm('user-form-create');
	}

	public function edit(){
		$userlist = $this->getUserList();
		foreach($userlist as $user){
			$users[$user['id']] = $user;
		}
		$this->output['edit']['list'] = $users;

	//	system::prePrintArray($_POST);
	}

}


?>
