<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");

$id=0;





if(isset($_GET["id"]))
{		
	$sql="update rights_managed_groups set title='".result($_POST["title"])."',description='".result($_POST["description"])."' where id=".(int)$_GET["id"];
	$db->execute($sql);
		
	$id=(int)$_GET["id"];
}
else
{
	$sql="insert into rights_managed_groups (title,description) values ('".result($_POST["title"])."','".result($_POST["description"])."')";
	$db->execute($sql);
		
	$sql="select id from rights_managed_groups where title='".result($_POST["title"])."' order by id desc";
	$rs->open($sql);
	$id=$rs->row["id"];
}


//Add ranges
if($id!=0)
{
	$sql="delete from rights_managed_options where id_parent=".$id;
	$db->execute($sql);
	
		$id_list=array();
		foreach ($_POST as $key => $value) 
		{
			if(preg_match("/options_price/i",$key))
			{
				$id_list[]=str_replace("options_price","",$key);
			}
		}
		for($i=0;$i<count($id_list);$i++)
		{
			$sql="insert into rights_managed_options (id_parent,price,title,adjust) values (".$id.",".(float)$_POST["options_price".$id_list[$i]].",'".result($_POST["options".$id_list[$i]."_title"])."','".result($_POST["options".$id_list[$i]."_adjust"])."')";
			$db->execute($sql); 
		}
}

		

$db->close();
	
header("location:index.php?d=2");
?>
