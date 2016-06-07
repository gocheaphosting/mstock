<?$site="forgot";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<div class="page_internal">
<h1><?=word_lang("forgot password")?>:</h1>




<?if(isset($_GET["d"])){?><?if($_GET["d"]==1){?><p class="error"><b><?=word_lang("ferror")?></b></p><?}?><?}?>

<script>
	form_fields=new Array("email");
	fields_emails=new Array('1');
	error_message="<?=word_lang("Incorrect field")?>";
</script>
<script src="<?=site_root?>/inc/jquery.qtip-1.0.0-rc3.min.js"></script>

<?
$sql="select * from pages where link='forgot'";
$rs->open($sql);
if(!$rs->eof)
{echo(translate_text($rs->row["content"]));}
?>
<br><br>
<form method="POST" action="remind.php" onSubmit="return my_form_validate();">

<div class="form_field">
	<span><b><?=word_lang("e-mail")?>:</b></span>
	<input type="text" name="email" id="email" class="ibox form-control" value="" style="width:250px">
</div>

<div class="form_field">
	<input type="submit" value="<?=word_lang("send")?>"  class='isubmit'>
</div>

</form>

</div>
<?include("../inc/footer.php");?>