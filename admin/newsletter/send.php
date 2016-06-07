<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_newsletter");

if($_POST["subject"]!="" and ($_POST["message_text"]!="" or $_POST["message_html"]!=""))
{
	$content="";
	if($_POST["html"]==0)
	{
		$content=$_POST["message_text"];
		$content_db=result($_POST["message_text"]);
	}
	else
	{
		$content=str_replace("\n","",str_replace("\r","",$_POST["message_html"]));
		$content_db=result_html($content);
	}
	
	$sql="insert into newsletter (data,touser,types,subject,content,html) values (".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_POST["to"])."','".result($_POST["types"])."','".result($_POST["subject"])."','".$content_db."',".(int)$_POST["html"].")";
	$db->execute($sql);

	if($_POST["condition"]==0)
	{
		if($_POST["html"]==1)
		{
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/header.tpl"))
			{
				$content=str_replace("{SITE_ROOT}",surl.site_root,file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/header.tpl")).$content;
			}
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/footer.tpl"))
			{
				$content.=str_replace("{SITE_ROOT}",surl.site_root,file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/footer.tpl"));
			}
		}

		$emails_mass=array();
		$users_mass=array();
		if($_POST["to"]=="admin")
		{
			$emails_mass[]=$global_settings["admin_email"];
			$users_mass[]="";
		}	
		elseif($_POST["to"]=="marketing")
		{
			$sql="select content from  newsletter_emails";
			$rs->open($sql);
			if(!$rs->eof)
			{
				$emails=explode(";",str_replace(" ","",str_replace(",",";",$rs->row["content"])));
				for($i=0;$i<count($emails);$i++)
				{
					$emails_mass[]=$emails[$i];
					$users_mass[]="";
				}
			}
		}
		else
		{
			$com="";
			if($_POST["to"]=="newsletter"){$com="where newsletter=1";}
			if($_POST["to"]=="buyer_newsletter"){$com="where newsletter=1 and utype='buyer'";}
			if($_POST["to"]=="seller_newsletter"){$com="where newsletter=1 and utype='seller'";}
			if($_POST["to"]=="affiliate_newsletter"){$com="where newsletter=1 and utype='affiliate'";}
			if($_POST["to"]=="common_newsletter"){$com="where newsletter=1 and utype='common'";}
			$sql="select login,email from users ".$com;
			$rs->open($sql);
			while(!$rs->eof)
			{
				$emails_mass[]=$rs->row["email"];
				$users_mass[]=$rs->row["login"];
				$rs->movenext();
			}	
		}
	
		for($i=0;$i<count($emails_mass);$i++)
		{
			if(($_POST["types"]=="all" and $_POST["html"]==0) or $_POST["types"]=="message")
			{
				if($users_mass[$i]!="")
				{
					$sql="insert into messages (touser,fromuser,subject,content,data,viewed,trash,del) values ('".$users_mass[$i]."','Site Administration','".result($_POST["subject"])."','".$content_db."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,0,0)";
					$db->execute($sql);
				}
			}
		
			$content=str_replace("{SITE_NAME}",$global_settings["site_name"],$content);
			$content=str_replace("{ADDRESS}",$global_settings["company_address"],$content);
		
			$content=translate_text($content);

			if($_POST["types"]=="all" or $_POST["types"]=="email")
			{
				$mail = new PHPMailer(true); //New instance, with exceptions enabled

				$body             = $content;
			
				if($global_settings["mailtype"]=="smtp")
				{
					$mail->IsSMTP();                           // tell the class to use SMTP
					$mail->SMTPAuth   = true;                  // enable SMTP authentication
					$mail->Port       = $global_settings["smtp_port"];                    // set the SMTP server port
					$mail->Host       = $global_settings["smtp_server"]; // SMTP server
					$mail->Username   = $global_settings["smtp_user"];     // SMTP server username
					$mail->Password   = $global_settings["smtp_password"];            // SMTP server password
				}

				if($global_settings["mailtype"]=="sendmail")
				{
					$mail->IsSendmail();  // tell the class to use Sendmail
				}

				$mail->From       = $global_settings["from_email"];
				$mail->FromName   = "";

				$to = $emails_mass[$i];

				$mail->AddAddress($to);

				$mail->Subject  = $_POST["subject"];

				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
				$mail->WordWrap   = 80; // set word wrap
	
				$mail->MsgHTML($body);

				if($_POST["html"]==1)
				{
					$mail->IsHTML(true); // send as HTML
				}
				else
				{
					$mail->IsHTML(false); // send as TEXT
				}

				$mail->Send();
			}
		}
	}
}

$db->close();

header("location:index.php?d=2");
?>