<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$id=@$_REQUEST['id'];

$file_storage=false;
$file_name="";

if($global_settings["amazon"] or $global_settings["rackspace"])
{
	$sql="select url,filename1,filename2,width,height,item_id,filesize from filestorage_files where id_parent=".(int)$id;
	$ds->open($sql);
	while(!$ds->eof)
	{
		if($ds->row["item_id"]!=0)
		{
			$file_name=$ds->row["url"]."/".$ds->row["filename2"];
		}

		$file_storage=true;
		$ds->movenext();
	}
}

if($file_storage)
{
	if($file_name!="")
	{
		echo("<h2 class='exif_header'>EXIF:	</h2>");
		echo(get_exif($file_name,false,(int)$id));
	}
}
else
{
	$sql="select server1,id_parent from photos where id_parent=".(int)$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$sql="select url from items where id_parent=".(int)$id;
		$dr->open($sql);
		if(!$dr->eof)
		{
			$img=$_SERVER["DOCUMENT_ROOT"].site_root.server_url($rs->row["server1"])."/".$rs->row["id_parent"]."/".$dr->row["url"];
			if(file_exists($img))
			{
				echo("<h2 class='exif_header'>EXIF:	</h2>");
				echo(get_exif($img,false,(int)$id));
			}
		}
	}
}

$db->close();
?>
