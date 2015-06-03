<?php


class header_imageSlider extends extender{

  public $output;


  public function __construct(){
    parent::__construct();
    $this->output['template'] = './imageslider.tpl';
    $this->output['slider']   = $this->getImages();
  }

  private function getImages(){
    $this->db->queryData('SELECT * FROM widget_header_imageSlider WHERE status = 1');
    $data = $this->db->return;
    foreach($data as $row){
      $images[] = $row;
    }

    return $images;
  }


}

?>