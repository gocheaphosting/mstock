<?if(!defined("site_root")){exit();}?>
<?if(isset($_SESSION["rights"]["orders_orders"])){?>
<div class="box box-primary" id="box_orders">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=word_lang("orders")?></h3>
                  <div class="box-tools pull-right">
                    <button id="orders_collapse" class="btn btn-box-tool" data-widget="collapse" onClick="collapse_tab('orders')"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('orders')"><i class="fa fa-times"></i></button>
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
							$sql="select count(id) as count_param from orders where status=1 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where status=1 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where status=1 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where status=1 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where status=0 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where status=0 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where status=0 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where status=0 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600)." group by status";
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
							$sql="select count(id) as count_param from orders where data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)." group by status";
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
							$sql="select sum(total) as count_param from orders where status=0 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600)." group by status";
							$rs->open($sql);
							if(!$rs->eof)
							{
								$count_param=$rs->row["count_param"];
							}
							echo(float_opt($count_param,2));
						?>
						</td>
						<td>
						<?
							$count_param=0;
							$sql="select sum(total) as count_param from orders where status=0 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600)." group by status";
							$rs->open($sql);
							if(!$rs->eof)
							{
								$count_param=$rs->row["count_param"];
							}
							echo(float_opt($count_param,2));
						?>
						</td>
						<td>
						<?
							$count_param=0;
							$sql="select sum(total) as count_param from orders where status=0 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600)." group by status";
							$rs->open($sql);
							if(!$rs->eof)
							{
								$count_param=$rs->row["count_param"];
							}
							echo(float_opt($count_param,2));
						?>
						</td>
						<td>
						<?
							$count_param=0;
							$sql="select sum(total) as count_param from orders where status=0 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)." group by status";
							$rs->open($sql);
							if(!$rs->eof)
							{
								$count_param=$rs->row["count_param"];
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
							$sql="select sum(total) as count_param from orders where status=1 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1*24*3600)." group by status";
							$rs->open($sql);
							if(!$rs->eof)
							{
								$count_param=$rs->row["count_param"];
							}
							echo(float_opt($count_param,2));
						?>
						</td>
						<td><b>
						<?
							$count_param=0;
							$sql="select sum(total) as count_param from orders where status=1 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-7*24*3600)." group by status";
							$rs->open($sql);
							if(!$rs->eof)
							{
								$count_param=$rs->row["count_param"];
							}
							echo(float_opt($count_param,2));
						?>
						</td>
						<td><b>
						<?
							$count_param=0;
							$sql="select sum(total) as count_param from orders where status=1 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-30*24*3600)." group by status";
							$rs->open($sql);
							if(!$rs->eof)
							{
								$count_param=$rs->row["count_param"];
							}
							echo(float_opt($count_param,2));
						?>
						</td>
						<td><b>
						<?
							$count_param=0;
							$sql="select sum(total) as count_param from orders where status=1 and data>".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)." group by status";
							$rs->open($sql);
							if(!$rs->eof)
							{
								$count_param=$rs->row["count_param"];
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