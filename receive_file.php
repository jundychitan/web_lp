<?php
date_default_timezone_set('Asia/Manila');
 
			include('connection.php');

			$dest_dir = (trim($_POST['dest_dir']));
			@$overwrite = $_POST['overwrite']+0;
 
			$dir = ("./upload/$dest_dir");
			 
			$date_upload = date("Y-m-d");
			$time_upload = date("H:i:s");
			
			$date_time = "$date_upload $time_upload |";	
						
							
			/*Disable*/
			/*CHECK DIRECTORY IF EXSIST*/
			if(!is_dir($dir)){
				//Directory does not exist, so lets create it.
				mkdir($dir, 0755);							
			}
			 
				$uploaddir = realpath('./upload/'.$dest_dir).'/';
				$uploadfile = $uploaddir . basename($_FILES['file_contents']['name']);
			
				$_app_path = realpath(dirname(__FILE__));
				
				$fname = basename($_FILES['file_contents']['name']);
				@$file_name = explode("_", $fname);
				/*CHECK FOR CONVERTED CSV FILE*/
				$csv_file = str_replace(".txt",".csv","$uploadfile");
				//echo $uploadfile
				//echo "<h2>$uploadfile</h2>";
				@$explode_desdir = explode("/", $uploaddir);				
				$location_detail = $explode_desdir[7];
				
			//if(@$overwrite == 1){
				
					//echo "File Exists.. will be overwriten\n";
					move_uploaded_file($_FILES['file_contents']['tmp_name'], $uploadfile);
					$upload_code_overwrite = $_FILES['file_contents']['error'];
					
					if ($upload_code_overwrite == 0) {
					
					$import = '0';
					echo 'SUCCESS';
					if($explode_desdir[6]=='lp'){
					/*Copy Uploaded to temporary dolder*/
					shell_exec("cp $uploadfile $_app_path/upload/importloadprofile/$location_detail"."_". basename($_FILES['file_contents']['name'])."");
					}

					$logUPLOAD_DETAILS = " OVERWRITE FILE $date_time $dest_dir $overwrite $uploadfile $uploaddir ";
					$logUPLOAD = file_put_contents('/var/www/html/lp/lp_upload_log.txt', $logUPLOAD_DETAILS.PHP_EOL , FILE_APPEND | LOCK_EX);							
					
					} 
					else {
					
					$import = '0';
					echo 'FAILED';
					
						if($upload_code_overwrite==1){
							$upload_err_description = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
						}
						else if($upload_code_overwrite==2){
							$upload_err_description = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
						}
						else if($upload_code_overwrite==3){
							$upload_err_description = "The uploaded file was only partially uploaded";
						}
						else if($upload_code_overwrite==4){
							$upload_err_description = "No file was uploaded";
						}
						else if($upload_code_overwrite==6){
							$upload_err_description = "Missing a temporary folder. Introduced in PHP 5.0.3";
						}
						else if($upload_code_overwrite==7){
							$upload_err_description = "Failed to write file to disk. Introduced in PHP 5.1.0";
						}
						else if($upload_code_overwrite==8){
							$upload_err_description = "A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0";
						}

					}	    
								
			//}
			/*else{

			@$file_dt = date('Y-m-d', strtotime($file_name[2]. ' + 1 days'));
			@$meter_id_lp_date = mysqli_real_escape_string($conn,$file_dt);
	
				//IT EXSIS
					move_uploaded_file($_FILES['file_contents']['tmp_name'], $uploadfile);
					$upload_code = $_FILES['file_contents']['error'];

						$import = '0';	
						echo 'SUCCESS';
					
					if($explode_desdir[6]=='lp'){
					Copy Uploaded to temporary dolder
					shell_exec("cp $uploadfile $_app_path/upload/importloadprofile/$location_detail"."_". basename($_FILES['file_contents']['name'])."");
					}
					
					$logUPLOAD_DETAILS = " COPY FILE $date_time $dest_dir $overwrite $uploadfile $uploaddir ";
					$logUPLOAD = file_put_contents('/var/www/html/lp/lp_upload_log.txt', $logUPLOAD_DETAILS.PHP_EOL , FILE_APPEND | LOCK_EX);		
			
						if($file_name[0] ==''){
							$file_name_new = "NO FILE NAME";
						}else{
							$file_name_new = $file_name[0];
						}						
				}*/












/*
UPLOAD_ERR_OK
Value: 0; There is no error, the file uploaded with success.

UPLOAD_ERR_INI_SIZE
Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.

UPLOAD_ERR_FORM_SIZE
Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.

UPLOAD_ERR_PARTIAL
Value: 3; The uploaded file was only partially uploaded.

UPLOAD_ERR_NO_FILE
Value: 4; No file was uploaded.

UPLOAD_ERR_NO_TMP_DIR
Value: 6; Missing a temporary folder. Introduced in PHP 5.0.3.

UPLOAD_ERR_CANT_WRITE
Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.

UPLOAD_ERR_EXTENSION
Value: 8; A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0.
*/
		
?>