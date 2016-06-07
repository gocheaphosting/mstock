<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_customers");
?>
<? include("../inc/begin.php");?>



<div class="back"><a href="index.php" class="btn btn-primary btn-sm btn-mini"><i class="icon-arrow-left fa fa-arrow-left"></i> <?=word_lang("back")?></a></div>


<h1><?=word_lang("user")?> &mdash; <?
if(!isset($_GET["id"]))
{
	echo(word_lang("add")." ");
}
else
{
	echo(word_lang("edit")." ");
}
?></h1>
<div class="box box_padding">

<?
$user_fields=array();
$user_fields["login"]="";
$user_fields["name"]="";
$user_fields["email"]="";
$user_fields["telephone"]="";
$user_fields["address"]="";
$user_fields["accessdenied"]=0;
$user_fields["country"]="";
$user_fields["category"]=$global_settings["userstatus"];
$user_fields["lastname"]="";
$user_fields["city"]="";
$user_fields["state"]="";
$user_fields["zipcode"]="";
$user_fields["avatar"]="";
$user_fields["photo"]="";
$user_fields["description"]="";
$user_fields["website"]="";
$user_fields["utype"]="buyer";
$user_fields["company"]="";
$user_fields["newsletter"]="";
$user_fields["paypal"]="";
$user_fields["moneybookers"]="";
$user_fields["dwolla"]="";
$user_fields["qiwi"]="";
$user_fields["payson"]="";
$user_fields["webmoney"]="";
$user_fields["bank_name"]="";
$user_fields["bank_account"]="";
$user_fields["business"]=0;
$user_fields["vat"]="";
$user_fields["vat_checked"]=0;
$user_fields["vat_checked_date"]=0;
$user_fields["country_checked"]=0;
$user_fields["country_checked_date"]=0;
if($global_settings["examination"])
{
	$user_fields["examination"]=0;
}
else
{
	$user_fields["examination"]=1;
}
$user_fields["passport"]="";
$user_fields["authorization"]="site";
$user_fields["aff_commission_buyer"]=$global_settings["buyer_commission"];
$user_fields["aff_commission_seller"]=$global_settings["seller_commission"];
$user_fields["payout_limit"]=$global_settings["payout_limit"];


