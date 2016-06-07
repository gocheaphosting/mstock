<?$site="subscription";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");exit();}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>
<h1><?=word_lang("subscription")?></h1>




<?
$sql="select title,user,data1,data2,bandwidth,bandwidth_limit,subscription,approved from subscription_list where user='".result($_SESSION["people_login"])."' and data2>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and bandwidth<bandwidth_limit and approved=1 order by data2 desc";
$ds->open($sql);
if($ds->eof)
{
	include("subscription_new.php");
}

include("subscription_status.php");
?>










<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>