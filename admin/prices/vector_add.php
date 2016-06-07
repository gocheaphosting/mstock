<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prices");


$shipped=0;
if(isset($_POST["shipped"]))
{
	$shipped=1;
	$_POST["types"]="shipped";
}

$sql="insert into vector_types (types,price,priority,thesame,title,license,shipped) values ('".result($_POST["types"])."',".(float)$_POST["price"].",".(int)$_POST["priority"].",".(int)$_POST["thesame"].",'".result($_POST["title"])."',".(int)$_POST["license"].",".$shipped.")";
$db->execute($sql);

//define id
$sql="select id_parent from vector_types order by id_parent desc";
$dr->open($sql);
$id=$dr->row['id_parent'];

$items_count=0;

if((int)$_POST["addto"]==1)
{
	$ux=explode(",",str_replace(" ","",result($_POST["types"])));
	$ux_reg="";
	for($i=0;$i<count($ux);$i++)
	{
		$ux_reg.=".".$ux[$i]."$";
		if($i!=count($ux)-1){$ux_reg.="|";}
	}

	$sql="select id_parent,server1 from vector order by id_parent";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($shipped!=1)
		{
			$dir = opendir ($_SERVER["DOCUMENT_ROOT"].site_root.server_url($rs->row["server1"])."/".$rs->row["id_parent"]);
 			 while ($file = readdir ($dir)) 
  			{
   				if($file <> "." && $file <> "..")
    			{
					if (preg_match("/".$ux_reg."/i",$file) and !preg_match("/thumb/i",$file)) 
					{ 
						$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$rs->row["id_parent"].",'".result($_POST["title"])."','".$file."',".(float)$_POST["price"].",".(int)$_POST["priority"].",".$shipped.",".$id.")";
						$db->execute($sql);
						$items_count++;
					}
				}
			}
			closedir ($dir);
		}
		else
		{
			$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$rs->row["id_parent"].",'".result($_POST["title"])."','',".(float)$_POST["price"].",".(int)$_POST["priority"].",".$shipped.",".$id.")";
			$db->execute($sql);
			$items_count++;
		}
		$rs->movenext();
	}
}

$db->close();

header("location:index.php?d=4&items_count=".$items_count."&type=add");
?>