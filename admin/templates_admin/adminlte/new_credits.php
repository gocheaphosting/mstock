<?if(!defined("site_root")){exit();}?>
<?if($global_settings["credits"] and isset($_SESSION["rights"]["orders_credits"])){?>
              <div class="box box-warning" id="box_new_credits">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=word_lang("new credits")?></h3>
                  <div class="box-tools pull-right">
                  	<?
                  	if(@$_SESSION["user_credits"] > 0)
					{
                  		?>
                  		<span class="label label-warning"><?=$_SESSION["user_credits"]?></span>
                  		<?
                  	}
                  	?>
                    <button  id="new_credits_collapse"  onClick="collapse_tab('new_credits')" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('new_credits')"><i class="fa fa-times"></i></button>
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
							<th><b><?=word_lang("quantity")?></b></th>
							<th><b><?=word_lang("status")?></b></th>
                        </tr>
                      </thead>
                      <tbody>                   
						<?
						$tr=1;
						$sql="select id_parent,user,data,quantity,approved from credits_list where quantity>0 order by data desc limit 6";
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
						<td>
						<?if($rs->row["quantity"]>0){?>
						<a href="../orders/payments.php?product_id=<?=$rs->row["id_parent"]?>&product_type=credits&print=1">
						<?}?>
						
						<b>#<?=$rs->row["id_parent"]?></b></a></td>
						<td><?=date(date_format,$rs->row["data"])?></td>
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
						<td><?=$rs->row["quantity"]?></td>
						<td>
						
						
						<div id="cstatus<?=$rs->row["id_parent"]?>" name="cstatus<?=$rs->row["id_parent"]?>"><a href="javascript:doLoad2(<?=$rs->row["id_parent"]?>);"><span class="label label-<?=$cl?>"><?if($rs->row["approved"]==1){echo(word_lang("approved"));}else{echo(word_lang("pending"));}?></span></a></div>
						
						
						
						<form id="cf<?=$rs->row["id_parent"]?>" enctype="multipart/form-data" style="margin:0">
						
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
                  <a href="../credits/" class="btn btn-sm btn-default btn-flat pull-right"><?=word_lang("All Credits")?></a>
                </div>
              </div>
              
<?}?>