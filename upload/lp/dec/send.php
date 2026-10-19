<?php
$target_url = "http://122.54.237.87/lp/receive_file.php";
$file_name_with_full_path = "/var/www/html/lp/upload/lp/dec/50457023_lp_2021-08-02_00:05:00";
if (function_exists('curl_file_create')) { // php 5.5+
  $cFile = curl_file_create($file_name_with_full_path);
} else { // 
  $cFile = '@' . realpath($file_name_with_full_path);
}

 $dest_dir = "lp/DECORP";
 $overwrite = 0;


$post = array('dest_dir' => $dest_dir,'overwrite' => '0','file_contents'=> $cFile);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$result=curl_exec ($ch);
curl_close ($ch);

?>