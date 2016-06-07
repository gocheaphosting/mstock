<?if(!defined("site_root")){exit();}?>
<?
if($site_enets_account!=""){

if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
header("header:payments_result.php?d=1");

}
else
{
?>

<form method="post" action="<?=enets_url?>"  name="process" id="process"> 
	<input type="hidden" name="txnRef" value="<?=$product_id?>"> 
	<input type="hidden" name="mid" value="<?=$site_enets_account?>"> 
	<input type="hidden" name="amount" value="<?=$product_total?>">

</form>

<?
}
}
?>
