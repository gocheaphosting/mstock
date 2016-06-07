<?$site="signup";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<div class="page_internal">
<h1><?=word_lang("sign up")?></h1>


<?
if(!isset($_SESSION["login"])){$user_fields["login"]="";}
else{$user_fields["login"]=$_SESSION["login"];}

if(!isset($_SESSION["name"])){$user_fields["name"]="";}
else{$user_fields["name"]=$_SESSION["name"];}

if(!isset($_SESSION["lastname"])){$user_fields["lastname"]="";}
else{$user_fields["lastname"]=$_SESSION["lastname"];}

if(!isset($_SESSION["country"])){$user_fields["country"]="";}
else{$user_fields["country"]=$_SESSION["country"];}

if(!isset($_SESSION["telephone"])){$user_fields["telephone"]="";}
else{$user_fields["telephone"]=$_SESSION["telephone"];}

if(!isset($_SESSION["address"])){$user_fields["address"]="";}
else{$user_fields["address"]=$_SESSION["address"];}

if(!isset($_SESSION["email"])){$user_fields["email"]="";}
else{$user_fields["email"]=$_SESSION["email"];}

if(!isset($_SESSION["city"])){$user_fields["city"]="";}
else{$user_fields["city"]=$_SESSION["city"];}

if(!isset($_SESSION["state"])){$user_fields["state"]="";}
else{$user_fields["state"]=$_SESSION["state"];}


if(!isset($_SESSION["zipcode"])){$user_fields["zipcode"]="";}
else{$user_fields["zipcode"]=$_SESSION["zipcode"];}

if(!isset($_SESSION["description"])){$user_fields["description"]="";}
else{$user_fields["description"]=$_SESSION["description"];}

if(!isset($_SESSION["website"])){$user_fields["website"]="";}
else{$user_fields["website"]=$_SESSION["website"];}

if(!isset($_SESSION["utype"])){$user_fields["utype"]="buyer";}
else{$user_fields["utype"]=$_SESSION["utype"];}

if(!isset($_SESSION["company"])){$user_fields["company"]="";}
else{$user_fields["company"]=$_SESSION["company"];}

if(!isset($_SESSION["newsletter"])){$user_fields["newsletter"]=1;}
else{$user_fields["newsletter"]=$_SESSION["newsletter"];}

if(!isset($_SESSION["business"])){$user_fields["business"]=0;}
else{$user_fields["business"]=$_SESSION["business"];}

if(!isset($_SESSION["vat"])){$user_fields["vat"]="";}
else{$user_fields["vat"]=$_SESSION["vat"];}

$ss="add";


if(!isset($_GET["utype"]))
{
	if($global_settings["common_account"])
	{
		$_GET["utype"]="common";
	}
	else
	{
		$_GET["utype"]="buyer";
	}
}
if(!isset($_GET["step"])){$_GET["step"]=1;}


$user_fields["utype"]=$_GET["utype"];






if($global_settings["user_signup"]==1)
{
	//One step signup
	include("signup_content.php");
}
else
{
	//Multiple steps sign up
	
	
	if($_GET["step"]==1 and $_GET["utype"]=="common")
	{
		?>
		<h2><?=word_lang("customer agreement")?></h2>
		<iframe src="<?=site_root?>/members/agreement.php?type=common" frameborder="no" scrolling="yes" class="framestyle_buyer"></iframe>
		<div style="margin-top:20px">
		<input name="terms" type="radio" value="1" onClick="document.buyerform.subm.disabled=false;"> <?=word_lang("i agree")?>
		<form method="post" name="buyerform" action="signup.php?step=2&utype=common" style="margin-top:10px">
		<input class='isubmit' value="<?=word_lang("continue")?>" name="subm" type="submit" disabled>
		</form>
		</div>
	<?
	}
	
	
	if($_GET["step"]==1 and $_GET["utype"]=="buyer")
	{
		?>
		<h2><?=word_lang("buyer agreement")?></h2>
		<iframe src="<?=site_root?>/members/agreement.php?type=buyer" frameborder="no" scrolling="yes" class="framestyle_buyer"></iframe>
		<div style="margin-top:20px">
		<input name="terms" type="radio" value="1" onClick="document.buyerform.subm.disabled=false;"> <?=word_lang("i agree")?>
		<form method="post" name="buyerform" action="signup.php?step=2&utype=buyer" style="margin-top:10px">
		<input class='isubmit' value="<?=word_lang("continue")?>" name="subm" type="submit" disabled>
		</form>
		</div>
	<?
	}


	if($_GET["step"]==1 and $_GET["utype"]=="affiliate")
	{
		?>
		<h2><?=word_lang("affiliate agreement")?></h2>

		<iframe src="<?=site_root?>/members/agreement.php?type=affiliate" frameborder="no" scrolling="yes" class="framestyle_buyer"></iframe>

		<div style="margin-top:20px">
		<input name="terms" type="radio" value="1" onClick="document.buyerform.subm.disabled=false;"> <?=word_lang("i agree")?>
		<form method="post" name="buyerform" action="signup.php?step=2&utype=affiliate" style="margin-top:10px">
		<input class='isubmit' value="<?=word_lang("continue")?>" name="subm" type="submit" disabled>
		</form>
		</div>
		<?
	}

	if($_GET["step"]==2 and ($_GET["utype"]=="buyer" or $_GET["utype"]=="affiliate" or $_GET["utype"]=="common"))
	{
		include("signup_content.php");
	}



	if($_GET["step"]==1 and $_GET["utype"]=="seller")
	{
		include("signup_content.php");
	}



	if($_GET["step"]==2 and $_GET["utype"]=="seller")
	{
		$sql="select content from pages where link='lecture'";
		$rs->open($sql);
		if(!$rs->eof)
		{
			echo(translate_text($rs->row["content"]));
		}
		?>
			<div style="margin-top:20px">
			<input name="terms" type="radio" value="1" onClick="document.sellerform.subm.disabled=false;"> <?=word_lang("i agree")?>
			<form method="post" name="sellerform" action="signup.php?step=3&utype=seller" style="margin-top:10px">
			<input class='isubmit' value="<?=word_lang("continue")?>" name="subm" type="submit" disabled>
			</form>
			</div>
		<?
	}


	if($_GET["step"]==3 and $_GET["utype"]=="seller")
	{
		?>
		<h2><?=word_lang("seller agreement")?></h2>
		<iframe src="<?=site_root?>/members/agreement.php?type=seller" frameborder="no" scrolling="yes" class="framestyle_seller"></iframe>

		<?if(isset($_GET["passport"])){echo("<p class=error>".word_lang("incorrect")." ".word_lang("passport")."</p>");}?>

		<form method="post" Enctype="multipart/form-data" name="sellerform" action="passport_upload.php">
		<div style="margin-top:20px;margin-bottom:2px"><b><?=word_lang("passport")?>:</b></div>
		<div><input style="width:300px" type="file" name="passport"><br><span class="smalltext">(*.jpg, <?=word_lang("size")?> < 2Mb.)</span></div>

		<div style="margin-top:15px">
		<input name="terms" type="radio" value="1" onClick="document.sellerform.subm.disabled=false;"> <?=word_lang("i agree")?><br>

		<input class='isubmit' value="<?=word_lang("continue")?>" name="subm" type="submit" disabled style="margin-top:10px">

		</div>
		</form>
		<?
	}
}
?>







</div>
<?include("../inc/footer.php");?>