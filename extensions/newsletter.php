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
				$rows[$num][$column] = $data;
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
			if(empty($_POST['datetime']) && $_POST['now'] == 'true'){
                            $_POST['datetime'] = date("Y-m-d H:i:s");
                            $status    = 0;
                        } elseif(empty($_POST['datetime']) && $_POST['now'] == 'false'){
                            $status    = 2;
                        } else {
                            $status = 0;
                        }
			$data['content'] 		= $_POST['content'];
			$data['status']  		= $status;
			$data['name']			= $_POST['subject'];
                        $data['planned']                = $_POST['datetime'];
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
                $this->output['datetime']       = date("Y-m-d h:i:s");
	}
        public function send(){
            $this->db->queryData('SELECT
                    s.id subscriber_id,
                    s.name subscriber_name,
                    s.email to_address,
                    n.id newsletter_id,
                    n.name from_name,
                    n.address from_address,
                    n.template_name,
                    m.id mail_id,
                    m.name subject,
                    m.content
                    FROM newsletter n
                    INNER JOIN newsletter_mail m ON n.id = m.newsletter_id
                    INNER JOIN newsletter_subscriber s ON n.id = s.newsletter_id
                    WHERE m.status = 0  AND m.planned < NOW()
                                        AND s.id > m.lastsubscriber_id');
            $sending = $this->db->return;     
            
            foreach($sending as $key=>$send){
                $data['lastsubscriber_id'] = $send['subscriber_id'];
                $search['id'] = $send['mail_id'];
                $this->db->updateTable('newsletter_mail', $search, $data);
		$to['to'][] = $send['to_address'];
                $from['address']   = $send['from_address'];
                $from['name']      = $send['from_name'];
		$this->mailTemplate($to,
                                    $send['subject'],
                                    $send['template_name'],
                                    array(
                                        'newsletter'=>
                                        array(
                                            'email_content'=>$send['content'])
                                        ),
                                    $from);
            }         
            $this->db->queryData('SELECT n.id newsletter_id,
                            (SELECT id FROM newsletter_subscriber
                                WHERE newsletter_id = n.id
                                ORDER BY id DESC
                                LIMIT 1) lastid 
                            FROM newsletter n');
            $lastids    = $this->db->return;
            $this->db->queryData('SELECT id, newsletter_id,lastsubscriber_id FROM newsletter_mail WHERE status = 0 AND planned < NOW()');
            $currentids = $this->db->return;
            $last = array();
            foreach($lastids as $lastid){
                $last[$lastid['newsletter_id']] = $lastid['lastid'];
            }
            foreach($currentids as $data){
                if($data['lastsubscriber_id'] == $last[$data['newsletter_id']]){
                    $this->db->updateTable(
                            'newsletter_mail',
                            array('id'=>$data['id']),
                            array('status'=>'1')
                        );
                }
            }
        }
        public function viewmails(){
            $this->db->queryData('SELECT m.id, m.name, m.planned, n.name as newsletter, m.status send FROM newsletter_mail m INNER JOIN newsletter n ON m.newsletter_id = n.id');
		$return  = $this->db->return;
		$columns = array();
		$rows    = array();
		foreach($return as $num=>$row){
			foreach($row as $column=>$data){
                            if(!in_array($column, $columns)){
                                $columns[]    = $column;
                            }
                            if($data == 1 && $column == 'send'){
                                $data = _('True');
                            } elseif($data == 0 && $column == 'send') {
                                $data = _('False');
                            } elseif($data == 2 && $column == 'send') {
                                $data = _('Paused');
                            }
                            $rows[$num][$column] = $data;
			}
		}
                $this->output['list']['columns'] = $columns;
		$this->output['list']['rows'] = $rows;
        }
}

?>