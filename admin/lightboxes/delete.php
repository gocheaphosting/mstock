<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_lightboxes");


$sql="select id from lightboxes";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id"]]))
	{
		$sql="delete from lightboxes where id=".$rs->row["id"];
		$db->execute($sql);

		$sql="delete from lightboxes_admin where id_parent=".$rs->row["id"];
		$db->execute($sql);

		$sql="delete from lightboxes_files where id_parent=".$rs->row["id"];
		$db->execute($sql);
	}
	$rs->movenext();
}


if(isset($_SERVER["HTTP_REFERER"]))
{
	$return_url=$_SERVER["HTTP_REFERER"];
}
else
{
	$return_url="../comments/";
}

$db->close();

redirect($return_url);
?>
