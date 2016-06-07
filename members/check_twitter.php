<?include("../admin/function/db.php");?>
<?
$sql="select * from users_qauth where activ=1 and title='Twitter'";
$rs->open($sql);
if(!$rs->eof)
{

	if(isset($_SERVER["HTTP_REFERER"]) and preg_match("/checkout/i",$_SERVER["HTTP_REFERER"]))
	{
		$_SESSION["redirect_url"]="checkout";
	}

	if(!isset($_SESSION["steps"]))
	{
		$_SESSION["steps"]="redirect";
	}



	include("../admin/function/twitteroauth.php");
	
	define('CONSUMER_KEY',$rs->row["consumer_key"]);
	define('CONSUMER_SECRET',$rs->row["consumer_secret"]);
	define('OAUTH_CALLBACK',surl.site_root."/members/check_twitter.php");

	if($_SESSION["steps"]=="redirect")
	{
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	
		switch ($connection->http_code)
		{
			case 200:
			$url = $connection->getAuthorizeURL($token);
			$_SESSION["steps"]="callback";
			header("Location:" . $url);
			exit();
			break;
			default:
			echo "Could not connect to Twitter. Refresh the page or try again later.";
		}
	}
	
	
	
	
	
	if($_SESSION["steps"]=="callback")
	{
	
	
		/* If the oauth_token is old redirect to the connect page. */
		if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token'])
		{
	 		$_SESSION['oauth_status'] = 'oldtoken';
	 		$_SESSION["steps"]="redirect";
 			 header("location:".site_root."/members/login.php");
 			 exit();
		}

		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

		/* Request access tokens from twitter */
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$_SESSION['access_token'] = $access_token;

		/* Remove no longer needed request tokens */
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);

		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $connection->http_code)
		{
  			/* The user has been verified and the access tokens can be saved for future use */
  			$_SESSION["steps"]="authorized";

		}
		else
		{
  			/* Save HTTP status for error dialog on connnect page.*/
  			$_SESSION["steps"]="redirect";
  			header("location:".site_root."/members/login.php");
  			exit();
		}

	}
	
	if($_SESSION["steps"]=="authorized")
	{
		/* If access tokens are not available redirect to connect page. */
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret']))
		{
  			header("location:".site_root."/members/login.php");
  			exit();
		}
		
		/* Get user access tokens out of the session. */
		$access_token = $_SESSION['access_token'];

		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

		/* If method is set change API call made. Test is called by default. */
		$content = $connection->get('account/verify_credentials');
		//var_dump($content); 
		//echo("Ok");
		//echo($content->screen_name);
		
		//Add new user into the database
		$temp_login=result($content->screen_name);
		$temp_name=result($content->name);
		$temp_description=result($content->description);
		$temp_website=result($content->url);
		$sql="select login from users where login='".$temp_login."'";
		$flag_add=false;
		$ds->open($sql);
		if($ds->eof)
		{
			//Add a new user
			$flag_add=true;
		}
		else
		{
			$sql="select login from users where (login='".$temp_login."' or login='twitter_".$temp_login."') and authorization='twitter'";
			$dr->open($sql);
			if($dr->eof)
			{
				//Add a new user
				$flag_add=true;				
			}
			//$temp_login="twitter_".$temp_login;
		}
		
		if($flag_add==true)
		{
			//Examination
			if($global_settings["examination"]){$exam=0;}
			else{$exam=1;}

			//insert new user
			$sql="insert into structure (id_parent,name,module_table) values (2,'".$temp_login."',28)";
			$db->execute($sql);

			$sql="select id from structure where name='".$temp_login."' order by id desc";
			$rs->open($sql);
			$id=$rs->row['id'];
			
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

			$sql="insert into users (login,password,name,country,telephone,address,email,data1,ip,accessdenied,lastname,city,zipcode,category,website,utype,company,newsletter,examination,authorization,aff_commission_buyer,aff_commission_seller,aff_visits,aff_signups,aff_referal,business,payout_limit) values ('".$temp_login."','','".$temp_name."','','','','',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SERVER["REMOTE_ADDR"])."',0,'','','','".$global_settings["userstatus"]."','".$temp_website."','".$utype."','',0,".$exam.",'twitter',".$global_settings["buyer_commission"].",".$global_settings["seller_commission"].",0,0,".$aff.",0,".(int)$global_settings["payout_limit"].")";
			$db->execute($sql);
			
			//insert the coupon for new user
			coupons_add($temp_login,"New Signup");
		}
		
		//Authorization
		user_authorization($temp_login,"","twitter");

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
	header("location:".site_root."/members/login.php");
  	exit();
}


?>
