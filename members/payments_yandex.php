<?if(!defined("site_root")){exit();}?>
<?
if($site_yandex_account!="")
{



if(!$site_yandex_test)
{
?>
<form action="https://money.yandex.ru/eshop.xml" method="post" name="process" id="process">
<?
}
else
{
?>
<form action="https://demomoney.yandex.ru/eshop.xml" method="post" name="process" id="process">
<?
}
?>
<input name="shopId" value="<?=$site_yandex_account?>" type="hidden"/>
<input name="scid" value="<?=$site_yandex_account2?>" type="hidden"/>
<input name="sum" value="<?=float_opt($product_total,2)?>" type="hidden">
<input name="customerNumber" value="<?=$_SESSION["people_login"]?>" type="hidden"/>
<input name="paymentType" value="<?=result($_REQUEST["yandex_payments"])?>" type="hidden"/>
<input name="orderNumber" value="<?=$product_type?>-<?=$product_id?>" type="hidden"/>
<input name="cps_email" value="<?=$_SESSION["people_email"]?>" type="hidden"/>
</form> 



<?
}
?>