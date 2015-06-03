<?php

/**
 * index short summary.
 *
 * index description.
 *
 * @version 1.0
 * @author Dempsey
 */
class article extends extender{
    private $data = array();
    public $template;
    public $output;

    public function __construct($data=''){
        parent::__construct();
        if(!$data == ''){
            $this->data = json_decode($data);
        }
        $this->getArticle();
    }
    
    public function getArticle(){
        foreach($this->data as $key=>$data){
            switch($key){
                case "id":
                    $this->db->queryRow('SELECT * FROM articles WHERE id = :id', array(':id'=>$data));
                    $article = $this->db->return;
                    if(count($article) > 0){
                        $this->template = $this->getTemplate($article['template']);
                        $this->output['template'] = $this->template;
                        $this->output['article'] = $article;
                    }
                    break;
            }
        }
    }
    
    public function getTemplate($id){
        $this->db->queryRow('SELECT file FROM article_templates WHERE id = :id', array(':id'=>$id));
        $file = system::settings('directory', 'templates').'acalia'.DIRECTORY_SEPARATOR.'article'.DIRECTORY_SEPARATOR.$this->db->return['file'];
        return $file;
    }
    
    public function setArticle(){
        $data = json_encode(array('id'=>1));
        echo $data;
    }
    
}
