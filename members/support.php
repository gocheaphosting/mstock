<?$site="support";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>

<input type="button" value="<?=word_lang("open a support request")?>" class="profile_button" onClick="location.href='support_new.php'">
<h1><?=word_lang("support")?></h1>


<?
if(isset($_GET["d"]))
{
	?>
	<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?=word_lang("sent")?>
    </div>
	<?
}
?>

<?
$sql="select id,id_parent,admin_id,user_id,subject,message,data,viewed_admin,viewed_user,rating,closed from support_tickets where id_parent=0 and user_id=".(int)$_SESSION["people_id"]." order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
?>

	<table border='0' cellpadding='0' cellspacing='0' style="margin-bottom:20px" class="profile_table" width="100%">
	<tr>
	<th class='hidden-phone hidden-tablet'><?=word_lang("date")?></th>
	<th style="width:80%"><?=word_lang("subject")?></th>
	<th class='hidden-phone hidden-tablet'><?=word_lang("status")?></th>
	</tr>
	<?
	while(!$rs->eof)
	{
	
		$new_messages=0;
		$total_messages=1;
	
		if($rs->row["viewed_user"]==0)
		{
			$new_messages++;
		}
	
		$sql="select id,viewed_user,user_id from support_tickets where id_parent=".$rs->row["id"];
		$ds->open($sql);
		while(!$ds->eof)
		{
			if($ds->row["viewed_user"]==0 and $ds->row["user_id"]==0)
			{
				$new_messages++;
			}
		
			$total_messages++;
			$ds->movenext();
		}
	
		if($new_messages>0)
		{
			$style="badge-important";
		}
		else
		{
			$style="";
		}
	
		?>
			<tr <?
				if($new_messages>0)
				{
					echo("class='snd2'");
				}
				?>>
				<td class='hidden-phone hidden-tablet'><div class='link_date'><?=show_time_ago($rs->row["data"])?></div></tb>
				<td><span class="badge <?=$style?>"><?=$total_messages?></span>  <a href="support_content.php?id=<?=$rs->row["id"]?>"><?=$rs->row["subject"]?></a> [ID: <?=$rs->row["id"]?>]</td>		
				<td class='hidden-phone hidden-tablet'>
					<?
						if($rs->row["closed"]==1)
						{
							echo('<span class="label label-success">'.word_lang("closed").'</span>');
						}
						else
						{
							echo('<span class="label label-important">'.word_lang("in progress").'</span>');
						}
					?>
				</td>
			</tr>
		<?
		$rs->movenext();
	}
	?>

	</table>
<?
}
else
{
	echo(word_lang("not found"));
}
?>



<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>