if(isset($_GET["id"]))
{
	$sql="select id_parent,login,name,email,telephone,address,data1,ip, accessdenied,country,category,lastname,city,state,zipcode,avatar,photo,description,website,utype,company,newsletter,paypal,moneybookers,examination,passport ,authorization,aff_commission_buyer,aff_commission_seller,aff_visits ,aff_signups,aff_referal,dwolla,qiwi,webmoney,business,bank_name,bank_account,payson,vat,country_checked,country_checked_date,vat_checked ,vat_checked_date,payout_limit from users where id_parent=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$user_fields["login"]=$rs->row["login"];
		$user_fields["name"]=$rs->row["name"];
		$user_fields["email"]=$rs->row["email"];
		$user_fields["telephone"]=$rs->row["telephone"];
		$user_fields["address"]=$rs->row["address"];
		$user_fields["data1"]=$rs->row["data1"];
		$user_fields["ip"]=$rs->row["ip"];
		$user_fields["accessdenied"]=$rs->row["accessdenied"];
		$user_fields["country"]=$rs->row["country"];
		$user_fields["category"]=$rs->row["category"];
		$user_fields["lastname"]=$rs->row["lastname"];
		$user_fields["city"]=$rs->row["city"];
		$user_fields["state"]=$rs->row["state"];
		$user_fields["zipcode"]=$rs->row["zipcode"];
		$user_fields["avatar"]=$rs->row["avatar"];
		$user_fields["photo"]=$rs->row["photo"];
		$user_fields["description"]=$rs->row["description"];
		$user_fields["website"]=$rs->row["website"];
		$user_fields["utype"]=$rs->row["utype"];
		$user_fields["company"]=$rs->row["company"];
		$user_fields["newsletter"]=$rs->row["newsletter"];
		$user_fields["paypal"]=$rs->row["paypal"];
		$user_fields["moneybookers"]=$rs->row["moneybookers"];
		$user_fields["dwolla"]=$rs->row["dwolla"];
		$user_fields["qiwi"]=$rs->row["qiwi"];
		$user_fields["webmoney"]=$rs->row["webmoney"];
		$user_fields["examination"]=$rs->row["examination"];
		$user_fields["passport"]=$rs->row["passport"];
		$user_fields["authorization"]=$rs->row["authorization"];
		$user_fields["aff_commission_buyer"]=$rs->row["aff_commission_buyer"];
		$user_fields["aff_commission_seller"]=$rs->row["aff_commission_seller"];
		$user_fields["aff_visits"]=$rs->row["aff_visits"];
		$user_fields["aff_signups"]=$rs->row["aff_signups"];
		$user_fields["aff_referal"]=$rs->row["aff_referal"];
		$user_fields["business"]=$rs->row["business"];
		$user_fields["bank_name"]=$rs->row["bank_name"];
		$user_fields["bank_account"]=$rs->row["bank_account"];
		$user_fields["payson"]=$rs->row["payson"];
		$user_fields["vat"]=$rs->row["vat"];
		$user_fields["country_checked"]=$rs->row["country_checked"];
		$user_fields["country_checked_date"]=$rs->row["country_checked_date"];
		$user_fields["vat_checked"]=$rs->row["vat_checked"];
		$user_fields["vat_checked_date"]=$rs->row["vat_checked_date"];
		$user_fields["payout_limit"]=$rs->row["payout_limit"];
	}
}
?>





<script type="text/javascript" language="JavaScript" src="<?=site_root?>/members/JsHttpRequest.js"></script>
<script language="javascript">



function check()
{
		flag=true
		if(document.getElementById('login_ok').value==-1 || document.getElementById('login_ok').value==0)
		{
			flag=false;
			document.getElementById('error_login').innerHTML="<span class='error'><?=word_lang("incorrect")?></span>";
		}

		if(document.getElementById('email_ok').value==-1 || document.getElementById('email_ok').value==0)
		{
			flag=false;
			document.getElementById('error_email').innerHTML="<span class='error'><?=word_lang("incorrect")?></span>";
		}
		return flag;
}


function check_login(value) 
{
	var req = new JsHttpRequest();
	req.onreadystatechange = function() 
	{
		if (req.readyState == 4) 
		{
			document.getElementById('error_login').innerHTML =req.responseText;
			if(req.responseText=="<span class='error'><?=word_lang("serror1")?></span>" || req.responseText=="<span class='error'><?=word_lang("incorrect")?></span>")
			{
				document.getElementById('login_ok').value=-1
			}
			else
			{
				document.getElementById('login_ok').value=1
			}
        }
    }
    req.open(null, '<?=site_root?>/members/check_login.php', true);
    req.send(  { login: value }  );
}



function check_email(value) 
{
	var req = new JsHttpRequest();
	req.onreadystatechange = function() 
	{
		if (req.readyState == 4) 
		{
			document.getElementById('error_email').innerHTML =req.responseText;
			if(req.responseText=="<span class='error'><?=word_lang("serror2")?></span>" || req.responseText=="<span class='error'><?=word_lang("incorrect")?></span>")
			{
				document.getElementById('email_ok').value=-1
			}
			else
			{
				document.getElementById('email_ok').value=1
			}
        }
    }
    req.open(null, '<?=site_root?>/members/check_email.php', true);
    req.send(  { email: value }  );
}



function check_password()
{
	if(document.getElementById('password2').value!="")
	{
		if(document.getElementById('password').value!=document.getElementById('password2').value)
		{
			document.getElementById('error_password').innerHTML ="<span class='error'><?=word_lang("not equal")?></span>";
		}
		else
		{
			document.getElementById('error_password').innerHTML ="";
		}
	}
}

