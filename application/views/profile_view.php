<div id="content">
<?php
if(isset($status)){
echo '<span class="success">' . $status . '</span>';
}
if(isset($error)){
echo '<span class="errors">' . $error . '</span>';
}
echo '<span class="errors">' . validation_errors() . '</span>';
?>
<?php if(isset($logged_in) && $logged_in = true && $user["user_id"] == $user_id){?>
<div style="display:table;background_color:red;margin:15px auto;">
<form action="https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/profile/update_details" method="POST">

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;vertical-align:middle;"><b>Profile picture:</b></div>
<div style="display:table-cell;"><image class="picture" src="<?php echo $user['image_path']; ?>" /></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Username:</b></div>
<div style="display:table-cell;"><?php echo $user["username"]; ?></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Email:</b></div>
<div style="display:table-cell;"><input type="text" name="email" value="<?php echo $user['email']; ?>" /></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Current password:</b></div>
<div style="display:table-cell;"><input type="password" name="current_password" value="" /></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>New passsword:</b></div>
<div style="display:table-cell;"><input type="password" name="new_password" value="" /></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Confirm new passsword:</b></div>
<div style="display:table-cell;"><input type="password" name="confirm_new_password" value="" /></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Registration date:</b></div>
<div style="display:table-cell;"><?php echo date("d.m.Y", strtotime($user["registration_date"])); ?></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Number of questions:</b></div>
<div style="display:table-cell;"><?php echo $user["number_of_questions"]; ?></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Number of answer:</b></div>
<div style="display:table-cell;"><?php echo $user["number_of_answers"]; ?></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Rating:</b></div>
<div style="display:table-cell;"><?php echo $user["rating"]; ?></div>
</div>

<div style="margin:15px 0px;">
<input type="submit" name="submit" value="Update details"/>
</div>
</form>
</div>
<?php
}
else{
?>
<div style="display:table;background_color:red;margin:15px auto;">

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;vertical-align:middle;"><b>Profile picture:</b></div>
<div style="display:table-cell;"><image class="picture" src="<?php echo $user['image_path']; ?>" /></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Username:</b></div>
<div style="display:table-cell;"><?php echo $user["username"]; ?></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Registration date:</b></div>
<div style="display:table-cell;"><?php echo date("d.m.Y", strtotime($user["registration_date"])); ?></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Number of questions:</b></div>
<div style="display:table-cell;"><?php echo $user["number_of_questions"]; ?></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Number of answer:</b></div>
<div style="display:table-cell;"><?php echo $user["number_of_answers"]; ?></div>
</div>

<div style="display:table-row;">
<div style="display:table-cell;text-align:left;padding:5px 0px;"><b>Rating:</b></div>
<div style="display:table-cell;"><?php echo $user["rating"]; ?></div>
</div>

</div>
<?php
}
?>
</div>
