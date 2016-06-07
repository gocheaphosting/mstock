<?
//Check access
admin_panel_access("users_newsletter");

if(!defined("site_root")){exit();}
?>


<form action="marketing_save.php" method="post">
<div class="admin_field">
<p>Here you can specify some other emails for a newsletter. They are not site user's emails.</p>
<?
$sql="select content from newsletter_emails";
$rs->open($sql);
if(!$rs->eof)
{
?>
	<textarea style="width:600px;height:150px;margin-left:6px" name="content"><?=$rs->row["content"]?></textarea>
	 <p><small> ";" - separator</small></p>
<?
}
?>
</div>

<div class="admin_field">
	<input type="submit" value="<?=word_lang("save")?>"  class="btn btn-primary">
</div>
</form>