function change_vat(value) 
{
	var req = new JsHttpRequest();
	req.onreadystatechange = function() 
	{
		if (req.readyState == 4) 
		{
			if(value == 0)
			{
				$('#vat1').addClass('btn-warning').removeClass('btn-default');
				$('#vat2').removeClass('btn-success').addClass('btn-default');
				$('#vat3').removeClass('btn-danger').addClass('btn-default');
			}
			if(value == 1)
			{
				$('#vat1').removeClass('btn-warning').addClass('btn-default');
				$('#vat2').addClass('btn-success').removeClass('btn-default');
				$('#vat3').removeClass('btn-danger').addClass('btn-default');
			}
			if(value == -1)
			{
				$('#vat1').removeClass('btn-warning').addClass('btn-default');
				$('#vat2').removeClass('btn-success').addClass('btn-default');
				$('#vat3').addClass('btn-danger').removeClass('btn-default');
			}
        }
    }
    req.open(null, 'check_vat.php', true);
    req.send(  { 'status': value,'id':<?=(int)@$_GET["id"]?> }  );
}

function change_country(value) 
{
	var req = new JsHttpRequest();
	req.onreadystatechange = function() 
	{
		if (req.readyState == 4) 
		{
			if(value == 0)
			{
				$('#country1').addClass('btn-warning').removeClass('btn-default');
				$('#country2').removeClass('btn-success').addClass('btn-default');
				$('#country3').removeClass('btn-danger').addClass('btn-default');
			}
			if(value == 1)
			{
				$('#country1').removeClass('btn-warning').addClass('btn-default');
				$('#country2').addClass('btn-success').removeClass('btn-default');
				$('#country3').removeClass('btn-danger').addClass('btn-default');
			}
			if(value == -1)
			{
				$('#country1').removeClass('btn-warning').addClass('btn-default');
				$('#country2').removeClass('btn-success').addClass('btn-default');
				$('#country3').addClass('btn-danger').removeClass('btn-default');
			}
        }
    }
    req.open(null, 'check_country.php', true);
    req.send(  { 'status': value,'id':<?=(int)@$_GET["id"]?> }  );
}


</script>







<form method="post" action="add.php<?if(isset($_GET["id"])){echo("?id=".$_GET["id"]);}?>"  Enctype="multipart/form-data" name="signupform" onsubmit="return check();">

<div class="subheader"><?=word_lang("common information")?></div>
<div class="subheader_text">


<div style="float:left;width:300px">

	<div class='admin_field'>
	<span><?=word_lang("login")?>:</span>
	<input type="text" id="login2" name="login" value="<?=$user_fields["login"]?>" style="width:200px"  onChange="check_login(this.value);"><div id="error_login" name="error_login"></div><input type="hidden" id="login_ok" name="login_ok" value="<?if(!isset($_GET["id"])){?>0<?}else{?>1<?}?>">
	</div>

<?if($user_fields["authorization"]=="site"){?>	
	<div class='admin_field'>
	<span><?=word_lang("password")?>:</span>
	<input id="password" type="password" name="password" value="<?if(isset($_GET["id"])){echo("********");}?>" style="width:200px" onChange="check_password();">
	<div id="error_password" name="error_password"></div>
	</div>
	

	<div class='admin_field'>
	<span><?=word_lang("confirm password")?>:</span>
	<input id="password2" type="password" name="password2" value="<?if(isset($_GET["id"])){echo("********");}?>" style="width:200px" onChange="check_password();">
	<div id="error_password2" name="error_password2"></div>
	</div>
<?}?>

	<div class='admin_field'>
	<span><?=word_lang("e-mail")?>:</span>
	<input type="text" id="email" name="email" onChange="check_email(this.value);" value="<?=$user_fields["email"]?>" style="width:200px"><div id="error_email" name="error_email" class="error"></div><input type="hidden" id="email_ok" name="email_ok" value="<?if(!isset($_GET["id"])){?>0<?}else{?>1<?}?>">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("status")?>:</span>
	<select style="width:200px" name="accessdenied">
		<option <?if($user_fields["accessdenied"]==0){echo("selected");}?> value="0"><?=word_lang("active")?></option>
		<option <?if($user_fields["accessdenied"]==1){echo("selected");}?> value="1"><?=word_lang("access denied")?></option>
	</select>
	</div>

	
	<div class='admin_field'>
	<span><?=word_lang("newsletter")?>:</span>
	<input type="checkbox" name="newsletter" <?if($user_fields["newsletter"]==1){echo("checked");}?>>
	</div>
	
	
