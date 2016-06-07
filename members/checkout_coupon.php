<?include("../admin/function/db.php");?>
<?

$sql="select coupon_code from coupons where coupon_code='".result($_POST["coupon"])."' and (total<>0 or percentage<>0) and used=0 and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
$rs->open($sql);
if(!$rs->eof)
{	
	$_SESSION["coupon_code"]=result($_POST["coupon"]);
	header("location:checkout.php");
}
else
{
	header("location:checkout.php?coupon=1");
}
?>



