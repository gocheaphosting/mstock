<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prints");

$id=0;

if(isset($_GET["id"]))
{
	$sql="update prints set title='".result($_POST["title"])."',description='".result($_POST["description"])."',price=".(float)$_POST["price"].",priority=".(int)$_POST["priority"].",weight=".(float)$_POST["weight"].",option1=".(int)$_POST["option1"].",option2=".(int)$_POST["option2"].",option3=".(int)$_POST["option3"].",option4=".(int)$_POST["option4"].",option5=".(int)$_POST["option5"].",option6=".(int)$_POST["option6"].",option7=".(int)$_POST["option7"].",option8=".(int)$_POST["option8"].",option9=".(int)$_POST["option9"].",option10=".(int)$_POST["option10"].",photo=".(int)@$_POST["photo"].",printslab=".(int)@$_POST["printslab"]." where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
	
	$id=(int)$_GET["id"];
}
else
{
	$sql="insert into prints (title,description,price,priority,weight,option1,option2,option3,option4,option5,option6,option7,option8,option9,option10,photo,printslab) values ('".result($_POST["title"])."','".result($_POST["description"])."',".(float)$_POST["price"].",".(int)$_POST["priority"].",".(float)$_POST["weight"].",".(int)$_POST["option1"].",".(int)$_POST["option2"].",".(int)$_POST["option3"].",".(int)$_POST["option4"].",".(int)$_POST["option5"].",".(int)$_POST["option6"].",".(int)$_POST["option7"].",".(int)$_POST["option8"].",".(int)$_POST["option9"].",".(int)$_POST["option10"].",".(int)@$_POST["photo"].",".(int)@$_POST["printslab"].")";
	$db->execute($sql);
	
	$sql="select id_parent from prints where title='".result($_POST["title"])."' order by id_parent desc";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$id=$rs->row["id_parent"];
	}
}

//Upload photos
if($id!=0)
{
	for($i=1;$i<6;$i++)
	{
		$_FILES["photo".$i]['name']=result_file($_FILES["photo".$i]['name']);
		if($_FILES["photo".$i]['size']>0)
		{
			if(preg_match("/jpg$/i",$_FILES["photo".$i]['name']) and !preg_match("/text/i",$_FILES["photo".$i]['type']))
			{
				$img1=site_root."/content/prints/product".$id."_".$i."_small.jpg";
				$img2=site_root."/content/prints/product".$id."_".$i."_big.jpg";
				
				move_uploaded_file($_FILES["photo".$i]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$img2);
				
				easyResize($_SERVER["DOCUMENT_ROOT"].$img2,$_SERVER["DOCUMENT_ROOT"].$img1,100,$global_settings["thumb_width"]);
				easyResize($_SERVER["DOCUMENT_ROOT"].$img2,$_SERVER["DOCUMENT_ROOT"].$img2,100,$global_settings["thumb_width2"]);
			}
		}
	}
}

$db->close();

header("location:index.php");
?>
