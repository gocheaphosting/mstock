<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_catalog");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>

<?if($global_settings["allow_vector"]){?>
<a class="btn btn-success toright" href="content.php?type=vector"><i class="icon-picture icon-white fa fa-leaf"></i>&nbsp; <?=word_lang("upload vector")?></a>
<?}?>
<?if($global_settings["allow_audio"]){?>
<a class="btn btn-success toright" href="content.php?type=audio"><i class="icon-music icon-white fa fa-music"></i>&nbsp; <?=word_lang("upload audio")?></a>
<?}?>
<?if($global_settings["allow_video"]){?>
<a class="btn btn-success toright" href="content.php?type=video"><i class="icon-film icon-white fa fa-film"></i>&nbsp; <?=word_lang("upload video")?></a>
<?}?>
<?if($global_settings["allow_photo"]){?>
<a class="btn btn-success toright" href="content.php?type=photo"><i class="icon-camera icon-white fa fa-photo"></i>&nbsp; <?=word_lang("upload photo")?></a>
<?}?>

<h1><?=word_lang("catalog")?>:</h1>

<script language="javascript">

function bulk_action(value)
{
document.getElementById("formaction").value=value;

document.getElementById("adminform").submit();
}


</script>


<?
//Get Search
$search="";
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if(isset($_POST["search"])){$search=result($_POST["search"]);}

//Get Search type
$search_type="";
if(isset($_GET["search_type"])){$search_type=result($_GET["search_type"]);}
if(isset($_POST["search_type"])){$search_type=result($_POST["search_type"]);}

//Get category ID
$category_id=0;
if(isset($_GET["category_id"])){$category_id=(int)$_GET["category_id"];}
if(isset($_POST["category_id"])){$category_id=(int)$_POST["category_id"];}

//Get type
$type="all";
if(isset($_GET["type"])){$type=result($_GET["type"]);}
if(isset($_POST["type"])){$type=result($_POST["type"]);}

//Get pub_type
$pub_type="all";
if(isset($_GET["pub_type"])){$pub_type=result($_GET["pub_type"]);}
if(isset($_POST["pub_type"])){$pub_type=result($_POST["pub_type"]);}

//Get pub_ctype
$pub_ctype="all";
if(isset($_GET["pub_ctype"])){$pub_ctype=result($_GET["pub_ctype"]);}
if(isset($_POST["pub_ctype"])){$pub_ctype=result($_POST["pub_ctype"]);}

//Get filter
$filter="all";
if(isset($_GET["filter"])){$filter=result($_GET["filter"]);}
if(isset($_POST["filter"])){$filter=result($_POST["filter"]);}


//Items
$items=30;
if(isset($_GET["items"])){$items=(int)$_GET["items"];}
if(isset($_POST["items"])){$items=(int)$_POST["items"];}


//Search variable
$var_search="search=".$search."&search_type=".$search_type."&category_id=".$category_id."&type=".$type."&items=".$items."&pub_type=".$pub_type."&pub_ctype=".$pub_ctype."&filter=".$filter;




//Sort by title
$atitle=0;
if(isset($_GET["atitle"])){$atitle=(int)$_GET["atitle"];}

//Sort by date
$adate=0;
if(isset($_GET["adate"])){$adate=(int)$_GET["adate"];}

//Sort by downloads
$adownloads=0;
if(isset($_GET["adownloads"])){$adownloads=(int)$_GET["adownloads"];}

//Sort by viewed
$aviewed=0;
if(isset($_GET["aviewed"])){$aviewed=(int)$_GET["aviewed"];}

//Sort by ID
$aid=0;
if(isset($_GET["aid"])){$aid=(int)$_GET["aid"];}

//Sort by default
if($atitle==0 and $adate==0 and $adownloads==0 and $aviewed==0 and $aid==0)
{
$adate=2;
}



//Add sort variable
$com="";

if($atitle!=0)
{
	$var_sort="&atitle=".$atitle;
	if($atitle==1){$com=" order by atitle ";}
	if($atitle==2){$com=" order by atitle desc ";}
}

if($adate!=0)
{
	$var_sort="&adate=".$adate;
	if($adate==1){$com=" order by adata ";}
	if($adate==2){$com=" order by adata desc ";}
}

