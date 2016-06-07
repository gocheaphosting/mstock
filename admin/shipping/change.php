<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_shipping");

$sql="select id from shipping order by title";
$rs->open($sql);
while(!$rs->eof)
{
	$activ=0;
	if(isset($_POST["activ".$rs->row["id"]]))
	{
		$activ=1;
	}
	
	$sql="update shipping set title='".result($_POST["title".$rs->row["id"]])."',shipping_time='".result($_POST["shipping_time".$rs->row["id"]])."',activ=".$activ." where id=".$rs->row["id"];
	$db->execute($sql);
		
	$rs->movenext();
}

$db->close();
	
header("location:index.php");
?>
