<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prints");

$sql="select id_parent from prints";
$rs->open($sql);
while(!$rs->eof)
{	
	$sql="update prints set title='".result($_POST["title".$rs->row["id_parent"]])."',priority=".(int)$_POST["priority".$rs->row["id_parent"]].",price=".(float)$_POST["price".$rs->row["id_parent"]].",weight=".(float)$_POST["weight".$rs->row["id_parent"]].",photo=".(int)@$_POST["photo".$rs->row["id_parent"]].",printslab=".(int)@$_POST["printslab".$rs->row["id_parent"]]." where id_parent=".$rs->row["id_parent"];
	$db->execute($sql);

	$rs->movenext();
}

if($_POST["addto"]==1)
{
	$sql="select id_parent,price from prints";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$sql="update prints_items set price=".float_opt($rs->row["price"],2)."  where printsid=".$rs->row["id_parent"];
		$db->execute($sql);
		
		$rs->movenext();
	}
}

if($_POST["addto"]==2)
{
	$sql="select id_parent from photos";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$sql="select * from prints where photo=1";
		$ds->open($sql);
		while(!$ds->eof)
		{
			$sql="select id_parent from prints_items where itemid=".$rs->row["id_parent"]." and printsid=".$ds->row["id_parent"];
			$dn->open($sql);
			if($dn->eof)
			{
				$sql="insert into prints_items (title,price,itemid,priority,printsid) values ('".$ds->row["title"]."',".float_opt($ds->row["price"],2).",".$rs->row["id_parent"].",".$ds->row["priority"].",".$ds->row["id_parent"].")";
				$db->execute($sql);
			}
			else
			{
				$sql="update prints_items set title='".$ds->row["title"]."',price=".float_opt($ds->row["price"],2).",priority=".$ds->row["priority"]." where itemid=".$rs->row["id_parent"]." and printsid=".$ds->row["id_parent"];
				$db->execute($sql);
			}
			$ds->movenext();
		}
		$rs->movenext();
	}
}

$db->close();

header("location:index.php");
?>
