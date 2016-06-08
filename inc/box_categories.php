<?
if(!defined("site_root")){exit();}
$hmenu="";
$box_categories2="";


//Menu for Template 23 - ...
if(file_exists($DOCUMENT_ROOT."/".$site_template_url."menu.tpl"))
{
	$menu=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."menu.tpl");
	$file_template=str_replace("{MENU}",$menu,$file_template);


	//Menu TSHOP for top categories + dropdown instead of menu.tpl
	/*
	$menu_content='<div class="navbar-collapse collapse"><ul class="nav navbar-nav">';

	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.url,b.photo,b.priority from structure a,category b where a.id=b.id_parent and a.id_parent=5 and  b.published=1 and b.password=''  order by b.priority";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$itg="";

		$sql="select a.id,a.id_parent,b.id_parent,b.title,b.url,b.photo,b.priority from structure a,category b where a.id=b.id_parent and a.id_parent=".$rs->row["id"]." and  b.published=1 and b.password=''  order by b.priority";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$itg.="<ul class='dropdown-menu'><li class='megamenu-content '><ul class='unstyled noMarginLeft'>";

			while(!$ds->eof)
			{
				$aa= translate_category($ds->row["id"],$ds->row["title"],'','');

				$itg.="<li><a href='".$ds->row["url"]."'>".$aa["title"]."</a></li>";

				$ds->movenext();
			}

			$itg.="</ul></li></ul>";
		}

		$aa= translate_category($rs->row["id"],$rs->row["title"],'','');

		$menu_content.="<li class='dropdown megamenu-20width'><a href='".$rs->row["url"]."'  data-toggle='dropdown' class='dropdown-toggle'>".$aa["title"]." <b class='caret'> </b> </a>  ".$itg."</li>";
		$rs->movenext();
	}

	$menu_content.='</ul></div>';
	$file_template=str_replace("{MENU}",$menu_content,$file_template);
	*/


	//Idea menu for category tree
	/*
	$menu_content='';

	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.url,b.photo,b.priority from structure a,category b where a.id=b.id_parent and a.id_parent=5 and  b.published=1 and b.password=''  order by b.priority";
	$ds->open($sql);

	while(!$ds->eof)
	{
		//Build categories tree
		$itg="";
		if (!$smarty->isCached('buildmenu6.tpl',"buildmenu|6|".$ds->row["id"]."|".$lng))
		{
			$nlimit=0;
			buildmenu6($ds->row["id"]);
		}
		$smarty->cache_lifetime = -1;
		$smarty->assign('buildmenu6', $itg);
		$itg=$smarty->fetch('buildmenu6.tpl',"buildmenu|6|".$ds->row["id"]."|".$lng);

		$aa= translate_category($ds->row["id"],$ds->row["title"],'','');

		$class_dropdown = "";
		$class_dropdown2 ="";

		if($itg != "")
		{
			 $class_dropdown = ' class="dropdown"';
			 $class_dropdown2 = ' class="dropdown-toggle" data-toggle="dropdown"';
		}

		$menu_content.="<li".$class_dropdown."><a href='".$ds->row["url"]."' ".$class_dropdown2.">".$aa["title"]."</a>".$itg."</li>";
		$ds->movenext();
	}
	$file_template=str_replace("{CATEGORY_TREE}",$menu_content,$file_template);
	*/
}



$hmenu="<script type=\"text/javascript\" src=\"".site_root."/inc/ddsmoothmenu.js\">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type=\"text/javascript\">



ddsmoothmenu.init({
	mainmenuid: \"smoothmenu\", //Menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to \"h\" or \"v\"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: [\"#804000\", \"#482400\"],
	contentsource: \"markup\" //\"markup\" or [\"container_id\", \"path_to_menu_file\"]
})

</script><div id=\"smoothmenu\" class=\"ddsmoothmenu\">";


$hmenu.="<ul>";


if($site!="categories" and $site!="userlist" and $site!="map" and !isset($_REQUEST["sphoto"]) and !isset($_REQUEST["svideo"]) and !isset($_REQUEST["saudio"]) and !isset($_REQUEST["svector"]) and @$module_table!=30 and @$module_table!=31 and @$module_table!=52 and @$module_table!=53)
{
	$li_selected="class='home_link'";
}
else
{
	$li_selected="";
}

