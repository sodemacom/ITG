<?php
$appData = array();
class Data{
    public static function get($index){
        global  $appData;
         if($index == null){
             $index = Route::getClass();
             
         }
        return  $appData[Route::getClass()][$index];
    }
    

    public static function set($index,$value){
         global $appData;
          if($index == null){
              $index = Route::getClass();
              
          }
          $appData[Route::getClass()][$index] = $value;
    }
    
    public static function clear(){
         global $appData;
         $appData = null();
    }
    
    
}