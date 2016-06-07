<?
//Check access
admin_panel_access("users_newsletter");

if(!defined("site_root")){exit();}
?>
<p>Here you can find all user's emails with enabled 'Newsletter' option.</p>
<?
$emails_buyers="";
$emails_sellers="";
$emails_affiliates="";
$emails_common="";

$sql="select email,utype from users where accessdenied=0";
$rs->open($sql);
while(!$rs->eof)
{
if($rs->row["email"]!="")
{
	if($rs->row["utype"]=="buyer")
	{
		if($emails_buyers!="")
		{
			$emails_buyers.="; ";
		}
		$emails_buyers.=$rs->row["email"];
	}
	if($rs->row["utype"]=="seller")
	{
		if($emails_sellers!="")
		{
			$emails_sellers.="; ";
		}
		$emails_sellers.=$rs->row["email"];
	}
	if($rs->row["utype"]=="affiliate")
	{
		if($emails_affiliates!="")
		{
			$emails_affiliates.="; ";
		}
		$emails_affiliates.=$rs->row["email"];
	}
	if($rs->row["utype"]=="common")
	{
		if($emails_common!="")
		{
			$emails_common.="; ";
		}
		$emails_common.=$rs->row["email"];
	}
}
	$rs->movenext();
}

?>
<p><b><?=word_lang("buyer")?>:</b></p>
<textarea style="width:600px;height:150px><?=$emails_buyers?></textarea>
<br>
<p><b><?=word_lang("seller")?>:</b></p>
<textarea style="width:600px;height:150px"><?=$emails_sellers?></textarea>
<br>
<p><b><?=word_lang("affiliate")?>:</b></p>
<textarea style="width:600px;height:150px"><?=$emails_affiliates?></textarea>
<br>
<p><b><?=word_lang("common")?>:</b></p>
<textarea style="width:600px;height:150px"><?=$emails_common?></textarea>