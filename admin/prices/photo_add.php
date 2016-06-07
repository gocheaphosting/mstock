<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prices");

include("../function/upload.php");

$sql="insert into sizes (size,price,priority,title,license,watermark,jpg,png,gif,raw,tiff,eps) values (".(int)$_POST["size"].",".(float)$_POST["price"].",".(int)$_POST["priority"].",'".result($_POST["title"])."',".(int)$_POST["license"].",".(int)@$_POST["watermark"].",".(int)@$_POST["jpg"].",".(int)@$_POST["png"].",".(int)@$_POST["gif"].",".(int)@$_POST["raw"].",".(int)@$_POST["tiff"].",".(int)@$_POST["eps"].")";
$db->execute($sql);

//define id
$sql="select id_parent from sizes order by id_parent desc";
$dr->open($sql);
$id=$dr->row['id_parent'];

$items_count=0;

if((int)$_POST["addto"]==1)
{
	$sql="select id_parent,server1 from photos order by id_parent";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$url=get_photo_file($rs->row["id_parent"]);
		
		if($url!="")
		{
			$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id,watermark) values (".$rs->row["id_parent"].",'".result($_POST["title"])."','".$url."',".(float)$_POST["price"].",".(int)$_POST["priority"].",0,".$id.",".(int)@$_POST["watermark"].")";
			$db->execute($sql);
		}
		
		$items_count++;
		$rs->movenext();
	}
}

$db->close();

header("location:index.php?d=1&items_count=".$items_count."&type=add");
?>