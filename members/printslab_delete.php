<?
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}

$sql="select id from galleries where user_id=".(int)$_SESSION["people_id"]." and id=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	$sql="delete from galleries where user_id=".(int)$_SESSION["people_id"]." and id=".(int)$_GET["id"];
	$db->execute($sql);

	$sql="delete from galleries_photos where id_parent=".(int)$_GET["id"];
	$db->execute($sql);

	if((int)$_GET["id"]!=0 and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]))
	{
		$dir = opendir ($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]);
		while ($file = readdir ($dir)) 
		{
			if($file <> "." && $file <> "..")
			{
				@unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]."/".$file);		
			}
		}
	
		@rmdir($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]);
	}
}

header("location:printslab.php");
?>

