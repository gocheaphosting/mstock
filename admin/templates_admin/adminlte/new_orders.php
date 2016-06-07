<?if(!defined("site_root")){exit();}?>
<?if(isset($_SESSION["rights"]["orders_orders"])){?>
              <div class="box box-info" id="box_new_orders">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=word_lang("new orders")?></h3>
                  <div class="box-tools pull-right">
                  	<?
                  	if(@$_SESSION["user_orders"] > 0)
					{
                  		?>
                  		<span class="label label-info"><?=$_SESSION["user_orders"]?></span>
                  		<?
                  	}
                  	?>
                    <button  id="new_orders_collapse" class="btn btn-box-tool" data-widget="collapse" onClick="collapse_tab('new_orders')"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('new_orders')"><i class="fa fa-times"></i></button>
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
							<th><b><?=word_lang("total")?></b></th>
							<th><b><?=word_lang("status")?></b></th>
							<th><b><?=word_lang("shipping")?></b></th>
                        </tr>
                      </thead>
                      <tbody>

                        
                        <?
						$tr=1;
						$sql="select id,user,data,total,status,shipped,shipping from orders order by data desc limit 5";
						$rs->open($sql);
						while(!$rs->eof)
						{
						
						$cl="success";
						if($rs->row["status"]!=1)
						{
						$cl="danger";
						}
						
						$cl2="info";
						if($rs->row["shipped"]!=1)
						{
						$cl2="warning";
						}
						?>
						<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
						<td><a href="../orders/order_content.php?id=<?=$rs->row["id"]?>"><b>#<?=$rs->row["id"]?></b></a></td>
						<td><?=date(date_format,$rs->row["data"])?></td>
						<td>
						<?
						$sql="select id_parent,login from users where id_parent=".$rs->row["user"];
						$ds->open($sql);
						if(!$ds->eof)
						{
						?><div class="link_user"><a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a></div><?
						}
						?>
						</td>
						<td><?=float_opt($rs->row["total"],2)?></td>
						<td>
						
						
						<div id="status<?=$rs->row["id"]?>" name="status<?=$rs->row["id"]?>"><a href="javascript:doLoad(<?=$rs->row["id"]?>);"><span class="label label-<?=$cl?>"><?if($rs->row["status"]==1){echo(word_lang("approved"));}else{echo(word_lang("pending"));}?></span></a></div>
						
						
						
						<form id="f<?=$rs->row["id"]?>" enctype="multipart/form-data" style="margin:0">
						
						<input type="hidden" name="id" value="<?=$rs->row["id"]?>">
						
						</form>
						
						
						
						</td>
						<td>
						<?
						if($rs->row["shipping"]*1!=0)
						{
						?>
						
						<div class="link_status" id="shipping<?=$rs->row["id"]?>" name="shipping<?=$rs->row["id"]?>"><a href="javascript:doLoad4(<?=$rs->row["id"]?>);"><span class="label label-<?=$cl2?>"><?if($rs->row["shipped"]==1){echo(word_lang("shipped"));}else{echo(word_lang("not shipped"));}?></span></a></div>
						
						
						
						<?
						}
						else
						{
						echo("<span class='label label-default'>".word_lang("digital")."</span>");
						}
						?>
						
						
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
                  <a href="../orders/" class="btn btn-sm btn-default btn-flat pull-right"><?=word_lang("All Orders")?></a>
                </div>
              </div>
              
<?}?>