$hmenu.="<li ".$li_selected."><a href='".site_root."/'>".word_lang("home")."</a></li>";


if($global_settings["allow_photo"]==1)
{
	if(isset($_REQUEST["sphoto"]) or @$module_table==30)
	{
		$li_selected="class='home_link'";
	}
	else
	{
		$li_selected="";
	}

	$hmenu.="<li ".$li_selected."><a href='".site_root."/index.php?sphoto=1'>".word_lang("photo")."</a></li>";
}

if($global_settings["allow_video"]==1)
{
	if(isset($_REQUEST["svideo"]) or @$module_table==31)
	{
		$li_selected="class='home_link'";
	}
	else
	{
		$li_selected="";
	}

	$hmenu.="<li ".$li_selected."><a href='".site_root."/index.php?svideo=1'>".word_lang("video")."</a></li>";
}

if($global_settings["allow_audio"]==1)
{
	if(isset($_REQUEST["saudio"]) or @$module_table==52)
	{
		$li_selected="class='home_link'";
	}
	else
	{
		$li_selected="";
	}

	$hmenu.="<li ".$li_selected."><a href='".site_root."/index.php?saudio=1'>".word_lang("audio")."</a></li>";
}

if($global_settings["allow_vector"]==1)
{
	if(isset($_REQUEST["svector"]) or @$module_table==53)
	{
		$li_selected="class='home_link'";
	}
	else
	{
		$li_selected="";
	}

	$hmenu.="<li ".$li_selected."><a href='".site_root."/index.php?svector=1'>".word_lang("vector")."</a></li>";
}




	//Build categories tree
	$itg="";
	if (!$smarty->isCached('buildmenu6.tpl',"buildmenu|6|".$lng))
	{
		$nlimit=0;
		buildmenu6(5);
	}
	$smarty->cache_lifetime = -1;
	$smarty->assign('buildmenu6', $itg);
	$itg=$smarty->fetch('buildmenu6.tpl',"buildmenu|6|".$lng);
	$categories_menu = $itg;



if(!isset($_SESSION["site_info_content"]) or $_SESSION["site_info_content"]=="" or $flag_ssl)
{
	$sql="select id_parent,link,title,url from pages where siteinfo=1 order by priority";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$url=page_url($rs->row["id_parent"],$rs->row["url"]);
		if($rs->row["link"]!=""){$url=$rs->row["link"];}
		$site_info_content[]="<li><a href='".$url."'>".word_lang($rs->row["title"])."</a></li>";
		$rs->movenext();
	}
	$_SESSION["site_info_content"]=$site_info_content;
}
else
{
	$site_info_content=$_SESSION["site_info_content"];
}

$file_template=str_replace("{SITE_INFO_LINKS}",$site_info_content,$file_template);

$N = count($site_info_content);
$NN = ceil($N/3);

$site_info_list[1][3] = "";
$site_info_list[2][3] = "";
$site_info_list[3][3] = "";

for ($i = 0; $i <$N;  $i++) {
	if  ($i < $NN) {
		$site_info_list[1][3] .= $site_info_content[$i];
	} elseif ($NN <= $i && $i < 2*$NN) {
		$site_info_list[2][3] .= $site_info_content[$i];
	} else {
		$site_info_list[3][3] .= $site_info_content[$i];
	}
}


$file_template=str_replace("{SITE_INFO_LINKS_1_3}",$site_info_list[1][3],$file_template);
$file_template=str_replace("{SITE_INFO_LINKS_2_3}",$site_info_list[2][3],$file_template);
$file_template=str_replace("{SITE_INFO_LINKS_3_3}",$site_info_list[3][3],$file_template);



if($site=="categories")
{
	$li_selected="class='home_link'";
}
else
{
	$li_selected="";
}

$hmenu.="<li ".$li_selected."><a href='".site_root."/members/categories.php'>".word_lang("categories")."</a>".$itg."</li>";




if($global_settings["userupload"]==1)
{
	if($site=="userlist")
	{
		$li_selected="class='home_link'";
	}
	else
	{
		$li_selected="";
	}

	$hmenu.="<li ".$li_selected."><a href='".site_root."/members/users_list.php'>".word_lang("photographers")."</a></li>";
}

$hmenu.="<li><a href='#'>".word_lang("site info")."</a><ul>".$site_info_content."</ul></li>";


