<?include("../admin/function/db.php");?>
<?include("download_mimes.php");?>
<?
if(isset($_GET["id"]))
{

	//Define if the publication is remote
	$flag_storage=false;

	$sql="select url from filestorage_files where id_parent=".(int)$_GET["id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$flag_storage=true;
	}

	//Show the sample
	$sql="select module_table from structure where id=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof and ($rs->row["module_table"]==30 or $rs->row["module_table"]==31 or $rs->row["module_table"]==52 or $rs->row["module_table"]==53))
	{
		if($rs->row["module_table"]==30)
		{
			$preview_url=show_preview((int)$_GET["id"],"photo",2,1,"","",false);
		}
		
		if($rs->row["module_table"]==31)
		{
			$preview_url=show_preview((int)$_GET["id"],"video",2,1,"","",false);
		}
		
		if($rs->row["module_table"]==52)
		{
			$preview_url=show_preview((int)$_GET["id"],"audio",2,1,"","",false);
		}
		
		if($rs->row["module_table"]==53)
		{
			$preview_url=show_preview((int)$_GET["id"],"vector",3,1,"","",false);
			if(preg_match("/icon_vector/",$preview_url))
			{
				$preview_url=show_preview((int)$_GET["id"],"vector",2,1,"","",false);
			}
		}
		
		$ext=explode(".",$preview_url);
		$file_extention=$ext[count($ext)-1];
		
		if(!$flag_storage)
		{
			$preview_url=$_SERVER["DOCUMENT_ROOT"].$preview_url;
		}
		
		ob_clean();
		header("Content-Type:".$mmtype[$file_extention]);
		header("Content-Disposition: attachment; filename=sample_".(int)$_GET["id"].".".$file_extention);
		ob_end_flush();
		@readfile($preview_url);
		exit();
	}
}

$db->close();
?>