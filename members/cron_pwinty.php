<?include("../admin/function/db.php");?>
<?
//The orders IDs must be sent to Pwinty
$pwinty_ids=array();

//The cron works since the order number
$order_number=0;
$sql="select order_number from pwinty";
$rs->open($sql);
if(!$rs->eof)
{
	$order_number=$rs->row["order_number"];
}


//Define necessary prints ids
$prints_mas=array();
$sql="select print_id from pwinty_prints where activ=1";
$ds->open($sql);
while(!$ds->eof)
{
	$prints_mas[]=$ds->row["print_id"];
	$ds->movenext();
}



//Define necessary orders ids
$sql="select id from orders where status=1 and id>".$order_number;
$rs->open($sql);
while(!$rs->eof)
{
	$prints_flag=true;

	$sql="select item from orders_content where prints=1 and id_parent=".$rs->row["id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$sql="select order_id from pwinty_orders where order_id=".$rs->row["id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$prints_flag=false;
		}
	
		while(!$ds->eof)
		{
			$sql="select printsid,itemid from prints_items where id_parent=".$ds->row["item"];
			$dr->open($sql);
			if($dr->eof or !in_array($dr->row["printsid"],$prints_mas))
			{
				$prints_flag=false;
			}
		
			$ds->movenext();
		}
		
		if($prints_flag)
		{
			$pwinty_ids[]=$rs->row["id"];
		}
	}
	
	$rs->movenext();
}
//End. The orders IDs must be sent to Pwinty



include("../admin/prints_pwinty/send_to_pwinty.php");

?>
<p>The orders have been sent to Pwinty prints service:</p>
<?
for($i=0;$i<count($pwinty_ids);$i++)
{
	echo("ID=".$pwinty_ids[$i]."<br>");
}

$db->close();
?>
