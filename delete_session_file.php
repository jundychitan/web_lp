<?php 

$files = glob('/var/www/html/lp/system/cache/sessions/*'); // get all file names

foreach($files as $file){ // iterate files

  if(is_file($file))
    unlink($file); // delete file
}

?>