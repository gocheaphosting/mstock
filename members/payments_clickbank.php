<?if(!defined("site_root")){exit();}?>
<?
if($site_clickbank_account!=""){




if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_clickbank_ipn==true)
{


function cbValid() {
global $site_clickbank_account2;
	$key=$site_clickbank_account2;
	$ccustname = $_REQUEST['ccustname'];
	$ccustemail = $_REQUEST['ccustemail'];
	$ccustcc = $_REQUEST['ccustcc'];
	$ccuststate = $_REQUEST['ccuststate'];
	$ctransreceipt = $_REQUEST['ctransreceipt'];
	$cproditem = $_REQUEST['cproditem'];
	$ctransaction = $_REQUEST['ctransaction'];
	$ctransaffiliate = $_REQUEST['ctransaffiliate'];
	$ctranspublisher = $_REQUEST['ctranspublisher'];
	$cprodtype = $_REQUEST['cprodtype'];
	$cprodtitle = $_REQUEST['cprodtitle'];
	$ctranspaymentmethod = $_REQUEST['ctranspaymentmethod'];
	$ctransamount = $_REQUEST['ctransamount'];
	$caffitid = $_REQUEST['caffitid'];
	$cvendthru = $_REQUEST['cvendthru'];
	$cbpop = $_REQUEST['cverify'];

	$xxpop = sha1("$ccustname|$ccustemail|$ccustcc|$ccuststate|$ctransreceipt|$cproditem|$ctransaction|"
		."$ctransaffiliate|$ctranspublisher|$cprodtype|$cprodtitle|$ctranspaymentmethod|$ctransamount|$caffitid|$cvendthru|$key");

    $xxpop=strtoupper(substr($xxpop,0,8));

    if ($cbpop==$xxpop) return 1;
	else return 0;
}


$rz=cbValid();



if ($rz)
{

$sql="select id_parent from credits_list where id_parent=".(int)$_GET["seed"];
$rs->open($sql);
	if(!$rs->eof)
	{
		credits_approve($rs->row["id_parent"],'');
							send_notification('credits_to_user',$rs->row["id_parent"]);
send_notification('credits_to_admin',$rs->row["id_parent"]);
	}

$sql="select id_parent from subscription_list where id_parent=".(int)$_GET["seed"];
$rs->open($sql);
	if(!$rs->eof)
	{
		subscription_approve($rs->row["id_parent"]);
				send_notification('subscription_to_user',$rs->row["id_parent"]);
send_notification('subscription_to_admin',$rs->row["id_parent"]);
	}


}


}
}
else
{



$aproduct=0;

if($_POST["tip"]=="credits")
{
$sql="select * from gateway_clickbank where credits=".(int)$_POST["credits"];
$ds->open($sql);
if(!$ds->eof)
{
$aproduct=$ds->row["product_id"];
}
}


if($_POST["tip"]=="subscription")
{
$sql="select * from gateway_clickbank where subscription=".(int)$_POST["subscription"];
$ds->open($sql);
if(!$ds->eof)
{
$aproduct=$ds->row["product_id"];
}
}



?>
<script language=javascript>function oo(){location.href='<?=clickbank_url?>?link=<?=$site_clickbank_account?>/<?=$aproduct?>/credit&seed=<?=$product_id?>';}oo();</script>

<?



}



}
?>