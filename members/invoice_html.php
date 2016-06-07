<?
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");exit();}

$flag = false;

$sql="select order_id,order_type from invoices where invoice_number=".(int)@$_GET["id"]." and status=1";
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["order_type"]=="orders")
	{
		$sql="select id from orders where id=".$rs->row["order_id"]." and user=".(int)$_SESSION["people_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag = true;	
		}	
	}
	if($rs->row["order_type"]=="credits")
	{
		$sql="select id_parent from credits_list where id_parent=".$rs->row["order_id"]." and user='".result($_SESSION["people_login"])."'";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag = true;		
		}	
	}
	if($rs->row["order_type"]=="subscription")
	{
		$sql="select id_parent from subscription_list where id_parent=".$rs->row["order_id"]." and user='".result($_SESSION["people_login"])."'";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag = true;		
		}	
	}
}

if($flag == true)
{
?>
	<html>
	<head>
	<title><?=word_lang("invoice")?> <?=$global_settings["invoice_prefix"]?><?=@$_GET["id"]?></title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=site_root?>/admin/templates_admin/adminlte/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=site_root?>/admin/templates_admin/adminlte/assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />    
	<link href="<?=site_root?>/admin/templates_admin/adminlte/assets/dist/css/style.css" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js" type="text/javascript"></script>
	</head>
	<body>
	<? 
	$invoice_content = '';
	include("../admin/invoices/invoice_content.php");
	echo($invoice_content);
	?>
	</body>
	</html>
<?
}
?>