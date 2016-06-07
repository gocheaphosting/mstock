<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_shipping");
?>
<? include("../inc/begin.php");?>


<?
$user_fields=array();
$user_fields["title"]="";
$user_fields["shipping_time"]="2-3 days";
$user_fields["activ"]=1;
$user_fields["methods"]="weight";
$user_fields["methods_calculation"]=1;
$user_fields["taxes"]=0;
$user_fields["regions"]=0;
$user_fields["weight_min"]=0;
$user_fields["weight_max"]=100;
$user_fields["flatrate"]=1;


$ranges_list=array();
$ranges_from=array();
$ranges_to=array();
$ranges_price=array();

if(isset($_GET["id"]))
{
	$sql="select * from shipping where id=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$user_fields["title"]=$rs->row["title"];
		$user_fields["shipping_time"]=$rs->row["shipping_time"];
		$user_fields["activ"]=$rs->row["activ"];
		$user_fields["methods"]=$rs->row["methods"];
		$user_fields["methods_calculation"]=$rs->row["methods_calculation"];
		$user_fields["taxes"]=$rs->row["taxes"];
		$user_fields["regions"]=$rs->row["regions"];
		$user_fields["weight_min"]=$rs->row["weight_min"];
		$user_fields["weight_max"]=$rs->row["weight_max"];
		
		if($user_fields["methods"]=="flatrate")
		{
			$sql="select price from shipping_ranges where id_parent=".(int)$_GET["id"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				$user_fields["flatrate"]=$ds->row["price"];
			}
			$ranges_list=array(0,1);
			$ranges_from=array(0,1);
			$ranges_to=array(1,1000000);
			$ranges_price=array(1,2);
		}
		else
		{
			$sql="select * from shipping_ranges where id_parent=".(int)$_GET["id"]." order by from_param";
			$ds->open($sql);
			if(!$ds->eof)
			{
				while(!$ds->eof)
				{
					$ranges_list[]=count($ranges_list);
					$ranges_from[]=$ds->row["from_param"];
					$ranges_to[]=$ds->row["to_param"];
					$ranges_price[]=$ds->row["price"];
					$ds->movenext();
				}
			}
			else
			{
				$ranges_list=array(0,1);
				$ranges_from=array(0,1);
				$ranges_to=array(1,1000000);
				$ranges_price=array(1,2);
			}
		}
	}
}
else
{
	$ranges_list=array(0,1);
	$ranges_from=array(0,1);
	$ranges_to=array(1,1000000);
	$ranges_price=array(1,2);
}


function build_rangers($method)
{
	global $ranges_list;
	global $ranges_from;
	global $ranges_to;
	global $ranges_price;
	
	$res="";
	
	for($i=0;$i<count($ranges_list);$i++)
	{
		$res.="<tr id='tr".$method.$ranges_list[$i]."'>
					<td><input name='".$method.$ranges_list[$i]."_1' id='".$method.$ranges_list[$i]."_1' type='text' value='".float_opt($ranges_from[$i],3)."' style='width:90px;'></td>
					<td><input name='".$method.$ranges_list[$i]."_2' id='".$method.$ranges_list[$i]."_2' type='text' value='".float_opt($ranges_to[$i],3)."' style='width:90px;'></td>
					<td><input name='".$method."_price".$ranges_list[$i]."' id='".$method."_price".$ranges_list[$i]."' type='text' value='".$ranges_price[$i]."' style='width:90px;'></td>
					<td><input type='button' class='btn' value='".word_lang("delete")."' style='width:70px' onClick=\"remove_range('".$method.$ranges_list[$i]."')\"></td>
				</tr>";
	}
	return $res;
}
?>


<div class="back"> <a href="index.php" class="btn btn-primary btn-sm btn-mini"><b><i class="fa fa-arrow-left"></i>&nbsp; <?=word_lang("back")?></a></div>

<script language="javascript">
function publications_select_all()
{
	$("#regions_list2 input:checkbox").each(function(){this.checked = !this.checked && !this.disabled;});
}

