<?
//Check access
admin_panel_access("settings_printful");

if(!defined("site_root")){exit();}
?>


<script language="javascript">
function publications_select_all(sel_form)
{
    if(sel_form.selector.checked)
   	{
        $("input:checkbox", sel_form).attr("checked",true);
    }
    else
    {
        $("input:checkbox", sel_form).attr("checked",false);
    }
}
</script>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">
<a href="https://www.theprintful.com"><b>Printful</b></a> print custom t-shirts, posters, canvas and other print products and send them to your customers.


</div>

<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">


<form method="post" action="change.php">

	<div class='admin_field'>
	<span>Printful API key:</span>
	<input type="text" name="printful_api" value="<?=$global_settings["printful_api"]?>" style="width:350px">
	</div>
	
	<div class='admin_field'>
	<span>Order ID:</span>
	<input type="text" name="printful_order_id" value="<?=$global_settings["printful_order_id"]?>" style="width:50px">
	<div class="smalltext">Starting with this ID the orders will be sent to Printful.</div>
	</div>
	
	<div class='admin_field'>
	<span>Mode:</span>
	<select name="printful_mode"  style="width:350px">
		<option value="noconfirm" <?if($global_settings["printful_mode"]=="noconfirm"){echo("selected");}?>>Place order without confirmation (test mode)</option>
		<option value="confirm" <?if($global_settings["printful_mode"]=="confirm"){echo("selected");}?>>Place order with confirmation (live mode)</option>
	</select>

	</div>
	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>
</form>

</div>





<div class="subheader"><?=word_lang("prints")?></div>
<div class="subheader_text">

<p>First you should associate <b>your prints products</b> with <b><a href="https://www.theprintful.com/products" target="blank">Printful Product IDs</a></b>.</p>



<p><b>Select product:</b></p>
<p><select style="width:300px" onChange="location.href='index.php?d=1&print_id='+this.value">
<option></option>
<?
$sql="select id_parent,title from prints order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	$sel="";
	if(@$_GET["print_id"]==$rs->row["id_parent"]){$sel="selected";}
	?>
	<option value="<?=$rs->row["id_parent"]?>" <?=$sel?>><?=$rs->row["title"]?></option>
	<?
	$rs->movenext();
}
?>
</select></p>

<?
if(isset($_GET["print_id"]))
{
	$sql="select option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where id_parent=".(int)$_GET["print_id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>
		<form method="post" action="change_prints.php?print_id=<?=(int)$_GET["print_id"]?>"  id="adminform" name="adminform">
		<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
		<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover">
		<tr>
		<?		
		for($i=1;$i<11;$i++)
		{
			if($rs->row["option".$i]!=0)
			{
				$sql="select * from products_options where id=".$rs->row["option".$i];
				$ds->open($sql);
				if(!$ds->eof)
				{			
					$sql="select * from products_options_items where id_parent=".$rs->row["option".$i];
					$dr->open($sql);
					while(!$dr->eof)
					{
						$print_values[]=$dr->row["title"];
						$dr->movenext();
					}
					
					$print_properties[$i."_".$rs->row["option".$i]]=$print_values;
					unset($print_values);
					?>
					<th><?=$ds->row["title"]?></th>
					<?
				}
			}
		}
		//var_dump($print_properties);
		?>
		<th>Printful ID</th>
		<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"> <?=word_lang("delete")?></th>
		</tr>
		<tr>
		<?
			foreach ($print_properties as $key => $value) 
			{
				?>
				<td>
				<select name="newoption<?=$key?>" class="ibox form-control" style="width:100px">
				<option value="0"></option>
				<?
				foreach ($value as $key2 => $value2) 
				{
					?>
					<option value="<?=$value2?>"><?=$value2?></option>
					<?
				}
				?>
				</select>
				</td>
				<?
			}
		?>
		<td><input type="text" name="newprintful_id" value="0" class="ibox form-control" style="width:100px"></td>
		<td style="text-align:center"><?=word_lang("new")?></td>
		</tr>
		<?
		$sql="select * from printful_prints where print_id=".(int)$_GET["print_id"]." order by id";
		$ds->open($sql);
		while(!$ds->eof)
		{
			?>
			<tr>
			<?
			foreach ($print_properties as $key => $value) 
			{
				?>
				<td>
				<select name="option<?=$key?>_<?=$ds->row["id"]?>" class="ibox form-control" style="width:100px">
				<option value="0"></option>
				<?
				foreach ($value as $key2 => $value2) 
				{
					$sel="";
					$ii=explode("_",$key);
					if($value2==@$ds->row["option".$ii[0]."_value"]){$sel="selected";}
					?>
					<option value="<?=$value2?>" <?=$sel?>><?=$value2?></option>
					<?
				}
				?>
				</select>
				</td>
				<?
			}
		?>
			
			<td><input type="text" name="printful_id<?=$ds->row["id"]?>" value="<?=$ds->row["printful_id"]?>" class="ibox form-control" style="width:100px"></td>
			<td style="text-align:center"><input type="checkbox" name="delete<?=$ds->row["id"]?>" value="1"></td>
			</tr>
			<?
			$ds->movenext();
		}
		?>
		</table>
		</div></div></div></div></div></div></div></div>
		<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin:10px 0px 20px 6px">
		</form>
		<?
	}
}
?>

</div>