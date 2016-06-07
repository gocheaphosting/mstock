<?$site="messages";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>









<?include("profile_top.php");?>
<?
$type="content";
?>

<h1><?=word_lang("messages")?></h1>





<?
$sql="select touser,fromuser,subject,content,data,viewed,id_parent from messages where id_parent=".(int)$_GET["m"]." and (touser='".result($_SESSION["people_login"])."' or fromuser='".result($_SESSION["people_login"])."')";
$rs->open($sql);
if(!$rs->eof)
{
?>



<table border="0" cellpadding="0" cellspacing="0">
<tr>

<td><?
				if($rs->row["fromuser"]!="Site Administration")
				{
					echo(show_user_avatar($rs->row["fromuser"],"login"));
				}
				else
				{
					echo("<b>".$rs->row["fromuser"]."</b>");
				}
				?></td>



<td style="padding-left:80px">
<div class="link_message"> <?=$rs->row["subject"]?></div>
</td>


<td style="padding-left:80px"><div class="link_date"><?=date(datetime_format,$rs->row["data"])?></div></td>
</tr>
</table>


<div class="t" style="width:100%;margin:10px 0px 15px -6px;display:block"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
<div style="width:700px"><?=str_replace("\n","<br>",$rs->row["content"])?></div>
</div></div></div></div></div></div></div></div>


<?if($rs->row["touser"]==$_SESSION["people_login"]){

$sql="update messages set viewed=1 where id_parent=".$rs->row["id_parent"];
$db->execute($sql);

?>
<input class='isubmit' type="button" value="<?=word_lang("reply")?>" onclick="location.href='messages_new.php?m=<?=$rs->row["id_parent"]?>'" style="margin-top:4px">
<?}?>

<?}?>





<?include("profile_bottom.php");?>























<?include("../inc/footer.php");?>