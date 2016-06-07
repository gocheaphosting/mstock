<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_productsoptions");

$id=0;

$activ=0;
$required=0;
if(isset($_POST["activ"]))
{
	$activ=1;
}
if(isset($_POST["required"]))
{
	$required=1;
}




if(isset($_GET["id"]))
{		
	$sql="update products_options set title='".result($_POST["title"])."',type='".result($_POST["type"])."',activ=".$activ.",required=".$required." where id=".(int)$_GET["id"];
	$db->execute($sql);
		
	$id=(int)$_GET["id"];
}
else
{
	$sql="insert into products_options (title,type,activ,required) values ('".result($_POST["title"])."','".result($_POST["type"])."',".$activ.",".$required.")";
	$db->execute($sql);
		
	$sql="select id from products_options where title='".result($_POST["title"])."' order by id desc";
	$rs->open($sql);
	$id=$rs->row["id"];
}


//Add ranges
if($id!=0)
{
	$sql="delete from products_options_items where id_parent=".$id;
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
			$sql="insert into products_options_items (id_parent,price,title,adjust) values (".$id.",".(float)$_POST["options_price".$id_list[$i]].",'".result($_POST["options".$id_list[$i]."_title"])."',".(int)$_POST["options".$id_list[$i]."_adjust"].")";
			$db->execute($sql); 
		}
}

		
$db->close();

	
header("location:index.php");
?>
