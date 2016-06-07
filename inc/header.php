<?
if(!defined("site_root")){exit();}
//id_parent determination
if(isset($_GET["id_parent"]))
{
	$id_parent=(int)$_GET["id_parent"];
}
elseif(isset($_GET["category"]))
{
	if(!preg_match("/^[0-9]+$/",$_GET["category"]))
	{
	$sql="select id_parent,title from category where title='".str_replace("-"," ",result3($_GET["category"]))."'";
	$dr->open($sql);
		if(!$dr->eof){$id_parent=$dr->row["id_parent"];}
		else{$id_parent=5;}
	}
	else
	{
	$sql="select id_parent from category where id_parent=".(int)$_GET["category"];
	$dr->open($sql);
		if(!$dr->eof){$id_parent=$dr->row["id_parent"];}
		else{$id_parent=5;}
	}
}
elseif(isset($_GET["acategory"]))
{
	$id_parent=(int)$_GET["acategory"];
}
elseif(isset($_POST["acategory"]))
{
	$id_parent=(int)$_POST["acategory"];
}
elseif(isset($_GET["catalog"]))
{
	$id_parent=item_id($_GET["catalog"],$_GET["ctypes"]);
}
else
{
	$id_parent=5;
}


//Module table
$module_table=0;
$module_parent=0;
if($id_parent!=5)
{
	$sql="select module_table,id_parent from structure where id=".(int)$id_parent;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$module_table=$rs->row["module_table"];
		$module_parent=$rs->row["id_parent"];
	}
	else
	{
		$module_table=30;
		$module_parent=5;
	}
	/*
	if(($module_table==30 or $module_table==31 or $module_table==52 or  $module_table==53) and !isset($_SESSION["people_id"]))
	{
		header("location:".site_root."/members/login.php");
		exit();
	}
	*/
}

//Affiliate
if(isset($_GET["aff"]))
{
	$sql="update users set aff_visits=aff_visits+1 where id_parent=".(int)$_GET["aff"];
	$db->execute($sql);
	setcookie("aff",(int)$_GET["aff"],time()+60*60*24*30,"/",str_replace("http://","",surl));
}




//user determination
$name_user="";
if(isset($_GET["user"]))
	{
	$sql="select id_parent,login from users where id_parent=".(int)$_GET["user"];
	$dr->open($sql);
	if(!$dr->eof){$user_id=$dr->row["id_parent"];$name_user=$dr->row["login"];}
	else{$user_id=0;}
}


//Variables
$file_template="";
$template_home="";
$template_header="";


//Header template
$template_header=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."header.tpl");


//Home_template
$template_home=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."home.tpl");




if(count($_POST)==0 and (count($_GET)==0 or isset($_GET["template"]) or (isset($_GET["aff"]) and count($_GET)==1) or isset($_GET["theme_color"])) and $site=="main" and $site_home_separated)
{
	$file_template=$template_home;
}
else
{
	$file_template=$template_header;
}


//Components
$out = array();
preg_match_all("|\{COMPONENT_(\d*)\}|U",$file_template, $out);
$k=0;
while(isset($out[0][$k]) and isset($out[1][$k]))
{
	$file_template=str_replace("{COMPONENT_".strval($out[1][$k])."}",show_component((int)$out[1][$k]),$file_template);
	$k++;
}



//Meta keywords description
$flag_social=false;
$social_mass=array();
$meta_keywords=$global_settings["meta_keywords"]." ";
$meta_description=$global_settings["meta_description"].". ";

//Meta categories
if($module_table==34)
{
	$sql="select id_parent,title,priority,password,description,keywords,photo,upload,published,url from category where id_parent=".(int)$id_parent;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$translate_results=translate_category($rs->row["id_parent"],$rs->row["title"],$rs->row["description"],$rs->row["keywords"]);
		
		$meta_keywords.=$translate_results["keywords"];
		$meta_description.=$translate_results["description"];
		$social_mass["type"]="category";
		$social_mass["title"]=$translate_results["title"];
		$social_mass["keywords"]=$translate_results["keywords"];
		$social_mass["description"]=$translate_results["description"];
		$social_mass["url"]=surl.site_root.$rs->row["url"];
		$social_mass["author"]="";
		$social_mass["google_x"]=0;
		$social_mass["google_y"]=0;
		$social_mass["data"]=0;
		$social_mass["image"]=$rs->row["photo"];
		
		if(!preg_match("/http/i",$social_mass["image"]))
		{
			$social_mass["image"]=surl.$social_mass["image"];
		}		
		
		$sql="select title from category where id_parent=".(int)$module_parent;
		$ds->open($sql);
		{
			$social_mass["category"]=$ds->row["title"];
		}
	}
}

