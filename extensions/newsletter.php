<?php

class newsletter extends extender{
	public $output;

	public function __construct(){
		parent::__construct();
		if(system::request()[0] == 'admin' && isset(system::request()[1]) && system::request()[1] == 'newsletter'){
			$this->loadAdmin();
		}
	}

	public function dashboard(){
		$this->db->queryData('	SELECT 
									*,
									(SELECT count(*) FROM newsletter_mail m WHERE m.newsletter_id = n.id) as mails,
									(SELECT count(*) FROM newsletter_subscriber s WHERE s.newsletter_id = n.id) as subscribers
								FROM newsletter n');
		$return  = $this->db->return;
		$columns = array();
		$rows    = array();
		foreach($return as $num=>$row){
			foreach($row as $column=>$data){
				if(!in_array($column, $columns)){
					if($column == 'template_name'){
						$column = 'template';
					}
					if($column == 'last_send'){
						$column = 'last send';
					}
					$columns[]    = $column;
				}
				$rows[$num][] = $data;
			}
		}
		$this->output['list']['columns'] = $columns;
		$this->output['list']['rows'] = $rows;
	}

	public function loadAdmin(){
		if($this->hasRights() == TRUE){
			$this->output['leftmenu'][0]['name']     = _('Dashboard');
			$this->output['leftmenu'][0]['function'] = '';
			$this->output['leftmenu'][1]['name']     = _('Create Newsletter');
			$this->output['leftmenu'][1]['function'] = 'create';
			$this->output['leftmenu'][2]['name']     = _('Edit Newsletter');
			$this->output['leftmenu'][2]['function'] = 'edit';
			$this->output['leftmenu'][3]['name']     = _('Create Mail');
			$this->output['leftmenu'][3]['function'] = 'createmail';
			$this->output['leftmenu'][4]['name']     = _('Edit unsend Mail');
			$this->output['leftmenu'][4]['function'] = 'editmail';
			$this->loadCurrentAdmin();
		}
	}

	public function loadCurrentAdmin(){
		if(isset(system::request()[2]) && !empty(system::request()[2])){
			$current_function = system::request()[2];
		} else {
			$current_function = 'dashboard';
		}
		$this->output['current_template'] = './adminpanel_newsletter_'.$current_function.'.tpl';
		$this->output['current_function'] = $current_function;
		if(method_exists($this, $current_function)){
			$this->$current_function();
		}
	}

	public function create(){
		if(isset($_POST['action']) && $_POST['action'] == 'createnewsletter'){
			unset($_POST['action']);
			$this->db->insertData('newsletter', $_POST);
		}
		$this->output['forms']['newsletter-form-create'] = $this->createForm('newsletter-form-create');
	}

	public function createmail(){
		if(isset($_POST['content']) && system::request()[3] == 'savemail'){
			print_r($_POST);
			$data['content'] 		= $_POST['content'];
			$data['status']  		= 1;
			$data['name']			= $_POST['subject'];
			$data['newsletter_id']	= 2;
			$this->db->queryRow('SELECT address FROM newsletter WHERE id = :id', array(':id'=>$data['newsletter_id']));
			$from['address'] 		= $this->db->return['address'];
			$from['name']			= 'A-VISION B.V.';
			if(isset($_POST['test']) && $_POST['test'] == TRUE){
				$mailaddress = $_POST['testmail'];
				$to['to'][]				= $mailaddress;
				$this->mailTemplate($to, $data['name'], 'avision_newsletter', array('newsletter'=>array('email_content'=>$data['content'])), $from);
			} else {
				$this->db->insertData('newsletter_mail', $data);
			}
		}
		$this->output['email_content']  = '';
		$this->output['email_template'] = '../email_templates/avision_newsletter.tpl';
	}
}

?>