</div>
<?if(isset($_GET["id"])){?>
<div class="box_stats box box-solid">
	
	<div><b><?=word_lang("date of registration")?>:</b> <?=date(date_short,$user_fields["data1"])?></div>
	
	<div>
		<b><?=word_lang("authorization")?>:</b> 
		<?
		if($user_fields["authorization"]=="site")
		{
			echo(word_lang("website"));
		}
		else
		{
			if($user_fields["authorization"]=="twitter")
			{
				echo("<a href='http://www.twitter.com/".$user_fields["login"]."'>Twitter</a>");
			}
			if($user_fields["authorization"]=="facebook")
			{
				echo("<a href='http://www.facebook.com/profile.php?id=".str_replace("fb","",$user_fields["login"])."'>Facebook</a>");
			}
			if($user_fields["authorization"]=="vk")
			{
				echo("<a href='http://vk.com/id".str_replace("vk","",$user_fields["login"])."'>Vkontakte</a>");
			}
			if($user_fields["authorization"]=="instagram")
			{
				echo("<a href='http://instagram.com/".str_replace("instagram_","",$user_fields["login"])."'>Instagram</a>");
			}
		}
		?>
	</div>
	
	
	<div><b>IP:</b> <?=$user_fields["ip"]?></div>
	
	<?
	if($user_fields["utype"]=="buyer" or $user_fields["utype"]=="common")
	{
		?>
		<div>
			<b><?=word_lang("orders")?>:</b> 
			<a href="../orders/index.php?search=<?=$user_fields["login"]?>&search_type=login">
			<?
				$sql="select count(id) as order_count from orders where user=".(int)$_GET["id"];
				$rs->open($sql);
				if(!$rs->eof)
				{
					echo($rs->row["order_count"]);
				}
				else
				{
					echo(0);
				}
			?>
			</a>
		</div>
		<?
		if($global_settings["credits"])
		{
			?>
			<div>
			<b><?=word_lang("credits")?>:</b> 
			<a href="../credits/index.php?search=<?=$user_fields["login"]?>&search_type=login">
			<?
				$sql="select sum(quantity) as credits_count from credits_list where user='".$user_fields["login"]."'";
				$rs->open($sql);
				if(!$rs->eof)
				{
					echo($rs->row["credits_count"]);
				}
				else
				{
					echo(0);
				}
			?>
			</a>
			</div>
			<?
		}
		if($global_settings["subscription"])
		{
			?>
			<div>
			<b><?=word_lang("subscription")?>:</b> 
			<a href="../subscription_list/index.php?search=<?=$user_fields["login"]?>&search_type=login">
			<?
				$sql="select count(id_parent) as subscription_count from subscription_list where user='".$user_fields["login"]."'";
				$rs->open($sql);
				if(!$rs->eof)
				{
					echo($rs->row["subscription_count"]);
				}
				else
				{
					echo(0);
				}
			?>
			</a>
			</div>
			<?
		}
	}
	if($user_fields["utype"]=="seller" or $user_fields["utype"]=="common")
	{
		$userbalance=0;
		$sales_count=0;

		$sql="select user,total from commission where user=".(int)$_GET["id"];
		$ds->open($sql);
		while(!$ds->eof)
		{
			$userbalance+=$ds->row["total"];
			if($ds->row["total"]>0)
			{
				$sales_count++;
			}
			$ds->movenext();
		}
		
		?>
			<div>
				<b><?=word_lang("sales")?>:</b> <a href="../commission/index.php?search=<?=$user_fields["login"]?>&search_type=login&pub_type=plus"><?=$sales_count?></a>
			</div>
			<div>
				<b><?=word_lang("commission")?>:</b> <a href="../commission/index.php?d=3"><?=currency(1,false)?><?=$userbalance?>
				 <?=currency(2,false)?></a>
			</div>
		<?
	}
	if($user_fields["utype"]=="affiliate" or $user_fields["utype"]=="common")
	{
		$affbalance=0;

		$sql="select total from affiliates_signups where aff_referal=".(int)$_GET["id"];
		$ds->open($sql);
		while(!$ds->eof)
		{
			$affbalance+=$ds->row["total"];
			$ds->movenext();
		}
		?>
			<div>
				<b><?=word_lang("affiliate")?> - <?=word_lang("commission")?>:</b> <a href="../affiliates/index.php?search=<?=$user_fields["login"]?>&search_type=affiliate"><?=currency(1,false)?><?=$affbalance?>
				 <?=currency(2,false)?></a>
			</div>
		<?
	}
	if($user_fields["utype"]=="buyer" or $user_fields["utype"]=="common")
	{
		$downloads_count=0;
		
		$sql="select count(id) as downloads_count from downloads where user_id=".user_url($user_fields["login"])." group by user_id";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$downloads_count=$ds->row["downloads_count"];
		}
		?>
		<div>
			<b><?=word_lang("downloads")?>:</b> <a href="../downloads/?search=<?=$user_fields["login"]?>&search_type=user"><?=$downloads_count?></a>
		</div>
		<?
	}
	?>
