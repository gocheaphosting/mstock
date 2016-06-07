<?
if(!defined("site_root")){exit();}

include($DOCUMENT_ROOT."/members/shopping_cart_add_content.php");




$file_template=str_replace("{BOX_SHOPPING_CART}","<div id='shopping_cart'>".$box_shopping_cart."</div>",$file_template);
$file_template=str_replace("{BOX_SHOPPING_CART_LITE}","<div id='shopping_cart_lite'>".$box_shopping_cart_lite."</div>",$file_template);
?>