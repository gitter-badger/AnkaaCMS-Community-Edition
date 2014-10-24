<?php

class ArticleAdmin extends Main{
    public $Article;
    protected $function;
    protected $values;
    
	function __construct($function='', $values=''){
        if(is_string($values)){
            $this->values = array($values);
        } else {
            $this->values = $values;
        }
	    $this->function = $function;
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
        header('Location: /'.$this->getRequest('lang').'/Admin/Article/');
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
        $this->listPages();
    }
    private function insertArticle($post=0){
        $replace[':t'] = $post['mce_0'];
        $replace[':s'] = $post['mce_1'];
        $replace[':c'] = $post['mce_2'];
        $replace[':sid'] = $this->getRequest('siteprofile');
        $replace = $this->htmlCleaner($replace, 'array');
        $this->q("INSERT INTO mod_article (title, subtitle, content, siteprofile_id) VALUES (:t, :s, :c, :sid)", "HTMLINPUT", $replace);
        $article = $this->q("SELECT id FROM mod_article WHERE title=:t AND subtitle=:s AND content=:c AND siteprofile_id=:sid", "HTMLINPUT", $replace);
        //file_put_contents('/home/ontwikkel/cms-data/logs/db-debug.log', var_dump($article));
        if($post['makePage'] == 1){


            $replace = array();
            $replace[':sid']    = $this->getRequest('siteprofile');
            $replace[':sl']     = str_replace('%26nbsp%3B', '', urlencode($post['mce_0']));
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
            // Create a menuitem for the created page
            $replace = array();
            $replace[':mid'] = 1;
            $replace[':iid'] = $page[0]['id'];
            $replace[':lnk'] = str_replace('&nbsp;', '', $post['mce_0']);
            $replace[':pid'] = 0;
            $replace[':tid'] = $page[0]['id'];
            $replace[':t'] = 1;
            // Use a URI save method for the title and slug here
            $replace[':url'] = './'.str_replace('%26nbsp%3B', '', urlencode($post['mce_0']));
            $replace[':sid'] = $this->getRequest('siteprofile');
            $replace[':s'] = 1;
            $this->q("INSERT INTO ext_menu (menuid, itemid, link, parentid, type, typeid, url, status, siteprofile_id) VALUES (:mid, :iid, :lnk, :pid, :t, :tid, :url, :s, :sid)", "ASSOC", $replace);
            
        } elseif($post['usePage'] == 1 && $post['useSelPage'] !== 'NOT'){
            $replace = array();
            $replace[':poid'] = 1;
            $replace[':loid'] = 1;
            $replace[':moid'] = 1;
            $replace[':paid'] = $post['useSelPage'];
            $replace[':act']  = 1;

            $check = $this->q('SELECT * FROM ext_page WHERE siteprofile_id = :sid AND id = :id', "ASSOC", array(':sid'=>$this->getRequest('siteprofile'), ':id'=>$post['useSelPage']));
            if(count($check) > 0){
                $this->q('INSERT INTO ext_page_module (position_id, location_id, module_id, page_id, active) VALUES (:poid, :loid, :moid, :paid, :act)',"ASSOC", $replace);
                $pagemod = $this->q('SELECT id FROM ext_page_module WHERE position_id = :poid AND location_id = :loid AND module_id = :moid AND page_id = :paid AND active = :act ORDER BY id DESC LIMIT 1', "ASSOC", $replace);
                $replace = array();
                $replace['pmid'] = $pagemod[0]['id'];
                $replace[':att'] = 'article_id';
                $replace[':str'] = $article[0]['id'];
                $this->q('INSERT INTO ext_page_module_attributes (pagemodule_id, attribute, string) VALUES (:pmid, :att, :str)', "ASSOC", $replace);
            } else {
                // Do nothing... attempt of hack!
            }
        }

        header('Location: /'.$this->getRequest('lang').'/Admin/Article/');
    }
    private function updateArticle($post,$id){
        $replace[':t'] = $post['mce_0'];
        $replace[':s'] = $post['mce_1'];
        $replace[':c'] = $post['mce_2'];
        $replace[':id'] = $id;
        $replace[':sid'] = $this->getRequest('siteprofile');
        $replace = $this->htmlCleaner($replace, 'array');
        $this->q("UPDATE mod_article SET title = :t, subtitle = :s, content = :c WHERE id = :id and siteprofile_id = :sid", "HTMLINPUT", $replace);
        header('Location: /'.$this->getRequest('lang').'/Admin/Article/');
    }
    private function editArticle($post=0,$values){
        $articleID = $values[0];
        $replace = array(':id'=>$articleID);
        $replace[':sid'] = $this->getRequest('siteprofile');
        $replace = $this->htmlCleaner($replace, 'array');
		$article = $this->q("SELECT * FROM mod_article WHERE id = :id and siteprofile_id = :sid",'HTMLINPUT',$replace);
        $path = $this->getIni('path');
        $this->Article['edit'][$article[0]['id']]['id'] = $article[0]['id'];
        $this->Article['edit'][$article[0]['id']]['title'] = $article[0]['title'];
		$this->Article['edit'][$article[0]['id']]['subtitle'] = $article[0]['subtitle'];
		$this->Article['edit'][$article[0]['id']]['content'] = $article[0]['content']; 
    }
    private function listPages(){
        $replace = array(':sid'=>$this->getRequest('siteprofile'));
        $pages = $this->q("SELECT id, parent, slug FROM ext_page WHERE siteprofile_id = :sid ORDER BY parent, slug", "ASSOC", $replace);
        $this->Article['pages'] = $pages;
    }
}
?>