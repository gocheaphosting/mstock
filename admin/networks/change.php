<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_networks");

$activ=0;
if(isset($_POST[strtolower(result($_GET["title"]))."_activ"]))
{
	$activ=1;
}

$sql="update users_qauth set activ=".$activ.",consumer_key='".result($_POST[strtolower(result($_GET["title"]))."_consumer_key"])."',consumer_secret='".result($_POST[strtolower(result($_GET["title"]))."_consumer_secret"])."'  where title='".result($_GET["title"])."'";
$db->execute($sql);

$d=1;
if($_GET["title"]=="Facebook"){$d=1;}
if($_GET["title"]=="Twitter"){$d=2;}
if($_GET["title"]=="Vkontakte"){$d=3;}
if($_GET["title"]=="Instagram"){$d=4;}

unset($_SESSION["social_result"]);

$db->close();

redirect("index.php?d=".$d);
?>