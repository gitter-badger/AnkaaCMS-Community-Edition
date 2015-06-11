<?php

class output_html{
  public $return;

  public function __construct($data, $show = 'display'){
    $output = new Smarty();
    $output->setTemplateDir(system::settings('directory', 'templates'));
    $output->setCompileDir(system::settings('directory', 'compile'));
    $output->setCacheDir(system::settings('directory', 'cache'));
    $request = explode('/', $_SERVER['REQUEST_URI']);
    $templatename = output::getSiteSettings($request[1].'_template');
    foreach($data as $key=>$value){
        $output->assign($key, $value);
    }
    if(file_exists(system::settings('directory', 'templates').DIRECTORY_SEPARATOR.output::getSiteSettings($request[1].'_template').DIRECTORY_SEPARATOR.'index.tpl')){
      $this->return = $output->$show($templatename.DIRECTORY_SEPARATOR.'index.tpl');
    } else {
      system::redirectDefault();
    }
    
  }
}

?>