<hr />
<?
	$documents_count=0;
	$sql="select count(id) as documents_count from documents where user_id=".user_url($user_fields["login"])." group by user_id";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$documents_count=$ds->row["documents_count"];
	}
	?>
	<div>
		<b><?=word_lang("Documents")?>:</b> <a href="../documents/?search=<?=$user_fields["login"]?>"><?=$documents_count?></a>
	</div>
	
	
	<div>
		<b><?=word_lang("country")?>:</b> <?=$user_fields["country"]?></a>
	</div>
		<a href="javascript:change_country(0)" id="country1" class="btn btn-<?if((int)$user_fields["country_checked"]==0){echo("warning");}else{echo("default");}?> btn-xs"><?=word_lang("Not checked")?></a>
		<a href="javascript:change_country(1)" id="country2" class="btn btn-<?if((int)$user_fields["country_checked"]==1){echo("success");}else{echo("default");}?>  btn-xs"><?=word_lang("Valid")?></a>
		<a href="javascript:change_country(-1)" id="country3" class="btn btn-<?if((int)$user_fields["country_checked"]==-1){echo("danger");}else{echo("default");}?>  btn-xs"><?=word_lang("Invalid")?></a>
		<?
		if($user_fields["country_checked_date"] != 0)
		{
			?>
			<br><small><?=word_lang("Last check")?>: <?=show_time_ago($user_fields["country_checked_date"])?></small>
			<?
		}
		?>
	<?
	if($user_fields["vat"]!="")
	{
		$vatCountry = substr($user_fields["vat"], 0, 2);
        $vatNumber = substr($user_fields["vat"], 2);
       	$apiURL = "http://ec.europa.eu/taxation_customs/vies/viesquer.do?ms=".$vatCountry."&vat=".$vatNumber;
		?>
		<hr />
		<div>
		<b><?=word_lang("VAT number")?>:</b> <?=$user_fields["vat"]?> <a href="<? echo "$apiURL"; ?>" target="blank" class="btn btn-xs btn-primary"><?=word_lang("Online check")?></a>
		</div>
		<div>
		<a href="javascript:change_vat(0)" id="vat1" class="btn btn-<?if((int)$user_fields["vat_checked"]==0){echo("warning");}else{echo("default");}?> btn-xs"><?=word_lang("Not checked")?></a>
		<a href="javascript:change_vat(1)" id="vat2" class="btn btn-<?if((int)$user_fields["vat_checked"]==1){echo("success");}else{echo("default");}?>  btn-xs"><?=word_lang("Valid")?></a>
		<a href="javascript:change_vat(-1)" id="vat3" class="btn btn-<?if((int)$user_fields["vat_checked"]==-1){echo("danger");}else{echo("default");}?>  btn-xs"><?=word_lang("Invalid")?></a>
		<?
		if($user_fields["vat_checked_date"] != 0)
		{
			?>
			<br><small><?=word_lang("Last check")?>: <?=show_time_ago($user_fields["vat_checked_date"])?></small>
			<?
		}
		?>
		</div>
		<?
	}
	?>
	<div style="margin-top:15px">
		<hr />
		<a class="btn btn-success" href="login.php?id=<?=(int)$_GET["id"]?>"><?=word_lang("login on the frontend")?></a>
	</div>
