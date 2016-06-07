<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_administrators");
?>
<? include("../inc/begin.php");?>





<div class="back"><a href="index.php" class="btn btn-mini"><i class="icon-arrow-left"></i> <?=word_lang("back")?></a></div>



<h1><?=word_lang("Administrators")?> &mdash; <?=word_lang("stats")?>

<?
$sql="select login from people where id=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	echo(" &mdash; ".$rs->row["login"]);
}
?>

</h1>




<?
//Получаем текущую страницу
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

$kolvo=20;
$kolvo2=7;


//Выводим всех пользователей
$sql="select * from people_access where user=".(int)$_GET["id"]." order by accessdate desc";
$rs->open( $sql );
if(!$rs->eof)
{
	?>
	<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border=0 cellpadding=5 cellspacing=1 class="table_admin  table table-striped table-hover">
	<tr>
	<th><b>IP</b></th>
	<th><b><?=word_lang("date")?></b></th>
	</tr>
	<?
	$k=0;
	$tr=1;
	while(!$rs->eof)
	{
		if($k>$kolvo*($str-1)-1 and $k<$kolvo*$str)
		{
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
			<td><div class="link_ip"><?=$rs->row["ip"]?></div></td>
			<td class="gray"><?=date(datetime_format,$rs->row["accessdate"])?></td>
			</tr>
			<?
		}
		$k++;
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<?if($rs->rc>$kolvo){?>
	<br><p>
	<?=paging($rs->rc,$str,$kolvo,$kolvo2,"stats.php","&id=".(int)$_GET["id"])?></p><?}?>
	<?
}
?>




<? include("../inc/end.php");?>