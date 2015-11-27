$(function(){
	//Add answers dynamically through AJAX
	$("#post_answer_form").submit(function(event){
		event.preventDefault();
		
		var question_id = $("#question").attr("data-question_id");
		var content = $("textarea").val();
		//disable the submit while ajax is running
		$("#submit").prop("disabled", true);
		$.ajax({
			url : "https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/answers/post_answer",
			type : "POST",
			data : { "question_id" : question_id, "content" : content },
			success : function(data){
				//If the answer was successfully saved then data.proceed would be set to true and so the nested AJAX call will run
				var data = JSON.parse(data);
				if(data.proceed){
					$.ajax({
						url : "https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/questions/view_question/"+question_id,
						type : "POST",
						data : { "view_with_ajax"  : true },
						success : function(answers_html){
							//Output the html
							$("#all_answers").html(answers_html);
							//Remove the answer form as the user has already posted an answer
							$("#answer_form_div").html("");
						}
					});
				}
				else{
					//If there was an error then display it and re-enable the submit button
					$(".errors").text(data.error);
					$("#submit").prop("disabled", false);
				}
			}
		});
	});
	
	//prevent form from submitting
	$("#search_term").keypress(function(event){
		//keycode 13 is the Enter key
		if(event.keyCode == 13){
			event.preventDefault();
		}
	});
	
	//guest search
	$("#search_term").keyup(function(event){
		
		var term = $("#search_term").val();
		
		if(term != "" && term != null){
			$.ajax({
				url : "https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/guest_search/term/"+encodeURIComponent(term),
				type : "GET",
				success : function(data){
					
					$("#target").html(data);				
				}
			});
		}
		else{
			
			$("#target").text("");
		}
	});
	
	//delete question
	$(".delete").click(function(event){
		
		var question_id = $("#question").attr("data-question_id");
		location.href = "https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/questions/delete/"+question_id;
	});
	
	//header search form
	$("#header_form").submit(function(event){
		event.preventDefault();
		var search_term = $("#header_input").val().trim();
		if(search_term != "" && search_term != null){
			
			location.href = "https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/search/term/" +encodeURIComponent(search_term);
		}
		else{
			return false;
		}
	});
});

/*
Bind a click event on the document for buttons with classes 'up' or 'down'
This is needed as answers generated through AJAX would not have this
event bound if it was setup just using the jQuery ready function
*/
$(document).on("click",".up,.down",function(){
	
	var class_ = $(this).attr("class");
	
	var result = class_.split(" ");
	var question_id = $("#question").attr("data-question_id");
	//extract the vote and answer_id from the class - example of a class = 'up 3' or 'down 5'
	//by splitting this class we can extract the vote and the id of the answer
	var vote = result[0];
	var answer_id = result[1];
	
	$.ajax({
		url : "https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/answers/update_rating",
		type : "POST",
		data : { "answer_id" : answer_id, "vote" : vote, "question_id" : question_id },
		success : function(){
			//After the vote was recorded we need to reload the answers so that they are re-ordered taking into account the
			//vote which may require some answers to be above/below others based on their rating
			$.ajax({
				url : "https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/questions/view_question/"+question_id,
				type : "POST",
				data : { "view_with_ajax"  : true },
				success : function(data){
					
					$("#all_answers").html(data);
				}
			});
		}
	});
});
