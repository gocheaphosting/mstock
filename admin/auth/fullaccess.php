<? include("../function/db.php");?>
<?

$slova="";
if(isset($_GET["d"]))
{
	$d=(int)$_GET["d"];
	if($d==1){$slova="<b>".word_lang("error")."</b>";}
	if($d==2){$slova="<b>Incorrect captcha</b>";}
	$slova="<div id='login_error'>".$slova."</div>";
}


$sql="select folder from templates_admin where activ=1";
$rs->open($sql);
$admin_template=$rs->row["folder"];

include("../templates_admin/".$admin_template."/login.php");
?>
