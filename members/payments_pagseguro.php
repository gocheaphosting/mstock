<?if(!defined("site_root")){exit();}?>
<?
if($site_pagseguro_account!="")
{
?>





<form name="process" id="process" method="post"   
action="https://pagseguro.uol.com.br/v2/checkout/payment.html">  

    <input type="hidden" name="receiverEmail" value="<?=$site_pagseguro_account?>">  
    <input type="hidden" name="currency" value="BRL">  
      
    <input type="hidden" name="itemId1" value="<?=$product_id?>">  
    <input type="hidden" name="itemDescription1" value="<?=$product_type?>">  
    <input type="hidden" name="itemAmount1" value="<?=float_opt($product_total,2)?>">  
    <input type="hidden" name="itemQuantity1" value="1">  
 
 <input type="hidden" name="tipo_frete" value="EN">
</form> 






<?
}
?>