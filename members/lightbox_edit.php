<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?

$id=(int)$_GET["id"];

if($id!=0)
{	
	//Check
	$sql="select id_parent,user_owner from lightboxes_admin where user=".(int)$_SESSION["people_id"]." and id_parent=".$id." and  user_owner=1";
	$rs->open($sql);
	if($rs->eof)
	{
		exit();
	}
	
	//Update the lightbox
	$sql="update lightboxes set title='".result($_POST["title"])."',description='".result($_POST["description"])."' where id=".$id;
	$db->execute($sql);
}
else
{
	//Add a new lightbox
	$sql="insert into lightboxes (title,description) values ('".result($_POST["title"])."','".result($_POST["description"])."')";
	$db->execute($sql);
	
	$sql="select id from lightboxes where title='".result($_POST["title"])."' order by id desc";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$id=$dr->row["id"];
		
		$sql="insert into lightboxes_admin (id_parent,user,user_owner) values (".$dr->row["id"].",".$_SESSION["people_id"].",1)";
		$db->execute($sql);
	}
}

//Remove administrators
$sql="delete from lightboxes_admin where id_parent=".$id." and user_owner<>1";
$db->execute($sql);

//Add administrators
foreach ($_POST as $key => $value) 
{
	if(preg_match("/user/i",$key))
	{
		$user_id=intval(str_replace("user","",$key));
		if($user_id!=0)
		{
			$sql="insert into lightboxes_admin (id_parent,user,user_owner) values (".$id.",".$user_id.",0)";
			$db->execute($sql);
		}
	}
}

$db->close();

header("location:lightbox.php");
?>