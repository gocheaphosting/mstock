<?include("../admin/function/db.php");?>
<?

//Check captcha
require_once('../admin/function/recaptchalib.php');
$flag=check_captcha();

if($flag)
{
	$sql="insert into support (name,email,telephone,method,question,data) values ('".result($_POST["name"])."','".result($_POST["email"])."','".result($_POST["telephone"])."','".result($_POST["method"])."','".result($_POST["question"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).")";
	$db->execute($sql);

	send_notification('contacts_to_admin');
	send_notification('contacts_to_user');
	
	$db->close();

	Header("location:thanks.php");
}
else
{
	$db->close();
	
	Header("location:index.php?d=1");
}















?>