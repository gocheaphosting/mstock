<?include("../admin/function/db.php");?>
<?

if(isset($_GET["id"]))
{
	$id_parent=(int)$_GET["id"];
	$subject="";
}
else
{
	$id_parent=0;
	$subject=result($_POST["subject"]);
}

$sql="insert into support_tickets (id_parent,admin_id,user_id,subject,message,data,viewed_admin,viewed_user,rating,closed) values (".$id_parent.",0,".(int)$_SESSION["people_id"].",'".$subject."','".result($_POST["message"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,1,0,0)";
$db->execute($sql);

$sql="select id from support_tickets where user_id=".(int)$_SESSION["people_id"]." order by id desc";
$rs->open($sql);
if(!$rs->eof)
{
	send_notification('support_to_admin',$rs->row["id"]);
}

if($id_parent!=0)
{
	$sql="update support_tickets set data=".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",closed=0 where id=".$id_parent;
	$db->execute($sql);
}



header("location:support.php?d=1");
?>