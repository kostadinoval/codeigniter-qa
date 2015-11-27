<?php

class Categories extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model("sessions_model");
		$this->load->model('questions_model');
		$this->load->model("answers_model");
		$this->load->model("user_details_model");
	}
	
	public function index(){
		
		$data = $this->sessions_model->get_user_data();		
		$data["title"] = "Categories";		
		$data["cat"] = $this->questions_model->get_categories();		
		$data["categories"] = $this->questions_model->question_count($data["cat"]);
		
		$this->load->view('../includes/header.php', $data);
		$this->load->view('categories_view');
		$this->load->view('../includes/footer.php');
	}
	
	/**********************************
	* Fetches all questions given a category
	***********************************/
	public function get_questions(){
		
		$category = $this->uri->segment(3);		
		$data = $this->sessions_model->get_user_data();		
		$data["title"] = "Questions in the " . strtolower($category) . " category";	
		$data["questions"] = $this->questions_model->get_questions_by_category($category);
		$data["category"] = $category;
		
		$this->load->view('../includes/header.php', $data);
		$this->load->view('questions_in_category_view');
		$this->load->view('../includes/footer.php');
	}

}

?>