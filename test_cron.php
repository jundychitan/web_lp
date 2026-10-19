<?php
	
			$date_upload = date("Y-m-d");
			$time_upload = date("H:i:s");
	
    		@$log_row = "RUN";
			$write_log_file = "test_cron.txt";			
			@$log_row .= "$date_upload $time_upload\n";

			file_put_contents($write_log_file, $log_row, FILE_APPEND | LOCK_EX);
?>