if($global_settings["google_coordinates"]==1)
{
	if($site=="map")
	{
		$li_selected="class='home_link'";
	}
	else
	{
		$li_selected="";
	}

	$hmenu.="<li ".$li_selected."><a href='".site_root."/members/map.php'>".word_lang("Google map")."</a></li>";
}

$hmenu.="</ul>";
$hmenu.="</div>";





	$box_categories="";
	if (!$smarty->isCached('buildmenu0.tpl',"buildmenu|0|".$lng))
	{
		$box_categories="<ul>";
		$box_categories2="<ul  class='dropdown2'>";

		$sql="select a.id,a.id_parent,b.id_parent,b.title,b.url,b.photo from structure a,category b where a.id=b.id_parent and a.id_parent=5 and  b.published=1 and b.password=''  order by b.title";
		//$sql="select id_parent,title,url,photo from category where published=1 and password='' order by title";
		$rs->open($sql);
		$n=0;
		while(!$rs->eof)
		{
			$translate_results=translate_category($rs->row["id_parent"],$rs->row["title"],"","");

			$box_categories.="<li><a href='".site_root.$rs->row["url"]."'>".$translate_results["title"]."</a></li>";
			if($n<20)
			{
				$box_categories2.="<li><a href='".site_root.$rs->row["url"]."'>".$translate_results["title"]."</a></li>";
			}
			$n++;
			$rs->movenext();
		}

		$box_categories.="</ul>";
		$box_categories2.="</ul>";
	}
	$smarty->cache_lifetime = -1;
	$smarty->assign('buildmenu0',$box_categories);
	$box_categories=$smarty->fetch('buildmenu0.tpl',"buildmenu|0|".$lng);






	$categories_list[0][0]="";
	$categories_list[1][2]="";
	$categories_list[2][2]="";
	$categories_list[1][3]="";
	$categories_list[2][3]="";
	$categories_list[3][3]="";
	$categories_list[1][4]="";
	$categories_list[2][4]="";
	$categories_list[3][4]="";
	$categories_list[4][4]="";
	$categories_list[1][6]="";
	$categories_list[2][6]="";
	$categories_list[3][6]="";
	$categories_list[4][6]="";
	$categories_list[5][6]="";
	$categories_list[6][6]="";

	$category_featured=array();
	$category_featured_url=array();
	$category_featured_photo=array();


	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.url,b.photo,b.featured from structure a,category b where a.id=b.id_parent and a.id_parent=5 and  b.published=1 and b.password=''  order by b.title";
	//$sql="select id_parent,title,url,photo,featured from category where published=1 and password='' order by title";
	$rs->open($sql);
	$n2=1;
	$n3=1;
	$n4=1;
	$n6=1;
	while(!$rs->eof)
	{
		$translate_results=translate_category($rs->row["id_parent"],$rs->row["title"],"","");

		$new_category="<li><a href='".site_root.$rs->row["url"]."'>".$translate_results["title"]."</a></li>";

		if($n2==3)
		{
			$n2=1;
		}
		if($n3==4)
		{
			$n3=1;
		}
		if($n4==5)
		{
			$n4=1;
		}
		if($n6==7)
		{
			$n6=1;
		}

		$categories_list[0][0].=$new_category;
		$categories_list[$n2][2].=$new_category;
 		$categories_list[$n3][3].=$new_category;
		$categories_list[$n4][4].=$new_category;
		$categories_list[$n6][6].=$new_category;

		if($rs->row["featured"]==1)
		{
			$category_featured[]=$translate_results["title"];
			$category_featured_url[]=site_root.$rs->row["url"];
			$category_featured_photo[]=$rs->row["photo"];
		}

		$n2++;
		$n3++;
		$n4++;
		$n6++;
		$rs->movenext();
	}




