<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_catalog");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<script>

function model_add(model_id,type,model_name)
{	
	if(type==0)
	{
		type_name="<?=word_lang("Model release")?>";
	}
	else
	{
		type_name="<?=word_lang("Property release")?>";
	}
	
	$('#models_list').append("<div class='clear' id='div_"+model_id+"' style='margin-bottom:5px'><div class='input-append' style='float:left;margin-right:4px'><a href='../models/content.php?id="+model_id+"' class='btn btn-small btn-default'><b>"+type_name+":</b> "+model_name+"</a></div><button class='btn btn-danger btn-small' type='button' onClick=\"model_delete('"+model_id+"');\"><?=word_lang("delete")?></button><input type='hidden' name='model"+model_id+"' value='"+type+"'></div>");
	
	document.getElementById('model0_'+model_id.toString()).style.display='none';
	document.getElementById('model1_'+model_id.toString()).style.display='none';
}

function model_delete(model_id)
{
	$('#div_'+model_id.toString()).remove()
	document.getElementById('model0_'+model_id.toString()).style.display='block';
	document.getElementById('model1_'+model_id.toString()).style.display='block';
}



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


<?
//If the item is new
$id=0;
if(isset($_GET["id"])){$id=(int)$_GET["id"];}

//Get type
$type="photo";
if(isset($_GET["type"]))
{
	$type=result($_GET["type"]);
}


if(isset($_GET["id"]))
{
	$sql="select module_table from structure where id=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		if($rs->row["module_table"]==30){$type="photo";}
		if($rs->row["module_table"]==31){$type="video";}
		if($rs->row["module_table"]==52){$type="audio";}
		if($rs->row["module_table"]==53){$type="vector";}
	}
}


	//Fields list
	$admin_fields=array("category","category2","category3","title","description","keywords","author","file","data","published","featured","viewed","downloaded","free","exclusive","contacts","content_type","model","adult","vote_like","vote_dislike");
	
	$admin_names=array(word_lang("category"),word_lang("category")." 2",word_lang("category")." 3",word_lang("title"),word_lang("description"),word_lang("keywords"),word_lang("author"),word_lang("file for sale"),word_lang("date"),word_lang("published"),word_lang("featured"),word_lang("viewed"),word_lang("downloads"),word_lang("free"),word_lang("exclusive price"),word_lang("contact us to get the price"),word_lang("content type"),word_lang("models"),word_lang("adult content"),word_lang("like"),word_lang("dislike"));

	//Fields meanings
	$admin_meanings=array("5","5","5","","","","","",mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),"1","0","0","0","0","0","0",$global_settings["content_type"],"",0,0,0);

	//Fields types
	$admin_types=array("category","category","category","text_translation","textarea_translation","textarea_translation","author","file","data","checkbox","checkbox","int","int","checkbox","checkbox","checkbox","content_type","model","checkbox","int","int");

if($type=="photo")
{
	$admin_fields[]="color";
	$admin_names[]=word_lang("color");
	$admin_meanings[]="";
	$admin_types[]="color";
	
	$admin_fields[]="editorial";
	$admin_names[]=word_lang("editorial");
	$admin_meanings[]="";
	$admin_types[]="checkbox";
}



if($type=="video")
{
	$admin_fields[]="duration";
	$admin_names[]=word_lang("duration");
	$admin_meanings[]=0;
	$admin_types[]="duration";
	
	$admin_fields[]="format";
	$admin_names[]=word_lang("clip format");
	$admin_meanings[]="";
	$admin_types[]="format";
	
	$admin_fields[]="ratio";
	$admin_names[]=word_lang("Aspect Ratio");
	$admin_meanings[]="";
	$admin_types[]="ratio";
	
	$admin_fields[]="rendering";
	$admin_names[]=word_lang("Field Rendering");
	$admin_meanings[]="";
	$admin_types[]="rendering";
	
	$admin_fields[]="frames";
	$admin_names[]=word_lang("Frames per Second");
	$admin_meanings[]="";
	$admin_types[]="frames";
	
	$admin_fields[]="holder";
	$admin_names[]=word_lang("Copyright Holder");
	$admin_meanings[]="";
	$admin_types[]="text";
	
	$admin_fields[]="usa";
	$admin_names[]=word_lang("U.S. 2257 Information");
	$admin_meanings[]="";
	$admin_types[]="text";
}


