<? include("../function/db.php");?>
<?
//Check captcha
require_once('../function/recaptchalib.php');
$flag_captcha=check_captcha();

if($flag_captcha)
{
	$sql="select * from people where login='".result($_POST["login"])."' and password='".md5(result($_POST["password"]))."'";
	$rs->open( $sql );
	if(!$rs->eof)
	{
		$_SESSION["user_id"]=$rs->row[ "id" ];
		$_SESSION["user_name"]=$rs->row[ "name" ];
		$_SESSION["user_login"]=$rs->row[ "login" ];
		$_SESSION["user_photo"]=site_root."/images/user.gif";
		$_SESSION["entry_admin"]=1;
		
		if($rs->row["photo"]!="")
		{
			$_SESSION["user_photo"]=$rs->row["photo"];
		}

		$sql="insert into people_access (user,accessdate,ip) values (".$rs->row[ "id" ].",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SERVER["REMOTE_ADDR"])."')";
		$db->execute($sql);
		
		//Rights
		$sql="select user_rights from people_rights where user=".$rs->row["id"];
		$ds->open($sql);
		while(!$ds->eof)
		{
			$_SESSION["rights"][$ds->row["user_rights"]]=1;
			$ds->movenext();
		}
		
		//Last values
		$users_properties=array("orders","credits","subscription","commission","downloads","uploads","exams","comments","lightboxes","users","support","messages","contacts","testimonials","blog","documents","payments","invoices");
		
		for($i=0;$i<count($users_properties);$i++)
		{
			$_SESSION["user_".$users_properties[$i]]=0;
			$_SESSION["user_".$users_properties[$i]."_id"]=0;
			
			$sql="select property,property_value from administrators_stats where administrator_id=".$rs->row[ "id" ]." and property='".$users_properties[$i]."'";
			$ds->open($sql);
			if($ds->eof)
			{
				$sql="insert into administrators_stats (property,property_value,administrator_id) values ('".$users_properties[$i]."',0,".$rs->row[ "id" ].")";
				$db->execute($sql);
			}
		}
		
		$sql="select property,property_value from administrators_stats where administrator_id=".$rs->row[ "id" ];
		$ds->open($sql);
		while(!$ds->eof)
		{
			
			//Calculate new orders
			if($ds->row["property"]=="orders")
			{
				$sql="select count(id) as count_param from orders where status=1 and id>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id from orders where status=1 order by id desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new credits
			if($ds->row["property"]=="credits" and $global_settings["credits"])
			{
				$sql="select count(id_parent) as count_param from credits_list where approved=1 and quantity>0 and id_parent>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id_parent from credits_list where approved=1 and quantity>0 order by id_parent desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id_parent"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			//Calculate new subscription
			if($ds->row["property"]=="subscription" and $global_settings["subscription"])
			{
				$sql="select count(id_parent) as count_param from subscription_list where approved=1 and id_parent>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id_parent from subscription_list where approved=1 order by id_parent desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id_parent"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new commission
			if($ds->row["property"]=="commission" and $global_settings["userupload"])
			{
				$sql="select count(id) as count_param from commission where total>0 and id>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id from commission where total>0 order by id desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new downloads
			if($ds->row["property"]=="downloads" and !$global_settings["printsonly"])
			{
				$sql="select count(id) as count_param from downloads where id>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id from downloads order by id desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			//Calculate new invoices
			if($ds->row["property"]=="invoices")
			{
				$sql="select count(id) as count_param from invoices where id>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id from invoices order by id desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new payments
			if($ds->row["property"]=="payments")
			{
				$sql="select count(id_parent) as count_param from payments where id_parent>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id_parent from payments order by id_parent desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id_parent"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			
			//Calculate new exam
			if($ds->row["property"]=="exams" and $global_settings["examination"])
			{
				$sql="select count(id) as count_param from  examinations where id>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id from  examinations order by id desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new comments
			if($ds->row["property"]=="comments" and $global_settings["reviews"])
			{
				$sql="select count(id_parent) as count_param from reviews where id_parent>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id_parent from reviews order by id_parent desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id_parent"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			//Calculate new lightboxes
			if($ds->row["property"]=="lightboxes")
			{
				$sql="select count(id) as count_param from  lightboxes where id>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id from  lightboxes order by id desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new users
			if($ds->row["property"]=="users")
			{
				$sql="select count(id_parent) as count_param from users where id_parent>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id_parent from users order by id_parent desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id_parent"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new documents
			if($ds->row["property"]=="documents")
			{
				$sql="select count(id) as count_param from documents where id>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id from documents order by id desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new messages
			if($ds->row["property"]=="messages" and $global_settings["messages"])
			{
				$sql="select count(id_parent) as count_param from messages where id_parent>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id_parent from messages order by id_parent desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id_parent"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new contacts
			if($ds->row["property"]=="contacts")
			{
				$sql="select count(id_parent) as count_param from support where id_parent>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id_parent from support order by id_parent desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id_parent"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			//Calculate new testimonials
			if($ds->row["property"]=="testimonials" and $global_settings["testimonials"])
			{
				$sql="select count(id_parent) as count_param from testimonials where id_parent>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id_parent from testimonials order by id_parent desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id_parent"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			//Calculate new blog
			if($ds->row["property"]=="blog" and $global_settings["blog"])
			{
				$sql="select count(id_parent) as count_param from blog where id_parent>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id_parent from blog order by id_parent desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id_parent"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			//Calculate new support tickets
			if($ds->row["property"]=="support" and $global_settings["support"])
			{
				$sql="select count(id) as count_param from  support_tickets where user_id<>0 and id>".$ds->row["property_value"]; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					if($ds->row["property_value"]>0)
					{
						$_SESSION["user_".$ds->row["property"]]=$dr->row["count_param"];
						$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
					}
				}
				
				$sql="select id from  support_tickets order by id desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			//Calculate new uploads
			if($ds->row["property"]=="uploads" and $global_settings["userupload"])
			{
				$count_uploads=0;
				
				if($global_settings["allow_photo"])
				{
					$sql="select count(id_parent) as count_param from  photos where userid<>0 and id_parent>".$ds->row["property_value"]; 
					$dr->open($sql);
					if(!$dr->eof)
					{
						$count_uploads+=$dr->row["count_param"];
					}
				}
				
				if($global_settings["allow_video"])
				{
					$sql="select count(id_parent) as count_param from  videos where userid<>0 and id_parent>".$ds->row["property_value"]; 
					$dr->open($sql);
					if(!$dr->eof)
					{
						$count_uploads+=$dr->row["count_param"];
					}
				}
				
				if($global_settings["allow_audio"])
				{
					$sql="select count(id_parent) as count_param from  audio where userid<>0 and id_parent>".$ds->row["property_value"]; 
					$dr->open($sql);
					if(!$dr->eof)
					{
						$count_uploads+=$dr->row["count_param"];
					}
				}
				
				if($global_settings["allow_vector"])
				{
					$sql="select count(id_parent) as count_param from  vector where userid<>0 and id_parent>".$ds->row["property_value"]; 
					$dr->open($sql);
					if(!$dr->eof)
					{
						$count_uploads+=$dr->row["count_param"];
					}
				}
				
				
				if($ds->row["property_value"]>0)
				{
					$_SESSION["user_".$ds->row["property"]]=$count_uploads;
					$_SESSION["user_".$ds->row["property"]."_id"]=$ds->row["property_value"];
				}
				
				$sql="select id from  structure where (module_table=30 or module_table=31 or module_table=52 or module_table=53) order by id desc"; 
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="update administrators_stats set property_value=".$dr->row["id"]." where administrator_id=".$rs->row[ "id" ]." and property='".$ds->row["property"]."'";
					$db->execute($sql);
				}
			}
			
			
			
			$ds->movenext();
		}
		
		redirect("../content/");
	}
	else
	{
		redirect("fullaccess.php?d=1");
	}
}
else
{
	redirect("fullaccess.php?d=2");
}

$db->close();
?>