//Meta photos
if($module_table==30)
{
	$sql="select id_parent,title,keywords,description,url,author,google_x,google_y,data,server1 from photos where id_parent=".(int)$id_parent;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$meta_keywords.=$rs->row["keywords"];
		$meta_description.=$rs->row["description"];
		$social_mass["type"]="photo";
		$social_mass["title"]=$rs->row["title"];
		$social_mass["keywords"]=$rs->row["keywords"];
		$social_mass["description"]=$rs->row["description"];
		$social_mass["url"]=surl.site_root.$rs->row["url"];
		$social_mass["author"]=$rs->row["author"];
		$social_mass["google_x"]=$rs->row["google_x"];
		$social_mass["google_y"]=$rs->row["google_y"];
		$social_mass["data"]=$rs->row["data"];
		$social_mass["image"]=show_preview($rs->row["id_parent"],"photo",2,1,$rs->row["server1"],$rs->row["id_parent"]);
		
		if(!preg_match("/http/i",$social_mass["image"]))
		{
			$social_mass["image"]=surl.$social_mass["image"];
		}		
		
		$sql="select title from category where id_parent=".(int)$module_parent;
		$ds->open($sql);
		{
			$social_mass["category"]=$ds->row["title"];
		}
	}
}

//Meta videos
if($module_table==31)
{
	$sql="select id_parent,title,keywords,description,url,author,google_x,google_y,data,server1 from videos where id_parent=".(int)$id_parent;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$meta_keywords.=$rs->row["keywords"];
		$meta_description.=$rs->row["description"];
		$social_mass["type"]="video";
		$social_mass["title"]=$rs->row["title"];
		$social_mass["keywords"]=$rs->row["keywords"];
		$social_mass["description"]=$rs->row["description"];
		$social_mass["url"]=surl.site_root.$rs->row["url"];
		$social_mass["author"]=$rs->row["author"];
		$social_mass["google_x"]=$rs->row["google_x"];
		$social_mass["google_y"]=$rs->row["google_y"];
		$social_mass["data"]=$rs->row["data"];
		$social_mass["image"]=show_preview($rs->row["id_parent"],"video",1,1,$rs->row["server1"],$rs->row["id_parent"]);
		
		if(!preg_match("/http/i",$social_mass["image"]))
		{
			$social_mass["image"]=surl.$social_mass["image"];
		}		
		
		$sql="select title from category where id_parent=".(int)$module_parent;
		$ds->open($sql);
		{
			$social_mass["category"]=$ds->row["title"];
		}
	}
}

//Meta audio
if($module_table==52)
{
	$sql="select id_parent,title,keywords,description,url,author,google_x,google_y,data,server1 from audio where id_parent=".(int)$id_parent;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$meta_keywords.=$rs->row["keywords"];
		$meta_description.=$rs->row["description"];
		$social_mass["type"]="audio";
		$social_mass["title"]=$rs->row["title"];
		$social_mass["keywords"]=$rs->row["keywords"];
		$social_mass["description"]=$rs->row["description"];
		$social_mass["url"]=surl.site_root.$rs->row["url"];
		$social_mass["author"]=$rs->row["author"];
		$social_mass["google_x"]=$rs->row["google_x"];
		$social_mass["google_y"]=$rs->row["google_y"];
		$social_mass["data"]=$rs->row["data"];
		$social_mass["image"]=show_preview($rs->row["id_parent"],"audio",1,1,$rs->row["server1"],$rs->row["id_parent"]);
		
		if(!preg_match("/http/i",$social_mass["image"]))
		{
			$social_mass["image"]=surl.$social_mass["image"];
		}		
		
		$sql="select title from category where id_parent=".(int)$module_parent;
		$ds->open($sql);
		{
			$social_mass["category"]=$ds->row["title"];
		}
	}
}

