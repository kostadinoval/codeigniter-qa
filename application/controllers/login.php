<?php

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model("sessions_model");
		$this->load->model("user_details_model");
	}

	public function index(){
		
		if($this->sessions_model->is_logged_in()){
			redirect("/home");
			exit();
		}
		
		$data["title"] = "Login";
		
		$this->load->view('../includes/header.php', $data);
		$this->load->view('login_view');
		$this->load->view('../includes/footer.php');
	}
	
	/***********************************
	* Function that validates the username and password fields
	* Uses the form validation library
	************************************/
	public function validate(){
		
		if($this->sessions_model->is_logged_in()){
			redirect("/home");
			exit();
		}
		
		$this->form_validation->set_rules("username","username","trim|required|max_length[20]");
		$this->form_validation->set_rules("password","password","required||max_length[30]");
		
		if($this->form_validation->run() == false){
			
			$data["title"] = "Login";

			$this->load->view('../includes/header.php', $data);
			$this->load->view('login_view');
			$this->load->view('../includes/footer.php');
		}
		else{
			
			$username = $this->input->post("username");
			$password = $this->input->post("password");
			
			$result = $this->user_details_model->check_user($username, $password);
			
			if($result != false){
				$data = array(
					"username" => $username,
					"user_id" => $result,
					"logged_in" => true
				);
				
				$this->sessions_model->set_user_data($data);
				redirect("/home");
				exit();
			}
			else{
				
				$data["title"] = "Login";
				$data["error"] = "Login unsuccessful. Please try again.";
				$this->load->view('../includes/header.php', $data);
				$this->load->view('login_view');
				$this->load->view('../includes/footer.php');
			}
		}
	}
}

?>