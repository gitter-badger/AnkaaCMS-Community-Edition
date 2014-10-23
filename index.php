<?php
$siteDomain = $_SERVER['SERVER_NAME'];
session_set_cookie_params('36000', '/', $siteDomain, false, false );
session_start();

include_once('./application/core/init.class.php');
include_once('./application/database/database.class.php');
include_once('./application/core/main.class.php');
include_once('./application/core/site.class.php');


$Main = new Site();



?>