//Meta vector
if($module_table==53)
{
	$sql="select id_parent,title,keywords,description,url,author,google_x,google_y,data,server1 from vector where id_parent=".(int)$id_parent;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$meta_keywords.=$rs->row["keywords"];
		$meta_description.=$rs->row["description"];
		$social_mass["type"]="vector";
		$social_mass["title"]=$rs->row["title"];
		$social_mass["keywords"]=$rs->row["keywords"];
		$social_mass["description"]=$rs->row["description"];
		$social_mass["url"]=surl.site_root.$rs->row["url"];
		$social_mass["author"]=$rs->row["author"];
		$social_mass["google_x"]=$rs->row["google_x"];
		$social_mass["google_y"]=$rs->row["google_y"];
		$social_mass["data"]=$rs->row["data"];
		$social_mass["image"]=show_preview($rs->row["id_parent"],"vector",2,1,$rs->row["server1"],$rs->row["id_parent"]);
		
		if(!preg_match("/http/i",$social_mass["image"]))
		{
			$social_mass["image"]=surl.$social_mass["image"];
		}		
		
		$sql="select title from category where id_parent=".(int)$module_parent;
		$ds->open($sql);
		{
			$social_mass["category"]=$ds->row["title"];
		}
	}
}


//Meta stock API
if(isset($_GET["stock_api"]))
{
	include("stock_api.php");
}

$meta_nocache="";
$rand_nocache="";
if(isset($_GET["template"]))
{
	$meta_nocache="<meta http-equiv='Cache-Control' content='no-cache, no-store, max-age=0, must-revalidate'/>
    <meta http-equiv='Pragma' content='no-cache'/>
    <meta http-equiv='Expires' content='Fri, 01 Jan 1990 00:00:00 GMT'/>";
    
    $rand_nocache="?t=".(int)$_GET["template"];
    
   	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  	header("Last-Modified: " . gmdate("D, d M Y H:i:s")." GMT");
 	header("Cache-Control: no-cache, must-revalidate");
  	header("Cache-Control: post-check=0,pre-check=0", false);
  	header("Cache-Control: max-age=0", false);
  	header("Pragma: no-cache");
}
$file_template=str_replace("{META_NOCACHE}",$meta_nocache,$file_template);
$file_template=str_replace("{RAND_NOCACHE}",$rand_nocache,$file_template);


$file_template=str_replace("{KEYWORDS}",$meta_keywords,$file_template);
$file_template=str_replace("{DESCRIPTION}",$meta_description,$file_template);
$file_template=str_replace("{META_SOCIAL}",get_social_meta_tags($social_mass),$file_template);







//Box categories
include("box_categories.php");

//Box shopping cart
include("box_shopping_cart.php");

//Box members template
include("box_members.php");

//Box search
include("box_search.php");

//Box site info
include("box_site_info.php");

//Box news
include("box_news.php");

//Box photographers
include("box_photographers.php");

//Box stat
include("box_stat.php");

//Box languages
include("box_languages.php");

//Box tag clouds
include("box_tag_clouds.php");




//Maps
$google_code="";
$file_template=str_replace("{GOOGLE_MAP}",$google_code,$file_template);
$file_template=str_replace("{GOOGLE_API}",$global_settings["google_api"],$file_template);