</div>
<?}?>
	
</div>


<script language="javascript">

function show_fields(types)
{
	if(types=="buyer")
	{
		document.getElementById('field_category').style.display="none";
		document.getElementById('field_examination').style.display="none";
		document.getElementById('field_paypal').style.display="none";
		document.getElementById('field_moneybookers').style.display="none";
		document.getElementById('field_dwolla').style.display="none";
		document.getElementById('field_qiwi').style.display="none";
		document.getElementById('field_webmoney').style.display="none";
		document.getElementById('field_bank').style.display="none";
		document.getElementById('field_payson').style.display="none";
		document.getElementById('field_aff_commission_buyer').style.display="none";
		document.getElementById('field_aff_commission_seller').style.display="none";
		
		document.getElementById('field_payout_limit').style.display="none";
										
	}
	if(types=="seller")
	{
		document.getElementById('field_category').style.display="block";
		document.getElementById('field_examination').style.display="block";
		document.getElementById('field_paypal').style.display="block";
		document.getElementById('field_moneybookers').style.display="block";
		document.getElementById('field_dwolla').style.display="block";
		document.getElementById('field_qiwi').style.display="block";
		document.getElementById('field_webmoney').style.display="block";
		document.getElementById('field_bank').style.display="block";
		document.getElementById('field_payson').style.display="block";
		document.getElementById('field_payout_limit').style.display="block";
		document.getElementById('field_aff_commission_buyer').style.display="none";
		document.getElementById('field_aff_commission_seller').style.display="none";
	}
	if(types=="affiliate")
	{
		document.getElementById('field_category').style.display="none";
		document.getElementById('field_examination').style.display="none";
		document.getElementById('field_paypal').style.display="block";
		document.getElementById('field_moneybookers').style.display="block";
		document.getElementById('field_dwolla').style.display="block";
		document.getElementById('field_qiwi').style.display="block";
		document.getElementById('field_webmoney').style.display="block";
		document.getElementById('field_bank').style.display="block";
		document.getElementById('field_payson').style.display="block";
		document.getElementById('field_payout_limit').style.display="block";
		document.getElementById('field_aff_commission_buyer').style.display="block";
		document.getElementById('field_aff_commission_seller').style.display="block";
	}
	if(types=="common")
	{
		document.getElementById('field_category').style.display="block";
		document.getElementById('field_examination').style.display="block";
		document.getElementById('field_paypal').style.display="block";
		document.getElementById('field_moneybookers').style.display="block";
		document.getElementById('field_dwolla').style.display="block";
		document.getElementById('field_qiwi').style.display="block";
		document.getElementById('field_webmoney').style.display="block";
		document.getElementById('field_bank').style.display="block";
		document.getElementById('field_payson').style.display="block";
		document.getElementById('field_payout_limit').style.display="block";
		document.getElementById('field_aff_commission_buyer').style.display="block";
		document.getElementById('field_aff_commission_seller').style.display="block";
	}
}

