<?php


$dest_dir = 'lp/CEDC';
			@$overwrite = 0;
 
			$dir = ("./upload/$dest_dir");
			 
			$date_upload = date("Y-m-d");
			$time_upload = date("H:i:s");
			
			$date_time = "$date_upload $time_upload |";	
						
			// $logUPLOAD_DETAILS = "$date_time $dest_dir $overwrite";
			// $logUPLOAD = file_put_contents('/var/www/html/lp/lp_upload_log.txt', $logUPLOAD_DETAILS.PHP_EOL , FILE_APPEND | LOCK_EX);		
			 				
			
				$uploaddir = realpath('./upload/'.$dest_dir).'/';
				//$uploadfile = $uploaddir . basename($_FILES['file_contents']['name']);
			
			
				$_app_path = realpath(dirname(__FILE__));
				echo "$uploaddir $dest_dir $_app_path\n";
				//$fname = basename($_FILES['file_contents']['name']);
				//@$file_name = explode("_", $fname);
				/*CHECK FOR CONVERTED CSV FILE*/
				//$csv_file = str_replace(".txt",".csv","$uploadfile");
	
				@$explode_desdir = explode("/", $_app_path."/upload/$dest_dir");				
				$location_detail = $explode_desdir[7];

						var_dump($explode_desdir);
						echo "\n $explode_desdir[6] $location_detail \n";
						