if($aviewed!=0)
{
	$var_sort="&aviewed=".$aviewed;
	if($aviewed==1){$com=" order by aviewed ";}
	if($aviewed==2){$com=" order by aviewed desc ";}
}

if($adownloads!=0)
{
	$var_sort="&adownloads=".$adownloads;
	if($adownloads==1){$com=" order by adownloaded ";}
	if($adownloads==2){$com=" order by adownloaded desc ";}
}

if($aid!=0)
{
	$var_sort="&aid=".$aid;
	if($aid==1){$com=" order by aid ";}
	if($aid==2){$com=" order by aid desc ";}
}




//Types
$mass_types=array();
if($global_settings["allow_photo"]){$mass_types[]="photo";}
if($global_settings["allow_video"]){$mass_types[]="video";}
if($global_settings["allow_audio"]){$mass_types[]="audio";}
if($global_settings["allow_vector"]){$mass_types[]="vector";}




//Items on the page
$items_mass=array(10,20,30,50,75,100);




//Search parameter
$com2="";

if($search!="")
{
	if($search_type=="title")
	{
		$com2.=" and (b.title like '%".$search."%' or b.description like '%".$search."%' or b.keywords like '%".$search."%') ";
	}
	if($search_type=="id")
	{
		$com2.=" and b.id_parent=".(int)$search." ";
	}
	if($search_type=="author")
	{
		$com2.=" and b.author = '".$search."' ";
	}
	if($search_type=="lightbox")
	{
		$lightbox_id=0;
		$sql="select id from lightboxes where title='".$search."'";
		$dr->open($sql);
		if(!$dr->eof)
		{
			$lightbox_id=$dr->row["id"];
		}
		
		$sql="select item from lightboxes_files where id_parent=".$lightbox_id;
		$dr->open($sql);
		while(!$dr->eof)
		{
			if($com2!=""){$com2.=" or ";}
			$com2.=" b.id_parent=".$dr->row["item"]." ";
			$dr->movenext();
		}
		$com2=" and (".$com2.") ";
	}
}

if($category_id!=0)
{
	$com2.=" and (a.id_parent = ".$category_id." or b.category2=".$category_id." or b.category3=".$category_id.") ";
}

if($pub_type=="featured")
{
	$com2.=" and (b.featured=1) ";
}

$sql_editorial="";
if($pub_type=="editorial")
{
	$sql_editorial.=" and (b.editorial=1) ";
}
if($pub_type=="free")
{
	$com2.=" and (b.free=1) ";
}
if($pub_type=="adult")
{
	$com2.=" and (b.adult=1) ";
}
if($pub_type=="exclusive")
{
	$com2.=" and (b.exclusive=1) ";
}
if($pub_type=="contacts")
{
	$com2.=" and (b.contacts=1) ";
}
if($pub_type=="exclusive_sold")
{
	$com2.=" and (b.exclusive=1 and b.published=-1) ";
}
if($pub_type=="approved")
{
	$com2.=" and (b.published=1) ";
}
if($pub_type=="pending")
{
	$com2.=" and (b.published=0) ";
}
if($pub_type=="declined")
{
	$com2.=" and (b.published=-1 and b.exclusive<>1) ";
}

if($pub_ctype!="all")
{
	$com2.=" and (b.content_type='".$pub_ctype."') ";
}


if($filter=="yes")
{
	$sql="select words from content_filter";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$words=explode(",",$ds->row["words"]);
		$words_sql="";
		for($i=0;$i<count($words);$i++)
		{
			if($words_sql!="")
			{
				$words_sql.=" or ";
			}
			if(trim($words[$i])!="")
			{
				$words_sql.=" b.title like '%".trim($words[$i])."%' or b.description like '%".trim($words[$i])."%' or b.keywords  like '%".trim($words[$i])."%' ";
			}
		}
		if($words_sql!="")
		{
			$com2.=" and (".$words_sql.") ";
		}
	}
}



//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql_mass=array();

$sql_mass["photo"]="select a.id as aid,a.id_parent as idp,a.module_table,b.id_parent,b.title as atitle,b.data as adata,b.published,b.description,b.viewed as aviewed,b.keywords,b.rating as arating,b.downloaded as adownloaded,b.free,b.featured,b.author,b.server1,b.category2,b.category3,b.url,b.content_type,exclusive from structure a,photos b where a.id=b.id_parent ".$com2.$sql_editorial;

