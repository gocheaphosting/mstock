<?$site="profile_about";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>

<h1><?=word_lang("my profile")?></h1>



		<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2">
		<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home">
		
		<tr>
			<th colspan="3"><?=word_lang("stats")?></th>
		</tr>







<?if($_SESSION["people_type"]=="buyer" or $_SESSION["people_type"]=="common"){?>
		<?if($global_settings["credits"]==1){?>
		<tr>
			<td><b><?=word_lang("balance")?>:</b></td>
	 		<td><div class="link_total"><span class="price"><b><?=float_opt(credits_balance(),2)?> <?=word_lang("credits")?></b></span></div></td>
	 		<td class='hidden-phone hidden-tablet'>[<a href="credits.php?d=1"><?=word_lang("buy credits")?></a>]</td>
	 	</tr>
	 	<?}?>
	 	
	 	<?if($global_settings["subscription"]==1){?>
		<tr>
			<td><b><?=word_lang("subscription")?>:</b></td>
			<?
			$sql="select title from subscription_list where user='".result($_SESSION["people_login"])."' and approved=1 and data2>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." order by data2 desc";
			$ds->open($sql);
			if(!$ds->eof)
			{
			?>
	 			<td><div class="link_subscription"><?=$ds->row["title"]?></div></td>
	 			<td class='hidden-phone hidden-tablet'></td>
	 		<?
	 		}
	 		else
	 		{
	 		?>
	 			<td><div class="link_date"><?=word_lang("no")?></div></td>
	 			<td class='hidden-phone hidden-tablet'>[<a href="subscription.php"><?=word_lang("buy subscription")?></a>]</td>
	 		<?
	 		}
	 		?>
	 	</tr>
	 	<?}?>
	 	
	 	<tr>
			<td><b><?=word_lang("my downloads")?>:</b></td>
	 		<td>
	 			<?
	 				$download_count=0;
	 				$sql="select count(id) as download_count from downloads where user_id=".(int)$_SESSION["people_id"]." and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
	 				$ds->open($sql);
	 				if(!$ds->eof)
	 				{
	 					$download_count=$ds->row["download_count"];
	 				}
	 			?>
	 			<div class="link_download"><a href="profile_downloads.php"><?=$download_count?></a></div>
	 		</td>
	 		<td class='hidden-phone hidden-tablet'></td>
	 	</tr>
	 	
	 	<tr>
			<td><b><?=word_lang("my favorite list")?>:</b></td>
	 		<td>
	 			<?
	 			$lightbox_count=0;
	 			
	 			$sql="select id_parent from lightboxes_admin where user=".(int)$_SESSION["people_id"];
				$rs->open($sql);
				while(!$rs->eof)
				{
					$sql="select title from lightboxes where id=".$rs->row["id_parent"]." order by title";
					$ds->open($sql);
					if(!$ds->eof)
					{
						$lightbox_count++;
					}
					$rs->movenext();
				}
	 			?>
	 			<div class="link_lightbox"><a href="lightbox.php"><?=$lightbox_count?></a></div>
	 		</td>
	 		<td class='hidden-phone hidden-tablet'></td>
	 	</tr>
<?}?> 	
	 	
	
	
	







