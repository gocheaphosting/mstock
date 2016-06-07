<?if(!defined("site_root")){exit();}?>
<?if($global_settings["subscription"] and isset($_SESSION["rights"]["orders_subscription"])){?>
              <div class="box box-success" id="box_subscription">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=word_lang("subscription")?></h3>
                  <div class="box-tools pull-right">
                    <button  id="subscription_collapse"  onClick="collapse_tab('subscription')" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('subscription')"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                    	<thead>
						<tr>
						<th><b><?=word_lang("stats")?></b></th>
						<th><b><?=word_lang("day")?></b></th>
						<th><b><?=word_lang("week")?></b></th>
						<th><b><?=word_lang("month")?></b></th>
						<th><b><?=word_lang("year")?></b></th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td><?=word_lang("approved")?></td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where approved=1 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where approved=1 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where approved=1 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where approved=1 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						</tr>
						<tr class="snd">
						<td><?=word_lang("pending")?></td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where approved=0 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where approved=0 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where approved=0 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where approved=0 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						</tr>
						<tr>
						<td><?=word_lang("quantity")?></td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select count(id_parent) as count_param from subscription_list where data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)." group by approved";
						$rs->open($sql);
						if(!$rs->eof)
						{
						$count_param=$rs->row["count_param"];
						}
						echo($count_param);
						?>
						</td>
						</tr>
						
						
						
						
						
						<tr class="snd">
						<td><?=word_lang("Total")?> (<?=word_lang("pending")?>)</td>
						<td>
						<?
						$count_param=0;
						$sql="select subscription,payments,total from subscription_list where approved=0 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600);
						$rs->open($sql);
						while(!$rs->eof)
						{
							$sql="select price from subscription where id_parent=".$rs->row["subscription"];
							$ds->open($sql);
							while(!$ds->eof)
							{
								$count_param+=$rs->row["total"]*$rs->row["payments"];
								$ds->movenext();
							}
							$rs->movenext();
						}
						echo(float_opt($count_param,2));
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select subscription,payments,total  from subscription_list where approved=0 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600);
						$rs->open($sql);
						while(!$rs->eof)
						{
							$sql="select price from subscription where id_parent=".$rs->row["subscription"];
							$ds->open($sql);
							while(!$ds->eof)
							{
								$count_param+=$rs->row["total"]*$rs->row["payments"];
								$ds->movenext();
							}
						$rs->movenext();
						}
						echo(float_opt($count_param,2));
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select subscription,payments,total  from subscription_list where approved=0 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600);
						$rs->open($sql);
						while(!$rs->eof)
						{
							$sql="select price from subscription where id_parent=".$rs->row["subscription"];
							$ds->open($sql);
							while(!$ds->eof)
							{
								$count_param+=$rs->row["total"]*$rs->row["payments"];
								$ds->movenext();
							}
							$rs->movenext();
						}
						echo(float_opt($count_param,2));
						?>
						</td>
						<td>
						<?
						$count_param=0;
						$sql="select subscription,payments,total  from subscription_list where approved=0 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600);
						$rs->open($sql);
						while(!$rs->eof)
						{
							$sql="select price from subscription where id_parent=".$rs->row["subscription"];
							$ds->open($sql);
							while(!$ds->eof)
							{
								$count_param+=$rs->row["total"]*$rs->row["payments"];
								$ds->movenext();
							}
							$rs->movenext();
						}
						echo(float_opt($count_param,2));
						?>
						</td>
						</tr>
						
						
						
						
						<tr>
						<td><?=word_lang("Total")?> (<?=word_lang("approved")?>)</td>
						<td><b>
						<?
						$count_param=0;
						$sql="select subscription,payments,total  from subscription_list where approved=1 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600);
						$rs->open($sql);
						while(!$rs->eof)
						{
							$sql="select price from subscription where id_parent=".$rs->row["subscription"];
							$ds->open($sql);
							while(!$ds->eof)
							{
								$count_param+=$rs->row["total"]*$rs->row["payments"];
								$ds->movenext();
							}
							$rs->movenext();
						}
						echo(float_opt($count_param,2));
						?>
						</td>
						<td><b>
						<?
						$count_param=0;
						$sql="select subscription,payments,total  from subscription_list where approved=1 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600);
						$rs->open($sql);
						while(!$rs->eof)
						{
							$sql="select price from subscription where id_parent=".$rs->row["subscription"];
							$ds->open($sql);
							while(!$ds->eof)
							{
								$count_param+=$rs->row["total"]*$rs->row["payments"];
								$ds->movenext();
							}
							$rs->movenext();
						}
						echo(float_opt($count_param,2));
						?>
						</td>
						<td><b>
						<?
						$count_param=0;
						$sql="select subscription,payments,total  from subscription_list where approved=1 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600);
						$rs->open($sql);
						while(!$rs->eof)
						{
							$sql="select price from subscription where id_parent=".$rs->row["subscription"];
							$ds->open($sql);
							while(!$ds->eof)
							{
								$count_param+=$rs->row["total"]*$rs->row["payments"];
								$ds->movenext();
							}
							$rs->movenext();
						}
						echo(float_opt($count_param,2));
						?>
						</td>
						<td><b>
						<?
						$count_param=0;
						$sql="select subscription,payments,total  from subscription_list where approved=1 and data1>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600);
						$rs->open($sql);
						while(!$rs->eof)
						{
							$sql="select price from subscription where id_parent=".$rs->row["subscription"];
							$ds->open($sql);
							while(!$ds->eof)
							{
								$count_param+=$rs->row["total"]*$rs->row["payments"];
								$ds->movenext();
							}
							$rs->movenext();
						}
						echo(float_opt($count_param,2));
						?>
						</td>
						</tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              
<?}?>