<?$site="messages";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>









<?include("profile_top.php");?>
<?
$type="new";
?>

<h1><?=word_lang("messages")?> - <?=word_lang("new message")?></h1>




<script>
	form_fields=new Array("to","subject","content");
	fields_emails=new Array(0,0,0);
	error_message="<?=word_lang("Incorrect field")?>";
</script>
<script src="<?=site_root?>/inc/jquery.qtip-1.0.0-rc3.min.js"></script>





<?
if(!isset($_GET["d"]) or $_GET["d"]==2)
{
	$touser="";
	$subject="";
	$content="";


	if(isset($_GET["m"]))
	{
		$sql="select touser,subject,content,data,id_parent,fromuser from messages where id_parent=".(int)$_GET["m"]." and touser='".result($_SESSION["people_login"])."'";
		$rs->open($sql);
		if(!$rs->eof)
		{
			$touser=$rs->row["fromuser"];
			$subject="Re: ".$rs->row["subject"];
			$content="\n\n\n\n\n\n".word_lang("you wrote").": ".date(datetime_format,$rs->row["data"])."\n".$rs->row["content"];
		}
	}

	if(isset($_GET["user"]))
	{
		$sql="select friend1,friend2 from friends where friend1='".result($_SESSION["people_login"])."' and friend2='".result3($_GET["user"])."'";
		$rs->open($sql);
		if($rs->eof)
		{
			$sql="insert into friends (friend1,friend2) values ('".result($_SESSION["people_login"])."','".result3($_GET["user"])."')";
			$db->execute($sql);
		}
		$touser=result3($_GET["user"]);
	}




?>






<form method="post" action="messages_add.php" onSubmit="return my_form_validate();">



<div class="form_field">
<span><b><?=word_lang("to")?>:</b></span>

<select name="to" id="to" style="width:200px" class='ibox form-control'><option value=""></option>
<?
$sql="select a.login,b.friend1,b.friend2 from users a,friends b where a.login=b.friend2 and b.friend1='".result($_SESSION["people_login"])."' order by a.login";
$rs->open($sql);
while(!$rs->eof)
{
$sel="";
if($touser==$rs->row["friend2"]){$sel="selected";}
?>
<option value="<?=$rs->row["friend2"]?>" <?=$sel?>><?=show_user_name($rs->row["friend2"])?></option>
<?
$rs->movenext();
}
?>

</select>
</div>


<div class="form_field">
<span><?=word_lang("cc")?>:</span>

<select name="cc" style="width:200px" class='ibox form-control'><option value=""></option>
<?
$sql="select a.login,b.friend1,b.friend2 from users a,friends b where a.login=b.friend2 and b.friend1='".result($_SESSION["people_login"])."' order by a.login";
$rs->open($sql);
while(!$rs->eof)
{
?>
<option value="<?=$rs->row["friend2"]?>"><?=show_user_name($rs->row["friend2"])?></option>
<?
$rs->movenext();
}
?>

</select>
</div>


<div class="form_field">
<span><?=word_lang("bcc")?>:</span>

<select name="bcc" style="width:200px" class='ibox form-control'><option value=""></option>
<?
$sql="select a.login,b.friend1,b.friend2 from users a,friends b where a.login=b.friend2 and b.friend1='".result($_SESSION["people_login"])."' order by a.login";
$rs->open($sql);
while(!$rs->eof)
{
?>
<option value="<?=$rs->row["friend2"]?>"><?=show_user_name($rs->row["friend2"])?></option>
<?
$rs->movenext();
}
?>

</select>
</div>


<div class="form_field">
<span><b><?=word_lang("subject")?>:</b></span>
<input class='ibox form-control' type="text" style="width:400px" name="subject"  id="subject" value="<?=$subject?>">
</div>





<div class="form_field">
<span><b><?=word_lang("content")?>:</b></span>
<textarea name="content" id="content" style="width:400px;height:250px" class='ibox form-control'><?=$content?></textarea>
</div>



<div class="form_field">
<input class='isubmit' type="submit" value="<?=word_lang("send")?>">
</div>

</form>

<p>* <?=word_lang("friend email")?></p>



<?}else{?>
<p><b><?=word_lang("sent")?><b></p>

<?}?>





<?include("profile_bottom.php");?>























<?include("../inc/footer.php");?>