function change_regions(value)
{
	if(value==0)
	{
		document.getElementById('regions_list').style.display='none';
	}
	else
	{
		document.getElementById('regions_list').style.display='block';
	}
}


function show_methods()
{
	types=new Array("weight","quantity","subtotal","flatrate");
	current_method=document.getElementById('methods').value;
	for(i=0;i<types.length;i++)
	{
		if(types[i]==current_method)
		{
			document.getElementById('method_'+types[i]).style.display='block';
		}
		else
		{
			document.getElementById('method_'+types[i]).style.display='none';
		}
	}
}


function show_dollar()
{
	if(document.getElementById('methods_calculation').value=="percent")
	{
		$('.dollar_percent').html("%");
		$('.dollar_percent2').html("%");
	}
	else
	{
		$('.dollar_percent').html("$");
		$('.dollar_percent2').html("$");
	}
}



item_id=<?=count($ranges_list)?>;
item_mass=new Array(
<?
for($i=0;$i<count($ranges_list);$i++)
{
	if($i!=0){echo(",");}
	echo($ranges_list[$i]);
}
?>
);



function add_range(value)
{
	item_id++;
	item_mass[item_mass.length]=item_id;
	$("#ranges_"+value+" > tbody").append("<tr id='tr"+value+item_id+"'><td><input type='text' name='"+value+item_id+"_1'  id='"+value+item_id+"_1' value='0' style='width:90px;' class='form-control'></td><td><input type='text' name='"+value+item_id+"_2' id='"+value+item_id+"_2' value='1' style='width:90px;' class='form-control'></td><td><input type='text' name='"+value+"_price"+item_id+"' id='"+value+"_price"+item_id+"' value='1' style='width:90px;' class='form-control'></td><td><input type='button' class='btn' value='<?=word_lang("delete")?>' style='width:70px' onClick=\"remove_range('"+value+item_id+"')\"></td></tr>");
}

function remove_range(value)
{
	$('#tr'+value).remove();
}

function check()
{
	flag=true;
	current_method=document.getElementById('methods').value;
	
	for(i=0;i<item_mass.length;i++)
	{
		id11=current_method+item_mass[i]+"_1";
		id12=current_method+item_mass[i]+"_2";
		if(document.getElementById(id11) && document.getElementById(id12))
		{
			$('#'+id11).css({'border-color':'#a8a8a8'});
			$('#'+id12).css({'border-color':'#a8a8a8'});
		}
	}
	
	for(i=0;i<item_mass.length;i++)
	{
		val11=-1;
		val12=-1;
		val21=-1;
		val22=-1;
		
		id11=current_method+item_mass[i]+"_1";
		if(document.getElementById(id11))
		{
			val11=document.getElementById(id11).value*1;
		}
		
		id12=current_method+item_mass[i]+"_2";
		if(document.getElementById(id12))
		{
			val12=document.getElementById(id12).value*1;
		}
		
		if(i<item_mass.length-1)
		{
			id21=current_method+item_mass[i+1]+"_1";
			if(document.getElementById(id21))
			{
				val21=document.getElementById(id21).value*1;
			}
			
			id22=current_method+item_mass[i+1]+"_2";
			if(document.getElementById(id22))
			{
				val22=document.getElementById(id22).value*1;
			}
		}

		if(val11!=-1 && val12!=-1  &&  val11>val12)
		{
			$('#'+id11).css({'border-color':'#d70c0c'});
			$('#'+id12).css({'border-color':'#d70c0c'});
			$('#'+id12).focus();
			flag=false;
		}
		
		if(val12!=-1 && val21!=-1  &&  val12>val21)
		{
			$('#'+id12).css({'border-color':'#d70c0c'});
			$('#'+id21).css({'border-color':'#d70c0c'});
			$('#'+id21).focus();			
			flag=false;
		}

	}
	
	if(flag==false)
	{
		alert("The first range must be smaller that the second one!");
	}
	
	return flag;
}

</script>


<h1><?=word_lang("shipping method")?> &mdash; <?
if(!isset($_GET["id"]))
{
	echo(word_lang("add")." ");
}
else
{
	echo(word_lang("edit")." ");
}
?></h1>







<div class="box box_padding">




