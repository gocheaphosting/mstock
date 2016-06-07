<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_support");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>



<div class="back"><a href="index.php" class="btn btn-primary btn-sm btn-mini"><i class="icon-arrow-left fa fa-arrow-left"></i> <?=word_lang("back")?></a></div>

<script type="text/javascript" src="<?=site_root?>/inc/js/raty/jquery.raty.min.js"></script>

<script type="text/javascript">
    $(function() {
      $.fn.raty.defaults.path = '<?=site_root?>/inc/js/raty/img';

      $('.star').raty({ score: 3 });
      
    });
</script>


<?
if(isset($_GET["id"]))
{
	$sql="update support_tickets set viewed_admin=1 where id=".(int)$_GET["id"]." or id_parent=".(int)$_GET["id"];
	$db->execute($sql);
	
	
	$sql="select id,subject,message,data,user_id,closed from support_tickets where id=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>

		<h1 style="margin-bottom:25px"><?=word_lang("support")?> &mdash; <?=$rs->row["subject"]?></h1>

		<div class="shadow_box box box_padding">
			<div class="shadow_box_title">			
				<div class="shadow_box_date"><?=show_time_ago($rs->row["data"])?></div>
				<div class="link_user"><a href="../customers/content.php?id=<?=$rs->row["user_id"]?>"><b><?=user_url_back($rs->row["user_id"])?></b></a></div>
			</div>
			<div class="shadow_box_content">
				<?=str_replace("\n","<br>",$rs->row["message"])?>
			</div>
		</div>
		
		<?
		$sql="select id,subject,message,data,user_id,closed,admin_id,rating from support_tickets where id_parent=".(int)$_GET["id"]." order by id";
		$ds->open($sql);
		while(!$ds->eof)
		{
			$box_style="";
			$box_style2="box-success";
			if($ds->row["admin_id"]!=0)
			{
				$box_style="2";
				$box_style2="box-primary";
			}
			?>
			<div class="shadow_box<?=$box_style?> box box_padding <?=$box_style2?>">
			<div class="shadow_box_title<?=$box_style?>">			
				<div class="shadow_box_date"><?=show_time_ago($ds->row["data"])?></div>
				<?
				if($ds->row["user_id"]!=0)
				{
				?>
					<div class="link_user"><a href="../customers/content.php?id=<?=$ds->row["user_id"]?>"><b><?=user_url_back($ds->row["user_id"])?></b></a></div>
				<?
				}
				else
				{
					$sql="select login from people where id=".$ds->row["admin_id"];
					$dr->open($sql);
					if(!$dr->eof)
					{
						?>
						<div class="link_user"><a href="../administrators/content.php?id=<?=$ds->row["admin_id"]?>"><b><?=$dr->row["login"]?></b></a></div>
						<?
					}
				}
				?>
			</div>
			<div class="shadow_box_content">
				<?=str_replace("\n","<br>",$ds->row["message"])?>
				

				
				<div class="shadow_box_footer">
					<input type="button" value="<?=word_lang("delete")?>" class="btn btn-small btn-sm btn-danger" style="margin:10px 0px 0px 0px;float:right" onClick="location.href='delete_post.php?id=<?=$ds->row["id"]?>&id_parent=<?=$rs->row["id"]?>'">
					
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
					readOnly   : true
				});
    			});
				</script>
				<div id="star<?=$ds->row["id"]?>" style="margin-top:7px"></div>
				<?
			}
			?>
					
					<div class="clear"></div>
				</div>
							
			</div>
			</div>
			<?
			$ds->movenext();
		}
		?>


			<div class="shadow_box box box_padding">

			
				<form method="post" action="add.php?id=<?=(int)$_GET["id"]?>">
								
				<textarea class="ibox form-control" style="width:90%;height:150px;margin:20px" name="message" id="message"></textarea>

				<input type="submit" value="<?=word_lang("send")?>" class="btn btn-primary" style="margin:0px 0px 20px 20px">
				
				</form>
			</div>
			<?
	}
}
?>



<? include("../inc/end.php");?>