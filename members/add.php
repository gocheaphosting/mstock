<?include("../admin/function/db.php");?>
<?
$_SESSION["login"]=@$_POST["login"];
$_SESSION["name"]=@$_POST["name"];
$_SESSION["lastname"]=@$_POST["lastname"];
$_SESSION["city"]=@$_POST["city"];
$_SESSION["state"]=@$_POST["state"];
$_SESSION["zipcode"]=@$_POST["zipcode"];
$_SESSION["country"]=@$_POST["country"];
$_SESSION["telephone"]=@$_POST["telephone"];
$_SESSION["address"]=@$_POST["address"];
$_SESSION["email"]=@$_POST["email"];
$_SESSION["utype"]=@$_POST["utype"];
$_SESSION["website"]=@$_POST["website"];
$_SESSION["company"]=@$_POST["company"];
$_SESSION["business"]=@$_POST["business"];


//ѕровер€ем есть ли такой пользователь
$sql="select login from users where login='".result($_POST["login"])."'";
$rs->open($sql);
if(!$rs->eof)
{
			if($_POST["utype"]=="seller" and $global_settings["user_signup"]==2)
			{
				header("location:signup.php?utype=seller&step=1&d=1");
				exit();
			}
			else
			{
				header("location:signup.php?d=1");
				exit();
			}
}
else
{
	$sql="select * from users where email='".result($_POST["email"])."'";
	$rs->open($sql);
	if(!$rs->eof)
	{
			if($_POST["utype"]=="seller" and $global_settings["user_signup"]==2)
			{
				header("location:signup.php?utype=seller&step=1&d=2");
				exit();
			}
			else
			{
				header("location:signup.php?d=2");
				exit();
			}
	}
	else
	{
		//Check captcha
		require_once('../admin/function/recaptchalib.php');
		$flag_captcha=check_captcha();
		
		if($flag_captcha and $_POST["login"]!="" and $_POST["password"]!="")
		{


			//Define type of the activation
			$accessdenied=0;
			$activation="on";
			$sql="select svalue from users_settings where activ=1";
			$rs->open($sql);
			if(!$rs->eof)
			{
				$activation=$rs->row["svalue"];
				if($activation=="on"){$accessdenied=0;}
				else{$accessdenied=1;}
			}

			//Examination
			if($global_settings["examination"]){$exam=0;}
			else{$exam=1;}

			if(isset($_POST["newsletter"])){$newsletter=1;}
			else{$newsletter=0;}
		
			$aff=0;
			if(isset($_COOKIE["aff"]))
			{
				$aff=(int)$_COOKIE["aff"];
			}

			$sql="insert into users (login,password,name,country,telephone,address,email,data1,ip,accessdenied,lastname,city,state,zipcode,category,website,utype,company,newsletter,examination,authorization,aff_commission_buyer,aff_commission_seller,aff_visits,aff_signups,aff_referal,business,vat,payout_limit) values ('".result($_POST["login"])."','".md5(result($_POST["password"]))."','".result(@$_POST["name"])."','".result(@$_POST["country"])."','".result(@$_POST["telephone"])."','".result(@$_POST["address"])."','".result(@$_POST["email"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SERVER["REMOTE_ADDR"])."',".$accessdenied.",'".result(@$_POST["lastname"])."','".result(@$_POST["city"])."','".result(@$_POST["state"])."','".result(@$_POST["zipcode"])."','".$global_settings["userstatus"]."','".result(@$_POST["website"])."','".result(@$_POST["utype"])."','".result(@$_POST["company"])."',".$newsletter.",".$exam.",'site',".$global_settings["buyer_commission"].",".$global_settings["seller_commission"].",0,0,".$aff.",".(int)@$_POST["business"].",'".result(@$_POST["vat"])."',".(int)$global_settings["payout_limit"].")";
			$db->execute($sql);
		
			$sql="select id_parent from users where 	login='".result($_POST["login"])."'";
			$rs->open($sql);
			$id=$rs->row['id_parent'];
			
			if(isset($_COOKIE["aff"]))
			{
				affiliate_add($aff,$id,result($_POST["utype"]));
			}


			$_SESSION["reguser"]=$id;


			//Confirmation email
			if($activation=="user")
			{
				$confirmation_link=surl.site_root."/members/confirm.php?id=".$id."&login=".$_POST["login"];
				send_notification('signup_to_user',$confirmation_link);
			}
			send_notification('signup_to_admin',$id);
	

			//insert the coupon for new user
			coupons_add(result($_POST["login"]),"New Signup");


			if($_POST["utype"]=="seller" and $global_settings["user_signup"]==2)
			{
				header("location:signup.php?utype=seller&step=2");
			}
			else
			{
				header("location:thanks.php?activation=".$activation);
			}
		}
		else
		{
			if($_POST["utype"]=="seller" and $global_settings["user_signup"]==2)
			{
				header("location:signup.php?utype=seller&step=1&d=4");
				exit();
			}
			else
			{
				header("location:signup.php?d=4");
				exit();
			}
		}
	}
}








?>