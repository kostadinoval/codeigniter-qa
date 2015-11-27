<div id="content">

<h3 class="categories_h3">List of categories with number of questions in each category</h3>

<?php
echo "<ul class=\"block_list\">";
foreach($categories as $category => $count){
	echo '<a href="https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/categories/get_questions/' . $category  . '"><li>' . $category . ' (' . $count . ')</li></a>';
}
echo "</ul>";
?>
</div>
