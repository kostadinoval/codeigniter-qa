<?php

class Questions extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model("sessions_model");
		$this->load->model('questions_model');
		$this->load->model("answers_model");
		$this->load->model("user_details_model");
	}
	
	public function index(){
		
		$data = $this->sessions_model->get_user_data();
		
		$data["title"] = "No question selected";
		$data["error"] = "No question selected.";
		$this->load->view('../includes/header.php', $data);
		$this->load->view('no_question_view');
		$this->load->view('../includes/footer.php');
	}
	
	/*******************************************
	* Function which displays a question
	* requires the question_id which is passed
	* as the third segment
	* e.g. /questions/view_question/2
	********************************************/
	public function view_question(){
		
		$id = $this->uri->segment(3);
		$data = array();
		
		if($id == NULL || $id == false || $id == ""){
			
			$this->invalid_question();
		}
		else{
			
			$data["question"] = $this->questions_model->get_question($id);
			
			if($data["question"] == false){
				$this->invalid_question();
			}
			else{
				
				$data["title"] = "View Question";
				
				if($this->sessions_model->is_logged_in()){
					
					$data["logged_in"] = true;
					$data["user_id"] = $this->sessions_model->get_user_id();
					//Retrieve all answers for this question
					//2 variables are passed to the get_answers() function (only requires 1 - the question id) this means
					//that a user is logged in and we need to check if they have already
					//answered that question and if so we need to control
					//whether or not they can post an answer
					$data["answers"] = $this->answers_model->get_answers($id, $data["user_id"]);
					$data["enable_post"] = $this->answers_model->can_answer($data["user_id"], $id);
					//If the logged in user is a moderator/admin then they can delete the question
					$data["enable_delete_question"] = $this->user_details_model->can_delete($data["user_id"]);
				}
				else{
					//Passing one variable to the get_answers() function means
					//that no user is logged in and so we do not need to query
					//the database about who can post an answer and who can delete questions
					//just retrieve the answers
					$data["answers"] = $this->answers_model->get_answers($id);
				}
				
				//This variable is only set to true when a new answer is posted
				//Whenever an answer is posted the answers for that question are reloaded via AJAX
				//By enabling this variable it tells the script that we want to load the answers
				//via AJAX. This is done so that whenever a user posts an answer the view question page does not
				//need to reload and all the answers are dynamically reloaded and ordered by their rating
				$view_with_ajax = $this->input->post("view_with_ajax");
				
				if($view_with_ajax){
					
					$html = "";
					
					if(count($data["answers"]) == 0){
						$html .= "<h3 style=\"margin-bottom:15px;\"> No answers </h3>";
					}
					else{
						if(count($data["answers"]) == 1){
							$html .= "<h3> 1 answer </h3>";
						}
						else if(count($data["answers"]) > 1){
							$html .= "<h3>" . count($data["answers"]) . " answers</h3>";
						}
					}
					
					foreach($data["answers"] as $answer){
						$html .= '<div class="answer_table">
								<div class="toolbar">';
						//If the logged in user is the one who posted the answer or the enable vote variable is set to false
						//then prevent the user from voting on that answer
						//Only logged in users are able to vote
						//If the logged in user posted that answer, they are not allowed to vote on their own answer
						if($data["user_id"] == $answer["user_id"] || $answer["enable_vote"] == false){
							$html .= '<button class="hidden">up</button>
								<div class="rating"><b>';
							$html .= $answer["rating"];
							$html .= '</b></div>
								<button class="hidden">down</button>';
						}
						else{
							//If someone other than the logged in user posted an answer then the logged in user can vote
							$html .= '<button class="up ' . $answer["answer_id"] . '">up</button>
								<div class="rating"><b>';
							$html .= $answer["rating"];
							$html .= '</b></div>
								<button class="down ' . $answer["answer_id"] . '">down</button>';
						}				
						//Run all user entered input through htmlspecialchars
						$html .= '</div><div class="cell">
								<div class="answer">';
						$html .= htmlspecialchars($answer["content"]);
						$html .= '</div>
								</div>
								
								<div class="user_info">
								<div class="posted_by">
								<p>Posted by:</p>';
						$html .= "<a class=\"usernames\" href=\"https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/profile/view/" . $answer["user_id"] . "\">";
						$html .= "<p><b>" . htmlspecialchars($answer["username"]) . "</b></p></a>";
						$html .= '</div>
								<img class="picture" src="' . $answer["image_path"] . '"/>
								<div class="posted_date">
								<p>Date posted:</p>';
						
						$time = date("H:i:s", strtotime($answer["date_posted"]));
						$html .= "<p><b>" . $time . "</b></p>"; 
						$date = date("d.m.Y", strtotime($answer["date_posted"]));
						$html .= "<p><b>" . $date . "</b></p>";
						$html .= '</div>
								</div>

								</div>';
					}
					
					echo $html;
				}
				else{
					//If questions view should not be loaded via AJAX then load it normally
					$this->load->view('../includes/header.php', $data);
					$this->load->view('question_view');
					$this->load->view('../includes/footer.php');
				}
			}
		}
	}
	
	/**********************************
	* Function which is responsible for posting a question
	***********************************/
	public function post_question(){
		
		if($this->sessions_model->is_logged_in()){
			
			if($this->input->post("submit")){
				
				$this->form_validation->set_rules("title","title","trim|required|min_length[10]|max_length[100]");
				$this->form_validation->set_rules("content","content","trim|required");
				$this->form_validation->set_rules("tags","tags","trim|required|callback_check_tags");
				
				if($this->form_validation->run() == false){
					
					$data = $this->sessions_model->get_user_data();
				
					$data["title"] = "Post question";
					
					$data["dropdown"] = array(
					"General" => "General",
					"Programming" => "Programming",
					"Lifestyle" => "Lifestyle",
					"Transport" => "Transport",
					"Entertainment" => "Entertainment",
					);
					
					$this->load->view('../includes/header.php', $data);
					$this->load->view('post_question_view');
					$this->load->view('../includes/footer.php');
				}
				else{
					
					$question = array(
						"question_id" => null,
						"user_id" => $this->sessions_model->get_user_id(),
						"title" => $this->input->post("title"),
						"content" => nl2br($this->input->post("content")),
						"category" => $this->input->post("category"),
						"date_posted" => null
					);
					
					$temp = explode(",",$this->input->post("tags"));
					$tags = array();
					$counter = 0;
					
					foreach($temp as $tag){
						$tags[$counter]["tag_id"] = null;
						$tags[$counter]["question_id"] = null;
						$tags[$counter]["tag"] = $tag;
						$counter++;
					}
					
					$question_id = $this->questions_model->post_question($question, $tags);
					
					$this->user_details_model->update_questions_count($question["user_id"]);
					//If the question was successfully stored then redirect to the view question function and passing the question id
					//When a user successfully posts a question they are redirected straight to the question view page for their new question
					if($question_id !== false){
						redirect("questions/view_question/" . $question_id);
						exit();
					}
				}
			}
			else{
				
				$data = $this->sessions_model->get_user_data();
				$data["title"] = "Post question";
				
				$data["dropdown"] = array(
					"General" => "General",
					"Programming" => "Programming",
					"Lifestyle" => "Lifestyle",
					"Transport" => "Transport",
					"Entertainment" => "Entertainment",
				);
				
				$this->load->view('../includes/header.php', $data);
				$this->load->view('post_question_view');
				$this->load->view('../includes/footer.php');
			}
		}
		else{
			
			redirect("/login");
			exit();
		}
	}
	/*******************************************
	* Callback function (see tags validation rule above) that checks to make sure that the tags for the question
	* do not contain illegal characters
	********************************************/
	public function check_tags($tags){
		if(preg_match("/^[A-Za-z0-9,+#_]+$/",$tags)){
			return true;
		}
		else{
			//Custom error message
			$this->form_validation->set_message('check_tags', "The %s field contains illegal characters.");
			return false;
		}
	}
	
	/*********************************************
	* Function that loads a simple view if an invalid question is requested
	* e.g. not passing a numeric value for the question_id
	*********************************************/
	public function invalid_question(){
	
		$data = $this->sessions_model->get_user_data();
		$data["title"] = "Invalid question";
		$data["error"] = "Invalid question.";
		
		$this->load->view('../includes/header.php', $data);
		$this->load->view('no_question_view');
		$this->load->view('../includes/footer.php');
	}
	
	/******************************************
	* Function called via AJAX which is used to delete a given question
	* as well as all associated answers and tags
	*******************************************/
	public function delete(){
		$question_id = $this->uri->segment(3);
		$deleted = $this->questions_model->delete_question($question_id);
		if($deleted){
			
			redirect("/home");
			exit();
		}
		else{
			exit();
		}
	}
}

?>