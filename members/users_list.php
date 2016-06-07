<?$site="userlist";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<div class="page_internal">


<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


//Количество новостей на странице
$kolvo=60;


//Количество страниц на странице
$kolvo2=k_str2;
?>


<h1><?=word_lang("photographers")?></h1>

<div class="seller_menu">
<?
$alfavit=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
//$alfavit=array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Э','Ю','Я','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

foreach($alfavit as $key => $value)
{
	$sel="";
	if(isset($_GET["letter"]) and (int)$_GET["letter"]==$key){$sel="class='seller_menu_active'";}
	?>
	<a href="users_list.php?letter=<?=strtolower($key)?>" <?=$sel?>><?=$value?></a>
	<?
}

?>
</div>

<div class="vertical_line">&nbsp;</div>



<?
$com="";
$com2="";
$aletter="";
if(isset($_GET["letter"]))
{
	if($global_settings["show_users_type"]=="name")
	{
		$com=" and name like '".$alfavit[(int)$_GET["letter"]]."%'";
	}
	else
	{
		$com=" and login like '".$alfavit[(int)$_GET["letter"]]."%'";
	}
	
	$aletter="&letter=".(int)$_GET["letter"];
}

if($global_settings["show_users_type"]=="name")
{
	$com2=" order by name";
}
else
{
	$com2=" order by login";
}



$n=0;
$sql="select id_parent,avatar,login from users where (utype='seller' or utype='common') ".$com.$com2;
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div style="clear:both;">
	<?
	while(!$rs->eof)
	{
		$qty=0;

		if($global_settings["allow_photo"])
		{
			$sql="select count(*) as count_rows from photos where published=1 and author='".$rs->row["login"]."'";
			$ds->open($sql);
			$qty+=$ds->row["count_rows"];
		}

		if($global_settings["allow_video"])
		{
			$sql="select count(*) as count_rows from videos where published=1 and author='".$rs->row["login"]."'";
			$ds->open($sql);
			$qty+=$ds->row["count_rows"];
		}

		if($global_settings["allow_audio"])
		{
			$sql="select count(*) as count_rows from audio where published=1 and author='".$rs->row["login"]."'";
			$ds->open($sql);
			$qty+=$ds->row["count_rows"];
		}

		if($global_settings["allow_vector"])
		{
			$sql="select count(*) as count_rows from vector where published=1 and author='".$rs->row["login"]."'";
			$ds->open($sql);
			$qty+=$ds->row["count_rows"];
		}
		
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{	
			if($qty>0)
			{
				?>
				<div style="padding-right:50px;width:300px;float:left;height:50px" class="seller_list">
				<?=show_user_avatar($rs->row["login"],"login")?>&nbsp;<span>(<?=$qty?> files)</span></div>
				<?
			}
		}
		if($qty>0)
		{
			$n++;
		}
		$rs->movenext();
	}
	?>
	</div>
	<div style="clear:both;"></div>
	<p><?echo(paging($n,$str,$kolvo,$kolvo2,site_root."/members/users_list.php",$aletter));?></p>
	<?
}
else
{
	echo("<p><b>".word_lang("not found")."</b></p>");
}
?>
</div>
<div style="clear:both;"></div>
<?include("../inc/footer.php");?>