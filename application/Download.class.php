<?php

class Download{
  private $content = '';

  function __construct($content, $filename, $type){
    $this->content = $content;
    $this->type    = $type;
    $this->filename= $filename;
  }
  function __destruct(){
    header('Content-Description: File Transfer');
    header('Content-Type: '.$this->type);
    header('Content-Disposition: attachment; filename='.$this->filename);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    ob_end_clean();
    echo $this->content;
    exit();
  }
}

?>
