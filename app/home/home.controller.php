<?php
   class Home extends MVC{
      
       
       public function __construct() {
           if(Route::getMethod() == null){
               echo "This is constuctor method";
           }
       }
        
        public function test(){
            $this->loadModel();
            $this->model->loadData();   
            
            $this->loadView();
            $this->view->showData();        
       }
       
   }
?>