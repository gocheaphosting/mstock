<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_newsletter");

$sql="update newsletter_emails set content='".result($_POST["content"])."'";
$db->execute($sql);

$db->close();

header("location:index.php?d=4");
?>