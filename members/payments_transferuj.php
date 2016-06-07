<?if(!defined("site_root")){exit();}?>
<?
if($site_transferuj_account!="")
{
?>
<form action="https://secure.transferuj.pl" method="post" name="process" id="process">

<input name="id" value="<?=$site_transferuj_account?>" type="hidden"/>
<input name="kwota" value="<?=float_opt($product_total,2)?>" type="hidden">
<input name="opis" value="<?=$product_name?>" type="hidden"/>

<input name="crc" value="<?=$product_type?>-<?=$product_id?>" type="hidden"/>
<input name="md5sum" value="<?=md5($site_transferuj_account.float_opt($product_total,2).$product_type."-".$product_id.$site_transferuj_password)?>" type="hidden"/>
<input type="hidden" name="pow_url_blad" value="<?=surl.site_root."/members/payments_result.php?d=2"?>"/>
<input type="hidden" name="wyn_url" value="<?=surl.site_root."/members/payments_transferuj_go.php"?>"/>
<input type="hidden" name="pow_url" value="<?=surl.site_root."/members/payments_result.php?d=1"?>"/>
<input name="email" value="<?=$_SESSION["people_email"]?>" type="hidden"/>

</form> 



<?
}
?>