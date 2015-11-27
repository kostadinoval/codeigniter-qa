<div id="content">
<div id="recent_questions">
<div style="display:block;height:10px;"></div>
<h3>Recent Questions</h3>
<?php 
	echo "<ul id=\"questions-ul\">";
	foreach($questions as $question){
		
		echo "<li id=\"" . $question["question_id"] . "\"><a href=\"
		https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/questions/view_question/" . $question["question_id"] . "\"><span><b>"
		. htmlspecialchars($question["title"]) . "</b></span></a></li>";
	}
	echo "</ul>";
?>
<div style="display:block;height:10px;"></div>
</div>


<div id="top_categories">
<div style="display:block;height:10px;"></div>
<h3>Top categories</h3>
<?php
foreach($top_categories as $category){
	echo "<p style=\"margin-bottom:10px;\"><a style=\"text-decoration:none;color:blue;\" href=\"https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/categories/get_questions/" . $category["category"] . "\"><b>" . $category["category"] . "</b> " . "(<b>" . $category["number"] . "</b>)</a></p>"; 
}
?>
<div style="display:block;height:5px;"></div>
</div>


<div id="top_users">
<div style="display:block;height:10px;"></div>
<h3>Top users</h3>
<?php
foreach($top_users as $user){
	echo "<p style=\"margin-bottom:10px;\"><a style=\"text-decoration:none;color:blue;\" href=\"https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/profile/view/" . $user["user_id"] . "\">" . "<b>" . $user["username"] . "</b> " . "(<b>" . $user["rating"] . "</b>)</a></p>"; 
}
?>
<div style="display:block;height:5px;"></div>
</div>



<div id="number_of_questions">
<div style="display:block;height:10px;"></div>
<h3>Number of questions</h3>
<span"><?php echo $number_of_questions;?></span>
<div style="display:block;height:10px;"></div>
</div>

<div id="number_of_users_online">
<div style="display:block;height:10px;"></div>
<h3>Number of users online</h3>
<span><?php echo $number_of_users_online;?></span>
<div style="display:block;height:10px;"></div>
</div>

</div>
