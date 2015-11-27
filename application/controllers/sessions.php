<?php

class Sessions extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model("sessions_model");
	}
	
	public function index(){
		echo $this->sessions_model->is_logged_in();
	}

}

?>
