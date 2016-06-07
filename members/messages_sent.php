<?$site="messages";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>


<?
$type="sent";
?>


<?include("profile_top.php");?>
<?


//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];


//Количество страниц на странице
$kolvo2=k_str2;
?>
<input type="button" value="<?=word_lang("new message")?>" class="profile_button" onClick="location.href='messages_new.php'">
<h1><?=word_lang("messages")?> - <?=word_lang("sentbox")?></h1>

<?

$sql="select id_parent,touser,fromuser,subject,data,viewed from messages where fromuser='".result($_SESSION["people_login"])."' order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%">
	<tr>
	<th><?=word_lang("to")?>:</th>
	<th width="50%"><?=word_lang("subject")?>:</th>
	<th class='hidden-phone hidden-tablet'><?=word_lang("date")?>:</th>
	</tr>
	<?
	$n=0;
	$tr=1;
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			?>
			<tr  <?if($tr%2==0){echo("class='snd'");}?>>
			<td nowrap><?=show_user_avatar($rs->row["touser"],"login")?></td>
			<td><div class="link_message"><a href="messages_content.php?m=<?=$rs->row["id_parent"]?>"><?if($rs->row["viewed"]==0){echo("<b>");}?><?=$rs->row["subject"]?></a></div></td>
			<td nowrap class='hidden-phone hidden-tablet'><div class="link_date"><?=show_time_ago($rs->row["data"])?></div></td>
			</tr>
			<?
		}
		$n++;
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	<?
	echo(paging($n,$str,$kolvo,$kolvo2,"messages_sent.php",""));
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>


<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>