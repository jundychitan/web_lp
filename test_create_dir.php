<?php
			#$dest_dir = trim($_POST['dest_dir']);
			#$overwrite = $_POST['overwrite'];
 
			$dir = "./upload/lp/AGOT";
			/*CHECK DIRECTORY IF EXSIST*/
			if(!is_dir($dir)){
				//$cln_dir_name = str_replace("%20"," ","$dir");
				//Directory does not exist, so lets create it.
				mkdir($dir, 0755);
			}
			
			?>