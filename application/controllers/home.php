<?php

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model("sessions_model");
		$this->load->model('questions_model');
		$this->load->model("user_details_model");
	}

	public function index(){
		
		$data = $this->sessions_model->get_user_data();
		$data["title"] = "Home";
		
		$data["questions"] = $this->questions_model->get_recent_questions();
		$data["top_users"] = $this->user_details_model->get_top_users();
		$data["number_of_questions"] = $this->questions_model->get_number_of_questions();
		$data["number_of_users_online"] = $this->user_details_model->get_number_of_users_online();
		$data["top_categories"] = $this->questions_model->get_top_categories();
		
		$this->load->view('../includes/header.php', $data);
		$this->load->view('home_view');
		$this->load->view('../includes/footer.php');
	}
}

?>
