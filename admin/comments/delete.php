<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_comments");


$sql="select id_parent from reviews";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id_parent"]]))
	{
		$sql="delete from reviews where id_parent=".$rs->row["id_parent"];
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