if($sql_editorial=="")
{
	$sql_mass["video"]="select a.id as aid,a.id_parent as idp,a.module_table,b.id_parent,b.title as atitle,b.data as adata,b.published,b.description,b.viewed as aviewed,b.keywords,b.rating as arating,b.downloaded as adownloaded,b.free,b.featured,b.author,b.server1,b.category2,b.category3,b.url,b.content_type,exclusive from structure a,videos b where a.id=b.id_parent ".$com2;

	$sql_mass["audio"]="select a.id as aid,a.id_parent as idp,a.module_table,b.id_parent,b.title as atitle,b.data as adata,b.published,b.description,b.viewed as aviewed,b.keywords,b.rating as arating,b.downloaded as adownloaded,b.free,b.featured,b.author,b.server1,b.category2,b.category3,b.url,b.content_type,exclusive from structure a,audio b where a.id=b.id_parent ".$com2;

	$sql_mass["vector"]="select a.id as aid,a.id_parent as idp,a.module_table,b.id_parent,b.title as atitle,b.data as adata,b.published,b.description,b.viewed as aviewed,b.keywords,b.rating as arating,b.downloaded as adownloaded,b.free,b.featured,b.author,b.server1,b.category2,b.category3,b.url,b.content_type,exclusive from structure a,vector b where a.id=b.id_parent ".$com2;
}

if($type=="all")
{
	$xx=0;
	$sql="";
	foreach ($mass_types as $key => $value)
	{
		if(isset($sql_mass[$value]))
		{
			if($xx!=0){$sql.=" union ";}
			$sql.="(".$sql_mass[$value].")";
			$xx++;
		}
	}
}
else
{
	$sql=$sql_mass[$type];
}



$sql.=$com;
if(!$global_settings["no_calculation"])
{
	$rs->open($sql);
	$record_count=$rs->rc;
}
else
{
	$record_count=$global_settings["no_calculation_total"];
}

$flag_show_end=true;
if($global_settings["no_calculation"])
{
	$flag_show_end=false;
}



//limit
$lm=" limit ".($kolvo*($str-1)).",".$kolvo;




$sql.=$lm;

//echo($sql);
$rs->open($sql);

?>
<div id="catalog_menu">


<form method="get" action="index.php" style="margin:0px">
<div class="toleft">
<span><?=word_lang("search")?>:</span>
<input type="text" name="search" style="width:200px;display:inline" class="ft" value="<?=$search?>" onClick="this.value=''">
<select name="search_type" style="width:120px;display:inline" class="ft">
<option value="id" <?if($search_type=="id"){echo("selected");}?>>ID</option>
<option value="title" <?if($search_type=="title"){echo("selected");}?>><?=word_lang("title")?></option>
<option value="author" <?if($search_type=="author"){echo("selected");}?>><?=word_lang("author")?></option>
<option value="lightbox" <?if($search_type=="lightbox"){echo("selected");}?>><?=word_lang("lightboxes")?></option>
</select>
</div>




<div class="toleft">
<span><?=word_lang("category")?>:</span>
<select name="category_id" style="width:240px" class="ft">
<option value="0"><?=word_lang("all")?></option>
<?
$itg="";




	$smart_buildmenu2_id="buildmenu|2|".$category_id."|".$lng;
	if (!$smarty->isCached('buildmenu2.tpl',$smart_buildmenu2_id))
	{
		$nlimit=0;
		buildmenu2(5,$category_id,2,0);
	}
	$smarty->cache_lifetime = -1;
	$smarty->assign('buildmenu2', $itg);
	$itg=$smarty->fetch('buildmenu2.tpl',$smart_buildmenu2_id);

echo($itg);
?>

</select>
</div>

<div class="toleft">
<span><?=word_lang("content")?>:</span>
<select name="type" style="width:100px" class="ft">
<option value="all"><?=word_lang("all")?></option>
<?
for($i=0;$i<count($mass_types);$i++)
{
$sel="";
if($type==$mass_types[$i]){$sel="selected";}
?>
<option value="<?=$mass_types[$i]?>" <?=$sel?>><?=word_lang($mass_types[$i])?></option>
<?
}
?>