$file_template=str_replace("{CATEGORY_LIST}",$categories_list[0][0],$file_template);
$file_template=str_replace("{CATEGORY_LIST_1_2}",$categories_list[1][2],$file_template);
$file_template=str_replace("{CATEGORY_LIST_2_2}",$categories_list[2][2],$file_template);
$file_template=str_replace("{CATEGORY_LIST_1_3}",$categories_list[1][3],$file_template);
$file_template=str_replace("{CATEGORY_LIST_2_3}",$categories_list[2][3],$file_template);
$file_template=str_replace("{CATEGORY_LIST_3_3}",$categories_list[3][3],$file_template);
$file_template=str_replace("{CATEGORY_LIST_1_4}",$categories_list[1][4],$file_template);
$file_template=str_replace("{CATEGORY_LIST_2_4}",$categories_list[2][4],$file_template);
$file_template=str_replace("{CATEGORY_LIST_3_4}",$categories_list[3][4],$file_template);
$file_template=str_replace("{CATEGORY_LIST_4_4}",$categories_list[4][4],$file_template);
$file_template=str_replace("{CATEGORY_LIST_1_6}",$categories_list[1][6],$file_template);
$file_template=str_replace("{CATEGORY_LIST_2_6}",$categories_list[2][6],$file_template);
$file_template=str_replace("{CATEGORY_LIST_3_6}",$categories_list[3][6],$file_template);
$file_template=str_replace("{CATEGORY_LIST_4_6}",$categories_list[4][6],$file_template);
$file_template=str_replace("{CATEGORY_LIST_5_6}",$categories_list[5][6],$file_template);
$file_template=str_replace("{CATEGORY_LIST_6_6}",$categories_list[6][6],$file_template);

for($n=0;$n<10;$n++)
{
	$file_template=str_replace("{CATEGORY_FEATURED_".$n."}",@$category_featured[$n],$file_template);
	$file_template=str_replace("{CATEGORY_FEATURED_URL_".$n."}",@$category_featured_url[$n],$file_template);
	$file_template=str_replace("{CATEGORY_FEATURED_PHOTO_".$n."}",@$category_featured_photo[$n],$file_template);
}


$file_template=str_replace("{BOX_CATEGORIES}",$box_categories,$file_template);
$file_template=str_replace("{BOX_CATEGORIES2}",$box_categories2,$file_template);
$file_template=str_replace("{HORIZONTAL_MENU}",$hmenu,$file_template);





$trends_list[0][0]="";
$trends_list[1][2]="";
$trends_list[2][2]="";
$trends_list[1][3]="";
$trends_list[2][3]="";
$trends_list[3][3]="";
$trends_list[1][4]="";
$trends_list[2][4]="";
$trends_list[3][4]="";
$trends_list[4][4]="";
$trends_list[1][6]="";
$trends_list[2][6]="";
$trends_list[3][6]="";
$trends_list[4][6]="";
$trends_list[5][6]="";
$trends_list[6][6]="";


$sql="select a.id,a.id_parent,b.id_parent,b.title,b.url,b.photo,b.featured from structure a,category b where a.id=b.id_parent and a.id_parent=7874 and  b.published=1 and b.password=''  order by b.title";
//$sql="select id_parent,title,url,photo,featured from category where published=1 and password='' order by title";
$rs->open($sql);
$n2=1;
$n3=1;
$n4=1;
$n6=1;
while(!$rs->eof)
{
	$translate_results=translate_category($rs->row["id_parent"],$rs->row["title"],"","");

	$new_trend="<li><a href='".site_root.$rs->row["url"]."'>".$translate_results["title"]."</a></li>";

	if($n2==3)
	{
		$n2=1;
	}
	if($n3==4)
	{
		$n3=1;
	}
	if($n4==5)
	{
		$n4=1;
	}
	if($n6==7)
	{
		$n6=1;
	}

	$trends_list[0][0].=$new_trend;
	$trends_list[$n2][2].=$new_trend;
	$trends_list[$n3][3].=$new_trend;
	$trends_list[$n4][4].=$new_trend;
	$trends_list[$n6][6].=$new_trend;

	$n2++;
	$n3++;
	$n4++;
	$n6++;
	$rs->movenext();
}

