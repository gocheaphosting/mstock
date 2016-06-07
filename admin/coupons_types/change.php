<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_couponstypes");



$sql="select id_parent,title,days,total,percentage,url,events,ulimit,bonus from coupons_types";
$rs->open($sql);
while(!$rs->eof)
{
	if($_POST["discount_type".$rs->row["id_parent"]]=="total")
	{
		$com=",percentage=0";
	}
	if($_POST["discount_type".$rs->row["id_parent"]]=="percentage")
	{
		$com=",total=0";
	}

	$sql="update coupons_types set title='".result($_POST["title".$rs->row["id_parent"]])."',days=".(int)$_POST["days".$rs->row["id_parent"]].",".result($_POST["discount_type".$rs->row["id_parent"]])."=".(float)$_POST["discount".$rs->row["id_parent"]].$com.",url='".result($_POST["url".$rs->row["id_parent"]])."',events='".result($_POST["events".$rs->row["id_parent"]])."',ulimit=".(int)$_POST["ulimit".$rs->row["id_parent"]].",bonus=".(float)$_POST["bonus".$rs->row["id_parent"]]." where id_parent=".$rs->row["id_parent"];
	$db->execute($sql);
	$rs->movenext();
}      

$db->close();

header("location:index.php");
?>