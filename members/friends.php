<?$site="friends";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>




<?include("profile_top.php");?>

<h1><?=word_lang("friends")?></h1>



<?
$n=1;
$sql="select friend1,friend2 from friends where friend1='".result($_SESSION["people_login"])."' order by friend2";
$rs->open($sql);
if(!$rs->eof)
{
	while(!$rs->eof)
	{
		?>
		<div style="margin-right:50px;padding-bottom:20px;width:200px;float:left">
		<?=show_user_avatar($rs->row["friend2"],"login")?> [<a href="friends_delete.php?user=<?=$rs->row["friend2"]?>"  onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a>]
		</div>
		<?
		$n++;
		$rs->movenext();
	}
}
else
{
	?>
	<p><b><?=word_lang("not found")?></b></p>
	<?
}
?>



<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>