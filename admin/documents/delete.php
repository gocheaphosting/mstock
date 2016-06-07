<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_documents");


$sql="select id,filename from documents";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id"]]))
	{
		$sql="delete from documents where id=".$rs->row["id"];
		$db->execute($sql);
		
		if($rs->row["filename"] != '' and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/users/doc_".$rs->row["id"]."_".$rs->row["filename"]))
		{
			unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/users/doc_".$rs->row["id"]."_".$rs->row["filename"]);
		}
	}
	$rs->movenext();
}


if(isset($_SERVER["HTTP_REFERER"]))
{
	$return_url=$_SERVER["HTTP_REFERER"];
}
else
{
	$return_url="../documents/";
}

$db->close();

redirect($return_url);
?>
