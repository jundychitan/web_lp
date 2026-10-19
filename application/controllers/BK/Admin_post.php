<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_post extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                //$this->load->model('admin_model');
                $this->load->helper('url_helper');
				$this->load->library('session');
				$this->load->helper('html');
        }
		
		public function account_settings_post()
		{		

			if(('currently_logged_in_as')=='Admin'  || $this->session->userdata('currently_logged_in_as')=='Site'){

					$this->load->helper('form');
					$this->load->library('form_validation');
	
					$this->form_validation->set_rules('user_name', 'Username', 'required');
					$this->form_validation->set_rules('location', 'Location', 'required');
					$this->form_validation->set_rules('old_password', 'Old Password', 'required|trim|min_length[5]|max_length[15]');
					$this->form_validation->set_rules('new_password', 'New Password', 'required|trim|min_length[5]|max_length[15]|matches[retype_new_password]');
					$this->form_validation->set_rules('retype_new_password', 'Confirm Password', 'required|trim|min_length[5]|max_length[15]');
					
					/*VALIDATION FAILED*/
					if ($this->form_validation->run() === FALSE)
					{

						$a = "<div style='color:red; display:inline-flex;'>" . form_error('user_name') . "</div>";
						$b = "<div style='color:red; display:inline-flex;'>" . form_error('location') . "</div>";
						$c = "<div style='color:red; display:inline-flex;'>" . form_error('old_password') . "</div>";
						$d = "<div style='color:red; display:inline-flex;'>" . form_error('new_password') . "</div>"; 
						$e = "<div style='color:red; display:inline-flex;'>" . form_error('retype_new_password') . "</div>";
								
						$f = "<div class='alert alert-warning alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><strong>***Requirled Field***</strong></div>";
						echo  $a . "\t" . $b . "\t" . $c . "\t" . $d . "\t" . $e . "\t" . $f . "\n";

					}
					//VALIDATION SUCCESS
					else{
						
						
						
						$username = $this->input->post('user_name');
						$location = $this->input->post('location');
						$user_password = md5($this->input->post('new_password'));
						$user_type = 'Admin';

		$user_currently_login = $_SESSION['user_name'];
		@$account_settings = explode(',', file_get_contents("config_file/$user_currently_login"));
		@$data['current_account_settings'] = $account_settings;
		
		@$user_name_current = $data['current_account_settings']['0'];
		
		@$user_password_current = $data['current_account_settings']['1'];
		
		@$location_current = $data['current_account_settings']['3'];
		
		@$user_password_old_from_input = md5($this->input->post('old_password'));
		
		if($user_password_current!=$user_password_old_from_input){
		
		/*Current password did not match to old password input*/
							$a = "";
							$b = "<div style='color:red;'>Wrong Password</div>";
							$c = "";
							$d = "";
							$e = "";
							$f = "<div class='alert alert-warning  alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><strong>Wrong Password!</strong></div>";			
						echo  $a . "\t" . $b . "\t" . $c . "\t" . $d . "\t" . $e . "\t" . $f . "\n";
		}else{

						$myfile_config_copy = fopen("config_file/$username", "w") or die("Unable to open file!");	
						fwrite($myfile_config_copy, "$user_name_current,$user_password,$user_type,$location_current");
						fclose($myfile_config_copy);
						
						
							$a = "";
							$b = "";
							$c = "";
							$d = "";
							$e = "";
							$f = "<div class='alert alert-success  alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>×</a><strong>Saved!</strong></div>";			
						echo  $a . "\t" . $b . "\t" . $c . "\t" . $d . "\t" . $e . "\t" . $f . "\n";
		}
					}
			}
			else{
				redirect('Login/invalid');  
			}
		   			
		}
		
		public function date_time_details()
		{			
			if(('currently_logged_in_as')=='Admin'  || $this->session->userdata('currently_logged_in_as')=='Site'){
				$a = date('M-d-Y');
				$b = date('H:i:s');
				echo  $a . "\t" . $b ."\n";
			}
			else{
				redirect('Login/invalid');  
			}
		}
				
}