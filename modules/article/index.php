<?php


class Article extends Main{	

	function __construct($attributes=0){
		parent::__construct();
		if(!is_array($attributes)){
			print_r($attributes);
		}
		else{
			$replace = array(':id'=>$attributes['article_id']);
			$article = $this->q("SELECT * FROM mod_article WHERE id = :id",'ASSOC',$replace);
            $path = $this->getIni('path');
            $this->ArticleAssign[$attributes['location']]['module'] = 'Article';
            $this->ArticleAssign[$attributes['location']]['template'] = $path['root'].'modules/article/index.tpl';
			$this->ArticleAssign[$attributes['location']]['title'] = $article[0]['title'];
			$this->ArticleAssign[$attributes['location']]['subtitle'] = $article[0]['subtitle'];
			$this->ArticleAssign[$attributes['location']]['content'] = $article[0]['content'];
		}
	}
    
    
    

}


?>