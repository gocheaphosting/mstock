<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_blockedip");


//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;

?>
<? include("../inc/begin.php");?>


<a class="btn btn-danger toright" href="remove_all.php"><i class="icon-trash icon-white"></i> <?=word_lang("remove all")?></a>


<h1><?=word_lang("blocked ip")?>:</h1>




<form method="post" action="add.php" style="margin-bottom:30px">
IP: <input type='text' name="ip" value="" style="width:150px;display:inline">&nbsp;<input type="submit" class="btn btn-success" value="<?=word_lang("add")?>" style="display:inline">
</form>




<?
//Выводим всех пользователей
$sql="select * from users_ip_blocked  order by data desc";
$rs->open( $sql );
if(!$rs->eof)
{
?>
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover">
<tr>
<th><b><?=word_lang("ip")?></b></th>
<th class="hidden-phone hidden-tablet"><b><?=word_lang("login")?></b></th>
<th class="hidden-phone hidden-tablet"><b><?=word_lang("date")?></b></th>
<th><b><?=word_lang("delete")?></b></th>
</tr>
<?
$n=0;
$tr=1;
while(!$rs->eof)
{
if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
{



?>
<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
<td><div class="link_ip"><?=$rs->row["ip"]?></div></td>
<td>
<?
$sql="select id_parent from  users where login='".$rs->row[ "login" ]."'";
$ds->open($sql);
if(!$ds->eof)
{
	?>
		<div class="link_login"><a href='../customers/content.php?id=<?=$ds->row["id_parent"]?>'><?=$rs->row["login"]?></a></div>
	<?
}
else
{
	echo("&mdash;");
}
?>
</td>
<td class="gray hidden-phone hidden-tablet"><?=date(date_format,$rs->row["data"])?></td>
<td>
<div class="link_delete"><a href='delete.php?ip=<?=$rs->row[ "ip" ]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
</td>
</tr>
<?
}
$tr++;
$n++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>
<?
echo(paging($n,$str,$kolvo,$kolvo2,"index.php",""));
}
else
{
?>
<b><?=word_lang("not found")?></b>
<?
}
?>

























<? include("../inc/end.php");?>