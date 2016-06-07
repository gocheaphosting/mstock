<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_notifications");


$sql="select events,title,message,enabled,priority,subject,html from notifications where events='".result($_GET["events"])."'";
$rs->open($sql);
if(!$rs->eof)
{
	$example="";
	
	if($rs->row["events"]=="contacts_to_admin" or $rs->row["events"]=="contacts_to_user")
	{
		$_POST["name"]="John";
		$_POST["email"]="john@yourdomain.com";
		$_POST["telephone"]="1-234-765-4967";
		$_POST["method"]="By email";
		$_POST["question"]="This is test Contact Us email.";
		
		$example=send_notification($rs->row["events"],"","","","",true);
	}
	
	if($rs->row["events"]=="fraud_to_user")
	{
		$example=send_notification($rs->row["events"],"hsdjh453j2","john@yourdomain.com","John","",true);
	}
	
	if($rs->row["events"]=="neworder_to_user" or $rs->row["events"]=="neworder_to_admin")
	{
		$sql="select id from orders order by id desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$example=send_notification($rs->row["events"],$ds->row["id"],"","","",true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there is no any live order in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	if($rs->row["events"]=="subscription_to_admin" or $rs->row["events"]=="subscription_to_user")
	{
		$sql="select id_parent from subscription_list order by id_parent desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$example=send_notification($rs->row["events"],$ds->row["id_parent"],"","","",true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there is no any live subscription in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	if($rs->row["events"]=="credits_to_admin" or $rs->row["events"]=="credits_to_user")
	{
		$sql="select id_parent from credits_list  where quantity>0 order by id_parent desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$example=send_notification($rs->row["events"],$ds->row["id_parent"],"","","",true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there are no any live credits in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	if($rs->row["events"]=="signup_to_admin")
	{
		$sql="select id_parent from users  order by id_parent desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$example=send_notification($rs->row["events"],$ds->row["id_parent"],"","","",true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there is no any live user in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	if($rs->row["events"]=="signup_to_user")
	{
		$_POST["email"]="user@email.com";
		$_POST["name"]="John";
		$example=send_notification($rs->row["events"],surl.site_root."/members/confirm.php?id=1234&login=userlogin","","","",true);
	}
	
	if($rs->row["events"]=="signup_guest")
	{
		$_POST["guest_email"]="user@email.com";
		$example=send_notification($rs->row["events"],"guest12234","sfrfe234xc2","","",true);
	}
	
	
	if($rs->row["events"]=="forgot_password")
	{
		$sql="select email from users where authorization='site'  order by id_parent desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$_POST["email"]=$ds->row["email"];
			$example=send_notification($rs->row["events"],"","","","",true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there is no any live user in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	if($rs->row["events"]=="tell_a_friend")
	{
		$_REQUEST["email"]="fromuser@email.com";
		$_REQUEST["email2"]="touser@email.com";
		$_REQUEST["name"]="John";
		$_REQUEST["name2"]="Nick";
		$example=send_notification($rs->row["events"],"http://www.domain.com/","","","",true);
	}
	
	if($rs->row["events"]=="commission_to_seller")
	{
		$sql="select total,user,orderid,item,publication,types,data,description from commission where total>0 order by data desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$example=send_notification($rs->row["events"],$ds->row["user"],$ds->row["orderid"],$ds->row["publication"],$ds->row["total"],true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there is no any live commission in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	if($rs->row["events"]=="commission_to_affiliate")
	{
		$sql="select userid,types,types_id,rates,total,data,aff_referal from affiliates_signups where total>0 order by data desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$example=send_notification($rs->row["events"],$ds->row["userid"],$ds->row["types_id"],"",$ds->row["total"],true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there is no any live commission in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	if($rs->row["events"]=="exam_to_admin" or $rs->row["events"]=="exam_to_seller")
	{
		$sql="select id,user,data,status,comments from examinations order by data desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$example=send_notification($rs->row["events"],$ds->row["user"],$ds->row["id"],"","",true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there is no any live examination in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	
	if($rs->row["events"]=="support_to_admin")
	{
		$sql="select id,id_parent from support_tickets where user_id<>0 order by data desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$example=send_notification($rs->row["events"],$ds->row["id"],"","","",true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there is no any live support request in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	if($rs->row["events"]=="support_to_user")
	{
		$sql="select id,id_parent from support_tickets where id_parent<>0 and admin_id<>0 order by data desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$example=send_notification($rs->row["events"],$ds->row["id"],"","","",true);	
		}
		else
		{
			$example="<p>Ops! Unfortunately there is no any live support request in the database. It is impossible to show the notification's preview.</p>";	
		}
	}
	
	
	if($rs->row["html"]!=1)
	{
		$example="<html><head><style>.header_preview{margin-bottom:15px;padding:9px;background:#4a4a4a;color:#FFFFFF;font:14px Arial;font-weight:bold}</style></head><body><div>".str_replace("\n","<br>",$example)."</div></body></html>";
	}
	
	echo($example);
}
?>
