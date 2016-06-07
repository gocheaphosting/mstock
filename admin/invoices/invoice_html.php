<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_invoices");
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
include("invoice_content.php");
echo($invoice_content);
?>
</body>
</html>
