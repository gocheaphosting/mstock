<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");


$photo=0;
$video=0;
$audio=0;
$vector=0;

if(isset($_POST["photo"])){$photo=1;}
if(isset($_POST["video"])){$video=1;}
if(isset($_POST["audio"])){$audio=1;}
if(isset($_POST["vector"])){$vector=1;}


if(isset($_GET["id"]))
{		
	$sql="update rights_managed set title='".result($_POST["title"])."',formats='".result($_POST["formats"])."',price='".(float)$_POST["price"]."',photo=".$photo.",video=".$video.",audio=".$audio.",vector=".$vector." where id=".(int)$_GET["id"];
	$db->execute($sql);
		
	$id=(int)$_GET["id"];
}
else
{
	$sql="insert into rights_managed (title,price,formats,photo,video,audio,vector) values ('".result($_POST["title"])."',".(float)$_POST["price"].",'".result($_POST["formats"])."',".$photo.",".$video.",".$audio.",".$vector.")";
	$db->execute($sql);

	$sql="select id from rights_managed where title='".result($_POST["title"])."' order by id desc";
	$rs->open($sql);
	$id=$rs->row["id"];

	$sql="insert into rights_managed_structure (id_parent,types,title,adjust,price,price_id,group_id,option_id,conditions,collapse) values (0,0,'Step 1','',0,".$id.",0,0,'',0)";
	$db->execute($sql);
}




$db->close();


header("location:index.php?d=1");
?>