$file_template=str_replace("{TREND_LIST}",$trends_list[0][0],$file_template);
#$file_template=str_replace("{TREND_LIST_1_2}",$trends_list[1][2],$file_template);
#$file_template=str_replace("{TREND_LIST_2_2}",$trends_list[2][2],$file_template);
#$file_template=str_replace("{TREND_LIST_1_3}",$trends_list[1][3],$file_template);
#$file_template=str_replace("{TREND_LIST_2_3}",$trends_list[2][3],$file_template);
#$file_template=str_replace("{TREND_LIST_3_3}",$trends_list[3][3],$file_template);
#$file_template=str_replace("{TREND_LIST_1_4}",$trends_list[1][4],$file_template);
#$file_template=str_replace("{TREND_LIST_2_4}",$trends_list[2][4],$file_template);
#$file_template=str_replace("{TREND_LIST_3_4}",$trends_list[3][4],$file_template);
#$file_template=str_replace("{TREND_LIST_4_4}",$trends_list[4][4],$file_template);
#$file_template=str_replace("{TREND_LIST_1_6}",$trends_list[1][6],$file_template);
#$file_template=str_replace("{TREND_LIST_2_6}",$trends_list[2][6],$file_template);
#$file_template=str_replace("{TREND_LIST_3_6}",$trends_list[3][6],$file_template);
#$file_template=str_replace("{TREND_LIST_4_6}",$trends_list[4][6],$file_template);
#$file_template=str_replace("{TREND_LIST_5_6}",$trends_list[5][6],$file_template);
#$file_template=str_replace("{TREND_LIST_6_6}",$trends_list[6][6],$file_template);



$cat_list[0][0]="";
$cat_list[1][2]="";
$cat_list[2][2]="";
$cat_list[1][3]="";
$cat_list[2][3]="";
$cat_list[3][3]="";
$cat_list[1][4]="";
$cat_list[2][4]="";
$cat_list[3][4]="";
$cat_list[4][4]="";
$cat_list[1][6]="";
$cat_list[2][6]="";
$cat_list[3][6]="";
$cat_list[4][6]="";
$cat_list[5][6]="";
$cat_list[6][6]="";


$sql="select a.id,a.id_parent,b.id_parent,b.title,b.url,b.photo,b.featured from structure a,category b where a.id=b.id_parent and a.id_parent=7474 and  b.published=1 and b.password=''  order by b.title";
//$sql="select id_parent,title,url,photo,featured from category where published=1 and password='' order by title";
$rs->open($sql);
//$n2=1;
//$n3=1;
//$n4=1;
//$n6=1;

while(!$rs->eof)
{
	$translate_results=translate_category($rs->row["id_parent"],$rs->row["title"],"","");

	$new_cat[]="<li><a href='".site_root.$rs->row["url"]."'>".$translate_results["title"]."</a></li>";

	/*if($n2==3)
	{
		$n2=1;
	}
	if($n3==4)
	{
		$n3=1;
	}
	if($n4==5)
	{
		$n4=1;
	}
	if($n6==7)
	{
		$n6=1;
	}*/
/*
	$cat_list[0][0].=$new_cat;
	$cat_list[$n2][2].=$new_cat;
	$cat_list[$n3][3].=$new_cat;
	$cat_list[$n4][4].=$new_cat;
	$cat_list[$n6][6].=$new_cat;
*/

	/*
	$n2++;
	$n3++;
	$n4++;
	$n6++;*/
	$rs->movenext();
}

$N = count($new_cat);
$NN = ceil($N/4);

for ($i = 0; $i <$N;  $i++) {
	if  ($i < $NN) {
		$cat_list[1][4] .= $new_cat[$i];
	} elseif ($NN <= $i && $i < 2*$NN) {
		$cat_list[2][4] .= $new_cat[$i];
	} elseif (2*$NN <= $i && $i < 3*$NN) {
		$cat_list[3][4] .= $new_cat[$i];
	} else {
		$cat_list[4][4] .= $new_cat[$i];
	}
}


#$file_template=str_replace("{CAT_LIST}",$cat_list[0][0],$file_template);
#$file_template=str_replace("{CAT_LIST_1_2}",$cat_list[1][2],$file_template);
#$file_template=str_replace("{CAT_LIST_2_2}",$cat_list[2][2],$file_template);
#$file_template=str_replace("{CAT_LIST_1_3}",$cat_list[1][3],$file_template);
#$file_template=str_replace("{CAT_LIST_2_3}",$cat_list[2][3],$file_template);
#$file_template=str_replace("{CAT_LIST_3_3}",$cat_list[3][3],$file_template);
$file_template=str_replace("{CAT_LIST_1_4}",$cat_list[1][4],$file_template);
$file_template=str_replace("{CAT_LIST_2_4}",$cat_list[2][4],$file_template);
$file_template=str_replace("{CAT_LIST_3_4}",$cat_list[3][4],$file_template);
$file_template=str_replace("{CAT_LIST_4_4}",$cat_list[4][4],$file_template);
#$file_template=str_replace("{CAT_LIST_1_6}",$cat_list[1][6],$file_template);
#$file_template=str_replace("{CAT_LIST_2_6}",$cat_list[2][6],$file_template);
#$file_template=str_replace("{CAT_LIST_3_6}",$cat_list[3][6],$file_template);
#$file_template=str_replace("{CAT_LIST_4_6}",$cat_list[4][6],$file_template);
#$file_template=str_replace("{CAT_LIST_5_6}",$cat_list[5][6],$file_template);
#$file_template=str_replace("{CAT_LIST_6_6}",$cat_list[6][6],$file_template);





