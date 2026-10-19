<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_load_data extends CI_Controller {

	public function __construct()
	{
	parent::__construct();
	$this->load->model('admin_model');
	$this->load->helper('url_helper');
	$this->load->library('session');
	$this->load->helper('html');
	}

	/*LOAD PROFILE REPORT LOGS*/
	public function meter_load_profile_report_post(){
	
	if($this->session->userdata('currently_logged_in_as')!=''){
	
	$this->load->helper('form');
	$this->load->library('form_validation');
	
	$this->form_validation->set_rules('date_start', 'Date Start', 'required');
	$this->form_validation->set_rules('date_end', 'Date End', 'required');
	$this->form_validation->set_rules('meter', 'Meter', 'required');
	$this->form_validation->set_rules('date_order', 'Date Order', 'required');
	//VALIDATION FAILED
	if ($this->form_validation->run() === FALSE)
	{
	 echo 'Invalid Please Select Site and Meter';
	}
	//VALIDATION SUCCESS
	else{
	//METER DETAILS 
	$meter_name = $this->input->post('meter');
	$data['meter_details']=$this->admin_model->get_meter_info($meter_name); 
	$meter_customer_name = $data['meter_details']['customer_name'];
	$meter_multiplier = $data['meter_details']['meter_multiplier'];

	@$meter_location = $data['meter_details']['meter_site_name'];
	@$meter_id = $data['meter_details']['meter_name'];
	
	$date_start = $this->input->post('date_start');
	$date_end = $this->input->post('date_end');
	$date_order = $this->input->post('date_order');
	
	@$meter_all_lp_report = $this->admin_model->meter_all_lp_report(@$meter_id,$date_start,$date_end,$date_order); 
	if(@$meter_all_lp_report==0){
	echo 'No Result Found';
	}
	else{
	$report_name = "$meter_id-LP"; 
	$_report_name = str_replace(' ','%20',$report_name);
	
	echo "<div align='right'><a href=" . base_url('reports/'. $_report_name . '.csv' ) . " class='btn btn-primary'>DOWNLOAD</a></div>";
	$myfile = fopen("reports/$report_name.csv", "w") or die("Unable to open file!");
	/*HEADER*/
	
	@$csv_line .= "Load Profile 1 - $meter_id\n";
	@$csv_line .= "\n";
	#@$csv_line .= "Customer,$meter_customer_name\n";
	@$csv_line .= "Protocol:,Dlms\n";
	@$csv_line .= "ID:,$meter_id\n";
	@$csv_line .= "FromDate:,$date_start\n";
	@$csv_line .= "ToDate:,$date_end\n";
	#@$csv_line .= "\n";
	//@$csv_line .= "0-0:1.0.0,0-0:96.240.12 [hex],1-1:1.5.0 [kW],1-1:1.8.0 [kWh],1-1:3.8.0 [kvarh],1-1:2.5.0 [kW],1-1:2.8.0 [kWh],1-1:4.8.0 [kvarh],1-1:13.5.0\n";
	@$csv_line .= "Clock (tm=5 min),Event ID,4(kWh),2(kWh),6(kWh),*(kWh),5(kvarh),7(kvarh),I1(A),I2(A),I3(A),n(A),V1(V),V2(V),V3(V),14.7.0(Hz),*(kW),*(kW),*(kW),*(*),*(*),*(*)\n";
	?>	
  <table width="40%" border="0">
  <tr>
    <td colspan="2"><b>Load Profile 1 - <?=$meter_customer_name;?></b></td>
  </tr>

  <tr>
    <td><b>Protocol:</b></td>
    <td>Dlms</td>
  </tr>
  <tr>
    <td><b>ID:</b></td>
    <td><?=$meter_id;?></td>
  </tr>
  <tr>
    <td><b>FromDate:</b></td>
    <td><?=$date_start;?></td>
  </tr>
  <tr>
    <td><b>ToDate:</b></td>
    <td><?=$date_end;?></td>
  </tr>
</table>
    <br>
	<div class='table-data-span'>
	<table class='table table-striped' border='1' bordercolor='#0095ff'>
	<thead>
	<tr>
	<th style='text-align:left !important;' >#</th>
	<th style='text-align:left !important;' nowrap="nowrap">Datetime</th>
	<th style='text-align:left !important;' nowrap="nowrap">Event ID</th>
    <th style='text-align:left !important;' nowrap="nowrap">4(kWh)</th>
    <th style='text-align:left !important;' nowrap="nowrap">2(kWh)</th>
    <th style='text-align:left !important;' nowrap="nowrap">6(kWh)</th>
    <th style='text-align:left !important;' nowrap="nowrap">*(kWh)</th>
    <th style='text-align:left !important;' nowrap="nowrap">5(kvarh)</th>
    <th style='text-align:left !important;' nowrap="nowrap">7(kvarh)</th>
    <th style='text-align:left !important;' nowrap="nowrap">I1(A)</th>
    <th style='text-align:left !important;' nowrap="nowrap">I2(A)</th>
    <th style='text-align:left !important;' nowrap="nowrap">I3(A)</th>
    <th style='text-align:left !important;' nowrap="nowrap">n(A)</th>
    <th style='text-align:left !important;' nowrap="nowrap">V1(V)</th>
    <th style='text-align:left !important;' nowrap="nowrap">V2(V)</th>
    <th style='text-align:left !important;' nowrap="nowrap">V3(V)</th>
    <th style='text-align:left !important;' nowrap="nowrap">14.7.0(Hz)</th>	
    <th style='text-align:left !important;' nowrap="nowrap">*(kW)</th>
    <th style='text-align:left !important;' nowrap="nowrap">*(kW)</th>
    <th style='text-align:left !important;' nowrap="nowrap">*(kW)</th>
    <th style='text-align:left !important;' nowrap="nowrap">*(*)</th>
    <th style='text-align:left !important;' nowrap="nowrap">*(*)</th>
    <th style='text-align:left !important;' nowrap="nowrap">*(*)</th>	
    </tr>
	</thead>
	<tbody>
	 <?php
	/*DATA*/
	$meter_all_lp_report_row = $meter_all_lp_report;
	$no = 1;
	foreach ($meter_all_lp_report_row as $meter_all_lp_report_cols){
	
	$meter_id = $meter_all_lp_report_cols['meter_id'];
	$event_id = $meter_all_lp_report_cols['event_id'];
	$datetime = $meter_all_lp_report_cols['datetime'];
	
	$ch_1 = $meter_all_lp_report_cols['ch_1']*$meter_multiplier;
	$ch_2 = $meter_all_lp_report_cols['ch_2']*$meter_multiplier;
	$ch_3 = $meter_all_lp_report_cols['ch_3']*$meter_multiplier;
	$ch_4 = $meter_all_lp_report_cols['ch_4']*$meter_multiplier;
	$ch_5 = $meter_all_lp_report_cols['ch_5']*$meter_multiplier;
	$ch_6 = $meter_all_lp_report_cols['ch_6']*$meter_multiplier;
	$ch_7 = $meter_all_lp_report_cols['ch_7'];
	$ch_8 = $meter_all_lp_report_cols['ch_8'];
	$ch_9 = $meter_all_lp_report_cols['ch_9'];
	$ch_10 = $meter_all_lp_report_cols['ch_10'];
	$ch_11 = $meter_all_lp_report_cols['ch_11'];
	$ch_12 = $meter_all_lp_report_cols['ch_12'];
	$ch_13 = $meter_all_lp_report_cols['ch_13'];
	$ch_14 = $meter_all_lp_report_cols['ch_14'];
	$ch_15 = $meter_all_lp_report_cols['ch_15']*$meter_multiplier;
	$ch_16 = $meter_all_lp_report_cols['ch_16']*$meter_multiplier;
	$ch_17 = $meter_all_lp_report_cols['ch_17']*$meter_multiplier;
	$ch_18 = $meter_all_lp_report_cols['ch_18'];
	$ch_19 = $meter_all_lp_report_cols['ch_19'];
	$ch_20 = $meter_all_lp_report_cols['ch_20'];

	@$csv_line .= "$datetime,$event_id,$ch_1,$ch_2,$ch_3,$ch_4,$ch_5,$ch_6,$ch_7,$ch_8,$ch_9,$ch_10,$ch_11,$ch_12,$ch_13,$ch_14,$ch_15,$ch_16,$ch_17,$ch_18,$ch_19,$ch_20\n";
	echo "<tr><td>$no</td><td nowrap='nowrap'>$datetime</td><td>$event_id</td><td>$ch_1</td><td>$ch_2</td><td>$ch_3</td><td>$ch_4</td><td>$ch_5</td><td>$ch_6<td>$ch_7</td><td>$ch_8</td><td>$ch_9</td><td>$ch_10</td><td>$ch_11</td><td>$ch_12</td><td>$ch_13</td><td>$ch_14</td><td>$ch_15</td><td>$ch_16</td><td>$ch_17</td><td>$ch_18</td><td>$ch_19</td><td>$ch_20</td></tr>";
	$no++;
	}
	
	fwrite($myfile, @$csv_line);
	fclose($myfile);
	}
	?>
	</tbody></table>
	</div>
	<?php
	}
	
	}
	else{
	redirect('Login/invalid');
	}
	 
	}

	/*LOAD PROFILE STATUS REPORT*/
	public function meter_load_profile_status_post(){
	
	if($this->session->userdata('currently_logged_in_as')!=''){
	

	//VALIDATION SUCCESS

	$date_start = $this->input->post('date_start');
	
	@$meter_lp_status_report = $this->admin_model->meter_lp_status_report(); 
	if(@$meter_lp_status_report==0){
	echo 'No Result Found';
	}
	else{
	?>
	<div class='table-data-span'>
	<table class="table" width="80%">
	<thead>
	<tr>
	<th>#</th>
	<th>Meter ID</th>
    <th>Client</th>
	<th>Location</th>
	<th>Date</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	
	<?php
	$no = 1;
	foreach ($meter_lp_status_report as $meter_lp_status_report_col):
	
	
		$client_name = $meter_lp_status_report_col['customer_name'];

	
	?>
    <tr>
    <td align="center"><?php echo $no; ?></td>
    <th ><?=$meter_lp_status_report_col['meter_name'];?></th>
    <th ><?php echo "$client_name";?></th>
    <th ><?=$meter_lp_status_report_col['meter_site_name'];?></th>
    <th style="text-align:center !important"><?=$meter_lp_status_report_col['datetime'];?></th>
	</tr>
	<?php
	$no++;
	endforeach; 
	?>
	</tbody>
	</table>
	</div>
    <?php	
	
	}
	
	}
	else{
	redirect('Login/invalid');
	}
	 
	}
	
	public function meter_load_profile_status_site_post(){
	
	if($this->session->userdata('currently_logged_in_as')!=''){

	$location_id = str_replace('%20',' ',$this->uri->segment(3));
	
	@$meter_lp_status_report = $this->admin_model->meter_lp_status_report_site($location_id); 
	if(@$meter_lp_status_report==0){
	echo 'No Result Found';
	}
	else{
	?>
	<div class='table-data-span'>
	<table class="table" width="80%">
	<thead>
	<tr>
	<th>#</th>
	<th>Meter ID</th>
    <!--<th>Client Account Number</th>
    <th>Client</th>-->
	<th>Location</th>
	<th>Date</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	
	<?php
	$no = 1;

	foreach ($meter_lp_status_report as $meter_lp_status_report_col):
		$client_name = $meter_lp_status_report_col['customer_name'];
	?>
    <tr>
    <td align="center"><?php echo $no; ?></td>
    <th ><?=$meter_lp_status_report_col['meter_name'];?></th>
    <th ><?=$meter_lp_status_report_col['meter_site_name'];?></th>
    <th style="text-align:center !important"><?=$meter_lp_status_report_col['datetime'];?></th>
	</tr>
	<?php
	$no++;
	endforeach; 
	?>
	</tbody>
	</table>
	</div>
    <?php	
	
	}
	
	}
	else{
	redirect('Login/invalid');
	}
	 
	}
	
	/*LOAD FILE*/
	public function load_profile_view(){ 
	$_file = $this->input->post('id');
	$user_currently_login = $_SESSION['user_name'];
	
	$file = str_replace('%20',' ',$_file);
	
	$_upload_path = realpath(dirname(__FILE__));
	$upload_path = explode('/',$_upload_path);
	
	if (empty($file))
	{
	show_404();
	}
	
	$file_meter_id = explode("_", $file);
	$meter_id = $file_meter_id[0];
	 @$data['meter_info'] = $this->admin_model->get_meter_info($meter_id);
	$user_location = str_replace(" ","",@$data['meter_info']['meter_site_name']);
	echo "<b>Customer Name:".@$data['meter_info']['customer_name']." ".@$data['meter_info']['other_description']."</b><br>";
	 
	$logs = file("$upload_path[0]/$upload_path[1]/$upload_path[2]/$upload_path[3]/$upload_path[4]/upload/lp/$user_location/$file");
	$n = 1;$nm = 1;
	foreach ($logs as $logs_rows):
	?><b><?=$logs_rows;?></b><br /><?php
	if ($n++ == 5) break;
	endforeach; 
	 ?>
	<div class='table-data-span'>
	<table class="table" width="80%">
	<thead>
	<tr>
	<th>#</th>
	<th>Date Time</th>
	<th>Event ID</th>
	<th>CH 1</th>
	<th>CH 2</th>
	<th>CH 3</th>
	<th>CH 4</th>
	<th>CH 5</th>
	<th>CH 6</th>
	<th>CH 7</th>
	<th>CH 8</th>
	<th>CH 9</th>
	<th>CH 10</th>
	<th>CH 11</th>
	<th>CH 12</th>
	<th>CH 13</th>
	<th>CH 14</th>
	<th>CH 15</th>
	<th>CH 16</th>
	<th>CH 17</th>
	<th>CH 18</th>
	<th>CH 19</th>
	<th>CH 20</th>
	</tr>
	</thead>
	<tbody>
	<?php
	 $logs_data = file("./upload/lp/$user_location/$file");
	$m = 1;
	foreach ($logs_data as $logs_data_rows):
	
	if($m>=7){
	@$log_data_col = explode(",", $logs_data_rows);
	?>
	<tr>
	<td align="center"><?php echo @$m-6; ?></td>
	<?php
	foreach ($log_data_col as $log_data_col_td_arr):
	?><th ><?=$log_data_col_td_arr;?></th><?php
	endforeach; 
	?>
	</tr>
	<?php
	}
	$m++;
	endforeach; 
	 ?>
	</tbody>
	</table>
	</div>
	<?php
	}

	/*LOAD PROFILE LIST*/
	public function load_company_file_lp(){
	$company_id = $_GET['company_id'];
	$meter_id = $company_id;
	$user_currently_login = $_SESSION['user_name'];
	
	@$meter_info = $this->admin_model->get_meter_info($meter_id);
	$user_location =  str_replace(" ","",@$meter_info['meter_site_name']);
	//$cln_dir_name = str_replace("%20"," ","$dir");
	
	$maindir = "./upload/lp/$user_location/" ;
	
	$mydir = opendir($maindir) ;
	@$limit = 10;
	
	if (isset($_GET["offset"])) { $page= $_GET["offset"]; } else { $page=1; }; 
	$offset = ($page-1) * $limit; 
	
	$files = array();
	$page='';
	$exclude = array( ".", "..", "index.php",".htaccess","guarantee.gif") ;
	
	foreach (glob($maindir."$company_id*") as $filename) {
	
	if (!in_array($filename, $exclude)) 
	{
	$files[] = $filename;;
	}
	}
	@closedir($mydir);
	rsort($files);
	$newICounter = (($offset + $limit) <= sizeof($files)) ? ($offset + $limit) : sizeof($files);
	$customer_name = @$meter_info['customer_name'];
	$other_description = @$meter_info['other_description'];
	echo "<b>Customer Name : ".@$customer_name." ".@$other_description."</b><br>";
	?>
	
	 <table class="table" width="80%">
	 <thead>
	 <tr><th>#</th><th>File</th><th>Download</th></tr></thead>
	<?php
	$n = 1;
	$_upload_path = realpath(dirname(__FILE__));
	$upload_path = explode('/',$_upload_path);
	
	for($i=$offset;$i<$newICounter;$i++) {
	
	$file_arr = $files[$i];
	$file_name = explode('/',$file_arr);
	
	$file_ext = explode('.',$file_name[4]);
	if(@$file_ext[1]=='txt'){
	/*.txt*/ 
	/*change file extention*/
	$out = shell_exec("mv /var/www/html/$upload_path[4]/upload/lp/$user_location/$file_name[4] /var/www/html/$upload_path[4]/upload/lp/$user_location/$file_ext[0].csv");
	}
	else{
	/*.csv*/
	}
	?>
	<tr>
	<td onClick="selected_file(this.id)" id="<?php echo "$file_ext[0].csv";?>"><?=$n;?></td>
	<td onClick="selected_file(this.id)" id="<?php echo "$file_ext[0].csv";?>"><?php echo "$file_ext[0].csv";?></td>
	<td><a href="<?php echo base_url(); ?>upload/lp/<?=$user_location;?>/<?php echo "$file_ext[0].csv"; ?>" target="new" download ><div align="center">&nbsp;<span class="glyphicon glyphicon-download-alt"></span>&nbsp;</div></a></td>
	</tr>
	<?php
	$n++;
	}
	
	?>
	</table>
	<?php
	$total_files = sizeof($files);
	
	$total_pages = number_format($total_files/10,0,'.','');
	
	if($total_files==0){
	$page_number = 0;
	}
	elseif($total_files>=11){
	$page_number = $total_pages+1;
	} 
	else{
	$page_number = 1;
	}
	
	echo "<b>Total Files : " . $total_files . "</b><br>";
	echo "<b>Total Pages : " . $page_number . "</b>";
	
	}


	public function load_history_view(){
	$_file = $this->input->post('id');
	$user_currently_login = $_SESSION['user_name'];
	
	$_upload_path = realpath(dirname(__FILE__));
	$upload_path = explode('/',$_upload_path);
	
	$file = str_replace('%20',' ',$_file);
	
	if (empty($file))
	{
	show_404();
	}
	
	$file_meter_id = explode("_", $file);
	$meter_id = $file_meter_id[0];
	@$data['meter_info'] = $this->admin_model->get_meter_info($meter_id);
	$user_location = str_replace(" ","",@$data['meter_info']['meter_site_name']);
	//$user_location =  str_replace(" ","",@$meter_info['meter_site_name']);

	echo "<b>Customer Name:".@$data['meter_info']['customer_name'].". ' ' ".@$data['meter_info']['other_description']."</b><br>";
	
	$logs = file("$upload_path[0]/$upload_path[1]/$upload_path[2]/$upload_path[3]/$upload_path[4]/upload/history/$user_location/$file");
	$n = 1;
	foreach ($logs as $logs_rows):
	?><b><?=$logs_rows;?></b><br /><?php
	if ($n++ == 7) break;
	endforeach; 
	 ?>
	<div class='table-data-span'>
	<table class="table" width="80%">
	<thead>
	<tr>
	<th>#</th>
	<th >Date and Time</th>
	<th >User ID</th>
	<th >?</th>
	</tr>
	</thead>
	<tbody>
	<?php
	 $logs_data = file("./upload/history/$user_location/$file");
	$m = 1;
	foreach ($logs_data as $logs_data_rows):
	if($m>=8){
	@$log_data_col = explode(",", $logs_data_rows);
	?>
	<tr>
	<td align="center"><?php echo @$m-7; ?></td>
	<td align="center"><?php echo @$log_data_col['1']; ?></td>
	<td align="center"><?php echo @$log_data_col['2']; ?></td>
	<td align="center"><?php echo @$log_data_col['3']; ?></td>
	</tr>
	 <?php
	
	}
	$m++;
	endforeach; 
	 ?>
	</tbody>
	</table>
	</div>
	<?php
	}

	public function load_company_file_history(){
	$company_id = $_GET['company_id'];
	$meter_id = $company_id;
	$user_currently_login = $_SESSION['user_name'];
	
	@$meter_info = $this->admin_model->get_meter_info($meter_id);

	$user_location = str_replace(" ","",@$meter_info['meter_site_name']);
	$maindir = "./upload/history/$user_location/" ;
	
	@$mydir = opendir(@$maindir) ;
	@$limit = 10;
	
	if (isset($_GET["offset"])) { $page= $_GET["offset"]; } else { $page=1; }; 
	$offset = ($page-1) * $limit; 
	
	$files = array();
	$page='';
	$exclude = array( ".", "..", "index.php",".htaccess","guarantee.gif") ;
	
	foreach (glob($maindir."$company_id*") as $filename) {
	
	if (!in_array($filename, $exclude)) 
	{
	$files[] = $filename;;
	}
	}
	@closedir(@$mydir);
	rsort($files);
	$newICounter = (($offset + $limit) <= sizeof($files)) ? ($offset + $limit) : sizeof($files);
	$customer_name = @$meter_info['customer_name'];
	$other_description = @$meter_info['other_description'];
	echo "<br><b>Customer Name : $customer_name $other_description</b><br>";
	?>
	 <table class="table" width="80%">
	 <thead>
	 <tr><th>#</th><th>File</th><th>Download</th></tr></thead>
	<?php
	$n = 1;
	$_upload_path = realpath(dirname(__FILE__));
	$upload_path = explode('/',$_upload_path);
	
	for($i=$offset;$i<$newICounter;$i++) {
	$file_arr = $files[$i];
	$file_name = explode('/',$file_arr);
	
	$file_ext = explode('.',$file_name[4]);
	if(@$file_ext[1]=='txt'){
	/*
	.txt
	change file extention
	*/
	$out = shell_exec("mv /var/www/html/$upload_path[4]/upload/history/$user_location/$file_name[4] /var/www/html/$upload_path[4]/upload/history/$user_location/$file_ext[0].csv");
	}
	else{
	/*.csv*/
	}
	
	?>
	<tr>
	<td onClick="selected_file(this.id)" id="<?php echo "$file_ext[0].csv";?>""><?=$n;?></td>
	<td onClick="selected_file(this.id)" id="<?php echo "$file_ext[0].csv";?>""><?php print $file_name[4];; ?></td>
	<td><a href="<?php echo base_url(); ?>upload/history/<?=$user_location;?>/<?php echo "$file_ext[0].csv";?>" target="new" ><div align="center">&nbsp;<span class="glyphicon glyphicon-download-alt"></span>&nbsp;</div></a></td>
	</tr>
	<?php
	$n++;
	}
	
	?>
	</table> 
	<?php
	$total_files = sizeof($files);
	
	$total_pages = number_format($total_files/10,0,'.','');
	
	if($total_files==0){
	$page_number = 0;
	}
	elseif($total_files>=11){
	$page_number = $total_pages+1;
	}
	else{
	$page_number = 1;
	}
	 
	echo "<b>Total Files : " . $total_files . "</b><br>";
	echo "<b>Total Pages : " . $page_number . "</b>";
	
	}

	public function select_location_meter_list(){
	$this->load->helper('form');
	$this->load->library('form_validation');
	$location_id = $this->input->post('location_id');
	/*QUERY METER LIST BY LOCATION*/
	$meter_list = $this->admin_model->get_location_meter_list($location_id);

	?>
	<label>Meter</label><br />
	<input type="text" class="form-control" id="company_id" name="company_id" list="meter_list" size="20" autocomplete="off" onchange="load_company_file()"> 
	<datalist id="meter_list">
	<?php
	foreach ($meter_list as $meter_col): 
	$meter_id = $meter_col['meter_name'];
	 $customer_name = $meter_col['customer_name'];
	?>
	<option value="<?php echo "$meter_id";?>"><?php echo "$customer_name-$meter_id"; ?></option>
	<?php
	endforeach;
	?>
	</datalist>
	<?php
	}

}