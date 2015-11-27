<?php

class Register extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model("sessions_model");
		$this->load->model('user_details_model');
	}

	public function index(){
		
		if($this->sessions_model->is_logged_in()){
			redirect("/home");
			exit();
		}
		else{
			
			$data["title"] = "Register";
			
			$this->load->view('../includes/header.php', $data);
			$this->load->view('register_view');
			$this->load->view('../includes/footer.php');
		}
	}
	
	/********************************************
	* Function which validates the user input when
	* a registration is attempted
	*********************************************/
	public function validate(){
		
		if($this->sessions_model->is_logged_in()){
			redirect("/home");
			exit();
		}
		
		$this->form_validation->set_rules("username","username","trim|required|min_length[5]|max_length[20]");
		$this->form_validation->set_rules("email","email","trim|required|valid_email");	
		$this->form_validation->set_rules("password","password","required|min_length[8]|max_length[30]");
		$this->form_validation->set_rules("confirm_password","confirm password","matches[password]|required");		
		
		
		if($this->form_validation->run() == false){
			
			$data = $this->sessions_model->get_user_data();
			$data["title"] = "Register";
			
			$this->load->view('../includes/header.php', $data);
			$this->load->view('register_view');
			$this->load->view('../includes/footer.php');
		}
		else{
			
			$unique = $this->user_details_model->unique_username_email($this->input->post("username"),$this->input->post("email"));		
			
			if($unique == true){
				
				$result = $this->user_details_model->register_user(array(
					"username" => $this->input->post("username"),
					"email" => $this->input->post("email"),
					"password" => $this->input->post("password")
				));
				
				$data["title"] = "Registration successful";
				$this->load->view('../includes/header.php', $data);
				$this->load->view('register_success_view');
				$this->load->view('../includes/footer.php');
			}
			else{
				
				$data["title"] = "Register";
				$data["error"] = "The username or email is already in use.";
				$this->load->view('../includes/header.php', $data);
				$this->load->view('register_view');
				$this->load->view('../includes/footer.php');
			}
		}
	}
}

?>