if($type=="audio")
{
	$admin_fields[]="duration";
	$admin_names[]=word_lang("duration");
	$admin_meanings[]=0;
	$admin_types[]="duration";
	
	$admin_fields[]="source";
	$admin_names[]=word_lang("Track Source");
	$admin_meanings[]="";
	$admin_types[]="source";
	
	$admin_fields[]="format";
	$admin_names[]=word_lang("Track Format");
	$admin_meanings[]="";
	$admin_types[]="track_format";
	
	
	$admin_fields[]="holder";
	$admin_names[]=word_lang("Copyright Holder");
	$admin_meanings[]="";
	$admin_types[]="text";
	
}

if($type=="vector")
{

	if($global_settings["flash"])
	{
	$admin_fields[]="flash_version";
	$admin_names[]="Flash ".word_lang("version");
	$admin_meanings[]="";
	$admin_types[]="text";
	
	$admin_fields[]="script_version";
	$admin_names[]="Script ".word_lang("version");
	$admin_meanings[]="";
	$admin_types[]="text";
	
	$admin_fields[]="flash_width";
	$admin_names[]="Flash ".word_lang("width");
	$admin_meanings[]="0";
	$admin_types[]="int";
	

	$admin_fields[]="flash_height";
	$admin_names[]="Flash ".word_lang("height");
	$admin_meanings[]="0";
	$admin_types[]="int";
	}
	
}

if($global_settings["google_coordinates"])
{
	$admin_fields[]="google_x";
	$admin_names[]=word_lang("Google coordinate X");
	$admin_meanings[]=0;
	$admin_types[]="float";
	
	$admin_fields[]="google_y";
	$admin_names[]=word_lang("Google coordinate Y");
	$admin_meanings[]=0;
	$admin_types[]="float";	
}


//If it isn't a new item
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
	if($type=="photo")
	{
		$sql = "select category2,category3,title,description,keywords,author,data,published,featured,viewed,downloaded,free,content_type,model,google_x,google_y,color,editorial,adult,exclusive,contacts,vote_like,vote_dislike from photos where id_parent=".(int)$_GET["id"];
	}
	if($type=="video")
	{
		$sql = "select category2,category3,title,description,keywords,author,data,published,featured,viewed,downloaded,free,content_type,model,duration,format,ratio,rendering,frames,holder,usa,google_x,google_y,adult,exclusive,contacts,vote_like,vote_dislike from videos where id_parent=".(int)$_GET["id"];
	}
	if($type=="audio")
	{
		$sql = "select category2,category3,title,description,keywords,author,data,published,featured,viewed,downloaded,free,content_type,model,duration,source,format,holder,google_x,google_y,adult,exclusive,contacts,vote_like,vote_dislike from audio where id_parent=".(int)$_GET["id"];
	}
	if($type=="vector")
	{
		$sql = "select category2,category3,title,description,keywords,author,data,published,featured,viewed,downloaded,free,content_type,model,flash_version,script_version,flash_width,flash_height,google_x,google_y,adult,exclusive,contacts,vote_like,vote_dislike from vector where id_parent=".(int)$_GET["id"];
	}
	$rs->open($sql);
	if(!$rs->eof)
	{
		for($i=1;$i<count($admin_fields);$i++)
		{
			if($admin_fields[$i]!="file")
			{
				$admin_meanings[$i]=$rs->row[$admin_fields[$i]];
			}
		}
	}
}


//Category's list
$itg="";
$nlimit=0;
buildmenu2(5,$admin_meanings[0],2,$id);
$admin_meanings[0]=$itg;

$itg="";
$nlimit=0;
buildmenu2(5,$admin_meanings[1],2,$id);
$admin_meanings[1]=$itg;

$itg="";
$nlimit=0;
buildmenu2(5,$admin_meanings[2],2,$id);
$admin_meanings[2]=$itg;
?>

<div class="back"><a href="index.php" class="btn btn-primary btn-sm btn-mini"><i class="icon-arrow-left fa fa-arrow-left"></i> <?=word_lang("back")?></a></div>

<h1><?
if($id==0)
{
	echo(word_lang("add")." ");
}
else
{
	echo(word_lang("edit")." ");
}

	if($type=="photo")
	{
	echo(word_lang("photo"));
	}
	if($type=="video")
	{
	echo(word_lang("video"));
	}
	if($type=="audio")
	{
	echo(word_lang("audio"));
	}
	if($type=="vector")
	{
	echo(word_lang("vector"));
	}


//upload limits:
$lvideo="UNLIMITED ";
$lpreviewvideo="UNLIMITED ";
$laudio="UNLIMITED ";
$lpreviewaudio="UNLIMITED ";
$lvector="UNLIMITED ";

//File form for photos
$file_form=true;


//Remove temp files
$tmp_folder="admin_".(int)$_SESSION["user_id"];
remove_files_from_folder($tmp_folder);


?>:</h1>

<?=build_admin_form("add.php?id=".$id,$type)?>









<? include("../inc/end.php");?>