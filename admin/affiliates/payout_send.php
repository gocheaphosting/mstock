<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("affiliates_payout");

$bank_info="";
if($_POST["method"]=="bank" and @$_POST["bank_name"]!="" and @$_POST["bank_account"]!="")
{
	$bank_info="\n".result(@$_POST["bank_name"]).": ".result(@$_POST["bank_account"]);
}

$sql="insert into affiliates_signups (total,userid,aff_referal,types,types_id,rates,data,status,gateway,description) values (".(-1*$_POST["total"]).",0,".(int)$_POST["user"].",'refund',0,0,".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,'".result($_POST["method"])."','".result($_POST["description"]).$bank_info."')";
$db->execute($sql);


$product_type="payout_affiliate";
$link_back=surl.site_root."/admin/affiliates/payout.php";


include("../commission/gateways.php");
?>
