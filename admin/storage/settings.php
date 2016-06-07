<?
//Check access
admin_panel_access("settings_storage");


if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
The script can store files on the <b>Local Server</b> where the software is installed or on clouds hostings such as <a href="http://www.rackspacecloud.com/2361.html"><b>Rackspace cloud files</b></a> and <a href="http://aws.amazon.com/s3/"><b>Amazon S3</b></a>.
</p>

<p>
The clouds hosting is cheap, easy and safe way to store media files and distribute them faster in Internet.
</p>

<p>
When you use <b>Rackspace clouds</b> or <b>Amazom S3</b> all files are stored on the <b>local server first</b> and then they are moved to a clouds hosting. 
</p>
</div>
<div class="subheader"><?=word_lang("stats")?></div>
<div class="subheader_text">



<?
$sql="select id from filestorage where types=1";
$rs->open($sql);
if(!$rs->eof)
{	
		$rackspace_server=$rs->row["id"];
}


$sql="select id from filestorage where types=2";
$rs->open($sql);
if(!$rs->eof)
{	
		$amazon_server=$rs->row["id"];
}



$pub_count=0;
$pub_files=0;
$pub_storage=0;

$rackspace_count=0;
$rackspace_files=0;
$rackspace_storage=0;

$amazon_count=0;
$amazon_files=0;
$amazon_storage=0;



$sql="select server1,filesize,id_parent from filestorage_files";
$rs->open($sql);
while(!$rs->eof)
{
	if($rs->row["server1"]==$rackspace_server)
	{
		$rackspace_files++;
		$rackspace_storage+=$rs->row["filesize"];
	}
	
	if($rs->row["server1"]==$amazon_server)
	{
		$amazon_files++;
		$amazon_storage+=$rs->row["filesize"];
	}
	
	$rs->movenext();
}

$mass_tables=array("photos","videos","audio","vector");
for($i=0;$i<count($mass_tables);$i++)
{
	$sql="select server2,id_parent from ".$mass_tables[$i];
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($rs->row["server2"]==$rackspace_server)
		{
			$rackspace_count++;
		}
		elseif($rs->row["server2"]==$amazon_server)
		{
			$amazon_count++;
		}
		else
		{
			$pub_count++;
		}
		$rs->movenext();
	}
}



$sql="select * from filestorage where types=0";
$rs->open($sql);
while(!$rs->eof)
{
	$dir = opendir ($DOCUMENT_ROOT.$rs->row["url"]);
	while ($file = readdir ($dir)) 
	{
		if(is_dir($DOCUMENT_ROOT.$rs->row["url"]."/".$file) and $file*1>1)
		{
   			 $dir2 = opendir ($DOCUMENT_ROOT.$rs->row["url"]."/".$file);
   			 while ($file2 = readdir ($dir2)) 
			{
				if($file2<>"." and $file2<>"..")
				{
					$pub_files++;
					$pub_storage+=filesize($DOCUMENT_ROOT.$rs->row["url"]."/".$file."/".$file2);
				}
			}
   			 closedir ($dir2);
		}
	}
	closedir ($dir);
	$rs->movenext();
}
?>


<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover">
<tr>
<th><b>Server</b></th>
<th><b>Publications</b></th>
<th><b>Files (with previews)</b></th>
<th><b>Disk Space</b></th>
</tr>
<tr>
<td class="big">Local server</td>


<td class="gray"><?=$pub_count?></td>
<td class="gray"><?=$pub_files?></td>
<td class="gray"><?=float_opt(($pub_storage/1024/1024),3)?> Mb.</td>
</tr>
<tr class="snd">
<td class="big">Rackspace clouds</td>
<td class="gray"><?=$rackspace_count?></td>
<td class="gray"><?=$rackspace_files?></td>
<td class="gray"><?=float_opt(($rackspace_storage/1024/1024),3)?> Mb.</td>
</tr>
<tr>
<td class="big">Amazon S3</td>
<td class="gray"><?=$amazon_count?></td>
<td class="gray"><?=$amazon_files?></td>
<td class="gray"><?=float_opt(($amazon_storage/1024/1024),3)?> Mb.</td>
</tr>



</table>
</div></div></div></div></div></div></div></div>

</div>