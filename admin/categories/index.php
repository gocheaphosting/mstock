<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_categories");
?>
<? include("../inc/begin.php");?>
<? include("../function/upload.php");?>




<a class="btn btn-success toright" href="content.php"><i class="icon-folder-open icon-white fa fa-folder-o"></i>&nbsp; <?=word_lang("Add category")?></a>

<h1><?=word_lang("categories")?>:</h1>

<script languages="javascript">

img_plus=new Image();
img_plus.src="../images/design/plus.gif";

img_minus=new Image();
img_minus.src="../images/design/minus.gif";

mcategories=new Array();
mparent=new Array();
mopen=new Array();
<?
$n=0;
$sql="select id_parent from category";
$rs->open($sql);
while(!$rs->eof)
{
?>
mcategories[<?=$n?>]=<?=$rs->row["id_parent"]?>;

<?
if(isset($_COOKIE["sub_".$rs->row["id_parent"]]) and (int)$_COOKIE["sub_".$rs->row["id_parent"]]==0)
{
?>
mopen[<?=$rs->row["id_parent"]?>]=0;
<?
}
else
{
?>
mopen[<?=$rs->row["id_parent"]?>]=1;
<?
}
?>
<?
$sql="select id_parent from structure where id=".$rs->row["id_parent"];
$dr->open($sql);
if(!$dr->eof)
{
?>
mparent[<?=$n?>]=<?=$dr->row["id_parent"]?>;
<?
}
$n++;
$rs->movenext();
}
?>

function category_select_all()
{
	if(document.getElementById("selectall").checked)
	{
		sel=true;
	}
	else
	{
		sel=false;
	}
	
	for(i=0;i<mcategories.length;i++)
	{
		document.getElementById("sel"+mcategories[i].toString()).checked=sel;
	}
}


function subopen(value)
{

	for(i=0;i<mparent.length;i++)
	{
		if(value==mparent[i])
		{
			if(mopen[value]==1)
			{
			document.getElementById("row"+mcategories[i].toString()).style.display='none';
			}
			else
			{
			document.getElementById("row"+mcategories[i].toString()).style.display='table-row';
			}
		}
	}
	
	if(mopen[value]==1)
	{
	document.getElementById("plus"+value.toString()).src=img_plus.src;
	mopen[value]=0;
	document.cookie = "sub_" + value + "=" + escape (0) + ";path=/";
	}
	else
	{
	document.getElementById("plus"+value.toString()).src=img_minus.src;
	mopen[value]=1;
	document.cookie = "sub_" + value + "=" + escape (1) + ";path=/";
	}
}



function bulk_action(value)
{
document.getElementById("formaction").value=value;

document.getElementById("adminform").submit();
}


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




<form method="post" action="edit.php" id="adminform" name="adminform">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr"><table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th><input type="checkbox"   name="selector"  onClick="publications_select_all(document.adminform);"></th>
<th><?=word_lang("priority")?></th>
<th><?=word_lang("title")?></th>
<th></th>
<th></th>
</tr>

<?
$itg="";
$nlimit=0;
buildmenu3(5,1);
echo($itg);
?>

</table>
</div></div></div></div></div></div></div></div>



<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">

<div id="actions">
	<input type="hidden" name="formaction" id="formaction" value="priority">
	<input type="button" class="btn btn-warning" class="isubmit" value="<?=word_lang("save")?>" onClick="bulk_action('priority');">&nbsp;&nbsp;<?=word_lang("or")?>&nbsp;&nbsp;<div class="btn-group dropup">
    <a class="btn btn-primary" href="#"><?=word_lang("select action")?></a>
    <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
    <ul class="dropdown-menu">
    		<li><a href="javascript:bulk_action('delete_category');"><i class="icon-trash"></i> <?=word_lang("delete selected")?></a></li>
			
			<?if($global_settings["allow_photo"]){?>
				<li><a href="javascript:bulk_action('thumbs');"><i class="icon-refresh"></i> <?=word_lang("regenerate thumbs")?></a></li>
			<?}?>
			
			<li><a href="javascript:bulk_action('bulk_change');"><i class="icon-tasks"></i> <?=word_lang("Bulk change titles, keywords, description")?></a></li>
    		
    		<li><a href="javascript:bulk_action('bulk_keywords');"><i class="icon-tags"></i> <?=word_lang("Bulk add/remove keywords")?></a></li>
	
			
			<li><a href="javascript:bulk_action('content');"><i class="icon-th"></i> <?=word_lang("change content type")?></a></li>
			
			<li><a href="javascript:bulk_action('free');"><i class="icon-download-alt"></i> <?=word_lang("change files to free/paid")?></a></li>
			
			<li><a href="javascript:bulk_action('featured');"><i class="icon-thumbs-up"></i> <?=word_lang("change files to featured")?></a></li>
			
			<?if($global_settings["allow_photo"]){?>
				<li><a href="javascript:bulk_action('editorial');"><i class="icon-picture"></i> <?=word_lang("change photos to editorial")?></a></li>
			<?}?>
			
			<?if($global_settings["adult_content"]){?>
				<li><a href="javascript:bulk_action('adult');"><i class="icon-user"></i> <?=word_lang("change files to adult")?></a></li>
			<?}?>
			
			<?if($global_settings["exclusive_price"]){?>
				<li><a href="javascript:bulk_action('exclusive');"><i class="icon-gift"></i> <?=word_lang("change files to exclusive")?></a></li>
			<?}?>
			
			<?if($global_settings["contacts_price"]){?>
				<li><a href="javascript:bulk_action('contacts');"><i class="icon-envelope"></i> <?=word_lang("change files to 'contact us to get the price'")?></a></li>
			<?}?>
			
			<li><a href="javascript:bulk_action('approve');"><i class="icon-ok"></i> <?=word_lang("approve")?>/<?=word_lang("decline")?></a></li>
			
			<?if($global_settings["rights_managed"]){?>
				<li><a href="javascript:bulk_action('rights_managed_categories');"><i class="icon-list-alt"></i> <?=word_lang("change rights-managed price")?></a></li>
			<?}?>
    </ul>
    </div>
	

	

</div>

		</div>
	</div>
</form>

<? include("../inc/end.php");?>