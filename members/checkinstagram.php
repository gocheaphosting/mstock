<?
include("../admin/function/db.php");
include("../admin/plugins/instagram/instagram.class.php");


$sql="select * from users_qauth where activ=1 and title='Instagram'";
$rs->open($sql);
if(!$rs->eof)
{
	$instagram = new Instagram(array(
    'apiKey'      => $rs->row["consumer_key"],
    'apiSecret'   => $rs->row["consumer_secret"],
    'apiCallback' => surl.site_root."/members/checkinstagram.php" // must point to success.php
	));
	
	$client_id=$rs->row["consumer_key"];
	$client_secret=$rs->row["consumer_secret"];
	$my_url=surl.site_root."/members/checkinstagram.php";
	
	if(isset($_SERVER["HTTP_REFERER"]) and preg_match("/checkout/i",$_SERVER["HTTP_REFERER"]))
	{
		$_SESSION["redirect_url"]="checkout";
	}


	if(!isset($_GET["code"]))
	{
		$loginUrl = $instagram->getLoginUrl();
		header("location:".$loginUrl);
  		exit();
	}
	
	
	
	
	
	if(isset($_GET["code"]))
	{
		$code = $_GET['code'];

		// Check whether the user has granted access
		if (true === isset($code))
		{
			// Receive OAuth token object
			$data = $instagram->getOAuthToken($code);

			if(!empty($data->user->username))
			{	
				//Add new user into the database
				$temp_login="instagram_".result($data->user->username);
				$temp_name=result($data->user->full_name);
				$temp_website=result($data->user->website);
				$temp_description=result($data->user->bio);
				$sql="select login from users where login='".$temp_login."'";
				$flag_add=false;
				$ds->open($sql);
				if($ds->eof)
				{
					//Add a new user
					$flag_add=true;
				}
	
				if($flag_add==true)
				{
					//Examination
					if($global_settings["examination"]){$exam=0;}
					else{$exam=1;}

					$aff=0;
					if(isset($_COOKIE["aff"]))
					{
						$aff=(int)$_COOKIE["aff"];
					}
			
					$utype="";
					if($global_settings["common_account"])
					{
						$utype="common";
					}

					$sql="insert into users (login,password,name,country,telephone,address,email,data1,ip,accessdenied,lastname,city,zipcode,category,website,utype,company,newsletter,examination,authorization,aff_commission_buyer,aff_commission_seller,aff_visits,aff_signups,aff_referal,business,payout_limit) values ('".$temp_login."','','".$temp_name."','','','','',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SERVER["REMOTE_ADDR"])."',0,'','','','".$global_settings["userstatus"]."','".$temp_website."','".$utype."','',0,".$exam.",'instagram',".$global_settings["buyer_commission"].",".$global_settings["seller_commission"].",0,0,".$aff.",0,".(int)$global_settings["payout_limit"].")";
					$db->execute($sql);
			
					//insert the coupon for new user
					coupons_add($temp_login,"New Signup");
				}
		
				//Authorization
				user_authorization($temp_login,"","instagram");

  				if($_SESSION["people_type"]=="buyer" or $_SESSION["people_type"]=="common")
				{
					if(isset($_SESSION["redirect_url"]) and $_SESSION["redirect_url"]=="checkout")
					{
						header("location:checkout.php");
						exit();
					}
					else
					{
						header("location:profile_home.php");
						exit();
					}
				}
				elseif($_SESSION["people_type"]=="seller" or $_SESSION["people_type"]=="affiliate")
				{
					header("location:profile_home.php");
					exit();
				}
				else
				{
					header("location:".site_root."/members/profile.php");
  					exit();
				}
			}
		}
		else
		{
			// Check whether an error occurred
			if (true === isset($_GET['error']))
			{
				echo 'An error occurred: '.$_GET['error_description'];
			}
		}
	}
}
else
{
	header("location:".site_root."/members/login.php");
  	exit();
}


?>