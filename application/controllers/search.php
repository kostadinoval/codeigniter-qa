<?php

class Search extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model("sessions_model");
		$this->load->model("questions_model");
		$this->load->model("answers_model");
		$this->load->model("user_details_model");
	}

	public function index(){
		redirect("/home");
	}
	
	/**********************************
	* Function that gets the search term submitted through the header search form
	* and retrieves all the data that guest_search/term function retrieves
	* Retrieves:
	* Question, who posted the question as well as the date
	* All the answers with the person who answered and their rating
	* All users who answered are ranked based on the rating of the provided answer and not by their total user rating
	***********************************/
	public function term(){
		
		$data = $this->sessions_model->get_user_data();
		$data["title"] = "Search";
		
		$search_term = urldecode($this->uri->segment(3));
		
		$data["questions"] = $this->questions_model->search_titles($search_term);
		
		if($data["questions"] != false){
			
			for($i=0;$i<count($data["questions"]);$i++){
				$data["questions"][$i]["answers"] = $this->answers_model->get_answers($data["questions"][$i]["question_id"]);
			}
			
			$index = 0;
			for($i=0; $i<count($data["questions"]); $i++){
				foreach($data["questions"][$i]["answers"] as $answer){
					$result = $this->user_details_model->get_rating($answer["user_id"]);
					
					$data["questions"][$i]["answers"][$index]["user_rating"] = $result;
					$index++;
				}
				$index = 0;
			}
			
			$html = "";
			
			foreach($data["questions"] as $question){
			
				$html .= '<div class="results_table">
							<div class="answer_info">
							<p class="number_of_answers">';
				
				if(count($question["answers"]) == 0){
					$html .= '<span class="guest_search_header"> 0 answers </span>';
				}
				else if(count($question["answers"]) == 1){
					$html .= '<span class="guest_search_header">1 answer </span>';
					$html .= '<p class="small_text">Users who answered(rating):</p><p class="small_text">(ordered by their answer rating)</p>';
				}
				else{
					$html .= '<span class="guest_search_header">' . count($question["answers"]) . ' answers </span>';
					$html .= '<p class="small_text">Users who answered(rating):</p><p class="small_text">(ordered by their answer rating)</p>';
				}
				$html .= '</p>';
				
				if(count($question["answers"]) > 0){
					$html .= '<ul class="who_answered_list">';
					foreach($question["answers"] as $answer){
						$html .= '<li><b>' . htmlspecialchars($answer["username"]);
						$html .= ' (' . $answer["user_rating"] . ')';
						$html .= '</b></li>';
					}
					$html .= '</ul>';
				}
				$html .= '</div>';
				$html .= '<div class="question_info">
							<p class="title">';
				$html .= '<a href="https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/questions/view_question/' . $question["question_id"] . '">';
				$html .= htmlspecialchars($question["title"]) . '</a></p>';
				$html .= '<span class="posted_by">Posted by: <b>' . htmlspecialchars($question["username"]) . '</b></span>';
				$html .= '<span class="posted_on">Posted on: <b>' . date("d.m.Y", strtotime($question["date_posted"])) . '</b></span>';
				$html .= '</div>
							</div>';
			}
			
			$data["html"] = $html;
			$this->load->view("../includes/header.php",$data);
			$this->load->view("search_view");
			$this->load->view("../includes/footer.php");
		}
		
		else{
			$data["title"] = "Search";
			$data["html"] = false;
			$this->load->view("../includes/header.php",$data);
			$this->load->view("search_view");
			$this->load->view("../includes/footer.php");
		}
	}
}

?>