<?php

if ($handle = opendir('/var/www/html/lp/upload/lp/CEDC/')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {

            echo "$entry\n";
			echo "cp /var/www/html/lp/upload/lp/CEDC/$entry /var/www/html/lp/upload/lp/importloadprofile/CEDC_$entry\n";
			//shell_exec("cp $uploadfile $_app_path/upload/importloadprofile/$location_detail"."_". basename($_FILES['file_contents']['name'])."");
			shell_exec("cp /var/www/html/lp/upload/lp/CEDC/$entry /var/www/html/lp/upload/importloadprofile/CEDC_$entry");
        }
    }

    closedir($handle);
}

?>