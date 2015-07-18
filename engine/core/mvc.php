<?php


class  MVC {
    
    var $data = null;
    var $model = null;
    var $view = null;
   
    
    public function loadModel(){
        global  $_PATH;
        include strtolower($_PATH['app'].Route::getClass()."/".Route::getClass().".model.php") ;
        $className = Route::getClass()."Model";
        $this->model = new $className();
    }
    
    
    public function loadView(){
        
         global  $_PATH;
         include strtolower($_PATH['app'].Route::getClass()."/".Route::getClass().".view.php") ;
           $className = Route::getClass()."View";
            $this->view = new $className();
    }
    
}

class View{
    
}
class Model{
    
}

?>