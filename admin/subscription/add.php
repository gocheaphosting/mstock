<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_subscription");



$content_type="";
$sql="select * from content_type order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	if($content_type!="" and isset($_POST["type".$rs->row["id_parent"]])){$content_type.="|";}
	if(isset($_POST["type".$rs->row["id_parent"]])){$content_type.=$rs->row["name"];}
	$rs->movenext();
}

$recurring=0;
if(isset($_POST["recurring"]))
{
	$recurring=1;
}

$sql="insert into subscription (title,price,days,content_type,bandwidth,priority,recurring,bandwidth_daily) values ('".result($_POST["title"])."',".result($_POST["price"]).",".(int)$_POST["days"].",'".$content_type."',".(int)$_POST["bandwidth"].",0,".$recurring.",".(int)$_POST["bandwidth_daily"].")";
$db->execute($sql);

$db->close();

header("location:index.php");
?>