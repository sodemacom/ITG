<?php
// explode  url width ?
$url_pettern = explode("?",$_SERVER['REQUEST_URI']);
 
 if($url_pettern[0][strlen($url_pettern[0])-1]!="/"){
        echo "Error 404.";
        exit();
}
foreach($_GET as $key => $val){
	$_URL = explode("/",$key);
	break;
}		
if(!$_GET){
    $_URL[0] = "Home";
    
}     




class URL{
    public static function chanel($index){
         global   $_URL;
        return $_URL[$index];
    }
}



class Route {
    
    public static function getClass(){
        global   $_URL;
        if($_URL){
            return $_URL[0];
        }else{
            return null;
        }
        
    }
    
    public static function getMethod(){
        global   $_URL;
        if($_URL){
            return $_URL[1];
        }else{
            return null;
        }
    }
    
    public static function getID(){
        global   $_URL;
        if($_URL){
            return $_URL[2];
        }else{
            return null;
        }
    }
    
    public static function go($pathName){
        return "?".$pathName;
    }
    
    
    public static function goToClass($className){
        return "?".$className;
    }
    
    public static function goToMethod($methodName){
        return "?".Route::getClass()."/".$methodName;
    }
    
    public static function goAdd(){
         return "?".Route::getClass()."/add";
    }
    
    public static function goEdit($index){
        return "?".Route::getClass()."/edit/".$index;
    }
    
    public static function goDelete($index){
        return "?".Route::getClass()."/delete/".$index;
    }
    
    public static function goDetail($index){
        return "?".Route::getClass()."/detail/".$index;
    }
    
    public static function goView($index){
        return "?".Route::getClass()."/view/".$index;
    }
    
    public static function goHome(){
        return "?".Route::getClass();
    }
    
    
}












?>