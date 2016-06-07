<?if(!defined("site_root")){exit();}?>
<?if($global_settings["subscription"] and isset($_SESSION["rights"]["orders_subscription"])){?>
              <div class="box box-danger" id="box_new_subscription">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=word_lang("new subscriptions")?></h3>
                  <div class="box-tools pull-right">
                  	<?
                  	if(@$_SESSION["user_subscription"] > 0)
					{
                  		?>
                  		<span class="label label-danger"><?=$_SESSION["user_subscription"]?></span>
                  		<?
                  	}
                  	?>
                    <button  id="new_subscription_collapse"  onClick="collapse_tab('new_subscription')" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('new_subscription')"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                        <tr>
							<th><b><?=word_lang("id")?></b></th>
							<th><b><?=word_lang("date")?></b></th>
							<th><b><?=word_lang("user")?></b></th>
							<th><b><?=word_lang("title")?></b></th>
							<th><b><?=word_lang("status")?></b></th>
                        </tr>
                      </thead>
                      <tbody>

                        
                        <?
						$tr=1;
						$sql="select id_parent,user,data1,title,approved,payments from subscription_list order by data1 desc limit 5";
						$rs->open($sql);
						while(!$rs->eof)
						{
						
						$cl="success";
						if($rs->row["approved"]!=1)
						{
						$cl="danger";
						}
						?>
						<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
						<td nowrap><a href="../subscription_list/edit.php?id=<?=$rs->row["id_parent"]?>"><b>#<?=$rs->row["id_parent"]?></b></a></td>
						<td nowrap><?=date(date_format,$rs->row["data1"])?></td>
						<td>
						<?
						$sql="select id_parent,login from users where login='".$rs->row["user"]."'";
						$ds->open($sql);
						if(!$ds->eof)
						{
						?><div class="link_user"><a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a></div><?
						}
						?>
						</td>
						<td><?if($rs->row["payments"]>1){echo("<font color='red'>".$rs->row["payments"]."&nbsp;x&nbsp;</font>");}?><?=$rs->row["title"]?></td>
						<td>
						
						
						<div id="sstatus<?=$rs->row["id_parent"]?>" name="sstatus<?=$rs->row["id_parent"]?>"><a href="javascript:doLoad3(<?=$rs->row["id_parent"]?>);"><span class="label label-<?=$cl?>"><?if($rs->row["approved"]==1){echo(word_lang("approved"));}else{echo(word_lang("pending"));}?></span></a></div>
						
						
						
						<form id="sf<?=$rs->row["id_parent"]?>" enctype="multipart/form-data" style="margin:0">
						
						<input type="hidden" name="id" value="<?=$rs->row["id_parent"]?>">
						
						</form>
						
						
						
						</td>
						</tr>
						<?
						$tr++;
						$rs->movenext();
						}
						?>

                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="box-footer clearfix">
                  <a href="../subscription_list/" class="btn btn-sm btn-default btn-flat pull-right"><?=word_lang("All Subscriptions")?></a>
                </div>
              </div>
              
<?}?>