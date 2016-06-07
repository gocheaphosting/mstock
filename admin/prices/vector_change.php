<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prices");




$sql="select * from licenses order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	$sql="select * from vector_types where license=".$rs->row["id_parent"]." order by priority";
	$dr->open($sql);
	while(!$dr->eof)
	{
		$shipped=0;
		if(isset($_POST["shipped".$dr->row["id_parent"]]))
		{
			$shipped=1;
			$_POST["types".$dr->row["id_parent"]]="shipped";
		}

		$sql="update vector_types  set title='".result($_POST["title".$dr->row["id_parent"]])."',types='".result($_POST["types".$dr->row["id_parent"]])."',price=".(float)$_POST["price".$dr->row["id_parent"]].",priority=".(int)$_POST["priority".$dr->row["id_parent"]].",thesame=".(int)$_POST["thesame".$dr->row["id_parent"]].",shipped=".$shipped." where id_parent=".$dr->row["id_parent"];
		$db->execute($sql);

		if(isset($_POST["delete".$dr->row["id_parent"]]))
		{
			$sql="delete from vector_types where id_parent=".$dr->row["id_parent"];
			$db->execute($sql);
		}

		if((int)$_POST["addto"]==1)
		{
			$sql="update items  set name='".result($_POST["title".$dr->row["id_parent"]])."',price=".(float)$_POST["price".$dr->row["id_parent"]].",priority=".(int)$_POST["priority".$dr->row["id_parent"]].",shipped=".$shipped." where price_id=".$dr->row["id_parent"];
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

header("location:index.php?d=4&type=change");
?>