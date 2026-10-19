<!DOCTYPE html>
<html>
<body>

<form action="" method="post" enctype="multipart/form-data">
   upload directory
    <input type="text" name="dest_dir" id="dest_dir">
   overwrite
     <input type="text" name="overwrite" id="overwrite">
   upload file
    <input type="file" name="file_contents" id="file_contents">
    <input type="submit" value="Upload File" name="submit">
</form>

</body>
</html>
<?php
	
if(isset($_POST["submit"])) {
	
include('connection.php');

 $dest_dir = trim($_POST['dest_dir']);
 $overwrite = $_POST['overwrite'];

			$dir = "./upload$dest_dir";
			echo $dir."<br>";
			$_app_path = realpath(dirname(__FILE__));
			echo $_app_path."<br>";
			/*CHECK DIRECTORY IF EXSIST*/
			if(!is_dir($dir)){
				//Directory does not exist, so lets create it.
				mkdir($dir, 0755);
			}

$uploaddir = realpath('./upload/'.$dest_dir).'/';
$uploadfile = $uploaddir . basename($_FILES['file_contents']['name']);

/*CHECK FOR CONVERTED CSV FILE*/
$csv_file = str_replace(".txt",".csv","$uploadfile");

				@$explode_desdir = explode("/", $uploaddir);		
				var_dump($explode_desdir);		
				$location_detail = $explode_desdir[2];

if (!file_exists($uploadfile) && !file_exists($csv_file)) {
	//echo 'NOT EXIST';
	if (move_uploaded_file($_FILES['file_contents']['tmp_name'], $uploadfile)) {
		echo 'SUCCESS-0';
		shell_exec("cp $uploadfile $_app_path/upload/importloadprofile/". basename($_FILES['file_contents']['name'])."");
		echo "<br>cp $uploadfile $_app_path/upload/importloadprofile/$location_detail"."_". basename($_FILES['file_contents']['name'])."<br>";	
		echo $uploadfile."<br>";
		/*BEGIN IMPORT*/
        //echo "File is valid, and was successfully uploaded.\n";
    } else {
		echo 'FAILED 23';
        //echo "Possible file upload attack!\n";
    }
}else{
	if($overwrite==1){
	    
		//echo "File Exists.. will be overwriten\n";
        if (move_uploaded_file($_FILES['file_contents']['tmp_name'], $uploadfile)) {
			echo 'SUCCESS';
            //echo "File is valid, and was successfully uploaded.\n";
        } else {
			echo 'FAILED 32';
            //echo "Possible file upload attack!\n";
        }	    
		
	}else{
			echo 'SUCCESS-1';
	    	//echo "File Exists.. not overwriten\n";
	}
}
		//include('file_import.php');

//print_r($_FILES);
}
?>