</script>

<div class="subheader"><?=word_lang("customer information")?></div>
<div class="subheader_text">
	<div class='admin_field'>
	<span><?=word_lang("type")?>:</span>
	<input type="radio" name="utype" <?if($user_fields["utype"]=="buyer"){echo("checked");}?> onClick="show_fields('buyer')" value="buyer"> <?=word_lang("buyer")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="utype" <?if($user_fields["utype"]=="seller"){echo("checked");}?> onClick="show_fields('seller')" value="seller"> <?=word_lang("seller")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="utype" <?if($user_fields["utype"]=="affiliate"){echo("checked");}?> onClick="show_fields('affiliate')" value="affiliate"> <?=word_lang("affiliate")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="utype" <?if($user_fields["utype"]=="common"){echo("checked");}?> onClick="show_fields('common')" value="common"> <?=word_lang("common")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	
	
	<div class='admin_field' id="field_category">
	<span><?=word_lang("category")?>:</span>
	<select name="category" style="width:200px">
		<?
		$sql="select name from user_category order by name";
		$rs->open($sql);
		while(!$rs->eof)
		{
			$sel="";
			if($rs->row["name"]==$user_fields["category"])
			{
				$sel="selected";
			}
			?>
			<option value="<?=$rs->row["name"]?>" <?=$sel?>><?=$rs->row["name"]?></option>
			<?
			$rs->movenext();
		}
		?>
	</select>
	</div>
	
	<div class='admin_field' id="field_examination">
	<span><?=word_lang("examination")?>:</span>
	<input type="checkbox" name="examination" <?if($user_fields["examination"]==1){echo("checked");}?>>
	</div>
	
	<div class='admin_field' id="field_payout_limit">
	<span><?=word_lang("Balance threshold for payout")?> (<?=$currency_code1?>):</span>
	<input type="text" name="payout_limit" value="<?=float_opt($user_fields["payout_limit"],2)?>" style="width:200px">
	</div>
	
	
	<?
	$sql="select * from payout where activ=1";
	$ds->open($sql);
	while(!$ds->eof)
	{
		?>
		<div class='admin_field' id="field_<?=$ds->row["svalue"]?>">
				<span><?=$ds->row["title"]?>:</span>
		<?
		if($ds->row["svalue"]=="bank")
		{
			?>
				<input class="ibox form-control" type="text" name="bank_account" value="<?=$user_fields["bank_account"]?>" style="width:230px;display:inline">
				<select class="ibox form-control"  name="bank_name" style="width:150px;display:inline">
					<option value=""><?=word_lang("banks")?></option>
						<?
							$sql="select title from banks order by title";
							$dn->open($sql);
							while(!$dn->eof)
							{
								$sel="";
								if($dn->row["title"]==$user_fields["bank_name"])
								{
									$sel="selected";
								}
								?>
								<option value="<?=$dn->row["title"]?>" <?=$sel?>><?=$dn->row["title"]?></option>
								<?
								$dn->movenext();
							}
						?>
				</select>
			<?
		}
		else
		{
			?>
				<input type="text" name="<?=$ds->row["svalue"]?>" value="<?=$user_fields[$ds->row["svalue"]]?>" style="width:200px">
			
		<?
		}
		?>
		</div>
		<?
		$ds->movenext();
	}
	?>
	
	<div class='admin_field' id="field_aff_commission_buyer">
	<span>Buyer signup commission:</span>
	<input type="text" name="aff_commission_buyer" value="<?=$user_fields["aff_commission_buyer"]?>" style="width:200px">
	</div>
	
	<div class='admin_field' id="field_aff_commission_seller">
	<span>Seller signup commission:</span>
	<input type="text" name="aff_commission_seller" value="<?=$user_fields["aff_commission_seller"]?>" style="width:200px">
	</div>
	
	<script language="javascript">
		show_fields('<?=$user_fields["utype"]?>');
	</script>

