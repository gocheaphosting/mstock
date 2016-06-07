<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_support");



$sql="insert into support_tickets (id_parent,admin_id,user_id,subject,message,data,viewed_admin,viewed_user,rating,closed) values (".(int)$_GET["id"].",".(int)$_SESSION["user_id"].",0,'','".result($_POST["message"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",1,0,0,0)";
$db->execute($sql);

$sql="update support_tickets set data=".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",closed=0 where id=".(int)$_GET["id"];
$db->execute($sql);

$sql="select id from support_tickets where admin_id=".(int)$_SESSION["user_id"]." order by id desc";
$rs->open($sql);
if(!$rs->eof)
{
	send_notification('support_to_user',$rs->row["id"]);
}

header("location:content.php?id=".(int)$_GET["id"]);
?>
