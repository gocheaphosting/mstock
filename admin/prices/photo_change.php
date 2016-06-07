<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prices");



$sql="select * from licenses order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	$sql="select * from sizes where license=".$rs->row["id_parent"]." order by priority";
	$dr->open($sql);
	while(!$dr->eof)
	{
		$sql="update sizes  set title='".result($_POST["title".$dr->row["id_parent"]])."',size=".(int)$_POST["size".$dr->row["id_parent"]].",price=".(float)$_POST["price".$dr->row["id_parent"]].",priority=".(int)$_POST["priority".$dr->row["id_parent"]].",watermark=".(int)@$_POST["watermark".$dr->row["id_parent"]].",jpg=".(int)@$_POST["jpg".$dr->row["id_parent"]].",png=".(int)@$_POST["png".$dr->row["id_parent"]].",gif=".(int)@$_POST["gif".$dr->row["id_parent"]].",raw=".(int)@$_POST["raw".$dr->row["id_parent"]].",tiff=".(int)@$_POST["tiff".$dr->row["id_parent"]].",eps=".(int)@$_POST["eps".$dr->row["id_parent"]]." where id_parent=".$dr->row["id_parent"];
		$db->execute($sql);

		if(isset($_POST["delete".$dr->row["id_parent"]]))
		{
			$sql="delete from sizes where id_parent=".$dr->row["id_parent"];
			$db->execute($sql);
		}

		if((int)$_POST["addto"]==1)
		{
			$sql="update items  set name='".result($_POST["title".$dr->row["id_parent"]])."',price=".(float)$_POST["price".$dr->row["id_parent"]].",priority=".(int)$_POST["priority".$dr->row["id_parent"]].",watermark=".(int)@$_POST["watermark".$dr->row["id_parent"]]." where price_id=".$dr->row["id_parent"];
			$db->execute($sql);

			if(isset($_POST["delete".$dr->row["id_parent"]]))
			{
				$sql="delete from items where price_id=".$dr->row["id_parent"];
				$db->execute($sql);
			}
		}

		$dr->movenext();
	}
	$rs->movenext();
}

$db->close();

header("location:index.php?d=1&type=change");
?>