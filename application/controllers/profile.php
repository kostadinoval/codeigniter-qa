<?php

class Profile extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model("sessions_model");
		$this->load->model('questions_model');
		$this->load->model("answers_model");
		$this->load->model("user_details_model");
	}
	
	public function index(){
		redirect("/home");
	}
	
	/**********************************
	* Function that displays user profiles
	***********************************/
	public function view(){
		$user_id = $this->uri->segment(3);
		$data = $this->sessions_model->get_user_data();
		$data["title"] = "View Profile";
		$data["user"] = $this->user_details_model->get_profile_details($user_id);
		
		$this->load->view("../includes/header.php",$data);
		$this->load->view("profile_view");
		$this->load->view("../includes/footer");
	}

	/**********************************
	* Function that updates user details
	***********************************/
	public function update_details(){
		
		if($this->input->post("submit")){
			
			$this->form_validation->set_rules("email","email","trim|required|valid_email");
			$this->form_validation->set_rules("current_password","current password","required|min_length[8]|max_length[30]");
			
			if($this->form_validation->run() != false){
				$new_password = $this->input->post("new_password");
				$confirm_new_password = $this->input->post("confirm_new_password");
				
				//if any of these fields are not empty then the user wants to update their password
				if(!empty($new_password) || !empty($confirm_new_password)){
					
					$this->form_validation->set_rules("new_password","new password","required|min_length[8]|max_length[30]");
					$this->form_validation->set_rules("confirm_new_password","confirm new password","matches[new_password]|required");
					
					if($this->form_validation->run() != false){
						
						$email = $this->input->post("email");
						$user_id = $this->sessions_model->get_user_id();
						$password = $this->input->post("current_password");
						
						$username = $this->user_details_model->get_username($user_id);
						
						//check if they entered the correct password before updating details
						$result = $this->user_details_model->check_user($username, $password);
						
						if($result == $user_id){
							//if the password they entered matches the account password in the database then we can update the details
							$this->user_details_model->update_details($user_id, array("email" => $email, "password" => $new_password));
							$data = $this->sessions_model->get_user_data();
							$data["title"] = "View Profile";
							$data["user"] = $this->user_details_model->get_profile_details($user_id);
							
							$data["status"] = "Your details have successfully been updated.";
							
							$this->load->view("../includes/header.php",$data);
							$this->load->view("profile_view");
							$this->load->view("../includes/footer");
						}
						else{
							//passwords did not match
							$user_id = $this->sessions_model->get_user_id();
							$data = $this->sessions_model->get_user_data();
							$data["title"] = "View Profile";
							$data["user"] = $this->user_details_model->get_profile_details($user_id);
							$data["error"] = "Your current password did not match our records. Please try again.";
							$this->load->view("../includes/header.php",$data);
							$this->load->view("profile_view");
							$this->load->view("../includes/footer");
						}
					}
					else{
						//failed validation
						$user_id = $this->sessions_model->get_user_id();
						$data = $this->sessions_model->get_user_data();
						$data["title"] = "View Profile";
						$data["user"] = $this->user_details_model->get_profile_details($user_id);
						$this->load->view("../includes/header.php",$data);
						$this->load->view("profile_view");
						$this->load->view("../includes/footer");
					}
				}
				else{
					//if the new password and the confirm new password fields are empty then they want to update their email
					$email = $this->input->post("email");
					$user_id = $this->sessions_model->get_user_id();
					$password = $this->input->post("current_password");
					
					$username = $this->user_details_model->get_username($user_id);
					//check if they entered the correct password before updating details
					$result = $this->user_details_model->check_user($username, $password);
					
					if($result == $user_id){
						//if the password they entered matches the account password in the databse then we can update the email
						$this->user_details_model->update_details($user_id, array("email" => $email));
						$data = $this->sessions_model->get_user_data();
						$data["title"] = "View Profile";
						$data["user"] = $this->user_details_model->get_profile_details($user_id);
						
						$data["status"] = "Your details have successfully been updated.";
						
						$this->load->view("../includes/header.php",$data);
						$this->load->view("profile_view");
						$this->load->view("../includes/footer");
					}
					else{
						//passwords did not match
						$user_id = $this->sessions_model->get_user_id();
						$data = $this->sessions_model->get_user_data();
						$data["title"] = "View Profile";
						$data["user"] = $this->user_details_model->get_profile_details($user_id);
						$data["error"] = "Your current password did not match our records. Please try again.";
						$this->load->view("../includes/header.php",$data);
						$this->load->view("profile_view");
						$this->load->view("../includes/footer");
					}
					
				}
			}
			else{
				//minimum requirements, i.e. email and current password were not entered so reload the view and display the errors
				$user_id = $this->sessions_model->get_user_id();
				$data = $this->sessions_model->get_user_data();
				$data["title"] = "View Profile";
				$data["user"] = $this->user_details_model->get_profile_details($user_id);
				$this->load->view("../includes/header.php",$data);
				$this->load->view("profile_view");
				$this->load->view("../includes/footer");
			}

		}
		else{
			//if the user tries to access this through /profile/update_details then redirect to /home
			redirect("/home");
			exit();
		}
	}
}

?>