<?if(($_SESSION["people_type"]=="seller" or $_SESSION["people_type"]=="common") and $global_settings["userupload"]){?>
	
	<?
		$userbalance=0;
		$sales_count=0;
		$payout=0;

		$sql="select user,total from commission where user=".(int)$_SESSION["people_id"]." and status=1";
		$ds->open($sql);
		while(!$ds->eof)
		{	
			if($ds->row["total"]>0)
			{
				$sales_count++;
				$userbalance+=$ds->row["total"];
			}
			else
			{
				$payout+=-1*$ds->row["total"];
			}
			$ds->movenext();
		}
	?>
	<tr>
		<td><b><?=word_lang("files")?>:</b></td>
		<td>
			<?
				$count_files=0;
				$sql="select count(id_parent) as count_files from photos where userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."'";
				$ds->open($sql);
				if(!$ds->eof)
				{
					$count_files+=$ds->row["count_files"];
				}
				
				$sql="select count(id_parent) as count_files from videos where userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."'";
				$ds->open($sql);
				if(!$ds->eof)
				{
					$count_files+=$ds->row["count_files"];
				}
				
				$sql="select count(id_parent) as count_files from audio where userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."'";
				$ds->open($sql);
				if(!$ds->eof)
				{
					$count_files+=$ds->row["count_files"];
				}
				
				$sql="select count(id_parent) as count_files from vector where userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."'";
				$ds->open($sql);
				if(!$ds->eof)
				{
					$count_files+=$ds->row["count_files"];
				}
			?>
			<div class="link_files"><a href="publications.php"><?=$count_files?></a></div>
		</td>
		<td class='hidden-phone hidden-tablet'></td>
	</tr>
	
	<tr>
		<td><b><?=word_lang("sales")?>:</b></td>
		<td><div class="link_commission"><a href="commission.php?d=2"><?=$sales_count?></a></div></td>
		<td class='hidden-phone hidden-tablet'></td>
	</tr>	
	
	<tr>
		<td><b><?=word_lang("commission")?>:</b></td>
		<td><div class="link_credits"><?=currency(1,true,"credits")?><a href="commission.php?d=2"><?=$userbalance?></a>
				 <?=currency(2,true,"credits")?></div></td>
		<td class='hidden-phone hidden-tablet'></td>
	</tr>
	
	<tr>
		<td><b><?=word_lang("refund")?>:</b></td>
		<td><div class="link_payout"><?=currency(1,false)?><a href="commission.php?d=3"><?=$payout?></a>
				 <?=currency(2,false)?></div></td>
		<td class='hidden-phone hidden-tablet'></td>
	</tr>


<?}?>










<?if(($_SESSION["people_type"]=="affiliate" or $_SESSION["people_type"]=="common")  and $global_settings["affiliates"]){?>

<?
		$affbalance=0;
		$affcount=0;
		$affpayout=0;

		$sql="select total from affiliates_signups where aff_referal=".(int)$_SESSION["people_id"]." and status=1";
		$ds->open($sql);
		while(!$ds->eof)
		{
			if($ds->row["total"]>0)
			{
				$affbalance+=$ds->row["total"];
			}
			else
			{
				$affpayout+=-1*$ds->row["total"];
			}
			$affcount++;
			$ds->movenext();
		}
?>
<tr>
	<td><b><?=word_lang("affiliate")?> &mdash; <?=word_lang("sales")?>:</b></td>
	<td><div class="link_commission"><a href="affiliate.php?d=2"><?=$affcount?></a></div></td>
	<td class='hidden-phone hidden-tablet'></td>
</tr>

<tr>
	<td><b><?=word_lang("affiliate")?> &mdash; <?=word_lang("commission")?>:</b></td>
	<td><div class="link_credits"><a href="affiliate.php?d=2"><?=currency(1,true,"credits")?><?=$affbalance?>
				 <?=currency(2,true,"credits")?></a></div></td>
	<td class='hidden-phone hidden-tablet'></td>
</tr>

<tr>
	<td><b><?=word_lang("affiliate")?> &mdash; <?=word_lang("refund")?>:</b></td>
	<td><div class="link_payout"><?=currency(1,false)?><a href="affiliate.php?d=3"><?=$affpayout?></a>
				 <?=currency(2,false)?></div></td>
	<td class='hidden-phone hidden-tablet'></td>
</tr>





<?}?>





	 	</table>
	 	</div></div></div></div></div></div></div></div>

<p>&nbsp;</p>














