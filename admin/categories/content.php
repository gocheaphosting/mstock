<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_categories");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<?
//If the category is new
$id=0;
if(isset($_GET["id"])){$id=(int)$_GET["id"];}


//Fields list
$admin_fields=array("category","title","priority","password","description","keywords","photo","upload","published","featured");

$admin_names=array(word_lang("category"),word_lang("title"),word_lang("priority"),word_lang("password"),word_lang("description"),word_lang("keywords"),word_lang("preview"),word_lang("upload"),word_lang("published"),word_lang("featured"));

//Fields meanings
$admin_meanings=array("5","","0","","","","","1","1","0");

//Fields types
if($global_settings["multilingual_categories"])
{
	$admin_types=array("category","text_translation","int","text","textarea_translation","textarea_translation","file","checkbox","checkbox","checkbox");
}
else
{
	$admin_types=array("category","text","int","text","textarea","textarea","file","checkbox","checkbox","checkbox");
}



//If it isn't a new category
if($id!=0)
{
	//Get parent category
	$sql="select id_parent from structure where id=".$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$admin_meanings[0]=$rs->row["id_parent"];
	}

	//Get field's meanings
	$sql = "select title,priority,password,description,keywords,photo,upload,published,featured from category where id_parent=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		for($i=1;$i<count($admin_fields);$i++)
		{
			$admin_meanings[$i]=$rs->row[$admin_fields[$i]];
		}
	}
}


//Category's list
$itg="";
$nlimit=0;
buildmenu2(5,$admin_meanings[0],2,$id);
$admin_meanings[0]=$itg;
?>

<div class="back"><a href="index.php" class="btn btn-mini"><i class="icon-arrow-left"></i> <?=word_lang("back")?></a></div>

<script>

function translation_add(param_field,param_lang,param_lang2,param_lang3,param_type)
{
	if(param_type=="text_translation")
	{
		input_code="<input type='text' name='translate_"+param_field+"_"+param_lang3+"' style='width:400px' class='ibox form-control' value=''>";
	}
	else
	{
		input_code="<textarea name='translate_"+param_field+"_"+param_lang3+"' style='width:400px;height:120px' class='ibox form-control'></textarea>";
	}
	
	$('#trans_'+param_field).append("<div class='clear' id='div_"+param_field+"_"+param_lang3+"' style='padding-top:20px'><div class='input-append' style='float:left;margin-right:4px'>"+input_code+"<span class='add-on' style='width:120px;text-align:left'><img src='<?=site_root?>/admin/images/languages/"+param_lang2+".gif'>&nbsp;<font class='langtext'>"+param_lang+"</font></span></div><button class='btn btn-danger' type='button' onClick=\"translation_delete('"+param_field+"','"+param_lang3+"');\"><?=word_lang("delete")?></button></div>");
	
	document.getElementById('li_'+param_field+'_'+param_lang3).style.display='none';
}

function translation_delete(param_field,param_lang3)
{
	$('#div_'+param_field+'_'+param_lang3).remove()
	document.getElementById('li_'+param_field+'_'+param_lang3).style.display='block';
}

</script>


<h1><?
if($id==0)
{
	echo(word_lang("add category"));
}
else
{
	echo(word_lang("edit"));
}
?>:</h1>

<?=build_admin_form("add.php?id=".$id,"category")?>

<? include("../inc/end.php");?>