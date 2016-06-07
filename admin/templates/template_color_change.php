<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_skins");


$sql="update templates set activ=0";
$db->execute($sql);

$sql="update templates set activ=1 where id=".(int)$_POST["activ"];
$db->execute($sql);


$sql="select * from templates order by id";
$rs->open($sql);
while(!$rs->eof)
{
	if($_POST["name".$rs->row["id"]]!="" and $_POST["url".$rs->row["id"]]!="")
	{
		$shome=1;

		$sql="update templates set name='".result($_POST["name".$rs->row["id"]])."',url='".result($_POST["url".$rs->row["id"]])."',shome=".$shome." where id=".$rs->row["id"];
		$db->execute($sql);


		if(isset($_POST["del".$rs->row["id"]]))
		{
			$sql="delete from templates where id=".$rs->row["id"];
			$db->execute($sql);
		}

	}
$rs->movenext();
}


$smarty->clearAllCache();

$db->close();

header("location:skins.php");
?>