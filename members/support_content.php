<?$site="support";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>

<script type="text/javascript" src="<?=site_root?>/inc/js/raty/jquery.raty.min.js"></script>

<script type="text/javascript">
    $(function() {
      $.fn.raty.defaults.path = '<?=site_root?>/inc/js/raty/img';

      $('.star').raty({ score: 3 });
      
    });
    
    function support_rating(id,score)
    {
    	var req = new JsHttpRequest();
        
    	// Code automatically called on load finishing.
   	 	req.onreadystatechange = function()
    	{
        	if (req.readyState == 4)
        	{
				
        	}
    	}
    	req.open(null, '<?=site_root?>/members/support_rating.php', true);
    	req.send( {id: id,score:score } );
    }
</script>

<?
if(isset($_GET["id"]))
{
	$sql="select subject,message,data,user_id,closed from support_tickets where id=".(int)$_GET["id"]." and user_id=".(int)$_SESSION["people_id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$sql="update support_tickets set viewed_user=1 where id=".(int)$_GET["id"]." or id_parent=".(int)$_GET["id"];
		$db->execute($sql);
		
		?>

		<h1><?=word_lang("support")?> &mdash; <?=$rs->row["subject"]?></h1>

		<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
		<div style="width:620px">
		<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home">
		<tr>
			<td style="width:70%"><?=show_user_avatar($rs->row["user_id"],"id")?></td>
			<td><div class="link_date"><?=show_time_ago($rs->row["data"])?></div></td>
		</tr>
		<tr>
		<td colspan="2"><?=str_replace("\n","<br>",$rs->row["message"])?></td>
		</tr>
		</table>
		</div>
		</div></div></div></div></div></div></div></div><br><br>
		
		<?
		$sql="select id,subject,message,data,user_id,closed,admin_id,rating from support_tickets where id_parent=".(int)$_GET["id"]." order by id";
		$ds->open($sql);
		while(!$ds->eof)
		{
			$box_style="";
			if($ds->row["admin_id"]!=0)
			{
				$box_style="bl2";
			}
			
			?>
			<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl <?=$box_style?>"><div class="br"><div class="tl"><div class="tr">
			<div style="width:620px">
			<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home">
			<tr>
				<td style="width:70%">
				<?
				if($ds->row["user_id"]!=0)
				{
					?>
					<?=show_user_avatar($ds->row["user_id"],"id")?>
					<?
				}
				else
				{
					$sql="select login,name from people where id=".$ds->row["admin_id"];
					$dr->open($sql);
					if(!$dr->eof)
					{
						?>
						<img src='<?=site_root?>/images/avatar.gif' width='<?=$global_settings["avatarwidth"]?>' align='absMiddle' border='0'>&nbsp;&nbsp;<b><?=$dr->row["name"]?></b>					
				
						<?
					}
				}
				?>
				</td>
				<td><div class="link_date"><?=show_time_ago($ds->row["data"])?></div></td>
			</tr>
			<tr>
			<td colspan="2"><?=str_replace("\n","<br>",$ds->row["message"])?>
			
			<?
			if($ds->row["admin_id"]!=0)
			{
				?>
				<script type="text/javascript">
    			$(function() {
      				$('#star<?=$ds->row["id"]?>').raty({
      				score: <?=$ds->row["rating"]?>,
 					half: true,
  					number: 5,
  					click: function(score, evt) {
    					support_rating(<?=$ds->row["id"]?>,score);
  					}
				});
    			});
				</script>
				<div id="star<?=$ds->row["id"]?>" style="float:right;margin-top:7px"></div>
				<?
			}
			?>
			

			
			</td>
			</tr>
			</table>
			</div>
			</div></div></div></div></div></div></div></div><br><br>
			<?
			$ds->movenext();
		}
		?>

		<?
		if($rs->row["closed"]==0)
		{
			?>
			<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
			<div style="width:620px">
				<script>
					form_fields=new Array("message");
					fields_emails=new Array(0);
					error_message="<?=word_lang("Incorrect field")?>";
				</script>
				<script src="<?=site_root?>/inc/jquery.qtip-1.0.0-rc3.min.js"></script>
			
				<form method="post" action="support_add.php?id=<?=(int)$_GET["id"]?>" onSubmit="return my_form_validate();">
								
				<textarea class="ibox form-control" style="width:600px;height:150px;margin-bottom:15px" name="message" id="message"></textarea>

				<input type="submit" class="isubmit" value="<?=word_lang("send")?>">
				
				</form>
			</div>
			</div></div></div></div></div></div></div></div><br><br>
			<?
		}
	}
}
?>


<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>