<div id="content">

<div class="errors">
<?php
if(isset($error))
echo $error . "<br/>";
echo validation_errors();
?>
</div>
<?php
echo form_open("register/validate");
?>
<label style="margin-right:10px;" for="username">Username:</label><input type="text" name="username" value="<?php echo set_value('username');?>" maxlength="20" size="15"  />
</br>
<div style="display:block;height:10px;"></div>
<label style="margin-right:10px;" for="email">Email:</label><input type="text" name="email" value="<?php echo set_value('email');?>" maxlength="100" size="20"  />
</br>
<div style="display:block;height:10px;"></div>
<label style="margin-right:10px;" for="password">Password:</label><input type="password" name="password" value="" maxlength="30" size="15"  />
</br>
<div style="display:block;height:10px;"></div>
<label style="margin-right:10px;" for="confirm_password">Confirm Password:</label><input type="password" name="confirm_password" value="" maxlength="30" size="15"  /></br>
<div style="display:block;height:10px;"></div>
<?php
echo form_submit("submit", "Register"); ?>
<div style="display:block;height:10px;"></div>
<?php
echo form_close();
?>
</div>
