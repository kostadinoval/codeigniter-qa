<?php

class Answers_model extends CI_Model {

	public function __construct(){

		parent::__construct();
		$this->load->database();
	}
	
	/******************************************
	* Function used to get all the answers for a given question_id
	* Optional second parameter which tells the script that we
	* require extra information because a user is logged in
	* If a user is logged in, i.e. a user_id is passed to this function, then the script
	* will check if that user is able to vote on each of the answers
	* if they have already voted or they are the person who posted the answer
	* they will not be able to vote
	*******************************************/
	public function get_answers($question_id, $user_id=false){
		$this->db->select("answer_id, user_id, content, rating, date_posted");
		$this->db->from("answers");
		$this->db->where("question_id", $question_id);
		$this->db->order_by("rating", "DESC");
		$this->db->order_by("date_posted", "ASC");
		$query = $this->db->get();
		$users = array();
		$enable_vote = array();
		$enable;
		if($user_id != false){
			$enable = true;
		}
		else{
			$enable = false;
		}
		if($query){
			$answers = $query->result_array();
			
			foreach($answers as $answer){
				$this->db->select("username, image_path");
				$this->db->from("users");
				$this->db->where("user_id",$answer["user_id"]);
				$query = $this->db->get();
				$users[] = $query->row_array();
				if($enable){
					
					$query = $this->db->get_where("voted", array(
						"question_id" => $question_id,
						"user_id" => $user_id,
						"answer_id" => $answer["answer_id"]
					));
					
					if($query->num_rows() == 0){
						$enable_vote[] = true;
					}
					
					else{
						$enable_vote[] = false;
					}
				}
			}
			
			for($i=0; $i<count($answers); $i++){
				$answers[$i]["username"] = $users[$i]["username"];
				$answers[$i]["image_path"] = $users[$i]["image_path"];
				if($enable){
					$answers[$i]["enable_vote"] = $enable_vote[$i];
				}
			}
			
			return $answers;
		}
		
		else{
			return false;
		}
	}
	
	/*********************************
	* Function responsible for saving a posted answer
	**********************************/
	public function post_answer($answer){
		
		$answer["date_posted"] = date("Y-m-d: H:i:s");
		
		$query = $this->db->insert("answers", $answer);
		if($query){
			return true;
		}
		else{
			return false;
		}
	}
	
	/**********************************
	* Function which checks whether a given user can post
	* an answer on a given question
	***********************************/
	public function can_answer($user_id, $question_id){
		$this->db->select("COUNT(*) as number_of_answers");
		$this->db->from("answers");
		$this->db->where("user_id", $user_id);
		$this->db->where("question_id", $question_id);
		$query = $this->db->get();
		
		if($query->row()->number_of_answers == 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	/************************************
	* Function which updates the rating for a given answer id
	* the vote parameter represents the type of vote e.g. 'up' or 'down'
	*************************************/
	public function update_rating($answer_id, $vote){
		if($vote == "up"){
			$this->db->set("rating", "rating+1", FALSE);
		}
		else if($vote == "down"){
			$this->db->set("rating", "rating-1", FALSE);
		}
		$this->db->where("answer_id", $answer_id);
		$this->db->update("answers");
	}
	
	/********************************
	* Function which updates the 'voted' table
	* after a user has voted
	*********************************/
	public function update_voted($data){
		$this->db->insert("voted", $data);
	}
}

?>