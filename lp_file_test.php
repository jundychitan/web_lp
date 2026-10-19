<?php

	date_default_timezone_set('Asia/Manila');
	
	/*FILE IMPORT*/
	/*PRODUCTION*/
	
	/*Get the list on the New Files*/
	
	include('connection.php');

			$date_upload = date("Y-m-d");
			$time_upload = date("H:i:s");
			
			$date_time = "$date_upload $time_upload";

		$_app_path = realpath(dirname(__FILE__));
		$import_path = "$_app_path/upload/importloadprofile/";
		
		#echo "$import_path";
		
		foreach (glob($import_path."*") as $filepath) {
			
		#echo $filepath."\n";
		
		@$explode_filepath = explode("/", $filepath);
		
		$file_name_details = $explode_filepath[7];
		echo $file_name_details."\n";
		
		@$explode_file_name_details = explode("_", $file_name_details);
		$location_details = $explode_file_name_details[0];
		$meter_details = $explode_file_name_details[1];
		
		/*check if already imported*/
		/**/
		
		/*Check import file on load_profile_imported_files*/
		$sql_check_import = "SELECT *
	 		FROM load_profile_imported_files WHERE file_name = '$file_name_details'";
	
		$result_check_import = mysqli_query($conn, $sql_check_import);
		echo @$import_file_count = mysqli_num_rows($result_check_import);
		

			
		
	
				/*Save to */
				/*unlink file after import*/				
				//shell_exec("rm $filepath");
		}
		
?>