</div>


<div class="subheader"><?=word_lang("contacts information")?></div>
<div class="subheader_text">

	<div class='admin_field'>
	<span><?=word_lang("first name")?>:</span>
	<input type="text" name="name" value="<?=$user_fields["name"]?>" style="width:300px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("last name")?>:</span>
	<input type="text" name="lastname" value="<?=$user_fields["lastname"]?>" style="width:300px">
	</div>
	

	
	<div class='admin_field'>
	<span><?=word_lang("telephone")?>:</span>
	<input type="text" name="telephone" value="<?=$user_fields["telephone"]?>" style="width:300px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("country")?>:</span>
	<select name="country" style="width:300px">
	<option value=""></option>
			<?
			$sql="select name from countries where activ=1 order by priority,name";
			$dd->open($sql);
			while(!$dd->eof)
			{
				$sel="";
				if($dd->row["name"]==$user_fields["country"]){$sel="selected";}
	
				?>
				<option value="<?=$dd->row["name"]?>" <?=$sel?>><?=$dd->row["name"]?></option>
				<?
				
				$dd->movenext();
			}
			?>
	</select>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("city")?>:</span>
	<input type="text" name="city" value="<?=$user_fields["city"]?>" style="width:300px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("state")?>:</span>
	<input type="text" name="state" value="<?=$user_fields["state"]?>" style="width:300px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("zipcode")?>:</span>
	<input type="text" name="zipcode" value="<?=$user_fields["zipcode"]?>" style="width:300px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("address")?>:</span>
	<textarea name="address" style="width:300px;height:50px"><?=$user_fields["address"]?></textarea> 
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("company")?>:</span>
	<input type="text" name="company" value="<?=$user_fields["company"]?>" style="width:300px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("website")?>:</span>
	<input type="text" name="website" value="<?=$user_fields["website"]?>" style="width:300px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("description")?>:</span>
	<textarea name="description" style="width:300px;height:50px"><?=$user_fields["description"]?></textarea> 
	</div>
	
	<div class="admin_field">
	<span><?=word_lang("status")?></span>
	<select name="business" style="width:310px">
		<option value="0" <?if($user_fields["business"]==0){echo("selected");}?>><?=word_lang("individual")?></option>
		<option value="1" <?if($user_fields["business"]==1){echo("selected");}?>><?=word_lang("business")?></option>
	</select>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("VAT number")?>:</span>
	<input type="text" name="vat" value="<?=$user_fields["vat"]?>" style="width:300px">
	</div>

</div>


<div class="subheader"><?=word_lang("photo")?></div>
<div class="subheader_text">

	<div class='admin_field'>
	<span><?=word_lang("photo")?>:</span>
	<input type="file" name="photo" style="width:300px">
	<?
	if($user_fields["photo"]!="")
	{
	?>
		<div style='padding-top:3px'><img 	src='<?=$user_fields["photo"]?>' style='margin:5px 0px 5px 0px'><br><a href='delete_photo.php?id=<?=$_GET["id"]?>&type=photo'><?=word_lang("delete")?></a></div>
	<?
	}
	?>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("avatar")?>:</span>
	<input type="file" name="avatar" style="width:300px">
	<?
	if($user_fields["avatar"]!="")
	{
	?>
		<div style='padding-top:3px'><img 	src='<?=$user_fields["avatar"]?>' style='margin:5px 0px 5px 0px'><br><a href='delete_photo.php?id=<?=$_GET["id"]?>&type=avatar'><?=word_lang("delete")?></a></div>
	<?
	}
	?>
	</div>
	




</div>

<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">
			<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>">
		</div>
	</div>


</form>

</div>


<? include("../inc/end.php");?>