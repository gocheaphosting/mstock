<?$site="contacts";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<div class="page_internal">
<h1><?=word_lang("contacts")?>:</h1>

<?
$sql="select * from pages where link='/contacts/'";
$rs->open($sql);
if(!$rs->eof)
{echo(translate_text($rs->row["content"]));}
?>


<script>
	form_fields=new Array("name","email","question","rn1");
	fields_emails=new Array(0,1,0,0);
	error_message="<?=word_lang("Incorrect field")?>";
</script>
<script src="<?=site_root?>/inc/jquery.qtip-1.0.0-rc3.min.js"></script>

<?
if(isset($_GET["d"]))
{
echo(word_lang("<p><b>Error. Incorrect captcha.</b></p>"));
}

$question="";
if(isset($_GET["file_id"]))
{
	$question=word_lang("I would like to purchase the file")." #".(int)$_GET["file_id"];
}
?>


<form method="post" action="send.php" onSubmit="return my_form_validate();">

<div class="form_field">
<span><b><?=word_lang("full name")?></b></span>
<input type="text" name="name" id="name" class="ibox form-control" value="<?=@$_SESSION["people_name"]?>" style="width:250px">
</div>

<div class="form_field">
<span><b><?=word_lang("e-mail")?></b></span>
<input type="text" name="email" id="email" class="ibox form-control" style="width:250px" value="<?=@$_SESSION["people_email"]?>">
</div>

<div class="form_field">
<span><?=word_lang("telephone")?>:</span>
<input type="text" name="telephone" class="ibox form-control" style="width:250px">
</div>

<div class="form_field">
<span><?=word_lang("contact method")?>:</span>
<select name="method" class="ibox form-control" style="width:250px"><option value="by e-mail""><?=word_lang("by e-mail")?><option value="by phone""><?=word_lang("by phone")?></select>
</div>

<div class="form_field">
<span><b><?=word_lang("question")?>:</b></span>
<textarea name="question" id="question" class="ibox form-control" style="width:500px;height:150px"><?=$question?></textarea>
</div>


<div class="form_field">
<?
	//Show captcha
	require_once('../admin/function/recaptchalib.php');
	echo(show_captcha());
?>
</div>

<div class="form_field">
<input type="submit" value="<?=word_lang("send")?>" class="isubmit">
</div>

</form>


</div>

<?include("../inc/footer.php");?>