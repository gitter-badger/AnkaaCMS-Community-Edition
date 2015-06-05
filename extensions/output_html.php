<?php

class output_html{
  public $return;

  public function __construct($data, $show = 'display'){
    $output = new Smarty();
    $output->setTemplateDir(system::settings('directory', 'templates'));
    $output->setCompileDir(system::settings('directory', 'compile'));
    $output->setCacheDir(system::settings('directory', 'cache'));
    $templatename = output::getSiteSettings('site_template');
    foreach($data as $key=>$value){
            $output->assign($key, $value);
        }
    $this->return = $output->$show($templatename.DIRECTORY_SEPARATOR.'index.tpl');

  }
}

?>