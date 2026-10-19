<?php
		/*Create File List on table 'load_profile_uploaded_files'*/
		/*Check If Exist*/
			if(@$overwrite == 1){

			@$file_dt = $file_name[2];
			@$meter_id_lp_date = mysqli_real_escape_string($conn,$file_dt);
	
			$sql_check_lp_list = "SELECT `file_directory`,
			 `file_name`,
			  `overwrite_count`
			   FROM load_profile_uploaded_files WHERE
			 file_name = '$fname' and
			  file_directory = '$uploaddir'";
			
			$result_check_lp_list = mysqli_query($conn, $sql_check_lp_list);			
	
			@$lp_info_row = mysqli_fetch_row($result_check_lp_list);
	
			$overwrite_count = @$lp_info_row[2] + 1 + 0;

if($fname!=''){
				if(mysqli_num_rows($result_check_lp_list)==0) {
						/*Insert To List*/
						$sql = "INSERT INTO `load_profile_uploaded_files` 
						(`meter_id`,
						`file_directory`,
						`file_name`,
						`file_date`,
						`overwrite_count`) 
						VALUES 
						('$file_name[0]',
						'$uploaddir',
						'$fname',
						'$meter_id_lp_date',
						'$overwrite_count')";
		
						if (mysqli_query($conn, $sql)) {
							/*echo "INSERT";*/
						} else {
							echo "Error: " . $sql . "<br>" . mysqli_error($conn);
						}
				}else{
						/*id	meter_id	file_directory	file_name	datetime	overwrite_count*/
						$sql = "UPDATE `load_profile_uploaded_files` SET 
						`meter_id` = '$file_name[0]',
						 `file_directory` = '$uploaddir',
						  `file_name` = '$fname',
						   `overwrite_count` = '$overwrite_count'";	
		
						if (mysqli_query($conn, $sql)) {
							/*echo "INSERT";*/
						} else {
							echo "Error: " . $sql . "<br>" . mysqli_error($conn);
						}
					}
					
					$sql_new = "INSERT INTO `load_profile_new_uploaded_files` 
					(`meter_id`,`file_directory`,`file_name`) 
					VALUES 
					('$file_name[0]','$uploaddir','$fname')";
	
					if (mysqli_query($conn, $sql_new)) {
						/*echo "INSERT";*/
					} else {
						echo "Error: " . $sql_new . "<br>" . mysqli_error($conn);
					}
					
			}
			else{
					
					/*Insert To List*/
					$sql = "INSERT INTO `load_profile_uploaded_files` 
					(`meter_id`,
					`file_directory`,
					`file_name`,
					`file_date`,
					`overwrite_count`) 
					VALUES 
					('$file_name[0]',
					'$uploaddir',
					'$fname',
					'$meter_id_lp_date',
					'1')";
	
					if (mysqli_query($conn, $sql)) {
						/*echo "INSERT";*/
					} else {
						echo "Error: " . $sql . "<br>" . mysqli_error($conn);
					}
	
					/*NEW FILES UPLOADED LIST*/
					/*List Will Be Deleted after Import*/
					/*Use to import data from file to load_profile table*/
					
					/*20181073*/
					$sql_new = "INSERT INTO `load_profile_new_uploaded_files` 
					(`meter_id`,
					 `file_directory`,
					  `file_name`) 
					VALUES 
					('$file_name[0]',
					 '$uploaddir',
					  '$fname')";
	
					if (mysqli_query($conn, $sql_new)) {
						/*echo "INSERT";*/
					} else {
						echo "Error: " . $sql_new . "<br>" . mysqli_error($conn);
					}
			}
}
?>