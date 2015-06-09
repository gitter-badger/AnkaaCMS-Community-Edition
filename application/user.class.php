<?php


class user extends extender{
	public $output;
	private $hash;

	public function __construct(){
		parent::__construct();
		$this->checkUser();
	}

	public function checkUser(){
		if($this->checkSession() !== FALSE){
			if($this->checkDBEntry() === TRUE){
				$this->output['loggedin'] = TRUE;
			} else {
				$this->output['loggedin'] = FALSE;
			}
		} else {
			$this->output['loggedin'] = FALSE;
			$this->userForm();
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
		$this->db->queryRow('SELECT * FROM user_session WHERE hash=:hash AND ip=:ip AND timeout IS NULL',
							array(':hash'=>$this->hash, 'ip'=>system::server('REMOTE_ADDR')));
		$return = $this->db->return;
		if(count($return) == 1){
			if((time() - $return['lastaction']) > output::getSiteSettings('user_timeout')){
				$this->output['message'] = _('Session Timed out');
				$this->delSession();
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function setAction(){
		$search['hash'] = $this->hash;
		$search['ip']   = system::server('REMOTE_ADDR');
		$update['lastaction'] = time();
		$this->db->updateTable('user_session', $search, $update);
	}

	public function userForm(){
		$form['head'] = _('Please Login');

		$fields[0]['labelclass']	= 'sr-only';
		$fields[0]['label']			= 'Email address';
		$fields[0]['tag']			= 'input';
		$fields[0]['type']			= 'email';
		$fields[0]['id']			= 'inputEmail';
		$fields[0]['name']			= 'email';
		$fields[0]['class']			= 'form-control';
		$fields[0]['placeholder']	= 'Email address';
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

		$fields[2]['labelclass']	= '';
		$fields[2]['label']			= 'Remember Me';
		$fields[2]['tag']			= 'input';
		$fields[2]['type']			= 'checkbox';
		$fields[2]['id']			= '';
		$fields[2]['name']			= 'remember';
		$fields[2]['class']			= '';
		$fields[2]['placeholder']	= '';
		$fields[2]['value']			= 'remember-me';
		$fields[2]['required']		= FALSE;
		$fields[2]['autofocus']		= FALSE;

		$fields[3]['labelclass']	= '';
		$fields[3]['label']			= 'Sign In';
		$fields[3]['tag']			= 'button';
		$fields[3]['type']			= 'submit';
		$fields[3]['id']			= '';
		$fields[3]['name']			= 'remember';
		$fields[3]['class']			= 'btn btn-lg btn-primary btn-block';
		$fields[2]['placeholder']	= '';
		$fields[3]['value']			= '';
		$fields[3]['required']		= FALSE;
		$fields[3]['autofocus']		= FALSE;


		$form['fields'] = $fields;
		$this->output['login']['form'] = $form;
	}
}


?>
