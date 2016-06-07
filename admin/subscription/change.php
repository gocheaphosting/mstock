<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_subscription");


$sql="select * from subscription";
$rs->open($sql);
while(!$rs->eof)
{
	$content_type="";
	$sql="select * from content_type order by priority";
	$ds->open($sql);
	while(!$ds->eof)
	{
		if($content_type!="" and isset($_POST["type".$rs->row["id_parent"]."_".$ds->row["id_parent"]]))
		{
			$content_type.="|";
		}
		if(isset($_POST["type".$rs->row["id_parent"]."_".$ds->row["id_parent"]]))
		{
			$content_type.=$ds->row["name"];
		}
		$ds->movenext();
	}
	
	$recurring=0;
	if(isset($_POST["recurring".$rs->row["id_parent"]]))
	{
		$recurring=1;
	}

	$sql="update subscription set title='".result($_POST["title".$rs->row["id_parent"]])."',price=".result($_POST["price".$rs->row["id_parent"]]).",days=".(int)$_POST["days".$rs->row["id_parent"]].",priority=".(int)$_POST["priority".$rs->row["id_parent"]].",bandwidth=".(int)$_POST["bandwidth".$rs->row["id_parent"]].",content_type='".$content_type."',recurring=".$recurring.",bandwidth_daily=".(int)$_POST["bandwidth_daily".$rs->row["id_parent"]]." where id_parent=".$rs->row["id_parent"];
	$db->execute($sql);

	$rs->movenext();
}

$db->close();


header("location:index.php");
?>