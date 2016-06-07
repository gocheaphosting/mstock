<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_taxes");



$sql="select * from tax order by title";
$rs->open($sql);
while(!$rs->eof)
{

	$enabled=0;
	if(isset($_POST["enabled".$rs->row["id"]])){$enabled=1;}
	
	$price_include=0;
	if(isset($_POST["price_include".$rs->row["id"]])){$price_include=1;}
	
	if(isset($_POST["files".$rs->row["id"]]))
	{
		$files=1;
	}
	if(isset($_POST["credits".$rs->row["id"]]))
	{
		$credits=1;
	}
	if(isset($_POST["subscription".$rs->row["id"]]))
	{
		$subscription=1;
	}
	if(isset($_POST["prints".$rs->row["id"]]))
	{
		$prints=1;
	}

	$sql="update tax set enabled=".$enabled.",title='".result($_POST["title".$rs->row["id"]])."',files=".$files.",credits=".$credits.",subscription=".$subscription.",prints=".$prints.",price_include=".$price_include.",rate_all=".(float)$_POST["rate_all".$rs->row["id"]].",rate_all_type=".(int)$_POST["rate_all_type".$rs->row["id"]]." where id=".$rs->row["id"];
	$db->execute($sql);
	
	$rs->movenext();
}

$db->close();

header("location:index.php");
?>