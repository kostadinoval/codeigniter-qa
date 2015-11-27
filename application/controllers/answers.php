<?php

class Answers extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model("sessions_model");
		$this->load->model('questions_model');
		$this->load->model("answers_model");
		$this->load->model("user_details_model");
	}
	
	public function index(){
		
	}
	
	/**************************************************************
	* Function which posts an answer.
	* This gets called through AJAX when a new answer is posted
	***************************************************************/
	public function post_answer(){
		
		$question_id = $this->input->post("question_id");
		$content = $this->input->post("content");
		
		
		if($content == "" || trim($content) == "" || $content == false || $content == null){
			
			echo json_encode(array("proceed" => false, "error" => "Cannot post a blank answer."));
		}
		else{
			
			$user_id = $this->sessions_model->get_user_id();
			
			$answer = array(			
				"answer_id" => null,
				"question_id" => $question_id,
				"user_id" => $user_id,
				"content" => nl2br($content),
				"rating" => 0,
				"date_posted" => null
			);
			
			$this->answers_model->post_answer($answer);
			$this->user_details_model->update_answers_count($answer["user_id"]);
			
			echo json_encode(array("proceed" => true));
		}
	}
	
	/*************************************************
	* Updates the rating of each of the answers
	**************************************************/
	public function update_rating(){
		//Collect all the required data
		//answer_id and vote are sent from the view via the AJAX call
		$answer_id = $this->input->post("answer_id");
		$vote = $this->input->post("vote");
		$user_id = $this->sessions_model->get_user_id();
		//question_id is also sent from the view via the AJAX call
		$question_id = $this->input->post("question_id");
		
		$this->answers_model->update_rating($answer_id, $vote);
		$this->answers_model->update_voted(array("vote_id" => null, "question_id" => $question_id, "user_id" => $user_id, "answer_id" => $answer_id, "vote" => $vote));
	}
}

?>