<form method="post" action="add.php<?if(isset($_GET["id"])){echo("?id=".$_GET["id"]);}?>"  Enctype="multipart/form-data" name="shipping_form" onsubmit="return check();">

<div class="subheader"><?=word_lang("common information")?></div>
<div class="subheader_text">




	<div class='admin_field'>
	<span><?=word_lang("title")?>:</span>
	<input type="text" name="title" value="<?=$user_fields["title"]?>" style="width:350px">
	</div>
	

	
	<div class='admin_field'>
	<span><?=word_lang("shipping time")?>:</span>
	<input type="text" name="shipping_time" value="<?=$user_fields["shipping_time"]?>" style="width:150px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("weight min/max")?> (<?=$global_settings["weight"]?>):</span>
	<input type="text" name="weight_min" value="<?=$user_fields["weight_min"]?>" style="width:50px">&nbsp;-&nbsp;<input type="text" name="weight_max" value="<?=$user_fields["weight_max"]?>" style="width:50px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("enabled")?>:</span>
	<input type="checkbox" name="activ" <?if($user_fields["activ"]){echo("checked");}?>>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("taxes")?>:</span>
	<input type="checkbox" name="taxes" <?if($user_fields["taxes"]){echo("checked");}?>>
	</div>

</div>
	
<div class="subheader"><?=word_lang("shipping charges")?></div>
<div class="subheader_text">

	<div class='admin_field'>
	<span><?=word_lang("calculation method")?>:</span>
	<select name="methods" id="methods" style="width:200px" onChange="show_methods()">
		<option value="weight" <?if($user_fields["methods"]=="weight"){echo("selected");}?>><?=word_lang("weight")?></option>
		<option value="quantity" <?if($user_fields["methods"]=="quantity"){echo("selected");}?>><?=word_lang("quantity")?></option>
		<option value="subtotal" <?if($user_fields["methods"]=="subtotal"){echo("selected");}?>><?=word_lang("subtotal")?></option>
		<option value="flatrate" <?if($user_fields["methods"]=="flatrate"){echo("selected");}?>><?=word_lang("flat rate")?></option>
	</select>&nbsp;&nbsp;
	<select name="methods_calculation" id="methods_calculation" style="width:70px" onChange="show_dollar()">
		<option value="percent" <?if($user_fields["methods_calculation"]=="percent"){echo("selected");}?>>%</option>
		<option value="currency" <?if($user_fields["methods_calculation"]=="currency"){echo("selected");}?>>$</option>
	</select>
	</div>
	
	<div id="method_weight" style="display:<?if($user_fields["methods"]=="weight"){echo("block");}else{echo("none");}?>">
		<div class='admin_field'>
			<table border="0" cellpadding="0" cellspacing="0" class="ranges" id="ranges_weight">
				<tbody>
				<tr>
					<td style='width:90px;'><b><?=word_lang("from")?> (<?=$global_settings["weight"]?>):</b></td>
					<td style='width:90px;'><b><?=word_lang("till")?> (<?=$global_settings["weight"]?>):</b></td>
					<td style='width:90px;'><b><?=word_lang("cost")?> (<div class="dollar_percent2"><?if($user_fields["methods_calculation"]=="percent"){echo("%");}else{echo("$");}?></div>):</b></td>
					<td></td>
				</tr>				
				<?=build_rangers("weight")?>
				</tbody>
			</table>
			<input type="button" value="<?=word_lang("add")?>" class="btn" onClick="add_range('weight')">
		</div>
	</div>
	
	
	<div id="method_quantity" style="display:<?if($user_fields["methods"]=="quantity"){echo("block");}else{echo("none");}?>">
		<div class='admin_field'>
			<table border="0" cellpadding="0" cellspacing="0" class="ranges" id="ranges_quantity">
				<tbody>
				<tr>
					<td style='width:90px;'><b><?=word_lang("from")?>:</b></td>
					<td style='width:90px;'><b><?=word_lang("till")?>:</b></td>
					<td style='width:90px;'><b><?=word_lang("cost")?> (<div class="dollar_percent2"><?if($user_fields["methods_calculation"]=="percent"){echo("%");}else{echo("$");}?></div>):</b></td>
					<td></td>
				</tr>		
				<?=build_rangers("quantity")?>
				</tbody>
			</table>
			<input type="button" value="<?=word_lang("add")?>" class="btn" onClick="add_range('quantity')">
		</div>
	</div>
	
	
	<div id="method_subtotal" style="display:<?if($user_fields["methods"]=="subtotal"){echo("block");}else{echo("none");}?>">
		<div class='admin_field'>
			<table border="0" cellpadding="0" cellspacing="0" class="ranges" id="ranges_subtotal">
				<tbody>
				<tr>
					<td style='width:90px;'><b><?=word_lang("from")?> ($):</b></td>
					<td style='width:90px;'><b><?=word_lang("till")?> ($):</b></td>
					<td style='width:90px;'><b><?=word_lang("cost")?> (<div class="dollar_percent2"><?if($user_fields["methods_calculation"]=="percent"){echo("%");}else{echo("$");}?></div>):</b></td>
					<td></td>
				</tr>
				<?=build_rangers("subtotal")?>
				</tbody>
			</table>
			<input type="button" value="<?=word_lang("add")?>" class="btn" onClick="add_range('subtotal')">
		</div>
	</div>
	
	
	<div id="method_flatrate" style="display:<?if($user_fields["methods"]=="flatrate"){echo("block");}else{echo("none");}?>">
		<div class='admin_field'>
			<span><?=word_lang("cost")?>:</span>
			<input type="text" name="flatrate" value="<?=float_opt($user_fields["flatrate"],2)?>" style="width:150px">&nbsp;&nbsp;<div class="dollar_percent"><?if($user_fields["methods_calculation"]=="percent"){echo("%");}else{echo("$");}?></div>
		</div>
	</div>

	
