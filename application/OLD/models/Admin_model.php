<?php
class Admin_model extends CI_Model {

        public function __construct()
        {
 		        $this->load->database();
				$this->load->helper('url');
				$this->load->library('session');
        }

		public function get_user_details_by_id($id)
		{			
		$query = $this->db->get_where('user_tb', array('id' => $id));
		return $query->row_array();
		}	

		public function get_company_meter_list($user_location)
		{			
		
		$currently_logged_in_as = $_SESSION['currently_logged_in_as'];	
		if($currently_logged_in_as == 'Admin'){
		
		$query = $this->db->query("SELECT * FROM meter_details WHERE 
		 meter_load_profile = 'YES'" );		
		 	
		}else{
		
		$query = $this->db->query("SELECT * FROM meter_details WHERE 
		meter_site_name = ".$this->db->escape($user_location)." and
		 meter_load_profile = 'YES'" );		
		 	
		}
		return $query->result_array();
		}	

		public function get_location_list($user_id)
		{			
		
		$query = $this->db->query("SELECT * FROM user_access_group WHERE 
		user_id = ".$this->db->escape($user_id)."" );		
		 	
		return $query->result_array();
		
		}	

		public function get_location_meter_list($location_id)
		{
		
		$query = $this->db->query("SELECT meter_name,customer_name FROM meter_details WHERE 
		meter_site_id = ".$this->db->escape($location_id)." and meter_load_profile='YES'" );	


		return $query->result_array();
		}
			
		public function user_account_action() 
		{
					
		$id = 1;	
		
		$query = $this->db->get_where('user_tb', array('id' => $id));
		$user_details = $query->row_array();

		$user_password_current = $user_details['user_password'];
		//echo "<br>";
		$user_password_old_from_input = md5($this->input->post('old_password'));
		
		if($user_password_current!=$user_password_old_from_input){
		//Current password did not match to old password input
							$a = "";
							$b = "<div style='color:red;'>Wrong Password</div>";
							$c = "";
							$d = "";
							$e = "<div class='alert alert-warning  alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><strong>Wrong Password!</strong></div>";			
							echo  $a . "\t" . $b . "\t" . $c . "\t" . $d . "\t" . $e . "\n";
		}else{

				$data = array(
				'user_name' => $this->input->post('user_name'),
				'user_password' => md5($this->input->post('new_password')),
				'user_type' => 'Admin'
				);

						//echo 'UPDATE';
						$this->db->where('id', $id);
						$this->db->update('user_tb', $data);
						
							$a = "";
							$b = "";
							$c = "";
							$d = "";
							$e = "<div class='alert alert-success  alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><strong>Saved!</strong></div>";			
							echo  $a . "\t" . $b . "\t" . $c . "\t" . $d . "\t" . $e . "\n";

		}
				
		}	
		//
		public function get_meter_info($meter_id){
		

		$query = $this->db->get_where('meter_details', array('meter_name' => $meter_id));
		return $query->row_array();
		
		}
		/*LOAD PROFILE LOGS*/
		public function meter_all_lp_report($meter_id,$date_start,$date_end,$date_order)
		{

		/*
		QUERY METER LOGS
		QUERY METER LOGS BY LOCATION METER ID DATE
		*/
		$query_meter_logs = $this->db->query("SELECT  
		`meter_id`, `datetime`, `event_id`, 
			`ch_1`, `ch_2`, `ch_3`, `ch_4`, 
				`ch_5`, `ch_6`, `ch_7`, `ch_8` FROM `load_profile` WHERE 
					meter_id = ".$this->db->escape($meter_id)." and 
		datetime >= ".$this->db->escape($date_start)." and
		datetime <= ".$this->db->escape($date_end)."
		ORDER BY datetime $date_order");
		$row_count = $query_meter_logs->num_rows();
		if($query_meter_logs->num_rows()>0)
		{
		return $query_meter_logs->result_array(); 
		}
		else
		{
		/*return NULL;*/
		}
		}
		
		/*LOAD PROFILE STATUS*/
		public function meter_lp_status_report()
		{

		$query_lp_logs = $this->db->query("
		SELECT 
			`meter_details`.`meter_name` AS `meter_name`,
				`meter_details`.`meter_site_name` AS `meter_site_name`,
					`meter_details`.`customer_name` AS `customer_name`,
		MAX(`load_profile`.`datetime`) AS `datetime`
			from (
			`meter_details` left join `load_profile`
			on((`meter_details`.`meter_name` = `load_profile`.`meter_id`))
			)  
			WHERE `meter_details`.meter_load_profile='YES'
			GROUP BY 
				`meter_details`.`meter_name`,
					`load_profile`.`meter_id` ,
				`meter_details`.`meter_site_name`
					ORDER BY `datetime` ASC
		");
		
		$row_count = $query_lp_logs->num_rows();
		if($query_lp_logs->num_rows()>0)
		{
		return $query_lp_logs->result_array(); 
		}
		else
		{
		/*return NULL;*/
		}
		}

		public function meter_lp_status_report_site($location_id)
		{

		$query_lp_logs = $this->db->query("
		SELECT 
			`meter_details`.`meter_name` AS `meter_name`,
				`meter_details`.`meter_site_name` AS `meter_site_name`,
					`meter_details`.`customer_name` AS `customer_name`,
		MAX(`load_profile`.`datetime`) AS `datetime`
			from (
			`meter_details` left join `load_profile`
			on((`meter_details`.`meter_name` = `load_profile`.`meter_id`))
			)  
			WHERE `meter_details`.meter_load_profile='YES' and 
			`meter_details`.`meter_site_name` = ".$this->db->escape($location_id)."
			GROUP BY 
				`meter_details`.`meter_name`,
					`load_profile`.`meter_id` ,
				`meter_details`.`meter_site_name`
					ORDER BY `datetime` ASC
		");
		
		$row_count = $query_lp_logs->num_rows();
		if($query_lp_logs->num_rows()>0)
		{
		return $query_lp_logs->result_array(); 
		}
		else
		{
		/*return NULL;*/
		}
		}
		
	public function logs_site_list(){
	$user_type = $_SESSION['user_type'];
	if($user_type == 'Admin'){
	
		$this->db->select('meter_site_id');
		$this->db->select('business_entity');
		
		$query = $this->db->get_where('user_access_group', array('user_id' => $user_id));
		return $query->result_array();
	
	}
	else{
		
		$user_id = $_SESSION['user_id_sap'];
		$this->db->select('meter_site_id');
		$this->db->select('business_entity');
		$query = $this->db->get_where('user_access_group', array('user_id' => $user_id));
		return $query->result_array();
		
	}
	}
}