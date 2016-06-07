<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");
?>
<? include("../inc/begin.php");?>


<?
$user_fields=array();
$user_fields["title"]="";
$user_fields["description"]="";




$ranges_list=array();
$ranges_from=array();
$ranges_to=array();
$ranges_price=array();

if(isset($_GET["id"]))
{
	$sql="select * from rights_managed_groups where id=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$user_fields["title"]=$rs->row["title"];
		$user_fields["description"]=$rs->row["description"];

			$sql="select * from rights_managed_options where id_parent=".(int)$_GET["id"]." order by id";
			$ds->open($sql);
			if(!$ds->eof)
			{
				while(!$ds->eof)
				{
					$ranges_list[]=count($ranges_list);
					$ranges_from[]=$ds->row["title"];
					$ranges_to[]=$ds->row["adjust"];
					$ranges_price[]=$ds->row["price"];
					$ds->movenext();
				}
			}
			else
			{
				$ranges_list=array(0,1);
				$ranges_from=array('New option 1','New option 2');
				$ranges_to=array("+","+");
				$ranges_price=array(1,1);
			}
	}
}
else
{
	$ranges_list=array(0,1);
	$ranges_from=array('New option 1','New option 2');
	$ranges_to=array("+","+");
	$ranges_price=array(1,1);
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
		$options="";
		if($ranges_to[$i]=="+")
		{
			$options="<option value='+' selected>+</option><option value='-'>-</option><option value='x'>x</option>";
		}
		elseif($ranges_to[$i]=="-")
		{
			$options="<option value='+'>+</option><option value='-' selected>-</option><option value='x'>x</option>";
		}
		else
		{
			$options="<option value='+'>+</option><option value='-'>-</option><option value='x' selected>x</option>";
		}
		
		$res.="<tr id='tr".$method.$ranges_list[$i]."'>
					<td><input name='".$method.$ranges_list[$i]."_title' id='".$method.$ranges_list[$i]."_title' type='text' value='".$ranges_from[$i]."' style='width:350px;'></td>
					<td><select name='".$method.$ranges_list[$i]."_adjust' id='".$method.$ranges_list[$i]."_adjust' style='width:70px;'>".$options."</select></td>
					<td><input name='".$method."_price".$ranges_list[$i]."' id='".$method."_price".$ranges_list[$i]."' type='text' value='".$ranges_price[$i]."' style='width:70px;'></td>
					<td><input type='button' class='btn' value='".word_lang("delete")."' style='width:70px' onClick=\"remove_range('".$method.$ranges_list[$i]."')\"></td>
				</tr>";
	}
	return $res;
}
?>


<div class="back"><a href="index.php?d=2" class="btn btn-primary btn-sm btn-mini"><b><i class="fa fa-arrow-left"></i> <?=word_lang("back")?></b></a></div>

<script language="javascript">
function publications_select_all(sel_form)
{
    if(sel_form.allrights.checked)
   	{
        $("input:checkbox", sel_form).attr("checked",true);
    }
    else
    {
        $("input:checkbox", sel_form).attr("checked",false);
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
	$("#ranges_"+value+" > tbody").append("<tr id='tr"+value+item_id+"'><td><input type='text' name='"+value+item_id+"_title'  id='"+value+item_id+"_title' value='New option' style='width:350px;'  class='form-control'></td><td><select name='"+value+item_id+"_adjust' id='"+value+item_id+"_adjust' style='width:70px;'  class='form-control'><option value='+' selected>+</option><option value='-'>-</option><option value='x'>x</option></select></td><td><input type='text' name='"+value+"_price"+item_id+"' id='"+value+"_price"+item_id+"' value='1' style='width:70px;' class='form-control'></td><td><input type='button' class='btn' value='<?=word_lang("delete")?>' style='width:70px' onClick=\"remove_range('"+value+item_id+"')\"></td></tr>");
}

function remove_range(value)
{
	$('#tr'+value).remove();
}


</script>


<h1><?=word_lang("groups")?> &mdash; <?
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






<form method="post" action="groups_add.php<?if(isset($_GET["id"])){echo("?id=".$_GET["id"]);}?>"  Enctype="multipart/form-data">

<div class="subheader"><?=word_lang("common information")?></div>
<div class="subheader_text">




	<div class='admin_field'>
	<span><?=word_lang("title")?>:</span>
	<input type="text" name="title" value="<?=$user_fields["title"]?>" style="width:350px">
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("description")?>:</span>
	<textarea name="description" style="width:350px;height:70px"><?=$user_fields["description"]?></textarea>
	</div>


</div>
	

<div class="subheader"><?=word_lang("options")?></div>
<div class="subheader_text">
	<div class='admin_field'>
		<table border="0" cellpadding="0" cellspacing="0" class="ranges" id="ranges_options">
			<tbody>
				<tr>
					<td style='width:150px;'><b><?=word_lang("title")?>:</b></td>
					<td style='width:50px;'><b><?=word_lang("price")?>:</b></td>
					<td style='width:70px;'></td>
					<td></td>
				</tr>
				<?=build_rangers("options")?>
			</tbody>
		</table>
		<input type="button" value="<?=word_lang("add")?>" class="btn" onClick="add_range('options')">
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