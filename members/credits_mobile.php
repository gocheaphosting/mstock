<?if(!defined("site_root")){exit();}?>

<?
if($site_fortumo_account!="")
{
?>
<script src="http://fortumo.com/javascripts/fortumopay.js" type="text/javascript"></script>
<a id="fmp-button" href="#" rel="<?=$site_fortumo_account?>/<?=$_SESSION["people_id"]?>">
 <img src="http://fortumo.com/images/fmp/fortumopay_96x47.png" width="96" height="47" alt="Mobile Payments by Fortumo" border="0" />
</a>
<?
}
?>




