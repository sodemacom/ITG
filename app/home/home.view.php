<?php


class HomeView{
    
        public function test(){
            echo "sssssssssssssssssssss";
        }
        
       public function showData(){
         echo Data::get("employee_name");
         echo "<br>";
         echo Data::get("employee_id");
          
       }
}
?>