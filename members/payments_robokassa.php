<?if(!defined("site_root")){exit();}?>
<?
if($site_robokassa_account!="")
{

// 1.
// Оплата заданной суммы с выбором валюты на сайте мерчанта
// Payment of the set sum with a choice of currency on merchant site 

// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)
$mrh_login = $site_robokassa_account;
$mrh_pass1 = $site_robokassa_password;

// номер заказа
// number of order
$inv_id = $product_id;

// описание заказа
// order description
$inv_desc = $product_type;

// сумма заказа
// sum of order
$out_summ = float_opt($product_total,2);

// тип товара
// code of goods
$shp_item = $product_type;

// предлагаемая валюта платежа
// default payment e-currency
$in_curr = "";

// язык
// language
$culture = "ru";

// кодировка
// encoding
$encoding = "utf-8";

// формирование подписи
// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

// HTML-страница с кассой
// ROBOKASSA HTML-page
/*
print "<script language=JavaScript ".
      "src='https://merchant.roboxchange.com/Handler/MrchSumPreview.ashx?".
      "MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&IncCurrLabel=$in_curr".
      "&Desc=$inv_desc&SignatureValue=$crc&Shp_item=$shp_item".
      "&Culture=$culture&Encoding=$encoding'></script>";
*/      
      
      print "<form action='https://auth.robokassa.ru/Merchant/Index.aspx' method=POST  name='process' id='process'>
      <input type=hidden name=MrchLogin value=$mrh_login>
      <input type=hidden name=OutSum value=$out_summ>
      <input type=hidden name=InvId value=$inv_id>
      <input type=hidden name=Desc value='$inv_desc'>
      <input type=hidden name=SignatureValue value=$crc>
      <input type=hidden name=IncCurrLabel value=$in_curr>
      <input type=hidden name=Culture value=$culture>
      <input type=hidden name=Shp_item value='$shp_item'>
      </form>";
}
?>