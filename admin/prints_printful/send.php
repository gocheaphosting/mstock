<? 
include("../function/db.php");

//Check access
admin_panel_access("settings_printful");

//The orders IDs must be sent to printful
$printful_ids=array();

foreach ($_POST as $key => $value) 
{
	if(preg_match("/sel[0-9]+/",$key))
	{
		$id=str_replace("sel","",$key);
		$sql="select order_id from printful_orders where order_id=".(int)$id;
		$ds->open($sql);
		if($ds->eof)
		{
			$printful_ids[]=$id;
		}
	}
}
//End. The orders IDs must be sent to printful



include("send_to_printful.php");

$db->close();

header("location:index.php?d=2");
?>