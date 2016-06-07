<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_messages");

$sql="select login from users where login='".result($_POST["to"])."'";
$ds->open($sql);
if(!$ds->eof)
{
	$sql="insert into messages (touser,fromuser,subject,content,data,viewed,trash,del) values ('".result($_POST["to"])."','Site Administration','".result($_POST["subject"])."','".result($_POST["content"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,0,0)";
	$db->execute($sql);
}

$db->close();

header("location:index.php");
?>
