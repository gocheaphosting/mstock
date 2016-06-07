<?include("../admin/function/db.php");?>
<html>
<title>Agreement</title>
<link rel=stylesheet type="text/css" href="<?=site_root?>/<?=$site_template_url?>style.css">
<script language="javascript" src="<?=site_root?>/inc/scripts.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body class="framebody">

<?
if(isset($_GET["type"]))
{
	if($_GET["type"]=="buyer")
	{
		$sql="select content from pages where link='buyer'";
	}
	if($_GET["type"]=="seller")
	{
		$sql="select content from pages where link='seller'";
	}
	if($_GET["type"]=="affiliate")
	{
		$sql="select content from pages where link='affiliate'";
	}
	if($_GET["type"]=="common")
	{
		$sql="select content from pages where link='common'";
	}
	if($_GET["type"]=="terms")
	{
		$sql="select content from pages where link='terms'";
	}
}

if(isset($_GET["id"]))
{
	$sql="select content from pages where id_parent=".(int)$_GET["id"];
}

$rs->open($sql);
if(!$rs->eof)
{
	echo(translate_text($rs->row["content"]));
}
?>


</body>
</html>