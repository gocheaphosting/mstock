<?$site="billing";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>
<?include("payments_settings.php");?>

<?
if(isset($_POST["payment"]))
{
	$_SESSION["billing_payment"]=result($_POST["payment"]);
}
?>
	
<h1><?=word_lang("buy ".result($_SESSION["billing_type"]))?></h1>

<div class="login_header">

<div style="float:right;">[ <a href="<?=result($_SESSION["billing_type"])?>.php?d=1"><?=word_lang("change")?></a> ]</div>	

<h2 style="margin-top:30px"><?=word_lang("order")?></h2></div>

<?
include("billing_content.php");
?>
	
<?if(($_SESSION["billing_type"]=="credits" and $global_settings["checkout_credits_billing"]) or ($_SESSION["billing_type"]=="subscription" and $global_settings["checkout_subscription_billing"])){?>


<div class="login_header">

<div style="float:right;">[ <a href="billing.php"><?=word_lang("change")?></a> ]</div>	

<h2 style="margin-top:30px"><?=word_lang("billing address")?></h2>
</div>

<p>
<?
			if($_SESSION["billing_firstname"]!="" or $_SESSION["billing_lastname"]!="")
			{
				echo($_SESSION["billing_firstname"]." ".$_SESSION["billing_lastname"]."<br>");
			}
			if($_SESSION["billing_address"]!="")
			{
				echo($_SESSION["billing_address"]."<br>");
			}
			if($_SESSION["billing_city"]!="" or $_SESSION["billing_zip"]!="" or $_SESSION["billing_country"]!="")
			{
				echo($_SESSION["billing_city"]." ".$_SESSION["billing_state"]." ".$_SESSION["billing_zip"].", ".$_SESSION["billing_country"]."<br>");
			}
			?>
</p>

<?}?>

	
<div class="login_header">

<div style="float:right;">[ <a href="billing2.php"><?=word_lang("change")?></a> ]</div>	


<h2 style="margin-top:30px"><?=word_lang("Payment gateways")?></h2>
</div>

<p>
<?
if(isset($payments[$_SESSION["billing_payment"]]))
{
	echo($payments[$_SESSION["billing_payment"]]);
}
?>
</p>


<?
$com="";
if($_SESSION["billing_type"]=="credits")
{
	$com="types=2";
}
if($_SESSION["billing_type"]=="subscription")
{
	$com="types=3";
}

$disabled="";
$mass="";
$i=0;

$sql="select id,title,page_id from terms where ".$com." order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	?>
	<div class="login_header">
		<h2 style="margin-top:30px"><?=word_lang($rs->row["title"])?></h2>
	</div>
	<iframe src="<?=site_root?>/members/agreement.php?id=<?=$rs->row["page_id"]?>" frameborder="no" scrolling="yes" class="framestyle_seller" style="width:835px;height:150px"></iframe><br>
	<input name="terms<?=$rs->row["id"]?>" id="terms<?=$rs->row["id"]?>" type="checkbox" value="1" onClick="check_terms(<?=$rs->row["id"]?>)"> <?=word_lang("i agree")?>
	
	<?
	$mass.="mass[".$i."]=".$rs->row["id"].";";
	
	$i++;
	$disabled="disabled";
	$rs->movenext();
}
if($disabled!="")
{
	?>
	<script>
		mass=new Array();	
		<?=$mass?>
	
		function check_terms(value)
		{
			flag=true;	
			
			for(i=0;i<mass.length;i++)
			{
				if(document.getElementById("terms"+mass[i].toString()) && $("#terms"+mass[i].toString()).is(':checked')==false)
				{
					flag=false;
				}
			}

			if(flag)
			{
				document.getElementById('place_order').disabled=false;
			}
			else
			{
				document.getElementById('place_order').disabled=true;
			}
		}
	</script>
	<?
}
?>




<form method="post" action="payments_process.php">

<?if($_SESSION["billing_type"]=="credits"){?>
<input type="hidden" name="credits" value="<?=$_SESSION["billing_id"]?>">
<?}?>
<?if($_SESSION["billing_type"]=="subscription"){?>
<input type="hidden" name="subscription" value="<?=$_SESSION["billing_id"]?>">
<?}?>

<input type="hidden" name="payment" value="<?=$_SESSION["billing_payment"]?>">

<input type="hidden" name="tip" value="<?=$_SESSION["billing_type"]?>">


<?if(isset($_POST["telephone"])){?>
<input type="hidden" name="telephone" value="<?=result($_POST["telephone"])?>">
<?}?>

<?if(isset($_POST["bank"])){?>
<input type="hidden" name="bank" value="<?=result($_POST["bank"])?>">
<?}?>

<?if(isset($_POST["yandex_payments"])){?>
<input type="hidden" name="yandex_payments" value="<?=result($_POST["yandex_payments"])?>">
<?}?>

<?if(isset($_POST["moneyua_method"])){?>
<input type="hidden" name="moneyua_method" value="<?=result($_POST["moneyua_method"])?>">
<?}?>

<input id="place_order" class='isubmit' type="submit" value="<?=word_lang("place order")?>" style="margin-top:30px" <?=$disabled?>>

</form>







<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>