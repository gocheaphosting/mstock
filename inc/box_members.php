<?
if(!defined("site_root")){exit();}




if(!isset($_SESSION['people_id']))
{
	$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."box_members_login.tpl");
	$boxcontent=str_replace("{WORD_LOGIN}",word_lang("login"),$boxcontent);
	$boxcontent=str_replace("{WORD_PASSWORD}",word_lang("password"),$boxcontent);
	$boxcontent=str_replace("{WORD_FORGOT}",word_lang("forgot password"),$boxcontent);

	if($global_settings["user_signup"]==2 and $global_settings["userupload"]==1 and !$global_settings["common_account"])
	{
		$boxcontent=str_replace("{WORD_SIGNUP}",word_lang("become a buyer"),$boxcontent);
	}
	else
	{
		$boxcontent=str_replace("{WORD_SIGNUP}",word_lang("sign up"),$boxcontent);
	}
	$boxcontent=str_replace("{WORD_SIGNUP_SELLER}",word_lang("become a seller"),$boxcontent);
	$boxcontent=str_replace("{WORD_SIGNUP_AFFILIATE}",word_lang("become an affiliate"),$boxcontent);



	$flag_seller=false;
	if($global_settings["userupload"] and $global_settings["user_signup"]==2 and !$global_settings["common_account"])
	{
		$flag_seller=true;
	}	
	$boxcontent=format_layout($boxcontent,"seller",$flag_seller);
	
	$flag_affiliate=false;
	if($global_settings["affiliates"] and $global_settings["user_signup"]==2 and !$global_settings["common_account"])
	{
		$flag_affiliate=true;
	}	
	$boxcontent=format_layout($boxcontent,"affiliate",$flag_affiliate);
	
	$social_result=get_social_networks();
	$boxcontent=str_replace("{SOCIAL_NETWORKS}",$social_result["vertical"],$boxcontent);
	$boxcontent=str_replace("{SOCIAL_NETWORKS_HORIZONTAL}",$social_result["horizontal"],$boxcontent);
	
	$boxcontent=format_layout($boxcontent,"facebook",@$_SESSION["social_result"]["facebook"]);
	$boxcontent=format_layout($boxcontent,"twitter",@$_SESSION["social_result"]["twitter"]);
	$boxcontent=format_layout($boxcontent,"vkontakte",@$_SESSION["social_result"]["vkontakte"]);
	$boxcontent=format_layout($boxcontent,"instagram",@$_SESSION["social_result"]["instagram"]);

	$box_members=$boxcontent;
}
else
{
	$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."box_members_profile.tpl");
	
	$boxcontent=str_replace("{WORD_USERNAME}",$_SESSION["people_login"],$boxcontent);


	$flag_credits=false;
	if(($global_settings["credits"] and $_SESSION["people_type"]=="buyer") or $_SESSION["people_type"]=="seller" or $_SESSION["people_type"]=="affiliate")
	{
		$flag_credits=true;
	}	
	$boxcontent=format_layout($boxcontent,"credits",$flag_credits);
	
	
	$flag_seller=false;
	if($_SESSION["people_type"]=="seller")
	{
		$flag_seller=true;
	}	
	$boxcontent=format_layout($boxcontent,"seller",$flag_seller);
	
	
	$flag_affiliate=false;
	if($_SESSION["people_type"]=="affiliate")
	{
		$flag_affiliate=true;
	}	
	$boxcontent=format_layout($boxcontent,"affiliate",$flag_affiliate);
	

	$flag_subscription=false;
	if($global_settings["subscription"] and $global_settings["credits"] and $_SESSION["people_type"]=="buyer")
	{
		$flag_subscription=true;
	}	
	$boxcontent=format_layout($boxcontent,"subscription",$flag_subscription);
	
	
	$flag_buyer=false;
	if($_SESSION["people_type"]=="buyer")
	{
		$flag_buyer=true;
	}	
	$boxcontent=format_layout($boxcontent,"buyer",$flag_buyer);



	if($_SESSION["people_type"]=="buyer")
	{
		if($global_settings["credits"])
		{
			$credits_balance=credits_balance();
			$credits_balance=(float)$credits_balance;
			$credits_balance=float_opt($credits_balance,2);
			$boxcontent=str_replace("{BALANCE}",strval($credits_balance)." Credits",$boxcontent);
			$boxcontent=str_replace("{WORD_BALANCE}",word_lang("balance"),$boxcontent);
		}
		else
		{
			$boxcontent=str_replace("{BALANCE}","",$boxcontent);
			$boxcontent=str_replace("{WORD_BALANCE}","",$boxcontent);
		}
		$boxcontent=str_replace("profile.php","profile_home.php",$boxcontent);
	}
	
	
	if($_SESSION["people_type"]=="seller")
	{
		$commission_balance=0;
		$sql="select user,total from commission where user=".(int)$_SESSION["people_id"]." and status=1";
		$ds->open($sql);
		while(!$ds->eof)
		{
			$commission_balance+=$ds->row["total"];
			$ds->movenext();
		}
		
		$commission_balance=$global_settings["payout_price"]*$commission_balance;

		$boxcontent=str_replace("{BALANCE}",currency(1,false).strval(float_opt($commission_balance,2))." ".currency(2,false),$boxcontent);
		$boxcontent=str_replace("{WORD_BALANCE}",word_lang("commission"),$boxcontent);
		$boxcontent=str_replace("profile.php","profile_home.php",$boxcontent);
		
	}
	
	
	
	if($_SESSION["people_type"]=="affiliate")
	{
		$commission_balance=0;
		$sql="select total from affiliates_signups where aff_referal=".(int)$_SESSION["people_id"]." and status=1";
		$ds->open($sql);
		while(!$ds->eof)
		{
			$commission_balance+=$ds->row["total"];
			$ds->movenext();
		}
		
		$commission_balance=$global_settings["payout_price"]*$commission_balance;
		$boxcontent=str_replace("{BALANCE}",currency(1,false).strval(float_opt($commission_balance,2))." ".currency(2,false),$boxcontent);
		$boxcontent=str_replace("{WORD_BALANCE}",word_lang("commission"),$boxcontent);
		
		$boxcontent=str_replace("profile.php","profile_home.php",$boxcontent);
	}


	if($_SESSION["people_type"]=="common")
	{
		$boxcontent=str_replace("profile.php","profile_home.php",$boxcontent);
	}


	$boxcontent=str_replace("{WORD_PROFILE}",word_lang("my profile"),$boxcontent);
	$boxcontent=translate_text($boxcontent);


	$boxcontent=str_replace("{WORD_LOGOUT}",word_lang("logout")." [".$_SESSION["people_login"]."]",$boxcontent);
	$boxcontent=str_replace("{LOGIN}",$_SESSION["people_login"],$boxcontent);
	$box_members=$boxcontent;
}
$file_template=str_replace("{BOX_MEMBERS}",$box_members,$file_template);
?>