<?
//Check access
admin_panel_access("settings_storage");


if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>When you use <b>Rackspace clouds</b> or <b>Amazom S3</b> all files are stored on the <b>local server</b> first.

<p>
You must set a <b>Cron task</b> in your hostings account and a special script will move the media files from the local server to the clouds one with some periodicity. It will work independantly and simplify a content upload process.</p>

<p>
You can find the cron script here:
</p>

<ul>
<li>
<b><?=site_root?>/members/cron_rackspace.php</b><br>for Rackspace Clouds
</li>

<li>
<b><?=site_root?>/members/cron_amazon.php</b></br>for Amazon S3
</li>
</ul>

<p>
You should <b>rename</b> the scripts for <b>security reasons</b> on ftp.
</p>

<p>The cron command's syntax depends on the server's settings.
We advice to use the commands which ping cron's URL - not physical path to the cron php file. </br>
</p>

<p><b>Examples of the cron commands:</b></p>

<ul>
<li>/usr/bin/lynx -source <?=surl?><?=site_root?>/members/cron_amazon.php</li>
<li>GET <?=surl?><?=site_root?>/members/cron_amazon.php > /dev/null</li>
</ul>



<p>
Also you have to check the <a href="../phpini/">php.ini settings</a>:<br>
<b>max_execution_time</b>
</p>

</div>
<div class="subheader">Cron Stats</div>
<div class="subheader_text">





<?
//Get Search
$search="";
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if(isset($_POST["search"])){$search=result($_POST["search"]);}

//Get Search type
$search_type="";
if(isset($_GET["search_type"])){$search_type=result($_GET["search_type"]);}
if(isset($_POST["search_type"])){$search_type=result($_POST["search_type"]);}





//Items
$items=30;
if(isset($_GET["items"])){$items=(int)$_GET["items"];}
if(isset($_POST["items"])){$items=(int)$_POST["items"];}


//Search variable
$var_search="search=".$search."&search_type=".$search_type."&items=".$items;






//Sort by date
$adate=0;
if(isset($_GET["adate"])){$adate=(int)$_GET["adate"];}



//Sort by ID
$aid=0;
if(isset($_GET["aid"])){$aid=(int)$_GET["aid"];}

//Sort by default
if($adate==0 and $aid==0)
{
$adate=2;
}



//Add sort variable
$com="";


if($adate!=0)
{
	$var_sort="&adate=".$adate;
	if($adate==1){$com=" order by data ";}
	if($adate==2){$com=" order by data desc ";}
}



if($aid!=0)
{
	$var_sort="&aid=".$aid;
	if($aid==1){$com=" order by publication_id ";}
	if($aid==2){$com=" order by publication_id desc ";}
}








//Items on the page
$items_mass=array(10,20,30,50,75,100);




//Search parameter
$com2="";

if($search!="")
{
	if($search_type=="id")
	{
		$com2.=" and publication_id=".(int)$search." ";
	}
}





//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select publication_id,data,logs from filestorage_logs where publication_id>0 ";


$sql.=$com2.$com;

$rs->open($sql);
$record_count=$rs->rc;





//limit
$lm=" limit ".($kolvo*($str-1)).",".$kolvo;




$sql.=$lm;

//echo($sql);
$rs->open($sql);

?>
<div id="catalog_menu">


<form method="get" action="index.php?d=5" style="margin:0px">
<div class="toleft">
<span><?=word_lang("search")?>:</span>
<input type="text" name="search" style="width:200px" class="ft" value="<?=$search?>" onClick="this.value=''"  value="Publication ID">
<input type="hidden" name="search_type" value="id">
</div>















<div class="toleft">
<span><?=word_lang("page")?>:</span>
<select name="items" style="width:70px" class="ft">
<?
for($i=0;$i<count($items_mass);$i++)
{
$sel="";
if($items_mass[$i]==$items){$sel="selected";}
?>
<option value="<?=$items_mass[$i]?>" <?=$sel?>><?=$items_mass[$i]?></option>
<?
}
?>

</select>
</div>

<div class="toleft">
<span>&nbsp;</span>
<input type="submit" class="btn btn-danger" value="<?=word_lang("search")?>">
</div>

<div class="toleft_clear"></div>
</form>


</div>



<?





if(!$rs->eof)
{
?>


<div style="padding-bottom:15px"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&d=5&".$var_search.$var_sort));?></div>





<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover">
<tr>
<th>
<a href="index.php?d=5&<?=$var_search?>&aid=<?if($aid==2){echo(1);}else{echo(2);}?>">ID</a> <?if($aid==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($aid==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>



<th>
<a href="index.php?d=5&<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>
<th><?=word_lang("files")?></th>
<th width="60%">Logs</th>
	
</tr>
<?
$tr=1;
while(!$rs->eof)
{

$sql="select id from structure where id=".$rs->row["publication_id"];
$ds->open($sql);
if($ds->eof)
{
	$sql="delete from filestorage_logs where publication_id=".$rs->row["publication_id"];
	$db->execute($sql);
	$rs->movenext();
}

?>
<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
<td>

<a href="../catalog/content.php?id=<?=$rs->row["publication_id"]?>"><?=$rs->row["publication_id"]?></a></td>


<td><?=date(datetime_format,$rs->row["data"])?></td>
<td>
<?
$sql="select id_parent,filename2,filesize,url from filestorage_files where id_parent=".$rs->row["publication_id"];
$ds->open($sql);
while(!$ds->eof)
{
?>
<a href="<?=$ds->row["url"]?>/<?=$ds->row["filename2"]?>"><?=$ds->row["filename2"]?></a> [<?=float_opt(($ds->row["filesize"]/1024/1024),3)?> Mb.]<br>
<?
$ds->movenext();
}

?>
</td>
<td><small><?=$rs->row["logs"]?></small></td>

</tr>
<?
$tr++;
$n++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>




<div style="padding-top:25px;"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&d=5".$var_search.$var_sort));?></div>
<?
}
else
{
echo("<p><b>".word_lang("not found")."</b></p>");
}
?>
</div>