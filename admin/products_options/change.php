<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_productsoptions");

$sql="select id from products_options order by title";
$rs->open($sql);
while(!$rs->eof)
{
	$activ=0;
	if(isset($_POST["activ".$rs->row["id"]]))
	{
		$activ=1;
	}
	
	$sql="update products_options set activ=".$activ." where id=".$rs->row["id"];
	$db->execute($sql);
		
	$rs->movenext();
}

$db->close();
	
header("location:index.php");
?>
