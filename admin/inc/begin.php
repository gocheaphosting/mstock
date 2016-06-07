<?
if(!defined("site_root")){exit();}

$sql="select folder from templates_admin where activ=1";
$rs->open($sql);
$admin_template=$rs->row["folder"];

include("../templates_admin/".$admin_template."/header.php");
?>


