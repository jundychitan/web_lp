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
		/*Check import file on load_profile_imported_files*/
		$sql_check_import = "SELECT * FROM load_profile_imported_files WHERE file_name = '$file_name_details'";	
		$result_check_import = mysqli_query($conn, $sql_check_import);
		@$import_file_count = mysqli_num_rows($result_check_import);
		
		if($import_file_count>=1){
				
				/*unlink file if already imported*/				
				shell_exec("rm $filepath");
				
		}else{
		
			/*Meter Details on meter table*/
			$sql_query_meter_info = "SELECT `id` as meter_details_id
				FROM meter_details WHERE meter_name = '$meter_details' and meter_site_name = '$location_details'";
		
			$result_check_meter_info = mysqli_query($conn, $sql_query_meter_info);
			@$meter_info_row = mysqli_fetch_row($result_check_meter_info);
			
			$meter_details_id = @$meter_info_row['0'];
	
			$sql_update_meter_load_profile = "UPDATE `meter_details` SET `meter_load_profile` = 'YES' WHERE `id` = '".$meter_details_id."'";	
							if (mysqli_query($conn, $sql_update_meter_load_profile)) {
							} else {
							echo "Error: " . $sql_update_meter_load_profile . "<br>" . mysqli_error($conn);
							}
	
					/*READ FILE HERE*/
					/*CHECK IF EXIST*/
					if ((file_exists($filepath)=='')) { 
						#$msg = "FILE NOT FOUND : $lp_file";
						#$msg = 'OK';				
					}else{
							
							#@$logs_data = file("$lp_file");	
							#$msg = 'OK';
							@$logs_data = file("$filepath");		
							$m = 1;
							foreach (@$logs_data as $logs_data_rows):
	
							if($m>=7){
	
													@$log_data_ch = explode(",", $logs_data_rows);
													$_log_data = explode(",", $logs_data_rows);
													$datetime = mysqli_real_escape_string($conn,$_log_data[0]); /*datetime*/
													$event_id = mysqli_real_escape_string($conn,$_log_data[1]); /*event_id*/
													$ch_1 = mysqli_real_escape_string($conn,$_log_data[2]); /*1.5.0_kW*/
													$ch_2 = mysqli_real_escape_string($conn,$_log_data[3]); /*1-1:1.30.2_kWh*/
													$ch_3 = mysqli_real_escape_string($conn,$_log_data[4]); /*1-1:3.30.2_kvarh*/
													$ch_4 = mysqli_real_escape_string($conn,$_log_data[5]); /*2.5.0_kW */
													$ch_5 = mysqli_real_escape_string($conn,$_log_data[6]); /*1-1:2.30.2_kWh*/
													@$ch_6 = mysqli_real_escape_string($conn,$_log_data[7])+0; /*1-1:4.30.2_kvarh*/
													@$ch_7 = mysqli_real_escape_string($conn,$_log_data[8])+0; /*1-1:4.30.2_kvarh*/
													@$ch_8 = mysqli_real_escape_string($conn,$_log_data[9])+0; /*1-1:4.30.2_kvarh*/
							
													@$ch_9 = mysqli_real_escape_string($conn,$_log_data[10])+0; /*1-1:4.30.2_kvarh*/
													@$ch_10 = mysqli_real_escape_string($conn,$_log_data[11])+0; /*1-1:4.30.2_kvarh*/
													@$ch_11 = mysqli_real_escape_string($conn,$_log_data[12])+0; /*1-1:4.30.2_kvarh*/
													@$ch_12 = mysqli_real_escape_string($conn,$_log_data[13])+0; /*1-1:4.30.2_kvarh*/
													@$ch_13 = mysqli_real_escape_string($conn,$_log_data[14])+0; /*1-1:4.30.2_kvarh*/
													@$ch_14 = mysqli_real_escape_string($conn,$_log_data[15])+0; /*1-1:4.30.2_kvarh*/
													@$ch_15 = mysqli_real_escape_string($conn,$_log_data[16])+0; /*1-1:4.30.2_kvarh*/
													@$ch_16 = mysqli_real_escape_string($conn,$_log_data[17])+0; /*1-1:4.30.2_kvarh*/
													@$ch_17 = mysqli_real_escape_string($conn,$_log_data[18])+0; /*1-1:4.30.2_kvarh*/
													@$ch_18 = mysqli_real_escape_string($conn,$_log_data[19])+0; /*1-1:4.30.2_kvarh*/
													@$ch_19 = mysqli_real_escape_string($conn,$_log_data[20])+0; /*1-1:4.30.2_kvarh*/
													@$ch_20 = mysqli_real_escape_string($conn,$_log_data[21])+0; /*1-1:4.30.2_kvarh*/
									
									/*INSERT LP*/				
										$sql = "INSERT INTO `load_profile` 
										(
										`location`,
										 `meter_id`,
										  `meter_details_id`,
										   `datetime`,
											`event_id`,
											 `ch_1`,
											  `ch_2`,
											   `ch_3`,
												`ch_4`,
												 `ch_5`,
												  `ch_6`,
												   `ch_7`,
													`ch_8`,
											`ch_9`,
											 `ch_10`,
											  `ch_11`,
											   `ch_12`,
												`ch_13`,
												 `ch_14`,
												  `ch_15`,
												   `ch_16`,
													`ch_17`,
													 `ch_18`,
													  `ch_19`,
													   `ch_20`
													   ) 
										VALUES 
										(
										'$location_details',
										 '$meter_details',
										  '$meter_details_id',
											'$datetime',
											 '$event_id',
											  '$ch_1',
											   '$ch_2',
												'$ch_3',
												 '$ch_4',
												  '$ch_5',
												   '$ch_6',
													'$ch_7',
													 '$ch_8',
										'$ch_9',
										 '$ch_10',
										  '$ch_11',
										   '$ch_12',
											'$ch_13',
											 '$ch_14',
											  '$ch_15',
											   '$ch_16',
												'$ch_17', 
												'$ch_18',
												 '$ch_19',
												  '$ch_20'
												   )";	
	
													if (mysqli_query($conn, $sql)) {
														//echo "INSERT";												
													} 
													else{
													echo "Error: " . $sql . "<br>" . mysqli_error($conn);
													}		
							}
							else{
							/*SKIP 0 - 7 ROW*/
							}
				
							$m++;
							endforeach;				
					}
	
					/*Save to imported list*/
					$sql_save_import = "INSERT INTO `load_profile_imported_files` 
						(`file_name`, `site_code`, `meter_name`)
						 VALUES ('$file_name_details', '$location_details', '$meter_details');";	
					$result_save_import = mysqli_query($conn, $sql_save_import);
					/*unlink file after import*/				
					shell_exec("rm $filepath");

		}
		}
		
?>