</select>
</div>




<div class="toleft">
<span><?=word_lang("type")?>:</span>
<select name="pub_type" style="width:170px" class="ft">
<option value="all"><?=word_lang("all")?></option>
<option value="free" <?if($pub_type=="free"){echo("selected");}?>><?=word_lang("free")?></option>
<option value="featured" <?if($pub_type=="featured"){echo("selected");}?>><?=word_lang("featured")?></option>
<option value="editorial" <?if($pub_type=="editorial"){echo("selected");}?>><?=word_lang("editorial")?></option>
<?
if($global_settings["adult_content"])
{
	?>
	<option value="adult" <?if($pub_type=="adult"){echo("selected");}?>><?=word_lang("adult content")?></option>
	<?
}
?>
<?
if($global_settings["exclusive_price"])
{
	?>
	<option value="exclusive" <?if($pub_type=="exclusive"){echo("selected");}?>><?=word_lang("exclusive price")?></option>
	<option value="exclusive_sold" <?if($pub_type=="exclusive_sold"){echo("selected");}?>><?=word_lang("exclusive price")?> - <?=word_lang("sold")?></option>
	<?
}
?>
<?
if($global_settings["contacts_price"])
{
	?>
	<option value="contacts" <?if($pub_type=="contacts"){echo("selected");}?>><?=word_lang("contact us to get the price")?></option>
	<?
}
?>
<option value="approved" <?if($pub_type=="approved"){echo("selected");}?>><?=word_lang("approved")?></option>
<option value="pending" <?if($pub_type=="pending"){echo("selected");}?>><?=word_lang("pending")?></option>
<option value="declined" <?if($pub_type=="declined"){echo("selected");}?>><?=word_lang("declined")?></option>
</select>
</div>


<div class="toleft">
<span><?=word_lang("content type")?>:</span>
<select name="pub_ctype" style="width:120px" class="ft">
<option value="all"><?=word_lang("all")?></option>
<?
$sql="select name from content_type order by priority";
$ds->open($sql);
while(!$ds->eof)
{
	$sel="";
	if($pub_ctype==$ds->row["name"])
	{
		$sel="selected";
	}
	?>
	<option value="<?=$ds->row["name"]?>" <?=$sel?>><?=$ds->row["name"]?></option>
	<?
	$ds->movenext();
}
?>
</select>
</div>



<div class="toleft">
<span><a href="filter.php"><?=word_lang("filter")?></a>:</span>
<select name="filter" style="width:70px" class="ft">
<option value="no" <?if($filter=="no"){echo("selected");}?>><?=word_lang("no")?></option>
<option value="yes" <?if($filter=="yes"){echo("selected");}?>><?=word_lang("yes")?></option>
</select>
</div>

<div class="toleft">
<span><?=word_lang("item")?>:</span>
<select name="items" style="width:70px" class="ft">
<?
for($i=0;$i<count($items_mass);$i++)
{
$sel="";
if($items_mass[$i]==$items){$sel="selected";}
?>
<option value="<?=$items_mass[$i]?>" <?=$sel?>><?=$items_mass[$i]?></option>
<?
}
?>

</select>
</div>

<div class="toleft">
<span>&nbsp;</span>
<input type="submit" class="btn btn-danger" value="<?=word_lang("search")?>">
</div>

<div class="toleft_clear"></div>
</form>


</div>



<?





if(!$rs->eof)
{
?>


<div style="padding-bottom:15px;float:right;"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort,false,$flag_show_end));?></div>
<div style="clear:both"></div>

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



<form method="post" action="../categories/edit.php" style="margin:0px"  id="adminform" name="adminform">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"></th>
<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&aid=<?if($aid==2){echo(1);}else{echo(2);}?>">ID</a> <?if($aid==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($aid==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>
<th><?=word_lang("image")?></th>
<th width="50%" class="hidden-phone hidden-tablet">


<?=word_lang("title")?>

</th>

<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>
<th class="hidden-phone hidden-tablet">

