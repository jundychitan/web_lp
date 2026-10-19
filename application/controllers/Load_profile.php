<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Load_profile extends CI_Controller {

	public function __construct(){
	parent::__construct();
	$this->load->model('load_profile_model');
	$this->load->helper('url_helper');
	$this->load->library('session');
	$this->load->helper('html');
	$this->load->helper(array('form', 'url'));
	}

	/*HISTORY LOG*/
	public function history_log(){
	
	if($this->session->userdata('currently_logged_in_as')=='Admin'){
	
	$user_currently_login = $_SESSION['user_name'];
	$data['user_currently_login'] = $_SESSION['user_name'];
	
	$data['title'] = 'History Log';
	$data['title_1'] = 'File List';
	$data['title_2'] = 'Log';
	
	$this->load->view('templates/header', $data);
	$this->load->view('loadprofile/navigation-admin');
	$this->load->view('loadprofile/history_log', array('error' => ' ' ));
	$this->load->view('templates/footer');
	
	}
	else if($this->session->userdata('currently_logged_in_as')=='Supervisor' || $this->session->userdata('currently_logged_in_as')=='Officer' || $this->session->userdata('currently_logged_in_as')=='Manager' || $this->session->userdata('currently_logged_in_as')=='RE'){
	$user_currently_login = $_SESSION['user_name'];
	$user_id = $_SESSION['user_id'];

	$data['user_currently_login'] = $_SESSION['user_name'];

	$data['title'] = 'Load Profile Log';
	$data['title_1'] = 'File List';
	$data['title_2'] = 'Log';
	
	/*GET LOCATION ACCESS*/
	$data['get_location_list'] = $this->load_profile_model->get_location_list($user_id);;
	
	$this->load->view('templates/header', $data);
	$this->load->view('loadprofile/navigation-admin');
	$this->load->view('loadprofile/history_log', array('error' => ' ' ));
	$this->load->view('templates/footer');
	
	}
	
	else{
	redirect('Login/invalid');
	}
	
	} 

	/*LOAD PROFILE*/
	public function load_profile_log(){
	
	if($this->session->userdata('currently_logged_in_as')=='Admin'){
	
	$user_currently_login = $_SESSION['user_name'];
	$data['user_currently_login'] = $_SESSION['user_name'];
	
	$data['title'] = 'Load Profile Log';
	$data['title_1'] = 'File List';
	$data['title_2'] = 'Log';
	
	/*USER LIST OF ACCESS*/
	$data['site_list'] = $this->load_profile_model->logs_site_list();

	$this->load->view('templates/header', $data);
	$this->load->view('loadprofile/navigation-admin');
	$this->load->view('loadprofile/load_profile_log', array('error' => ' ' ));
	$this->load->view('templates/footer');
	
	}
	else if($this->session->userdata('currently_logged_in_as')=='Supervisor' || $this->session->userdata('currently_logged_in_as')=='Officer' || $this->session->userdata('currently_logged_in_as')=='Manager' || $this->session->userdata('currently_logged_in_as')=='RE'){
	$user_currently_login = $_SESSION['user_name'];
	$user_id = $_SESSION['user_id'];
	$data['user_currently_login'] = $_SESSION['user_name'];
	
	$data['title'] = 'Load Profile Log';
	$data['title_1'] = 'File List';
	$data['title_2'] = 'Log';
	
	/*GET LOCATION ACCESS*/
	$data['site_list'] = $this->load_profile_model->get_location_list($user_id);;
	
	$this->load->view('templates/header', $data);
	$this->load->view('loadprofile/navigation-admin');
	$this->load->view('loadprofile/load_profile_log', array('error' => ' ' ));
	$this->load->view('templates/footer');
	
	} 
	
	else{
	//redirect('Login/invalid');
	}
	
	}

	/*LOAD PROFILE REPORT FROM TABLE*/
	public function load_profile_report(){
	
	if($this->session->userdata('currently_logged_in_as')=='Admin'){
	
	$user_currently_login = $_SESSION['user_name'];
	$data['user_currently_login'] = $_SESSION['user_name'];
	
	$data['title'] = 'Load Profile Report';
	$data['title_1'] = 'List';
	$data['title_2'] = 'Log';
	
	$data['site_list'] = $this->load_profile_model->logs_site_list();
	
	$this->load->view('templates/header', $data);
	$this->load->view('loadprofile/navigation-admin');
	$this->load->view('loadprofile/load_profile_report_log', array('error' => ' ' ));
	$this->load->view('templates/footer');
	
	}
	else if($this->session->userdata('currently_logged_in_as')=='Supervisor' || $this->session->userdata('currently_logged_in_as')=='Officer' || $this->session->userdata('currently_logged_in_as')=='Manager' || $this->session->userdata('currently_logged_in_as')=='RE'){
	$user_currently_login = $_SESSION['user_name'];
	$user_id = $_SESSION['user_id'];
	$data['user_currently_login'] = $_SESSION['user_name'];
	
	$data['title'] = 'Load Profile Log';
	$data['title_1'] = 'File List';
	$data['title_2'] = 'Log';
	
	/*GET LOCATION ACCESS*/
	$data['site_list'] = $this->load_profile_model->get_location_list($user_id);;
	
	$this->load->view('templates/header', $data);
	$this->load->view('loadprofile/navigation-admin');
	$this->load->view('loadprofile/load_profile_report_log', array('error' => ' ' ));
	$this->load->view('templates/footer');
	
	}
	else{
	redirect('Login/invalid');
	}
	
	}
	
	/*LOAD PROFILE STATUS*/
	public function load_profile_status(){

if($this->session->userdata('currently_logged_in_as')=='Admin' || $this->session->userdata('currently_logged_in_as')=='Supervisor' || $this->session->userdata('currently_logged_in_as')=='Officer' || $this->session->userdata('currently_logged_in_as')=='Manager' || $this->session->userdata('currently_logged_in_as')=='RE'){
	
	$user_currently_login = $_SESSION['user_name'];
	$user_id = $_SESSION['user_id'];
	$data['user_currently_login'] = $_SESSION['user_name'];
	
	$data['title'] = 'Load Profile Status';
	$data['title_1'] = 'File List';
	$data['title_2'] = 'Log';
		
	/*GET LOCATION ACCESS*/
	if($this->session->userdata('currently_logged_in_as')=='Admin'){
	
	$data['get_location_list'] = $this->load_profile_model->site_list($user_id);
	
	}
	else{

	$data['get_location_list'] = $this->load_profile_model->site_list_for_non_admin($user_id);
	
	}
	
	$this->load->view('templates/header', $data);
	$this->load->view('loadprofile/navigation-admin');
	$this->load->view('loadprofile/lp_status', array('error' => ' ' ));
	$this->load->view('templates/footer');
	
	}
	
	else{
	echo 'invalid';//redirect('Login/invalid');
	}
	
	}


}