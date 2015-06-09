<?php

function autoloader($classname){
    if(file_exists('./application/'.$classname.'.class.php')){
        include_once('./application/'.$classname.'.class.php');
        spl_autoload($classname);
    }
}
spl_autoload_extensions('.class.php');
spl_autoload_register('autoloader');

include('./application/extend.class.php');
$system = new system();
$loader = new output();


?>