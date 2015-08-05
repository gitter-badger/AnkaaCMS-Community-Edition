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
        $_SESSION['KCFINDER'] = array('disabled'=>FALSE);
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
        $mail->Port       = '25';//$servers[0]['port'];

        if(!empty($servers[0]['username'])){
            $mail->SMTPAuth = true;
        } else {
            $mail->SMTPAuth = false;
        }
        
        if(is_array($from)){
            $mail->From       = $from['address'];
            if(isset($from['name'])){
                $mail->FromName   = $from['name'];
            }
        } else {
            $mail->From       = output::getSiteSettings('email_address');
            $mail->FromName   = output::getSiteSettings('email_name');
        }

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

        
        
        $body = $this->mailImages($body, $mail);

        $mail->Subject    = $subject;
        $mail->Body       = $body;
        $mail->AltBody    = '';
        
        if(!$mail->send()){
            return FALSE;
        } else {
            return TRUE;
        }
        foreach($images2unset as $img){
            unset($img);
        }
        unset($mail);
    }
    protected function mailImages($body, &$mail){
        $dom = new DOMDocument();
        $dom->loadHTML($body);
        
        // Get each image in the source and process accordingly
        foreach($dom->getElementsByTagName('img') as $key=>$img){
            $origin = $img->getAttribute('src');
            // Check wether full url is given in src attribute.
            if(!isset(parse_url($origin)['scheme'])){
                // If not, set url from site
                $origin = output::getSiteSettings('site_url').$origin;
            }
            $image = file_get_contents($origin);
            $name = 'image'.$key;
            $path = system::settings('directory', 'temp').'image'.$key;
            $dim = getimagesize($path);
            file_put_contents($path, $image);
            $mail->addAttachment($path, $name);
            $img->setAttribute('src', 'cid:'.$name);
            
            $style = explode(';', $img->getAttribute('style'));
            // If image itself is larger then asked for, resize using
            // HTML and CSS for each mailclient.
            foreach($style as $styling){
                if(stripos($styling, 'width') !== FALSE){
                    $w = str_replace('px', '', explode(' ', $styling)[1]);
                }
                if(stripos($styling, 'height') !== FALSE){
                    $h = str_replace('px', '', explode(' ', $styling)[2]);
                }
            }
            if(isset($w) && isset($h)){
                $dim[0] = $w;
                $dim[1] = $h;
            }
                        
            if($img->getAttribute('width') <= 0 && $img->getAttribute('height') <= 0){
                $img->setAttribute('width',$dim[0]);
                $img->setAttribute('height',$dim[1]);
            } elseif(isset($w) && isset($h)){
                $img->setAttribute('width',$dim[0]);
                $img->setAttribute('height',$dim[1]);
            }
            $img->setAttribute('style', $img->getAttribute('style').' background-size: contain; background-image:url(\''.$origin.'\');');
            // remove image from tmp folder
            $images2unset[] = $path;
        }
        return $dom->saveHTML();       
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
