<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_commission");
?>


<?
foreach ($_POST as $key => $value) 
{
	if(preg_match("/sel/",$key))
	{
		$key_mass=explode("sel",$key);
		$sql="delete from commission where id=".(int)$key_mass[1];
		$db->execute($sql);
	}
}



if(isset($_SERVER["HTTP_REFERER"]))
{
	$return_url=$_SERVER["HTTP_REFERER"];
}
else
{
	$return_url="index.php";
}

$db->close();

redirect($return_url);


?>