</div>


<div class="subheader"><?=word_lang("regions")?></div>
<div class="subheader_text">
	<input type="radio" name="regions" value="0" <?if(!$user_fields["regions"]){echo("checked");}?> onClick="change_regions(0)">&nbsp;<?=word_lang("everywhere")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="regions" value="1" <?if($user_fields["regions"]){echo("checked");}?> onClick="change_regions(1)">&nbsp;<?=word_lang("regions")?>
	
	<div id="regions_list" style="display:<?if(!$user_fields["regions"]){echo("none");}else{echo("block");}?>">
		<?
			$regions_activ=array();
			if(isset($_GET["id"]))
			{
				$sql="select country,state from shipping_regions where id_parent=".(int)$_GET["id"];
				$rs->open($sql);
				while(!$rs->eof)
				{
					$regions_activ[$rs->row["country"]][$rs->row["state"]]=1;
					$rs->movenext();
				}
			}
		
			$j=0;
			?>
			<div><input  type="checkbox" id="selector" name="selector" value="1" onClick="publications_select_all();">&nbsp;&nbsp;<b><?=word_lang("select all")?></b></div><div id="regions_list2">
			<?
			$sql="select name,id from countries where activ=1 order by priority,name";
			$dd->open($sql);
			while(!$dd->eof)
			{	
				$sel="";
				if(isset($regions_activ[$dd->row["name"]]['']))
				{
					$sel="checked";
				}
				?>
				<div><input name="country<?=$dd->row["id"]?>" type="checkbox" <?=$sel?>>&nbsp;&nbsp;<?=$dd->row["name"]?></div>
				<?
				if(isset($mstates[$dd->row["name"]]))
				{
					foreach ($mstates[$dd->row["name"]] as $key => $value) 
					{
						$sel="";
						if(isset($regions_activ[$dd->row["name"]][$value]))
						{
							$sel="checked";
						}
						?>
						<div style="margin-left:20px"><input name="state<?=$j?>" type="checkbox" <?=$sel?>>&nbsp;&nbsp;<?=$value?></div>
						<?
						$j++;
					}
				}
				$dd->movenext();
			}
	?>
	</div>
	</div>
</div>



<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">
			<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>">
		</div>
	</div>




</form>


</div>

<? include("../inc/end.php");?>