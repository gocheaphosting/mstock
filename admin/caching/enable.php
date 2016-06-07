<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_caching");

$caching=0;

if(isset($_POST["caching"]))
{
	$caching=1;
}

$sql="update settings set activ=".$caching." where setting_key='caching'";
$db->execute($sql);

$db->close();
header("location:index.php");
?>