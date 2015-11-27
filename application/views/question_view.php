<div id="content">
<div class="break"></div>

<div class="question_table">
<div class="toolbar">
<?php
if(isset($logged_in) && $logged_in == true){
if(isset($user_id) && $user_id != $question[0]["user_id"]){
?>
<button class="save">Save</button>
<?php
}
if(isset($enable_delete_question) && $enable_delete_question == true){
?>
<button class="delete">Delete</button>
<?php
}
}
?>
</div>
<div class="cell">
<div id="question" data-question_id="<?php echo $question[0]["question_id"] ?>">
<div id="title">
<?php echo htmlspecialchars($question[0]["title"]); ?>
</div>
<div id="body">
<?php echo htmlspecialchars($question[0]["content"]); ?>
</div>
<div id="category">
<?php echo "<b>Category:</b> " . htmlspecialchars($question[0]["category"]); ?>
</div>
<div id="tags">
<?php echo "<b>Tags:</b> " . htmlspecialchars($question[0]["tags"]); ?>
</div>
</div>
</div>
<div class="user_info">
<div class="posted_by">
<p>Posted by: </p>
<?php echo "<a class=\"usernames\" href=\"https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/profile/view/" . $question[0]['user_id'] . "\">" . "<p><b>" . htmlspecialchars($question[0]["username"]) . "</b></p></a>"; ?>
</div>
<img class="picture" src="<?php echo $question[0]["image_path"]; ?>" />
<div class="posted_date">
<p>Date posted:</p>
<?php
$time = date("H:i:s", strtotime($question[0]["date_posted"]));
echo "<p><b>" . $time . "</b></p>"; 
$date = date("d.m.Y", strtotime($question[0]["date_posted"]));
echo "<p><b>" . $date . "</b></p>"; 
?>
</div>
</div>
</div>
<div id="all_answers">
<?php
if(isset($answers)){
	if(count($answers) == 0){
		echo "<h3 style=\"margin-bottom:15px;\"> No answers </h3>";
	}
	else{
		if(count($answers) == 1){
			echo "<h3> 1 answer </h3>";
		}
		else if(count($answers) > 1){
			echo "<h3>" . count($answers) . " answers</h3>";
		}
		foreach($answers as $answer){?>
			<div class="answer_table">
			<div class="toolbar">
			<?php
			if(!isset($user_id) || $user_id == $answer["user_id"] || $answer["enable_vote"] == false){?>
			<button class="hidden">up</button>
			<div class="rating"><b><?php echo $answer["rating"]; ?></b></div>
			<button class="hidden">down</button>
			<?php
			}
			else {
			?>
			<?php echo "<button class=\"up " . $answer["answer_id"] ."\">up</button>" ?>
			<div class="rating"><b><?php echo $answer["rating"]; ?></b></div>
			<?php echo "<button class=\"down " . $answer["answer_id"] . "\">down</button>"; ?>
			<?php
			}
			?>
			</div>
			<div class="cell">
			<div class="answer">
				<?php
					echo htmlspecialchars($answer["content"]);
				?>
			</div>
			</div>

			<div class="user_info">
			<div class="posted_by">
			<p>Posted by:</p>
				<?php
					echo "<a class=\"usernames\" href=\"https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/profile/view/" . $answer['user_id'] . "\">" . "<p><b>" . htmlspecialchars($answer["username"]) . "</b></p></a>";
				?>
			</div>
			<img class="picture" src="<?php echo $answer["image_path"]; ?>"/>
			<div class="posted_date">
			<p>Date posted:</p>
			<?php 
				$time = date("H:i:s", strtotime($answer["date_posted"]));
				echo "<p><b>" . $time . "</b></p>"; 
				$date = date("d.m.Y", strtotime($answer["date_posted"]));
				echo "<p><b>" . $date . "</b></p>";
			?>
			</div>
			</div>

			</div>
		<?php };
	}
}
?>
</div>
<div style="width:100%;" id="answer_form_div">
<div class="errors">
</div>
<?php
if(isset($logged_in) && $logged_in){
	if(isset($enable_post) && $enable_post){
		echo form_open("answers/post_answer", array("id" => "post_answer_form"));
		?>
		<label for="content">Post Answer:</label><br/><textarea style="min-height:150px;width:80%;resize:vertical;" name="content"><?php echo set_value("content"); ?></textarea>
		<br/>
		<div style="display:block;height:10px;"></div>
		<?php
		echo form_submit(array("id" => "submit", "name" => "submit"), "Post answer");

		echo form_close(); ?>
		<div style="display:block;height:10px;"></div>
		<?php
	}
}
?>
</div>
</div>
