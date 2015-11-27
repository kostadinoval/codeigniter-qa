<div id="content">

<h3 class="categories_h3"><? if(isset($title)){echo "Questions in the \"" . $category . "\" category" ;} ?></h3>

<?php 
	if(isset($questions) && $questions != false){

	echo "<ul class=\"block_list\">";
	foreach($questions as $question){
		
		echo "<a href=\"
		https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/questions/view_question/" . $question["question_id"] . "\"><li>"
		. htmlspecialchars($question["title"]) . "</li></a>";
	}
	echo "</ul>";
	
	}
	else{
		echo "<p>No questions in this cateogry</p>";
	}
?>
</div>
