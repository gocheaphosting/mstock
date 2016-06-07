<?$site="support";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>

<h1><?=word_lang("open a support request")?></h1>

<script>
	form_fields=new Array("subject","message");
	fields_emails=new Array(0,0);
	error_message="<?=word_lang("Incorrect field")?>";
</script>
<script src="<?=site_root?>/inc/jquery.qtip-1.0.0-rc3.min.js"></script>

<form method="post" action="support_add.php" onSubmit="return my_form_validate();">

<div class="form_field">
	<span><b><?=word_lang("subject")?>:</b></span>
	<input type="text" class="ibox form-control" style="width:300px" name="subject" id="subject" value="">
</div>

<div class="form_field">
	<span><b><?=word_lang("content")?>:</b></span>
	<textarea class="ibox form-control" style="width:600px;height:250px" name="message" id="message"></textarea>
</div>



<div class="form_field">
	<input type="submit" class="isubmit" value="<?=word_lang("send")?>">
</div>
	
</form>


<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>