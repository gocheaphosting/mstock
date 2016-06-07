<?$site="testimonials";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>



<?
$type="for me";
?>



<?include("profile_top.php");?>



<h1><?=word_lang("testimonials")?> - <?=word_lang("for me")?></h1>

<?


//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];


//Количество страниц на странице
$kolvo2=k_str2;
?>




<?
$n=0;
$tr=1;
$sql="select id_parent,touser,fromuser,data,content from testimonials where touser='".result($_SESSION["people_login"])."' order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%">
	<tr>
	<th><?=word_lang("from")?>:</th>
	<th class='hidden-phone hidden-tablet'><?=word_lang("date")?>:</th>
	<th width="60%"><?=word_lang("content")?>:</th>
	</tr>
	<?
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?>>
			<td><?=show_user_avatar($rs->row["fromuser"],"login")?></td>
			<td nowrap class='hidden-phone hidden-tablet'><div class="link_date"><?=show_time_ago($rs->row["data"])?></div></td>
			<td nowrap><div id="c<?=$rs->row["id_parent"]?>" name="c<?=$rs->row["id_parent"]?>"><?=str_replace("\n","<br>",$rs->row["content"])?></div></td>
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
	echo(paging($n,$str,$kolvo,$kolvo2,"testimonials_for_me.php",""));
	}
	else
	{
		echo("<b>".word_lang("not found")."</b>");
	}
?>


<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>