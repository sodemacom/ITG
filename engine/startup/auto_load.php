<?php
 
 
        global $_PATH;      //ClassPath of framework
        global $cfg;
	$_PATH['app'] = "app/";
        $_PATH['core'] = "engine/core/";
        $_PATH['plugins'] = "plugins/";
        $_PATH['helpers'] = "helpers/";
	 
       
       function __autoload($classFile) //Auto load class
	{	$clsChk = $classFile;
		
	        global $_PATH;	
		$classFile = strtolower($classFile);
		foreach($_PATH as $folder) 
		{
			$openFile = @fopen($folder.$classFile.".php",'r');
			if($openFile)
			{	
				include_once($folder.$classFile.".php");
                                fclose($openFile);
				break;
                        }else{
                           if(is_dir($folder.$classFile)){
                              $openFile = @fopen($folder.$classFile."/".$classFile.".controller.php",'r');
                                if($openFile)
                                {	
                                        include_once($folder.$classFile."/".$classFile.".controller.php");
                                        fclose($openFile);
                                        break;
                                } 
                           }
                        }
		}
		if(!class_exists($clsChk)){die('class '.$clsChk.' not found.');}
	}
        
        
?>