<?if($_SESSION["people_type"]=="buyer" or $_SESSION["people_type"]=="common"){?>	 	
	 	<?
	 	$sql="select publication_id,link from downloads where user_id=".(int)$_SESSION["people_id"]." and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
	 	$ds->open($sql);
	 	if(!$ds->eof)
	 	{
	 		?>
	 		<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2">
	 		<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home">
		
			<tr>
				<th colspan="3"><?=word_lang("Last downloads")?></th>
			</tr>
			<?
			$n=0;
			while(!$ds->eof)
			{
				if($n<7)
				{
					?>
						<tr>
							<?
							$sql="select name,module_table from structure where id=".$ds->row["publication_id"];
							$dr->open($sql);
							if(!$dr->eof)
							{
								$img_preview="";
								if($dr->row["module_table"]==30)
								{
									$img_preview=show_preview($ds->row["publication_id"],"photo",1,1,"","");
								}
								if($dr->row["module_table"]==31)
								{
									$img_preview=show_preview($ds->row["publication_id"],"video",1,1,"","");
								}
								if($dr->row["module_table"]==52)
								{
									$img_preview=show_preview($ds->row["publication_id"],"audio",1,1,"","");
								}
								if($dr->row["module_table"]==53)
								{
									$img_preview=show_preview($ds->row["publication_id"],"vector",1,1,"","");
								}
							?>
								<td><div class="profile_home_preview" style="background:url('<?=$img_preview?>')" onClick="location.href='download.php?f=<?=$ds->row["link"]?>'"></div></td>
								<td class='hidden-phone hidden-tablet'><?=$ds->row["publication_id"]?> - <?=$dr->row["name"]?></td>
							<?
							}
							?>
							<td><div class="link_download"><a href="download.php?f=<?=$ds->row["link"]?>"><?=word_lang("download")?></a></div></td>
						</tr>
					<?
				}
				$n++;
				$ds->movenext();
			}
			?>
			</table>
			</div></div></div></div></div></div></div></div>
			<p>&nbsp;</p>
	 		<?
	 	}
	 	?>
<?}?>














<?
if(($_SESSION["people_type"]=="seller" or $_SESSION["people_type"]=="common") and $global_settings["userupload"])
{
	$sql="select total,user,orderid,item,publication,types,data from commission where user=".(int)$_SESSION["people_id"]." and total>0 order by data desc";
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>
		<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2">
		<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home">
		
			<tr class='main'>
				<th colspan="4"><?=word_lang("Last sales")?></th>
			</tr>
			<?
			$n=0;
			while(!$rs->eof)
			{
				if($n<7)
				{
					$idp=$rs->row["publication"];
					$url="";
					$title="";

					if($rs->row["types"]!="prints_items")
					{
						$sql="select id_parent,title from ".$rs->row["types"]." where id_parent=".$idp;
						$dr->open($sql);
						if(!$dr->eof)
						{
							$url=item_url($dr->row["id_parent"]);
							$title=$dr->row["title"];
						}
					}
					else
					{
						$sql="select id_parent,title,itemid from ".$rs->row["types"]." where id_parent=".$idp;
						$dr->open($sql);
						if(!$dr->eof)
						{
							$url=item_url($dr->row["itemid"]);
							$title=$dr->row["title"];
						}
					}
					?>
					<tr>
						<td>
						<?
							$sql="select name,module_table from structure where id=".$idp;
							$dr->open($sql);
							if(!$dr->eof)
							{
								$img_preview="";
								if($dr->row["module_table"]==30)
								{
									$img_preview=show_preview($idp,"photo",1,1,"","");
								}
								if($dr->row["module_table"]==31)
								{
									$img_preview=show_preview($idp,"video",1,1,"","");
								}
								if($dr->row["module_table"]==52)
								{
									$img_preview=show_preview($idp,"audio",1,1,"","");
								}
								if($dr->row["module_table"]==53)
								{
									$img_preview=show_preview($idp,"vector",1,1,"","");
								}
							?>
								<div class="profile_home_preview" style="background:url('<?=$img_preview?>')" onClick="location.href='<?=$url?>'"></div>
							<?
							}
							?>
						</td>
						<td><?=$idp?> - <a href="<?=$url?>"><?=$title?></a></td>
						<td><span class="price"><?=currency(1,true,"credits");?><?=float_opt($rs->row["total"],2)?> <?=currency(2,true,"credits");?></span></td>
						<td><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
					</tr>
					<?
				}
				$n++;
				$rs->movenext();
			}
			?>
		</table>
		</div></div></div></div></div></div></div></div>
		<p>&nbsp;</p>
		<?
	}
}
?>















