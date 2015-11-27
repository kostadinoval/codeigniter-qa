<?php

class Sessions_model extends CI_Model {

	//Model which deals with setting and getting session data

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function set_user_data($data){
		$this->session->set_userdata($data);
	}
	
	public function is_logged_in(){
		return $this->session->userdata("logged_in");
	}
	
	public function get_username(){
		return $this->session->userdata("username");
	}
	
	public function get_user_id(){
		return $this->session->userdata("user_id");
	}
	
	public function get_user_data(){
		return $this->session->all_userdata();
	}
}

?>