<a href="index.php?<?=$var_search?>&aviewed=<?if($aviewed==2){echo(1);}else{echo(2);}?>"><?=word_lang("viewed")?></a> <?if($aviewed==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($aviewed==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>
<th class="hidden-phone hidden-tablet">

<a href="index.php?<?=$var_search?>&adownloads=<?if($adownloads==2){echo(1);}else{echo(2);}?>"><?=word_lang("downloads")?></a> <?if($adownloads==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adownloads==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>
<th class="hidden-phone hidden-tablet"><?=word_lang("type")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("author")?></th>
<th></th>
<th></th>
<th></th>
</tr>
<?
$tr=1;
while(!$rs->eof)
{
?>
<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="checkbox" name="sel<?=$rs->row["aid"]?>" id="sel<?=$rs->row["aid"]?>"></td>
<td class="hidden-phone hidden-tablet"><?=$rs->row["aid"]?></td>
<td class='preview_img'><?

//Define preview
$generated="";

if($rs->row["module_table"]==30)
{
	$item_img=show_preview($rs->row["aid"],"photo",1,1,$rs->row["server1"],$rs->row["aid"]);
	$hoverbox_results=get_hoverbox($rs->row["aid"],"photo",$rs->row["server1"],"","");
}
if($rs->row["module_table"]==31)
{
	if($global_settings["ffmpeg_cron"])
	{
		$sql="select data1 from ffmpeg_cron where id=".$rs->row["aid"]." and data2=0";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$generated=word_lang("Previews are being created. Queue number is");
			
			$queue=1;
			$sql="select count(id) as queue_count from ffmpeg_cron where data1<".$ds->row["data1"]." and data2=0";
			$dr->open($sql);
			if(!$dr->eof)
			{
				$queue=$dr->row["queue_count"];
			}

			$generated.=" <b>".$queue."</b>";
		}
	}
	
	$item_img=show_preview($rs->row["aid"],"video",1,1,$rs->row["server1"],$rs->row["aid"]);
	$hoverbox_results=get_hoverbox($rs->row["aid"],"video",$rs->row["server1"],"","");
}
if($rs->row["module_table"]==52)
{
	$item_img=show_preview($rs->row["aid"],"audio",1,1,$rs->row["server1"],$rs->row["aid"]);
	$hoverbox_results=get_hoverbox($rs->row["aid"],"audio",$rs->row["server1"],"","");
}
if($rs->row["module_table"]==53)
{
	$item_img=show_preview($rs->row["aid"],"vector",1,1,$rs->row["server1"],$rs->row["aid"]);
	$hoverbox_results=get_hoverbox($rs->row["aid"],"vector",$rs->row["server1"],"","");
}
?>
<a href="content.php?id=<?=$rs->row["aid"]?>"><img src="<?=$item_img?>" border="0" <?=$hoverbox_results["hover"]?>></a>
</td>
<td class="hidden-phone hidden-tablet"><div class="link_file"><a href="content.php?id=<?=$rs->row["aid"]?>"><?=$rs->row["atitle"]?></a></div><small><?=$generated?></small></td>
<td class="hidden-phone hidden-tablet gray"><?=date(date_short,$rs->row["adata"])?></div></td>
<td class="hidden-phone hidden-tablet"><?=$rs->row["aviewed"]?></td>
<td class="hidden-phone hidden-tablet"><a href="../downloads/?search=<?=$rs->row["aid"]?>&search_type=file"><?=$rs->row["adownloaded"]?></a></td>
<td class="hidden-phone hidden-tablet">
<?
if($rs->row["module_table"]==30){echo(word_lang("photo"));}
if($rs->row["module_table"]==31){echo(word_lang("video"));}
if($rs->row["module_table"]==52){echo(word_lang("audio"));}
if($rs->row["module_table"]==53){echo(word_lang("vector"));}

?>
</td>

<td class="hidden-phone hidden-tablet"><div class="link_user"><a href="../customers/content.php?id=<?=user_url($rs->row["author"])?>"><?=$rs->row["author"]?></a></div></td>
<td>
	<?
	if($rs->row["published"]==1)
	{
	?>
		<div class="link_preview"><a href="<?=item_url($rs->row["aid"],$rs->row["url"])?>"><?=word_lang("preview")?></a></div>
	<?
	}
	if($rs->row["published"]==0)
	{
	?>
		<span class="label label-important"><?=word_lang("pending")?></span>
	<?
	}
	if($rs->row["published"]==-1 and $rs->row["exclusive"]==0)
	{
	?>
		<span class="label label-inverse"><?=word_lang("declined")?></span>
	<?
	}
	if($rs->row["published"]==-1 and $rs->row["exclusive"]==1)
	{
	?>
		<span class="label label-success"><?=word_lang("sold")?></span>
	<?
	}
	?>
</td>
<td><div class="link_edit"><a href="content.php?id=<?=$rs->row["aid"]?>"><?=word_lang("edit")?></a></div></td>
<td><div class="link_delete"><a href="delete.php?id=<?=$rs->row["aid"]?>&<?=$var_search.$var_sort?>"  onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div></td>
</tr>
<?
$n++;
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>


<div style="padding-top:30px;float:right"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort,false,$flag_show_end));?></div>






<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">


<div id="actions">
	<input type="hidden" name="formaction" id="formaction" value="delete_publication">
	<input type="submit" class="btn btn-danger" onClick="return confirm('<?=word_lang("delete")?>?');" value="<?=word_lang("delete")?>" >&nbsp;&nbsp;<?=word_lang("or")?>&nbsp;&nbsp;<div class="btn-group dropup">
    <a class="btn btn-primary" href="#"><?=word_lang("select action")?></a>
    <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
    <ul class="dropdown-menu">
    		<li><a href="javascript:bulk_action('change_publication');"><i class="icon-edit"></i> <?=word_lang("change")?></a></li>
    		
    		<li><a href="javascript:bulk_action('bulk_change_publication');"><i class="icon-tasks"></i> <?=word_lang("Bulk change titles, keywords, description")?></a></li>
    		
    		<li><a href="javascript:bulk_action('bulk_keywords_publication');"><i class="icon-tags"></i> <?=word_lang("Bulk add/remove keywords")?></a></li>
			
			<?if($global_settings["allow_photo"]){?>
				<li><a href="javascript:bulk_action('thumbs_publication');"><i class="icon-refresh"></i> <?=word_lang("regenerate thumbs")?></a></li>
			<?}?>
			
			<li><a href="javascript:bulk_action('content_publication');"><i class="icon-th"></i> <?=word_lang("change content type")?></a></li>
			
			<li><a href="javascript:bulk_action('move_publication');"><i class="icon-share-alt"></i> <?=word_lang("move to category")?></a></li>
			
			<li><a href="javascript:bulk_action('regenerate_urls');"><i class="icon-repeat"></i> <?=word_lang("regenerate urls")?></a></li>
			
			
			<li><a href="javascript:bulk_action('free_publication');"><i class="icon-download-alt"></i> <?=word_lang("change files to free/paid")?></a></li>
			
			<li><a href="javascript:bulk_action('featured_publication');"><i class="icon-thumbs-up"></i> <?=word_lang("change files to featured")?></a></li>
			
			<?if($global_settings["allow_photo"]){?>
				<li><a href="javascript:bulk_action('editorial_publication');"><i class="icon-picture"></i> <?=word_lang("change photos to editorial")?></a></li>
			<?}?>
			
			<?if($global_settings["adult_content"]){?>
				<li><a href="javascript:bulk_action('adult_publication');"><i class="icon-user"></i> <?=word_lang("change files to adult")?></a></li>
			<?}?>
			
			<?if($global_settings["exclusive_price"]){?>
				<li><a href="javascript:bulk_action('exclusive_publication');"><i class="icon-gift"></i> <?=word_lang("change files to exclusive")?></a></li>
			<?}?>
			
			<?if($global_settings["contacts_price"]){?>
				<li><a href="javascript:bulk_action('contacts_publication');"><i class="icon-envelope"></i> <?=word_lang("change files to 'contact us to get the price'")?></a></li>
			<?}?>
			
			<li><a href="javascript:bulk_action('approve_publication');"><i class="icon-ok"></i> <?=word_lang("approve")?>/<?=word_lang("decline")?></a></li>
			
			<?if($global_settings["rights_managed"]){?>
				<li><a href="javascript:bulk_action('rights_managed');"><i class="icon-list-alt"></i> <?=word_lang("change rights-managed price")?></a></li>
			<?}?>
    </ul>
    </div>
	
	
</div>


		</div>
	</div>

</form>

<?
}
else
{
echo("<p><b>".word_lang("not found")."</b></p>");
}
?>

<? include("../inc/end.php");?>