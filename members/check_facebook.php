<?include("../admin/function/db.php");?>
<?
$sql="select * from users_qauth where activ=1 and title='Facebook'";
$rs->open($sql);
if(!$rs->eof)
{

	$client_id=$rs->row["consumer_key"];
	$client_secret=$rs->row["consumer_secret"];
	$my_url=surl.site_root."/members/check_facebook.php";
	
	if(isset($_SERVER["HTTP_REFERER"]) and preg_match("/checkout/i",$_SERVER["HTTP_REFERER"]))
	{
		$_SESSION["redirect_url"]="checkout";
	}


	if(!isset($_GET["code"]))
	{
		$url="https://graph.facebook.com/oauth/authorize?client_id=".$client_id."&redirect_uri=".$my_url;
		header("location:".$url);

  		exit();
	}
	
	
	
	
	
	if(isset($_GET["code"]))
	{

  			$token_url ="https://graph.facebook.com/oauth/access_token?client_id="
  		  . $client_id . "&redirect_uri=" . $my_url
  		  . "&client_secret=" . $client_secret 
  		  . "&code=" . $_GET['code'];
  		  
  		  	$access_token = file_get_contents($token_url);
  		  	$_SESSION["access_token"]=$access_token;
			$_SESSION["steps_facebook"]="authorized";


	
		$graph_url = "https://graph.facebook.com/me?" .$_SESSION["access_token"];
		$results = json_decode(file_get_contents($graph_url));

		//echo($results->id."<br>");	
		//echo($results->first_name."<br>");	
		//echo($results->last_name."<br>");	
		//echo($results->link."<br>");	


   	 
	if(isset($results->id))
	{
		//Add new user into the database
		if(isset($results->username) and $results->username != '')
		{
			$temp_login="fb_".result($results->username);
		}
		else
		{
			$temp_login="fb".result($results->id);
		}
		$temp_name=result($results->first_name);
		$temp_lastname=result($results->last_name);
		$temp_website=result($results->link);
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

			$sql="insert into users (login,password,name,country,telephone,address,email,data1,ip,accessdenied,lastname,city,zipcode,category,website,utype,company,newsletter,examination,authorization,aff_commission_buyer,aff_commission_seller,aff_visits,aff_signups,aff_referal,business,payout_limit) values ('".$temp_login."','','".$temp_name."','','','','',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SERVER["REMOTE_ADDR"])."',0,'".$temp_lastname."','','','".$global_settings["userstatus"]."','".$temp_website."','".$utype."','',0,".$exam.",'facebook',".$global_settings["buyer_commission"].",".$global_settings["seller_commission"].",0,0,".$aff.",0,".(int)$global_settings["payout_limit"].")";
			$db->execute($sql);
			
			//insert the coupon for new user
			coupons_add($temp_login,"New Signup");
		}
		
		//Authorization
		user_authorization($temp_login,"","facebook");
		
				

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
}
else
{
	header("location:".site_root."/members/login.php");
  	exit();
}


?>