<?
if(($_SESSION["people_type"]=="affiliate" or $_SESSION["people_type"]=="common")  and $global_settings["affiliates"])
{
	$sql="select userid,types,types_id,rates,total,data from affiliates_signups where aff_referal=".(int)$_SESSION["people_id"]." and total>0 order by data desc";
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>
		<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2">
		<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home">
		
			<tr class='main'>
				<th colspan="4"><?=word_lang("Last sales")?></th>
			</tr>
			<?
			$n=0;
			while(!$rs->eof)
			{
				if($n<7)
				{
					?>
					<tr>
						<td><?=word_lang($rs->row["types"])?> # <?=$rs->row["types_id"]?></td>
						<td><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
						<td><span class="price"><?=currency(1);?><?=float_opt($rs->row["total"],2)?> <?=currency(2);?></span> (<?=$rs->row["rates"]?>%)</td>
					</tr>
					<?
				}
				$n++;
				$rs->movenext();
			}
			?>
		</table>
		</div></div></div></div></div></div></div></div>
		<p>&nbsp;</p>
		<?
	}
}
?>


<?
$com="";
if($_SESSION["people_type"]=="buyer" or $_SESSION["people_type"]=="common")  
{
	$com=" and buyer=1 ";
}
if($_SESSION["people_type"]=="seller" or $_SESSION["people_type"]=="common")  
{
	$com=" and seller=1 ";
}
if($_SESSION["people_type"]=="affiliate" or $_SESSION["people_type"]=="common")  
{
	$com=" and affiliate=1 ";
}

$sql="select id,title,description from documents_types where enabled=1 ".$com." order by priority";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2">
		<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home">		
			<tr class='main'>
				<th colspan="4"><?=word_lang("Documents")?></th>
			</tr>
			<?
			while(!$rs->eof)
			{
				?>
				<tr>
					<td><b><?=$rs->row["title"]?></b><br><small><?=$rs->row["description"]?></small></td>
					<?
					$sql="select title,status,comment from documents where user_id=".(int)$_SESSION["people_id"]." and id_parent=".$rs->row["id"]." order by data desc";
					$ds->open($sql);
					if(!$ds->eof)
					{
						?>
						<td><span class="label label-success"><?=word_lang("Uploaded")?></span></td>
						<td>
						<?
						if($ds->row["status"]==1)
						{
							?>
							<span class="label label-success"><?=word_lang("approved")?></span>
							<?
						}
						if($ds->row["status"]==0)
						{
							?>
							<span class="label label-warning"><?=word_lang("pending")?></span>
							<?
						}
						if($ds->row["status"]==-1)
						{
							?>
							<span class="label label-danger"><?=word_lang("declined")?></span>
							<?
							if($ds->row["comment"]!="")
							{
								?>
								<br><small><?=$ds->row["comment"]?></small>
								<?
							}
						}
						?>
						</td>
						<?
					}
					else
					{
						?>
						<td><span class="label label-danger"><?=word_lang("Not Uploaded")?></span></td>
						<td></td>
						<?
					}
					?>
					<td>[<a href="profile_about.php#documents"><?=word_lang("upload")?></a>]</td>
				</tr>
				<?
				$rs->movenext();
			}
			?>
		</table>
		</div></div></div></div></div></div></div></div>
		<p>&nbsp;</p>
	<?
}
?>



<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>