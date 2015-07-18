<?php
//This Application Development by ITGET Business Solution 
//Powered by ITGET PHP Framework Version 1.0.alpha.1 
//www.itget.net info@itget.net

include 'url_rewrite.php';
include 'auto_load.php';


$className = Route::getClass();
$obj = new $className();
if( ($methodName = Route::getMethod())!==null){
   $obj->$methodName();
}
//$home->$_URL[1]();
?>