//Site name
$sname=$global_settings["site_name"];
$path="";
if($site=="news"){$sname.=" - ".word_lang("news");$path="<li>".word_lang("news")."</li>";}
if($site=="about"){$sname.=" - ".word_lang("about");$path="<li>".word_lang("about")."</li>";}
if($site=="contacts"){$sname.=" - ".word_lang("contacts");$path="<li>".word_lang("contacts")."</li>";}
if($site=="support"){$sname.=" - ".word_lang("support");$path="<li>".word_lang("support")."</li>";}
if($site=="shopping_cart"){$sname.=" - ".word_lang("shopping cart");$path="<li>".word_lang("shopping cart")."</li>";}
if($site=="checkout"){$sname.=" - ".word_lang("checkout");$path="<li>".word_lang("checkout")."</li>";}
if($site=="page"){$sname.="";$path="<li>".word_lang("site info")."</li>";}
if($site=="main")
{
	$sname=get_title_path(5,$id_parent,"structure","name","","").$sname;
	$path=get_title_path(5,$id_parent,"structure","name","","",true);
	/*
	$sql="select name from structure where id=".(int)$id_parent;
	$ds->open($sql);
	if(!$ds->eof)
	{
		$sname=$ds->row["name"];
	}
	*/
}
if($site=="forgot"){$sname.=" - ".word_lang("forgot password");$path="<li>".word_lang("forgot password")."</li>";}
if($site=="orders"){$sname.=" - ".word_lang("orders");$path="<li>".word_lang("orders")."</li>";}
if($site=="profile"){$sname.=" - ".word_lang("my profile");$path="<li>".word_lang("my profile")."</li>";}
if($site=="favorite"){$sname.=" - ".word_lang("my favorite list");$path="<li>".word_lang("my favorite list")."</li>";}
if($site=="signup"){$sname.=" - ".word_lang("sign up");$path="<li>".word_lang("sign up")."</li>";}
if($site=="login"){$sname.=" - ".word_lang("login");$path="<li>".word_lang("login")."</li>";}
if($site=="languages"){$sname.=" - ".word_lang("languages");$path="<li>".word_lang("languages")."</li>";}
if($site=="categories"){$sname.=" - ".word_lang("categories");$path="<li>".word_lang("Browse categories")."</li>";}
if($site=="profile_about"){$sname.=" - ".word_lang("my profile");$path="<li>".word_lang("my profile")."</li>";}
if($site=="credits"){$sname.=" - ".word_lang("credits");$path="<li>".word_lang("credits")."</li>";}
if($site=="subscription"){$sname.=" - ".word_lang("subscription");$path="<li>".word_lang("subscription")."</li>";}
if($site=="profile_downloads"){$sname.=" - ".word_lang("my downloads");$path="<li>".word_lang("my downloads")."</li>";}
if($site=="coupons"){$sname.=" - ".word_lang("coupons");$path="<li>".word_lang("coupons")."</li>";}
if($site=="printslab"){$sname.=" - ".word_lang("prints lab");$path="<li>".word_lang("prints lab")."</li>";}
if($site=="lightbox"){$sname.=" - ".word_lang("my favorite list");$path="<li>".word_lang("my favorite list")."</li>";}
if($site=="friends"){$sname.=" - ".word_lang("friends");$path="<li>".word_lang("friends")."</li>";}
if($site=="messages"){$sname.=" - ".word_lang("messages");$path="<li>".word_lang("messages")."</li>";}
if($site=="blog"){$sname.=" - ".word_lang("blog");$path="<li>".word_lang("blog")."</li>";}
if($site=="reviews"){$sname.=" - ".word_lang("comments");$path="<li>".word_lang("comments")."</li>";}
if($site=="testimonials"){$sname.=" - ".word_lang("testimonials");$path="<li>".word_lang("testimonials")."</li>";}
if($site=="upload" or $site=="upload_jquery" or $site=="upload_plupload" or $site=="upload_java" or $site=="upload_flash" or $site=="upload_video" or $site=="upload_audio" or $site=="upload_vector" or $site=="upload_category" or $site=="publications"){$sname.=" - ".word_lang("my upload");$path="<li>".word_lang("my upload")."</li>";}
if($site=="commission"){$sname.=" - ".word_lang("my commission");$path="<li>".word_lang("my commission")."</li>";}
if($site=="affiliate"){$sname.=" - ".word_lang("affiliate");$path="<li>".word_lang("affiliate")."</li>";}
if($site=="license"){$sname.=" - ".word_lang("license");$path="<li>".word_lang("license")."</li>";}
if($site=="models"){$sname.=" - ".word_lang("models");$path="<li>".word_lang("models")."</li>";}
if($site=="map"){$sname.=" - Google map";$path="<li>Google map</li>";}

if($site=="user"){$sname.=" - ".word_lang("user")." - ".$name_user;$path="<li><a href='".site_root."/members/users_list.php'>".word_lang("users")."</a></li><li>".$name_user."</li>";}
if($site=="user_portfolio"){$sname.=" - ".word_lang("portfolio")." - ".$name_user;$path="<li>".word_lang("portfolio")."</li><li>".$name_user."</li>";}
if($site=="user_blog"){$sname.=" - ".word_lang("blog")." - ".$name_user;$path="<li>".word_lang("blog")."</li><li>".$name_user."</li>";}
if($site=="user_testimonials"){$sname.=" - ".word_lang("testimonials")." - ".$name_user;$path="<li>".word_lang("testimonials")."</li><li>".$name_user."</li>";}
if($site=="user_friends"){$sname.=" - ".word_lang("friends")." - ".$name_user;$path="<li>".word_lang("friends")."</li><li>".$name_user."</li>";}
if($site=="user_lightbox"){$sname.=" - ".word_lang("my favorite list")." - ".$name_user;$path="<li>".word_lang("my favorite list")."</li><li>".$name_user."</li>";}
if($site=="userlist"){$sname.=" - ".word_lang("users");$path="<li>".word_lang("users")."</li>";}

