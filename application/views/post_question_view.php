<div id="content">

<div class="errors">
<?php
if(isset($error))
echo $error . "<br/>";
echo validation_errors();
?>
</div>
<?php
echo form_open("questions/post_question/");
?>
<label style="margin-right:10px;" for="title">Title:</label><input type="text" name="title" value="<?php echo set_value("title");?>" maxlength="50" size="50" />
</br>
<div style="display:block;height:10px;"></div>
<label for="content">Content:</label><br/><textarea style="resize:vertical;min-height:300px;width:60%;" name="content"><?php echo set_value("content"); ?></textarea>
</br>
<div style="display:block;height:10px;"></div>
<label style="margin-right:10px;"  for="category">Category:</label><?php echo form_dropdown("category",$dropdown,"General"); ?>
<br/>
<div style="display:block;height:10px;"></div>
<label style="margin-right:10px;"  for="tags">Tags - comma separated:</label><input type="text" name="tags" value="<?php echo set_value("tags");?>" maxlength="30" size="30" />
<br/>
<div style="display:block;height:10px;"></div>
<?php
echo form_submit("submit", "Post question"); ?>
<div style="display:block;height:10px;"></div>
<?php
echo form_close();
?>
</div>
