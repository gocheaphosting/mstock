<? include("../function/db.php");?><?//Check accessadmin_panel_access("settings_storage");


if($_GET["id"]!=1){	$sql="select id,url from filestorage where id=".(int)$_GET["id"]." and types=0";
	$rs->open($sql);
	if(!$rs->eof)	{		$dir_amount = -2;		$dir = opendir ($DOCUMENT_ROOT.$rs->row["url"]);		while ($file = readdir ($dir)) 		{			if(is_dir($DOCUMENT_ROOT.$rs->row["url"]."/".$file))			{   				$dir_amount++;			}		}		closedir ($dir);				if(file_exists($DOCUMENT_ROOT."/".$rs->row["url"]) and $dir_amount==0 and $rs->row["url"]!="")		{			@unlink($DOCUMENT_ROOT."/".$rs->row["url"]."/.htaccess");			@rmdir($DOCUMENT_ROOT."/".$rs->row["url"]);			$sql="delete from filestorage where id=".(int)$_GET["id"];
			$db->execute($sql);		}	}}unset($_SESSION["site_server_activ"]);unset($_SESSION["site_server"]);$db->close();header("location:index.php?d=2");
?>