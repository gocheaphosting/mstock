<? 
include("../function/db.php");

//Check access
admin_panel_access("settings_printful");


$sql="select option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where id_parent=".(int)$_GET["print_id"];
$rs->open($sql);
if(!$rs->eof)
{	
	//Change old print types
	$sql="select * from printful_prints where print_id=".(int)$_GET["print_id"]." order by id";
	$ds->open($sql);
	while(!$ds->eof)
	{
		if(isset($_POST["delete".$ds->row["id"]]))
		{
			$sql="delete from printful_prints where id=".$ds->row["id"];
			$db->execute($sql);
		}
		else
		{
			$sql_update="update printful_prints set printful_id=".(int)$_POST["printful_id".$ds->row["id"]]."";
	
			for($i=1;$i<11;$i++)
			{			
				$sql_update.=",option".$i."_value='".result(@$_POST["option".$i."_".$rs->row["option".$i]."_".$ds->row["id"]])."'";					
			}
	
			$sql_update.=" where id=".$ds->row["id"];
			$db->execute($sql_update);
		}
		$ds->movenext();
	}	
	
	//Add new print type
	if((int)$_POST["newprintful_id"]!=0)
	{
		$sql_new="insert into printful_prints (print_id,printful_id,option1,option1_value,option2,option2_value,option3,option3_value,option4,option4_value,option5,option5_value,option6,option6_value,option7,option7_value,option8,option8_value,option9,option9_value,option10,option10_value) values (".(int)$_GET["print_id"].",".(int)$_POST["newprintful_id"]."";
	
		for($i=1;$i<11;$i++)
		{			
			$sql_new.=",".$rs->row["option".$i].",'".result(@$_POST["newoption".$i."_".$rs->row["option".$i]])."'";					
		}
	
		$sql_new.=")";
		$db->execute($sql_new);
	}
}




$db->close();

header("location:index.php?d=1&print_id=".(int)$_GET["print_id"]);
?>