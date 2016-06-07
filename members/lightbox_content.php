<?$site="lightbox";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>

<h1><?=word_lang("my favorite list")?>
<?
if(isset($_GET["id"]))
{
	echo(" &mdash; ".word_lang("edit"));
}
else
{
	echo(" &mdash; ".word_lang("add"));
}



$users_field=array();
$users_field["title"]="";
$users_field["description"]="";

if(isset($_GET["id"]))
{
	$id=(int)$_GET["id"];
	
	//Check
	$sql="select id_parent,user_owner from lightboxes_admin where user=".(int)$_SESSION["people_id"]." and id_parent=".$id." and  user_owner=1";
	$rs->open($sql);
	if($rs->eof)
	{
		exit();
	}
	
	$sql="select title,description from lightboxes where id=".$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$users_field["title"]=$rs->row["title"];
		$users_field["description"]=$rs->row["description"];
	}
}
else
{
	$id=0;
}
?>
</h1>

<script>
	form_fields=new Array("title");
	fields_emails=new Array(0,0);
	error_message="<?=word_lang("Incorrect field")?>";
</script>
<script src="<?=site_root?>/inc/jquery.qtip-1.0.0-rc3.min.js"></script>

<form method="post" action="lightbox_edit.php?id=<?=$id?>" onSubmit="return my_form_validate();">

<div class="form_field">
	<span><b><?=word_lang("title")?>:</b></span>
	<input type="text" class="ibox form-control" style="width:300px" name="title" id="title" value="<?=$users_field["title"]?>">
</div>

<div class="form_field">
	<span><b><?=word_lang("description")?>:</b></span>
	<textarea class="ibox form-control" style="width:300px;height:70px" name="description" id="description"><?=$users_field["description"]?></textarea>
</div>

<div class="form_field">
	<span><b><?=word_lang("administrators")?>*:</b></span>

<?
$n=1;
$sql="select friend1,friend2 from friends where friend1='".result($_SESSION["people_login"])."' order by friend2";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<table border="0" cellpadding="0" cellspacing="0"> 
	<tr>
	<?
	while(!$rs->eof)
	{
		$user_id=0;
		$sql="select id_parent from users where login='".$rs->row["friend2"]."'";
		$dr->open($sql);
		if(!$dr->eof)
		{
			$user_id=$dr->row["id_parent"];
		}
		
		$sel="";
		$sql="select id_parent from lightboxes_admin where user=".$user_id." and id_parent=".$id;
		$dr->open($sql);
		if(!$dr->eof)
		{
			$sel="checked";
		}
		
		if($n%4==0){echo("</tr><tr valign=top>");}
		?>
		<td style="padding-right:50px;padding-bottom:20px">
		<input type="checkbox" name="user<?=$user_id?>" <?=$sel?>>&nbsp;<?=show_user_name($rs->row["friend2"],"login")?>
		</td>
		<?
		$n++;
		$rs->movenext();
	}
	?>
	</tr>
	</table>
	<?
}
else
{
	?>
	<p><b><?=word_lang("not found")?></b></p>
	<?
}
?>	

</div>


<div class="form_field">
	<input type="submit" class="isubmit" value="<?=word_lang("save")?>">
</div>
	
</form>

<p>* You may assign only your friends to the administrators.</p>

<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>