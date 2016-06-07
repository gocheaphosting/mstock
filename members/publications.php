<?$site="publications";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?include("../inc/header.php");?>



<? include("../admin/function/show.php");?>


<?
if(isset($_GET["d"]))
{
	$d=(int)$_GET["d"];
}

$scategory=false;
$sphoto=false;
$svideo=false;
$saudio=false;
$svector=false;
$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$rs->open($sql);
if(!$rs->eof)
{
if($rs->row["category"]==1){$scategory=true;}
if($rs->row["upload"]==1){$sphoto=true;}
if($rs->row["upload2"]==1){$svideo=true;}
if($rs->row["upload3"]==1){$saudio=true;}
if($rs->row["upload4"]==1){$svector=true;}
}





?>



<?
if($scategory==true)
{
	if(!isset($_GET["d"]))
	{
		$d=1;
	}
}

if($svector==true and $global_settings["allow_vector"]==1)
{
	if(!isset($_GET["d"]))
	{
		$d=5;
	}
}

if($saudio==true and $global_settings["allow_audio"]==1)
{
	if(!isset($_GET["d"]))
	{
		$d=4;
	}
}

if($svideo==true and $global_settings["allow_video"]==1)
{
	if(!isset($_GET["d"]))
	{
		$d=3;
	}
}


if($sphoto==true and $global_settings["allow_photo"]==1)
{
	if(!isset($_GET["d"]))
	{
		$d=2;
	}
}
?>


<?include("profile_top.php");?>


<h1><?=word_lang("my publications")?> &mdash; 
<?
if($d==1){echo(word_lang("categories"));}
if($d==2){echo(word_lang("photos"));}
if($d==3){echo(word_lang("videos"));}
if($d==4){echo(word_lang("audio"));}
if($d==5){echo(word_lang("vector"));}
?>
</h1>











<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];


//Количество страниц на странице
$kolvo2=k_str2;






if(isset($_GET["status"])){$pstatus=(int)$_GET["status"];}
else{$pstatus=-1;}



if(isset($_GET["pid"])){$pid=(int)$_GET["pid"];}
else{$pid=0;}

if(isset($_GET["ptitle"])){$ptitle=(int)$_GET["ptitle"];}
else{$ptitle=0;}

if(isset($_GET["pviewed"])){$pviewed=(int)$_GET["pviewed"];}
else{$pviewed=0;}

if(isset($_GET["pdownloads"])){$pdownloads=(int)$_GET["pdownloads"];}
else{$pdownloads=0;}

if(isset($_GET["pdata"])){$pdata=(int)$_GET["pdata"];}
else{$pdata=2;}

//Status
$com="";

if($pstatus>=0)
{
	if($pstatus==2)
	{
		$com=" and published=-1";
	}
	else
	{
		$com=" and published=".$pstatus;
	}
}


//Sort
$com2="";
if($pdownloads==1){$com2=" order by downloaded";}
if($pdownloads==2){$com2=" order by downloaded desc";}
if($pid==1){$com2=" order by id_parent";}
if($pid==2){$com2=" order by id_parent desc";}
if($ptitle==1){$com2=" order by title";}
if($ptitle==2){$com2=" order by title desc";}
if($pviewed==1){$com2=" order by viewed";}
if($pviewed==2){$com2=" order by viewed desc";}
if($pdata==1){$com2=" order by data";}
if($pdata==2){$com2=" order by data desc";}





$mstatus=array();
$mstatus["all"]=-1;
$mstatus["approved"]=1;
$mstatus["pending"]=0;
$mstatus["declined"]=2;

$varsort="&pid=".$pid."&ptitle=".$ptitle."&pviewed=".$pviewed."&pdownloads=".$pdownloads."&pdata=".$pdata;

if($pid==1){$varsort_id="&pid=2&ptitle=0&pviewed=0&pdownloads=0&pdata=0";}
elseif($pid==2){$varsort_id="&pid=1&ptitle=0&pviewed=0&pdownloads=0&pdata=0";}
else{$varsort_id="&pid=1&ptitle=0&pviewed=0&pdownloads=0&pdata=0";}


if($ptitle==1){$varsort_title="&pid=0&ptitle=2&pviewed=0&pdownloads=0&pdata=0";}
elseif($ptitle==2){$varsort_title="&pid=0&ptitle=1&pviewed=0&pdownloads=0&pdata=0";}
else{$varsort_title="&pid=0&ptitle=1&pviewed=0&pdownloads=0&pdata=0";}


if($pviewed==1){$varsort_viewed="&pid=0&ptitle=0&pviewed=2&pdownloads=0&pdata=0";}
elseif($pviewed==2){$varsort_viewed="&pid=0&ptitle=0&pviewed=1&pdownloads=0&pdata=0";}
else{$varsort_viewed="&pid=0&ptitle=0&pviewed=2&pdownloads=0&pdata=0";}


if($pdownloads==1){$varsort_downloads="&pid=0&ptitle=0&pviewed=0&pdownloads=2&pdata=0";}
elseif($pdownloads==2){$varsort_downloads="&pid=0&ptitle=0&pviewed=0&pdownloads=1&pdata=0";}
else{$varsort_downloads="&pid=0&ptitle=0&pviewed=0&pdownloads=2&pdata=0";}

if($pdata==1){$varsort_data="&pid=0&ptitle=0&pviewed=0&pdownloads=0&pdata=2";}
elseif($pdata==2){$varsort_data="&pid=0&ptitle=0&pviewed=0&pdownloads=0&pdata=1";}
else{$varsort_data="&pid=0&ptitle=0&pviewed=0&pdownloads=0&pdata=2";}

if($d==2){$table="photos";}
if($d==3){$table="videos";}
if($d==4){$table="audio";}
if($d==5){$table="vector";}


?>

<?if($d!=1){?>
<div style="margin-bottom:20px;margin-top:5px">
<?
foreach ($mstatus as $key => $value)
{
?><a href="publications.php?d=<?=$d?>&status=<?=$value?><?=$varsort?>" class="<?if($value==$pstatus){echo("a");}?>sortmenu"><?=word_lang($key)?></a><?
}
?>
</div>
<?}?>



<?if($d==1 and $scategory==true){include("publications_category.php");}?>
<?if($d==2 and $sphoto==true){include("publications_content.php");}?>
<?if($d==3 and $svideo==true){include("publications_content.php");}?>
<?if($d==4 and $saudio==true){include("publications_content.php");}?>
<?if($d==5 and $svector==true){include("publications_content.php");}?>
















<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>