<?include("../admin/function/db.php");?>



<?
$sql="select id_parent from users where authorization='site' and email='".result($_POST["email"])."'";
$rs->open($sql);
if(!$rs->eof)
{
	send_notification('forgot_password');
	Header("location: forgot_thanks.php");
}
else
{
	Header("location: forgot.php?d=1");
}

$db->close();
?>
