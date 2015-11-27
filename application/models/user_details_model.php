<?php

class User_details_model extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	/****************************
	* Function which checks whether the chosen username and
	* email are unique
	* Used when a user tries to register
	*****************************/
	public function unique_username_email($username, $email){
		$this->db->select("username");
		$this->db->from("users");
		$this->db->where("LOWER(username)", strtolower($username));
		$query = $this->db->get();
		
		$this->db->select("email");
		$this->db->from("users");
		$this->db->where("LOWER(email)", strtolower($email));
		$query2 = $this->db->get();

		if(($query->num_rows() == 0) && ($query2->num_rows() == 0))
			return true;
		else
			return false;
	}
	
	/*********************************
	* Function which given an array of user details
	* will create an array called values that will
	* be used to insert all the user details in the database
	**********************************/
	public function register_user($user_details){
		$values["user_id"] = null;
		$values["username"] = $user_details["username"];
		$values["email"] = $user_details["email"];
		$values["password"] = $this->get_hashed_password($user_details["password"]);
		$values["registration_date"] = date("Y-m-d: H:i:s");
		$values["image_path"] = "https://w1416464.users.ecs.westminster.ac.uk/CI_1/application/images/default.jpg";
		$values["user_type"] = "N";
		$values["number_of_questions"] = 0;
		$values["number_of_answers"] = 0;
		$values["rating"] = 0;
		
		$query = $this->db->insert("users", $values);	

		if($query)
			return true;
		else
			return false;
	}
	
	/*********************************
	* Function used to hash user passwords when registering
	* The salt is created by hashing the date/time, shuffling the string and 
	* selecting a sub-string of 64 characters starting at 32
	* The salt is stored with the hashed password by storing the first 32 characters of the salt
	* at the start of the hashed password and the remaining 32 characters of the salt at the end
	* It returns the final string which is ready to be inserted into the database
	**********************************/
	private function get_hashed_password($password){
		$salt = substr(str_shuffle(hash("sha512", date("Y-m-d H:i:s"))),32,64);
		$hashed_password = hash("sha512",$password.$salt);
		$final_pass = substr($salt,0,32) . $hashed_password . substr($salt,32,32);
		return $final_pass;
	}
	
	/*********************************
	* Function which checks if the provided username and password
	* match a record in the database
	* The password string(which contains the hidden salt and the hashed password) from the database for that username is retrieved
	* The salt is reconstructed
	**********************************/
	public function check_user($username, $password){
		$this->db->select("password");
		$this->db->from("users");
		$this->db->where("username", $username);
		$query = $this->db->get();
		
		$pass = $query->row()->password;
		$salt = substr($pass,0,32) . substr($pass,160,32);
		
		$this->db->select("user_id");
		$this->db->from("users");
		$this->db->where("username", $username);
		$this->db->where("password", substr($pass,0,32) . hash("sha512", $password . $salt) . substr($pass,160,32));
		$query = $this->db->get();
		
		if($query->num_rows == 1){
			return $query->row()->user_id;
		}
		else{
			return false;
		}
	}
	
	/*****************************
	* Function which updates the question count
	* whenever a new question is posted
	* The function also updates the rating by adding one
	* to the existing rating for that user
	******************************/
	public function update_questions_count($user_id){
		$this->db->set("number_of_questions", "number_of_questions+1", FALSE);
		$this->db->set("rating", "rating+1", FALSE);
		$this->db->where("user_id", $user_id);
		$this->db->update("users");
	}
	
	/*************************
	* Function which updates the answers count
	* whenever a new answer is posted
	* The function also updates the rating by adding one
	* to the existing rating for that user
	**************************/
	public function update_answers_count($user_id){
		$this->db->set("number_of_answers", "number_of_answers+1", FALSE);
		$this->db->set("rating", "rating+1", FALSE);
		$this->db->where("user_id", $user_id);
		$this->db->update("users");
	}
	
	/***************************
	* Function which given a user id
	* will retrieve their rating
	***************************/
	public function get_rating($user_id){
		$this->db->select("rating");
		$this->db->from("users");
		$this->db->where("user_id", $user_id);
		$query = $this->db->get();
		
		return $query->row()->rating;
	}
	
	/*****************************
	* Function which uses the ci_sessions table to
	* get a count of the number of users online
	* Used in the home view
	******************************/
	public function get_number_of_users_online(){
		$this->db->select("COUNT(*) AS number_of_users_online");
		$this->db->from("ci_sessions");
		$this->db->where("user_data !=", "");
		$query = $this->db->get();
		
		return $query->row()->number_of_users_online;
	}
	
	/******************************
	* Function which selects the top 10 users based
	* on their rating
	* Used in the home view
	*******************************/
	public function get_top_users(){
		$this->db->select("user_id, username, rating");
		$this->db->from("users");
		$this->db->order_by("rating", "DESC");
		$this->db->limit(10,0);
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/******************************
	* Function which returns a true or false
	* depending on whether a moderator/admin is logged in
	* A moderator will have a user type of 'M'
	* and so if the user type is 'M' then they
	* will be allowed to delete questions
	********************************/
	public function can_delete($user_id){
		$this->db->select("user_type");
		$this->db->from("users");
		$this->db->where("user_id",$user_id);
		$query = $this->db->get();
		
		if($query->row()->user_type == "M"){
			return true;
		}
		else{
			return false;
		}
	}
	/************************
	* Function that returns a username based on a given user_id
	*************************/
	public function get_username($user_id){
		$this->db->select("username");
		$this->db->from("users");
		$this->db->where("user_id", $user_id);
		$query = $this->db->get();
		return $query->row()->username;
	}
	
	/*************************
	* Function that retrieves all user details, except user type,
	* used to display the user profile
	**************************/
	public function get_profile_details($user_id){
		$this->db->select("user_id, username, email, registration_date, image_path, user_type, number_of_questions, number_of_answers, rating");
		$this->db->from("users");
		$this->db->where("user_id", $user_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	/****************************
	* Function that updates the user details table
	* whenever a logged in user changes their details
	* through the profile view
	****************************/
	public function update_details($user_id, $data){
		if(!empty($data["password"])){
			$data["password"]= $this->get_hashed_password($data["password"]);
			$this->db->where("user_id", $user_id);
			$this->db->update("users", $data);	
		}
		else{
			$this->db->where("user_id", $user_id);
			$this->db->update("users", $data);
		}
	}
}

?>