$sql="select a.id,a.id_parent,b.id_parent,b.title,b.url,b.photo,b.featured from structure a,category b where a.id=b.id_parent and a.id_parent=7904 and  b.published=1 and b.password=''  order by b.title";
//$sql="select id_parent,title,url,photo,featured from category where published=1 and password='' order by title";
$rs->open($sql);
//$n2=1;
//$n3=1;
//$n4=1;
//$n6=1;

while(!$rs->eof)
{
	$translate_results=translate_category($rs->row["id_parent"],$rs->row["title"],"","");

	$new_style[]="<li><a href='".site_root.$rs->row["url"]."'>".$translate_results["title"]."</a></li>";

	/*if($n2==3)
	{
		$n2=1;
	}
	if($n3==4)
	{
		$n3=1;
	}
	if($n4==5)
	{
		$n4=1;
	}
	if($n6==7)
	{
		$n6=1;
	}*/
	/*
        $cat_list[0][0].=$new_cat;
        $cat_list[$n2][2].=$new_cat;
        $cat_list[$n3][3].=$new_cat;
        $cat_list[$n4][4].=$new_cat;
        $cat_list[$n6][6].=$new_cat;
    */

	$N++;
	/*
	$n2++;
	$n3++;
	$n4++;
	$n6++;*/
	$rs->movenext();
}

$N = count($new_cat);
$NN = ceil($N/4);

for ($i = 0; $i <$NN;  $i++) {
	if  ($i < $NN) {
		$style_list[1][4] .= $new_style[$i];
	} elseif ($NN <= $i && $i < 2*$NN) {
		$style_list[2][4] .= $new_style[$i];
	} elseif (2*$NN <= $i && $i < 3*$NN) {
		$style_list[3][4] .= $new_style[$i];
	} else {
		$style_list[4][4] .= $new_style[$i];
	}
}


#$file_template=str_replace("{CAT_LIST}",$cat_list[0][0],$file_template);
#$file_template=str_replace("{CAT_LIST_1_2}",$cat_list[1][2],$file_template);
#$file_template=str_replace("{CAT_LIST_2_2}",$cat_list[2][2],$file_template);
#$file_template=str_replace("{CAT_LIST_1_3}",$cat_list[1][3],$file_template);
#$file_template=str_replace("{CAT_LIST_2_3}",$cat_list[2][3],$file_template);
#$file_template=str_replace("{CAT_LIST_3_3}",$cat_list[3][3],$file_template);
$file_template=str_replace("{STYLE_LIST_1_4}",$style_list[1][4],$file_template);
$file_template=str_replace("{STYLE_LIST_2_4}",$style_list[2][4],$file_template);
$file_template=str_replace("{STYLE_LIST_3_4}",$style_list[3][4],$file_template);
$file_template=str_replace("{STYLE_LIST_4_4}",$style_list[4][4],$file_template);
#$file_template=str_replace("{CAT_LIST_1_6}",$cat_list[1][6],$file_template);
#$file_template=str_replace("{CAT_LIST_2_6}",$cat_list[2][6],$file_template);
#$file_template=str_replace("{CAT_LIST_3_6}",$cat_list[3][6],$file_template);
#$file_template=str_replace("{CAT_LIST_4_6}",$cat_list[4][6],$file_template);
#$file_template=str_replace("{CAT_LIST_5_6}",$cat_list[5][6],$file_template);
#$file_template=str_replace("{CAT_LIST_6_6}",$cat_list[6][6],$file_template);

?>
