<?php

class ArticleAdmin extends Main{
    public $Article;
    protected $function;
    protected $values;
    
	function __construct($function='', $values=''){
	    $this->function = $function;
        $this->values = $values;
        parent::__construct();
        $replace = array(':sid' => $this->getRequest('siteprofile'));
        $articles = $this->q('SELECT * FROM mod_article WHERE siteprofile_id = :sid','ASSOC',$replace);
        if(key_exists('mce_0', $_POST)){
            if(isset($values[0])){
                $this->updateArticle($_POST, $values[0]);   
            }
        }
        if(!empty($this->function)){
            $functionname = $this->function.'Article';
            $this->$functionname($_POST,$this->values);
        } else {
            $this->Article = $articles;
        }
    }
    
    private function removeArticle($post=0, $values){
        $replace = array(':id'=>$values[0]);
        $replace[':sid'] = $this->getRequest('siteprofile');
		$article = $this->q("DELETE FROM mod_article WHERE id = :id and siteprofile_id = :sid",'ASSOC',$replace);
        header('Location: /Admin/Article/');
    }
    
    private function newArticle($post=0){
        if(!empty($post['mce_0'])){
             $this->insertArticle($_POST);
        }
        $this->Article['new'][0]['title'] = _("title");
        $this->Article['new'][0]['subtitle'] = _("subtitle");
        $this->Article['new'][0]['content'] = _("content");
        $this->Article['new'][0]['makePage'] = _("Create a new page for this article");
        $this->Article['new'][0]['usePage'] = _("Use an existing page:");
    }
    
    private function insertArticle($post=0){
        $replace[':t'] = $post['mce_0'];
        $replace[':s'] = $post['mce_1'];
        $replace[':c'] = $post['mce_2'];
        $replace[':sid'] = $this->getRequest('siteprofile');
        $this->q("INSERT INTO mod_article (title, subtitle, content, siteprofile_id) VALUES (:t, :s, :c, :sid)", "ASSOC", $replace);
        $article = $this->q("SELECT id FROM mod_article WHERE title=:t AND subtitle=:s AND content=:c AND siteprofile_id=:sid", "ASSOC", $replace);
        if($post['makePage'] == 1){


            $replace = array();
            $replace[':sid']    = $this->getRequest('siteprofile');
            $replace[':sl']     = $post['mce_0'];
            $replace[':p']      = 0;
            $replace[':mid']    = 0;
            $replace[':t']      = 'default';
            $replace[':st']     = 1;
            // Make the page
            $this->q("INSERT INTO ext_page (siteprofile_id, slug, parent, moduleid, template, status) VALUES (:sid,:sl,:p,:mid,:t,:st)", "ASSOC", $replace);
            $page = $this->q("SELECT id FROM ext_page WHERE siteprofile_id = :sid AND slug = :sl", "ASSOC", array(':sid'=>$replace[':sid'],':sl'=>$replace[':sl']));
            $module = $this->q("SELECT id FROM modules WHERE name = :name", "ASSOC", array(':name' => 'Article'));
            $replace = array();
            $replace[':poid']   = 1;
            $replace[':lid']    = 1;
            $replace[':mid']    = $module[0]['id'];
            $replace[':paid']   = $page[0]['id'];
            $replace[':a']      = 1;
            // Show correct article on the correct page
            $this->q("INSERT INTO ext_page_module (position_id, location_id, module_id, page_id, active) VALUES (:poid, :lid, :mid, :paid, :a)", "ASSOC", $replace);
            $replace = array();
            $replace[':poid']   = 1;
            $replace[':lid']    = 1;
            $replace[':mid']    = $module[0]['id'];
            $replace[':paid']   = $page[0]['id'];
            $pagemod = $this->q("SELECT id FROM ext_page_module WHERE position_id = :poid AND location_id = :lid AND module_id = :mid AND page_id = :paid","ASSOC", $replace);
            $replace = array();
            $replace[':pmid']   = $pagemod[0]['id'];
            $replace[':att']    = 'article_id';
            $replace[':str']    = $article[0]['id'];
            $this->q("INSERT INTO ext_page_module_attributes (pagemodule_id, attribute, string) VALUES (:pmid, :att, :str)", "ASSOC", $replace);

            $replace = array();
            $replace[':mid'] = 1;
            $replace[':iid'] = $page[0]['id'];
            $replace[':pid'] = 0;
            $replace[':tid'] = 1;
            $replace[':t'] = 1;
            $replace[':url'] = './'.$post['mce_0'];
            $replace[':sid'] = $this->getRequest('siteprofile');
            $replace[':s'] = 1;
            $this->q("INSERT INTO ext_menu (menuid, itemid, parentid, type, typeid, url, status, siteprofile_id) VALUES (:mid, :iid, :pid, :t, :tid, :url, :s, :sid)", "ASSOC", $replace);
            
        }
        header('Location: /Admin/Article/');
    }
    
    private function updateArticle($post,$id){
        $replace[':t'] = $post['mce_0'];
        $replace[':s'] = $post['mce_1'];
        $replace[':c'] = $post['mce_2'];
        $replace[':id'] = $id;
        $replace[':sid'] = $this->getRequest('siteprofile');
        $this->q("UPDATE mod_article SET title = :t, subtitle = :s, content = :c WHERE id = :id and siteprofile_id = :sid", "ASSOC", $replace);
        header('Location: '.$this->getRequest('lang').'/Admin/Article/');
    }
    private function editArticle($post=0,$values){
        $articleID = $values[0];
        $replace = array(':id'=>$articleID);
        $replace[':sid'] = $this->getRequest('siteprofile');
		$article = $this->q("SELECT * FROM mod_article WHERE id = :id and siteprofile_id = :sid",'ASSOC',$replace);
        $path = $this->getIni('path');
        $this->Article['edit'][0]['id'] = $article[0]['id'];
        $this->Article['edit'][0]['title'] = $article[0]['title'];
		$this->Article['edit'][0]['subtitle'] = $article[0]['subtitle'];
		$this->Article['edit'][0]['content'] = $article[0]['content']; 
    }
}
?>