if(isset($_GET["stock_api"]))
{
	$sname.=" - ".@$meta_title;
}


$file_template=str_replace("{SITE_NAME}",$sname,$file_template);
$file_template=str_replace("{PATH}",$path,$file_template);
$file_template=str_replace("{TELEPHONE}",$global_settings["telephone"],$file_template);
$file_template=str_replace("{FACEBOOK}",$global_settings["facebook_link"],$file_template);
$file_template=str_replace("{GOOGLE}",$global_settings["google_link"],$file_template);
$file_template=str_replace("{TWITTER}",$global_settings["twitter_link"],$file_template);











//Language
$file_template=str_replace("{LNG}",strtolower($lng),$file_template);




//Meta tag
$file_template=str_replace("{MTG}",$mtg,$file_template);






$file_template=format_layout($file_template,"sitephoto",$global_settings["allow_photo"]);
$file_template=format_layout($file_template,"sitevideo",$global_settings["allow_video"]);
$file_template=format_layout($file_template,"siteaudio",$global_settings["allow_audio"]);
$file_template=format_layout($file_template,"sitevector",$global_settings["allow_vector"]);
$file_template=format_layout($file_template,"sitecredits",$global_settings["credits"]);
$file_template=format_layout($file_template,"sitesubscription",$global_settings["subscription"]);

$flag_auth=false;
$flag_noauth=true;
if(isset($_SESSION["people_id"]))
{
	$flag_auth=true;
	$flag_noauth=false;
}

$file_template=format_layout($file_template,"auth",$flag_auth);
$file_template=format_layout($file_template,"noauth",$flag_noauth);


//Social
$sql="select activ,title from users_qauth";
$dr->open($sql);
while(!$dr->eof)
{
	if($dr->row["title"]=="Facebook")
	{
		$file_template=format_layout($file_template,"facebook",$dr->row["activ"]);
	}
	if($dr->row["title"]=="Twitter")
	{
		$file_template=format_layout($file_template,"twitter",$dr->row["activ"]);
	}
	if($dr->row["title"]=="Vkontakte")
	{
		$file_template=format_layout($file_template,"vk",$dr->row["activ"]);
	}
	if($dr->row["title"]=="Instagram")
	{
		$file_template=format_layout($file_template,"instagram",$dr->row["activ"]);
	}	

	$dr->movenext();
}

//Prints
$file_template=format_layout($file_template,"prints",$global_settings["prints"]);

$prints_list="";
if($global_settings["prints"])
{
	$sql="select id_parent,title,priority from prints order by priority";
	$dr->open($sql);
	while(!$dr->eof)
	{
		$prints_list.="<li><a href='".site_root."/index.php?search=&print_id=".$dr->row["id_parent"]."'>".$dr->row["title"]."</a></li>";
		$dr->movenext();
	}
}
$file_template=str_replace("{PRINTS_LIST}",$prints_list,$file_template);


$file_template=str_replace("{CURRENCY_CODE1}",currency(1),$file_template);
$file_template=str_replace("{CURRENCY_CODE2}",currency(2),$file_template);



//Theme color
$theme_color=1;


if(isset($_COOKIE["theme_color"]))
{
	$theme_color=(int)$_COOKIE["theme_color"];
}

if(isset($_GET["theme_color"]))
{
	$theme_color=(int)$_GET["theme_color"];
	setcookie("theme_color",$theme_color,time()+60*60*24*30,"/",str_replace("http://","",surl));
}


$file_template=str_replace("{THEME_COLOR}",$theme_color,$file_template);



//Site root
$file_template=str_replace("{SITE_ROOT}",site_root."/",$file_template);

//Template root
$file_template=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$file_template);


//Add necessary divs
$divs="<div id='lightbox_menu_ok'></div><div id='lightbox_menu_error'></div><div id='lightbox' style='top:0px;left:0px;position:absolute;z-index:1000;display:none'></div>";

$file_template_mass=explode("</body>",$file_template);
if(count($file_template_mass)==2)
{
	$file_template=$file_template_mass[0].$divs."</body>".$file_template_mass[1];
}
else
{
	$file_template.=$divs;
}







echo(translate_text($file_template));




if($id_parent==5 and count($_POST)==0 and (count($_GET)==0 or isset($_GET["template"]) or (isset($_GET["aff"]) and count($_GET)==1) or isset($_GET["theme_color"])) and $site=="main" and $site_home_separated)
{
exit();
}




?>