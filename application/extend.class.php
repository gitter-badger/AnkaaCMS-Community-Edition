<?php

/**
 * extend short summary.
 *
 * extend description.
 *
 * @version 1.0
 * @author Dempsey
 */
class extender{
    protected $db;
    protected $request = array();
    protected $cwd;

    public function __construct(){
        $this->cwd = $_SERVER['DOCUMENT_ROOT'];
        $this->db = new database();
        $this->db->fetchMethod = PDO::FETCH_ASSOC;
        if(isset($_SERVER['REDIRECT_URL'])){
            $path       = explode('/', $_SERVER['REDIRECT_URL']);
        } else {
            $path       = array('');
        }
        unset($path[0]);
        $this->request    = implode('/',$path);
    }

    public function hasRights(){
        return TRUE;
    }

    protected function createForm($name = ''){
        $this->db->queryRow('SELECT * FROM form WHERE name = :name', array(':name'=>$name));
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

    protected function sendEmail($to, $from, $subject, $body, $attachments=array()){
        $mail = new PHPMailer;
        $mail->isSMTP(true);
        $mail->isHTML(true);
        $mail->setLanguage('nl');

        $this->db->queryData('SELECT * FROM email_server WHERE status = 1');
        $servers = $this->db->return;

        foreach($servers as $key=>$server){
            $this->db->queryRow('SELECT iv FROM crypto_values WHERE table_name = :table AND id = :id',
                                array(':table'=>'email_server', ':id'=> $server['id']));
            $c = $this->db->return['iv'];
            $r['c'] = $c;
            $r['e'] = $server['password'];
            $server['password'] = system::crypto(1, $r);
            $servers[$key] = $server;
        }

        $mail->Host       = $servers[0]['hostname'];
        $mail->Username   = $servers[0]['username'];
        $mail->Password   = $servers[0]['password'];
        $mail->SMTPSecure = $servers[0]['security'];
        $mail->Port       = $servers[0]['port'];

        if(is_array($from)){
            $mail->From = $from['address'];
            $mail->From = $from['name'];
        } else {
            $mail->From       = output::getSiteSettings('email_address');
            $mail->FromName   = output::getSiteSettings('email_name');
        }
        //$mail->addReplyTo('');

        if(array_key_exists('bcc', $to)){
            foreach($to['bcc'] as $bcc){
                $mail->addBCC($bcc);
            }
        }
        if(array_key_exists('cc', $to)){
            foreach($to['cc'] as $bcc){
                $mail->addBCC($bcc);
            }
        }
        if(array_key_exists('to', $to)){
            foreach($to['to'] as $bcc){
                $mail->addBCC($bcc);
            }
        }

        $mail->Subject    = $subject;
        $mail->Body       = $body;
        $mail->AltBody    = '';

        //$mail->addAttachment('');
        
        
        if(!$mail->send()){
            echo $mail->ErrorInfo;
            return FALSE;
        } else {
            return TRUE;
        }
        
        unset($mail);
    }

    protected function mailData($name, $language){
        $this->db->queryRow('SELECT data FROM email_template_data WHERE name = :name AND language = :lang',
                            array(':name'=>$name, ':lang'=>$language));
        $ser            = $this->db->return;
        $uns            = unserialize($ser['data']);
        $set['subject'] = $uns['subject'];
        unset($uns['subject']);
        $set['data']    = $uns;
        return $set;

    }
    protected function mailTemplate($to = array(), $subject, $template, $data, $from=''){
        $smarty = new Smarty();
        $smarty->setTemplateDir(system::settings('directory', 'templates'));
        $smarty->setCompileDir(system::settings('directory', 'compile'));
        $smarty->setCacheDir(system::settings('directory', 'cache'));
        $templatename = 'email_templates'.DIRECTORY_SEPARATOR.$template.'.tpl';
        if(!file_exists(system::settings('directory', 'templates').$templatename)){
            if(is_array($data)){
                return FALSE;
            } else {
                $body = $data;
            }
        } else {
            if(is_array($data)){
                foreach($data as $key=>$value){
                    $smarty->assign($key, $value);
                }
                $body = $smarty->fetch($templatename);
                if($this->sendEmail($to, $from, $subject, $body) === TRUE){
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
    }

}
