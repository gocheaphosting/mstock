<?if(!defined("site_root")){exit();}?>
<?
if($site_payprin_account!="")
{

$hashseed = mktime ();
$hashdata = "sale" . ":" . $site_payprin_password . ":" . float_opt($product_total,2) . ":" . $product_id . ":" . $hashseed ; 
$hash = md5 ( $hashdata ); 
$UMhash="m/".$hashseed."/".$hash."/y";
?>



<form action="https://axisgwy.payprin.com/interface/epayform/" name="process" id="process" method="POST">
<input type="hidden" name="UMkey" value="<?=$site_payprin_account?>"> 
<input type="hidden" name="UMcommand" value="sale"> 
<input type="hidden" name="UMamount" value="<?=float_opt($product_total,2)?>"> 
<input type="hidden" name="UMinvoice" value="<?=$product_id?>"> 
<input type="hidden" name="UMdescription" value="<?=$product_type?>"> 
<input type="hidden" name="UMhash" value="<?=$UMhash?>"> 
</form>

<?
}
?>