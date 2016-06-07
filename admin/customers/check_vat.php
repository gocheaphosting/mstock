<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_customers");

include("../../members/JsHttpRequest.php");

$JsHttpRequest =new JsHttpRequest($mtg);


$check_date = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

if((int)@$_REQUEST["status"] == 0)
{
	$check_date = 0;
}

$sql="update users set vat_checked=".(int)@$_REQUEST["status"].",vat_checked_date=".$check_date." where id_parent=".(int)@$_REQUEST["id"];
$db->execute($sql);

$db->close();
?>
