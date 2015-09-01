<?php
if('INCLUDED' === FALSE){
  die();
}

if(isset($_GET['step'])){
  switch($_GET['step']){
    case "0":
    default:
      include('checks.php');
      break;
  }
} else {
  include('checks.php');
}


?>
