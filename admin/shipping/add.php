<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_shipping");

$id=0;

$activ=0;
$taxes=0;
if(isset($_POST["activ"]))
{
	$activ=1;
}
if(isset($_POST["taxes"]))
{
	$taxes=1;
}




if(isset($_GET["id"]))
{		
	$sql="update shipping set title='".result($_POST["title"])."',shipping_time='".result($_POST["shipping_time"])."',weight_min=".(int)$_POST["weight_min"].",weight_max=".(int)$_POST["weight_max"].",activ=".$activ.",taxes=".$taxes.",regions=".(int)$_POST["regions"].",methods='".result($_POST["methods"])."',methods_calculation='".result($_POST["methods_calculation"])."' where id=".(int)$_GET["id"];
	$db->execute($sql);
		
	$id=(int)$_GET["id"];
}
else
{
	$sql="insert into shipping (title,shipping_time,weight_min,weight_max,activ,taxes,regions,methods,methods_calculation) values ('".result($_POST["title"])."','".result($_POST["shipping_time"])."',".(int)$_POST["weight_min"].",".(int)$_POST["weight_max"].",".$activ.",".$taxes.",".(int)$_POST["regions"].",'".result($_POST["methods"])."','".result($_POST["methods_calculation"])."')";
	$db->execute($sql);
		
	$sql="select id from shipping where title='".result($_POST["title"])."' order by id desc";
	$rs->open($sql);
	$id=$rs->row["id"];
}


//Add ranges
if($id!=0)
{
	$sql="delete from shipping_ranges where id_parent=".$id;
	$db->execute($sql);
	
	if($_POST["methods"]=="flatrate")
	{
		$sql="insert into shipping_ranges (id_parent,price,from_param,to_param) values (".$id.",".(float)$_POST["flatrate"].",0,0)";
		$db->execute($sql); 
	}
	else
	{
		$id_list=array();
		foreach ($_POST as $key => $value) 
		{
			if(preg_match("/".$_POST["methods"]."_price/i",$key))
			{
				$id_list[]=str_replace($_POST["methods"]."_price","",$key);
			}
		}
		for($i=0;$i<count($id_list);$i++)
		{
			$sql="insert into shipping_ranges (id_parent,price,from_param,to_param) values (".$id.",".(float)$_POST[$_POST["methods"]."_price".$id_list[$i]].",".(float)$_POST[$_POST["methods"].$id_list[$i]."_1"].",".(float)$_POST[$_POST["methods"].$id_list[$i]."_2"].")";
			$db->execute($sql); 
		}
	}
}

		
//Add regions
if($id!=0)
{
	$sql="delete from shipping_regions where id_parent=".$id;
	$db->execute($sql);
	
	if($_POST["regions"]==1)
	{
		$j=0;
		$sql="select name,id from countries where activ=1 order by priority,name";
		$dd->open($sql);
		while(!$dd->eof)
		{
			if(isset($_POST["country".$dd->row["id"]]))
			{
				$sql="insert into shipping_regions (id_parent,country,state) values (".$id.",'".$dd->row["name"]."','')";
				$db->execute($sql); 
			}
			
			if(isset($mstates[$dd->row["name"]]))
			{						
				foreach ($mstates[$dd->row["name"]] as $key => $value) 
				{
					if(isset($_POST["state".$j]))
					{
						$sql="insert into shipping_regions (id_parent,country,state) values (".$id.",'".$dd->row["name"]."','".$value."')";
						$db->execute($sql); 
					}
					
					$j++;
				}
			}
			$dd->movenext();
		}
	}
}

$db->close();
	
header("location:index.php");
?>
