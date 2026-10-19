<?php
		date_default_timezone_set('Asia/Manila');
		/*CONNECTION*/
		include('connection.php');

		/*SELECT meter or RTU not equal to Current Date*/
		/*Update FORCE LP of the RTU to upload the file on the server*/
						
		$hour  = date("H");	
		
		if($hour >= 1 && $hour <= 8){
							
			$sql_select_meters = "SELECT 
			`meter_reading`.`meter_details`.`meter_name` AS `meter_name`,
			`meter_reading`.`meter_details`.`rtu_sn_number_id` AS `rtu_sn_number_id`,
            `meter_reading`.`meter_details`.`rtu_sn_number` AS `rtu_sn_number`,
            `meter_reading`.`meter_details`.`meter_load_profile` AS `meter_load_profile`,
		MAX(`meter_reading`.`load_profile_uploaded_files`.`file_date`) AS `meter_lp_date`
			from (
			`meter_reading`.`meter_details` left join `meter_reading`.`load_profile_uploaded_files`
			on((`meter_reading`.`meter_details`.`meter_name` = `meter_reading`.`load_profile_uploaded_files`.`meter_id`))
			)  
			WHERE `meter_reading`.`meter_details`.meter_load_profile='YES'
			GROUP BY  
				/*`meter_reading`.`meter_details`.`meter_name`,*/
				`meter_reading`.`load_profile_uploaded_files`.`meter_id`,
				`meter_reading`.`meter_details`.`meter_site_name`
				ORDER BY `meter_lp_date` ASC";
			$sql_select_meters_result = mysqli_query($conn, $sql_select_meters);

			$today = date("Y-m-d");

			while($meter_info_row = mysqli_fetch_array($sql_select_meters_result))
            {
				
				$meter_name = $meter_info_row['meter_name'];
				$rtu_sn_number_id = $meter_info_row['rtu_sn_number_id'];
				$rtu_sn_number = $meter_info_row['rtu_sn_number'];
				$meter_lp_date = $meter_info_row['meter_lp_date'];
				$meter_load_profile = $meter_info_row['meter_load_profile'];
				
				if($today!=$meter_lp_date){
				/*Turn on Force LP*/
				mysqli_query($conn, "UPDATE `meter_rtu` SET update_rtu_force_lp='1' where `id` = '$rtu_sn_number_id'");
				$res = 'yes';
				}
				if($today<=$meter_lp_date){
				$res = 'no';
				}
				else{
				$res = 'no';
				}
				echo "$meter_name - $rtu_sn_number - $meter_lp_date  - $meter_load_profile(TODAY : $today) |$today!=$meter_lp_date| $res\n";

			}
		}else{
		/*Do Nothing*/	
		/*echo "DO NOTHING";*/
		}

		/*REF*/	
		/*TESTING*/
		/*SELECT `meter_name`,`rtu_sn_number_id`,`rtu_sn_number`,`meter_lp_date`,`meter_load_profile`,DATE_SUB(CURDATE(), INTERVAL 1 DAY) FROM `meter_details` WHERE
			 `meter_lp_date`<DATE_SUB(CURDATE(), INTERVAL 1 DAY)
			 and `meter_load_profile` = 'YES' ORDER BY `meter_details`.`meter_lp_date` ASC
			 
		DATE_SUB(CURDATE(), INTERVAL 1 DAY)
		DATE_ADD(CURDATE(), INTERVAL 1 DAY)	 
		
		SELECT `meter_name`,`rtu_sn_number_id`,`rtu_sn_number`,`meter_lp_date`,`meter_load_profile`,DATE_SUB(CURDATE(), INTERVAL 1 DAY) FROM `meter_details`
		 WHERE `meter_lp_date`!=CURDATE() and `meter_load_profile` = 'YES' ORDER BY `meter_details`.`meter_lp_date` ASC

		SELECT `meter_name`,`rtu_sn_number_id`,`rtu_sn_number`,`meter_lp_date`,`meter_load_profile`,`meter_lp_log_date`,DATE_SUB(CURDATE(), INTERVAL 1 DAY) FROM `meter_details`
		 WHERE `meter_lp_date`!=CURDATE() and `meter_load_profile` = 'YES' ORDER BY `meter_details`.`meter_lp_date` ASC

		*/
		
		/*
		SELECT `meter_name`,`rtu_sn_number_id`,`rtu_sn_number`,`meter_lp_date`,`meter_load_profile` FROM `meter_details` WHERE
			 `meter_lp_date`!=CURDATE() and 
			 	`meter_load_profile` = 'YES' ORDER BY `meter_details`.`meter_lp_date` ASC
		*/
		/*METER LP DATE AND METER LP LOG DATE addedd on meter_details*/
		/*March 21, 2019*/

?>