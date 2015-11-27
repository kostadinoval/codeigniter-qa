<?php

class Questions_model extends CI_Model {

	public function __construct(){

		parent::__construct();
		$this->load->database();
	}
	
	/************************************
	* Function that gets the ten most recent questions
	* used to generate the home view list of recent questions
	*************************************/
	public function get_recent_questions(){

		$this->db->select("question_id, title");
		$this->db->from("questions");
		$this->db->order_by("date_posted", "DESC");
		$this->db->limit(10,0);
		$query = $this->db->get();

		return $query->result_array();
	}
	
	/*********************************
	* Function which retrieves all question data when given a question id
	**********************************/
	public function get_question($id){

		$this->db->select("question_id, title, user_id, content, category, date_posted");
		$this->db->from("questions");
		$this->db->where("question_id",$id);
		$query = $this->db->get();
		
		if(count($query->result_array()) != 1){
			return false;
		}
		
		$temp = $query->result_array();
		
		$user_id = $temp[0]["user_id"];
		
		$this->db->select("username, image_path");
		$this->db->from("users");
		$this->db->where("user_id", $user_id);
		$query = $this->db->get();
		
		$temp[0]["username"] = $query->row()->username;
		$temp[0]["image_path"] = $query->row()->image_path;
		
		
		$this->db->select("tag");
		$this->db->where("question_id", $id);
		$this->db->from("tags");
		$query = $this->db->get();

		$tags = null;
		
		foreach($query->result_array() as $arr){
			foreach($arr as $val){
				$tags[] = $val;
			}
		}
		
		$temp[0]["tags"] = implode(", ", $tags);
		
		return $temp;
	}
	
	/*****************************
	* Function which saves a posted question
	******************************/
	public function post_question($question, $tags){
		
		$question["date_posted"] = date("Y-m-d: H:i:s");
		
		$query = $this->db->insert("questions", $question);
		$question_id = $this->db->insert_id();
		if($query){
			
			for($i=0; $i<count($tags); $i++){
				$tags[$i]["question_id"] = $question_id;
				$this->db->insert("tags",$tags[$i]);
			}
			
			return $question_id;
		}
		else{
			
			return false;
		}
	}
	
	/***********************************
	* Function which is used to get all the questions
	* which match a given search term
	* Used with the Guest Search
	* Matching is case-insensitive
	************************************/
	public function search_titles($search_term){
		$this->db->select("question_id, user_id, title, date_posted");
		$this->db->from("questions");
		$this->db->like("LOWER(title)", strtolower($search_term));
		$query  = $this->db->get();
		
		if($query->num_rows() > 0){
			$questions = $query->result_array();
			
			for($i=0; $i<count($questions); $i++){
				$this->db->select("username");
				$this->db->from("users");
				$this->db->where("user_id",$questions[$i]["user_id"]);
				$query = $this->db->get();
				$questions[$i]["username"] = $query->row()->username;
			}
			
			return $questions;
		}
		
		return false;
	}
	
	/******************************************
	* Function which returns the number of questions
	* Used in the home view in order to display
	* the number of questions that have been posted
	*******************************************/
	public function get_number_of_questions(){
		$this->db->select("COUNT(*) AS number_of_questions");
		$this->db->from("questions");
		$query = $this->db->get();
		
		return $query->row()->number_of_questions;
	}
	
	/*******************************
	* Function which deletes a question, any associated answers, tags and votes, given a question_id
	********************************/
	public function delete_question($question_id){
		$this->db->where("question_id", $question_id);
		$this->db->limit(1);
		$query = $this->db->delete("questions");

		$this->db->where("question_id", $question_id);
		$query = $this->db->delete("answers");
		
		$this->db->where("question_id", $question_id);
		$query = $this->db->delete("tags");

		$this->db->where("question_id", $question_id);
		$query = $this->db->delete("voted");

		return true;

	}
	
	/*****************************
	* Function which returns all the available categories
	* that questions can be associated with
	******************************/
	public function get_categories(){
		return array("General", "Programming", "Lifestyle", "Transport", "Entertainment");
	}
	
	/*******************************
	* Function which returns the categories with the most questions in them
	* Used in the home view
	********************************/
	public function get_top_categories(){
		$this->db->select("category, COUNT(*) as number");
		$this->db->from("questions");
		$this->db->group_by("category");
		$this->db->order_by("number", "DESC");
		$query = $this->db->get();
		
		return $query->result_array();
	}
	/**************************
	* Function which returns the number of questions 
	* in each of the categories
	* Used to populate the browse categories view
	***************************/
	public function question_count($categories){
		$count = array();
		foreach($categories as $category){
			$this->db->select("COUNT(*) as number");
			$this->db->from("questions");
			$this->db->where("category", $category);
			$query = $this->db->get();
			$count[$category] = $query->row()->number;
		}
		return $count;
	}
	
	/******************************
	* Function which returns all questions given
	* a specific category e.g. Programming
	*******************************/
	public function get_questions_by_category($category){
		$this->db->select("question_id, title");
		$this->db->from("questions");
		$this->db->where("category",$category);
		$query = $this->db->get();
		if($query->num_rows != 0){
			return $query->result_array();
		}
		else{
			return false;
		}
	}
}

?>