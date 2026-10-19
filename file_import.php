<?php
		/*BEGIN IMPORT*/
		include('connection.php');

		$m = 1;
		$logs_data = file("$uploadfile");		
		$directory_meter_id = explode("/", $uploadfile);
		$_file_name = $directory_meter_id[8];
		$file_name = explode("_", $_file_name);
	    $meter_id = mysqli_real_escape_string($conn,$file_name[0]);
		
			/*UPDATE column meter_load_profile on meter_details*/
			$sql_update_meter_load_profile = "UPDATE `meter_details` SET `meter_load_profile` = 'YES' WHERE `id` = '".$meter_id."'";	
						if (mysqli_query($conn, $sql_update_meter_load_profile)) {
						} else {
						echo "Error: " . $sql_update_meter_load_profile . "<br>" . mysqli_error($conn);
						}
		
			foreach ($logs_data as $logs_data_rows):
						if($m>=7 && $directory_meter_id[6] == 'lp'){

	    $meter_id = mysqli_real_escape_string($conn,$file_name[0]);

						@$log_data_ch = explode(",", $logs_data_rows);
						$_log_data = explode(",", $logs_data_rows);
						$datetime = mysqli_real_escape_string($conn,$_log_data[0]); /*datetime*/
						$event_id = mysqli_real_escape_string($conn,$_log_data[1]); /*event_id*/
						$ch_1 = mysqli_real_escape_string($conn,$_log_data[2])+0; /*1.5.0_kW*/
						$ch_2 = mysqli_real_escape_string($conn,$_log_data[3])+0; /*1-1:1.30.2_kWh*/ 
						$ch_3 = mysqli_real_escape_string($conn,$_log_data[4])+0; /*1-1:3.30.2_kvarh*/
						$ch_4 = mysqli_real_escape_string($conn,$_log_data[5])+0; /*2.5.0_kW */
						$ch_5 = mysqli_real_escape_string($conn,$_log_data[6])+0; /*1-1:2.30.2_kWh*/
						$ch_6 = mysqli_real_escape_string($conn,$_log_data[7])+0; /*1-1:4.30.2_kvarh*/
						$ch_7 = mysqli_real_escape_string($conn,$_log_data[8])+0; /*1-1:4.30.2_kvarh*/
						$ch_8 = mysqli_real_escape_string($conn,$_log_data[9])+0; /*1-1:4.30.2_kvarh*/

						$ch_9 = mysqli_real_escape_string($conn,$_log_data[10])+0; /*1-1:4.30.2_kvarh*/
						$ch_10 = mysqli_real_escape_string($conn,$_log_data[11])+0; /*1-1:4.30.2_kvarh*/
						$ch_11 = mysqli_real_escape_string($conn,$_log_data[12])+0; /*1-1:4.30.2_kvarh*/
						$ch_12 = mysqli_real_escape_string($conn,$_log_data[13])+0; /*1-1:4.30.2_kvarh*/
						$ch_13 = mysqli_real_escape_string($conn,$_log_data[14])+0; /*1-1:4.30.2_kvarh*/
						$ch_14 = mysqli_real_escape_string($conn,$_log_data[15])+0; /*1-1:4.30.2_kvarh*/
						$ch_15 = mysqli_real_escape_string($conn,$_log_data[16])+0; /*1-1:4.30.2_kvarh*/
						$ch_16 = mysqli_real_escape_string($conn,$_log_data[17])+0; /*1-1:4.30.2_kvarh*/
						$ch_17 = mysqli_real_escape_string($conn,$_log_data[18])+0; /*1-1:4.30.2_kvarh*/
						$ch_18 = mysqli_real_escape_string($conn,$_log_data[19])+0; /*1-1:4.30.2_kvarh*/
						$ch_19 = mysqli_real_escape_string($conn,$_log_data[20])+0; /*1-1:4.30.2_kvarh*/
						$ch_20 = mysqli_real_escape_string($conn,$_log_data[21])+0; /*1-1:4.30.2_kvarh*/

		$sql_check_lp = "SELECT `meter_id`,`datetime` FROM load_profile WHERE meter_id = '$meter_id' and datetime = '$datetime'";
		$result_check_lp = mysqli_query($conn, $sql_check_lp);			

		if (mysqli_num_rows($result_check_lp)==0) {
						/*INSERT LP*/				
						$sql = "INSERT INTO `load_profile` 
						(
						`meter_id`, `datetime`, `event_id`, `ch_1`, `ch_2`, `ch_3`, `ch_4`, `ch_5`, `ch_6`, `ch_7`, `ch_8`,
						`ch_9`, `ch_10`, `ch_11`, `ch_12`, `ch_13`, `ch_14`, `ch_15`, `ch_16`, `ch_17`, `ch_18`, `ch_19`, `ch_20`
						) 
						VALUES 
						(
						'$meter_id','$datetime','$event_id','$ch_1','$ch_2','$ch_3','$ch_4','$ch_5','$ch_6','$ch_7','$ch_8',
						'$ch_9','$ch_10','$ch_11','$ch_12','$ch_13','$ch_14','$ch_15','$ch_16','$ch_17','$ch_18','$ch_19','$ch_20'
						)";	
		
						if (mysqli_query($conn, $sql)) {
							/*echo "INSERT";*/
						} else {
						echo "Error: " . $sql . "<br>" . mysqli_error($conn);
						}
						
		}else{
			
		/*UPDATE IF OVERWRITE == 1
		echo 'EXIST';*/
				if($overwrite == 1){
						$sql = "UPDATE `load_profile` SET `event_id`='$event_id', `ch_1`='$ch_1', `ch_2`='$ch_2', `ch_3`='$ch_3',
					 `ch_4`='$ch_4', `ch_5`='$ch_5', `ch_6`='$ch_6' , `ch_7`='$ch_7', `ch_8`='$ch_8', `ch_9`='$ch_9', `ch_10`='$ch_10', `ch_11`='$ch_11'
					 , `ch_12`='$ch_12', `ch_13`='$ch_13', `ch_14`='$ch_14', `ch_15`='$ch_15'
					 , `ch_16`='$ch_16', `ch_17`='$ch_17', `ch_18`='$ch_18', `ch_19`='$ch_19', `ch_20`='$ch_20' 
					 WHERE `meter_id` = '$meter_id' and `datetime`='$datetime'";	
		
						if (mysqli_query($conn, $sql)) {
						} else {
						echo "Error: " . $sql . "<br>" . mysqli_error($conn);
						}

				}else{
				}
		}
						
						}else{
						/*SKIP 0 - 7 ROW*/
						}
				$m++;				
			endforeach;
			
		/*Change File Extention from .txt to .csv
		$txt_to_csv = shell_exec("mv $uploadfile $csv_file");
		*/
?>
