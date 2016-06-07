<?
if(!defined("site_root")){exit();}

$flag_ssl=false;
if((isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"]=="on") or preg_match("/https/i",surl))
{
	$flag_ssl=true;
}


$global_settings=array();

//Define a template
$site_template_home=0;
$site_template_id=0;
$site_home_separated=false;

if(isset($_GET["template"]))
{
	$crm=" id=".(int)$_GET["template"];
	setcookie("template",(int)$_GET["template"],time()+60*60*2,str_replace("http://","",surl));
}
elseif(isset($_COOKIE["template"]))
{
	$crm=" id=".(int)$_COOKIE["template"];
}
else
{
	$crm="activ=1";
}

$sql="select id,url,shome,home from templates where ".$crm;
$rs->open($sql);
if(!$rs->eof)
{
	$site_template_id=$rs->row["id"];
	$site_template_url=$rs->row["url"];
	if($rs->row["shome"]==1)
	{
		$site_home_separated=true;
	}
	$site_template_home=$rs->row["home"];
}
else
{
	$site_template_url="";
}



//Define server
$site_server_activ=1;
$site_servers=array();
$sql="select id,url,activ from filestorage order by id";
$rs->open($sql);
while(!$rs->eof)
{
	if($rs->row["activ"]==1)
	{
		$site_server_activ=$rs->row["id"];
	}
	$site_servers[$rs->row["id"]]=$rs->row["url"];
	$rs->movenext();
}





//Setings
$sql="select setting_key,svalue,activ from settings";
$rs->open($sql);
while(!$rs->eof)
{
	if($rs->row["setting_key"]=='lightbox_photo' or $rs->row["setting_key"]=='lightbox_video' or $rs->row["setting_key"]=='userupload' or $rs->row["setting_key"]=='usa_2257' or $rs->row["setting_key"]=='allow_photo' or $rs->row["setting_key"]=='allow_video' or $rs->row["setting_key"]=='allow_audio' or $rs->row["setting_key"]=='blog' or $rs->row["setting_key"]=='messages' or $rs->row["setting_key"]=='testimonials' or $rs->row["setting_key"]=='reviews' or $rs->row["setting_key"]=='friends' or $rs->row["setting_key"]=='prints' or $rs->row["setting_key"]=='photo_remote' or $rs->row["setting_key"]=='video_remote' or $rs->row["setting_key"]=='audio_remote' or $rs->row["setting_key"]=='printsonly' or $rs->row["setting_key"]=='watermarkinfo' or $rs->row["setting_key"]=='allow_vector' or $rs->row["setting_key"]=='vector_remote' or $rs->row["setting_key"]=='credits' or $rs->row["setting_key"]=='download_sample' or $rs->row["setting_key"]=='subscription' or $rs->row["setting_key"]=='subscription_only' or $rs->row["setting_key"]=='common_account' or $rs->row["setting_key"]=='related_items' or $rs->row["setting_key"]=='zoomer' or $rs->row["setting_key"]=='moderation' or $rs->row["setting_key"]=='prints_users' or $rs->row["setting_key"]=='model' or $rs->row["setting_key"]=='show_model' or $rs->row["setting_key"]=='flash' or $rs->row["setting_key"]=='examination' or $rs->row["setting_key"]=='google_coordinates' or $rs->row["setting_key"]=='exif' or $rs->row["setting_key"]=='affiliates' or $rs->row["setting_key"]=='google_captcha' or $rs->row["setting_key"]=='site_guest' or $rs->row["setting_key"]=='java_uploader' or $rs->row["setting_key"]=='flash_uploader' or $rs->row["setting_key"]=='jquery_uploader' or $rs->row["setting_key"]=='plupload_uploader' or $rs->row["setting_key"]=='seller_prices' or $rs->row["setting_key"]=='language_detection' or $rs->row["setting_key"]=='adult_content' or $rs->row["setting_key"]=='prints_photos' or $rs->row["setting_key"]=='prints_vectors' or $rs->row["setting_key"]=='auto_paging' or $rs->row["setting_key"]=='auto_paging_default' or $rs->row["setting_key"]=='auth_freedownload' or $rs->row["setting_key"]=='auth_rating' or $rs->row["setting_key"]=='multilingual_categories' or 
	$rs->row["setting_key"]=='multilingual_publications' or $rs->row["setting_key"]=='rights_managed' or $rs->row["setting_key"]=='royalty_free' or $rs->row["setting_key"]=='rights_managed_sellers' or $rs->row["setting_key"]=='caching' or $rs->row["setting_key"]=='show_content_type' or $rs->row["setting_key"]=='support' or $rs->row["setting_key"]=='search_history' or $rs->row["setting_key"]=='prints_lab' or $rs->row["setting_key"]=='grid' or $rs->row["setting_key"]=='fixed_width' or $rs->row["setting_key"]=='fixed_height' or $rs->row["setting_key"]=='checkout_order_billing' or $rs->row["setting_key"]=='checkout_order_shipping' or $rs->row["setting_key"]=='checkout_credits_billing' or $rs->row["setting_key"]=='checkout_subscription_billing' or $rs->row["setting_key"]=='contacts_price' or $rs->row["setting_key"]=='exclusive_price' or $rs->row["setting_key"]=='left_search' or $rs->row["setting_key"]=='left_search_default' or $rs->row["setting_key"]=='credits_currency' or $rs->row["setting_key"]=='taxes_cart' or $rs->row["setting_key"]=='no_calculation' or $rs->row["setting_key"]=='users_rating' or $rs->row["setting_key"]=='users_rating_limited')
	{
		$global_settings[$rs->row["setting_key"]]=(int)$rs->row["activ"];
	}
	else
	{
		$global_settings[$rs->row["setting_key"]]=$rs->row["svalue"];
	}

	if($rs->row["setting_key"]=='affiliates')
	{
		if(!file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/members/affiliate.php"))
		{
			$global_settings["affiliates"]=0;
		}
		else
		{
			$global_settings["affiliates"]=$rs->row["activ"];
		}
	}

	//Date const
	if($rs->row["setting_key"]=='date_format')
	{
		define( "date_format", $rs->row["svalue"] );
		define( "date_format2", $rs->row["svalue"] );
		define( "date_short", $rs->row["svalue"] );
		define( "time_format", "H:i:s" );
		define( "datetime_format", date_format . " " . time_format );
	}

	if($rs->row["setting_key"]=='userupload')
	{		
		if(!file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/members/upload.php"))
		{
			$global_settings["userupload"]=0;
		}
		else
		{
			$global_settings["userupload"]=$rs->row["activ"];
		}		
	}

	$rs->movenext();
}
//End settings



$currency_code1="";
$currency_code2="";
$currency_egold=0;
$sql="select code1,code2 from currency where activ=1";
$rs->open($sql);
if(!$rs->eof)
{
	$currency_code1=$rs->row["code1"];
	$currency_code2=$rs->row["code2"];
}


if($currency_code1=="NGN")
{
	$currency_code2="&#8358;";
}
if($currency_code1=="RUR")
{
	$currency_code2="&#8358;";
}


//Aspect ratio
$aspect_ratio=array();
if(@$global_settings["allow_video"])
{
	$sql="select name,width,height from video_ratio";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$aspect_ratio[$rs->row["name"]]=$rs->row["height"]/$rs->row["width"];
		$_SESSION["aspect_ratio"][$rs->row["name"]]=$rs->row["height"]/$rs->row["width"];
		$rs->movenext();
	}
}





//Languages
$lang_name["Russian"]="Русский";
$lang_name["English"]="English";
$lang_name["German"]="Deutsch";
$lang_name["French"]="Français";
$lang_name["Arabic"]="العربية";
$lang_name["Afrikaans formal"]="Afrikaans formal";
$lang_name["Afrikaans informal"]="Afrikaans informal";
$lang_name["Brazilian"]="Português brasileiro";
$lang_name["Bulgarian"]="Български";
$lang_name["Chinese traditional"]="漢語";
$lang_name["Chinese simplified"]="汉语";
$lang_name["Catalan"]="Сatalà";
$lang_name["Czech"]="Česky";
$lang_name["Danish"]="Dansk";
$lang_name["Dutch"]="Nederlands";
$lang_name["Estonian"]="Eesti";
$lang_name["Finnish"]="Suomi";
$lang_name["Georgian"]="ქართული";
$lang_name["Greek"]="Ελληνικά";
$lang_name["Hebrew"]="עברית";
$lang_name["Hungarian"]="Magyar";
$lang_name["Indonesian"]="Indonesia";
$lang_name["Italian"]="Italiano";
$lang_name["Japanese"]="日本語";
$lang_name["Latvian"]="Latviešu";
$lang_name["Lithuanian"]="Lietuvių";
$lang_name["Malaysian"]="Melayu";
$lang_name["Norwegian"]="Norsk";
$lang_name["Persian"]="فارسی";
$lang_name["Polish"]="Polski";
$lang_name["Portuguese"]="Português";
$lang_name["Romanian"]="Română";
$lang_name["Serbian"]="Српски";
$lang_name["Slovakian"]="Slovenčina";
$lang_name["Slovenian"]="Slovenski";
$lang_name["Spanish"]="Español";
$lang_name["Swedish"]="Svenska";
$lang_name["Thai"]="ภาษาไทย";
$lang_name["Turkish"]="Türkçe";
$lang_name["Ukrainian"]="Українська";
$lang_name["Croatian"]="Hrvatski";
$lang_name["Icelandic"]="Íslenska";
$lang_name["Vietnamese"]="Vietnamese";
$lang_name["Azerbaijan"]="Azerbaijan";



$lang_symbol["English"]="en";
$lang_symbol["Russian"]="ru";
$lang_symbol["German"]="de";
$lang_symbol["French"]="fr";
$lang_symbol["Arabic"]="ar";
$lang_symbol["Afrikaans formal"]="af";
$lang_symbol["Afrikaans informal"]="af";
$lang_symbol["Brazilian"]="br";
$lang_symbol["Bulgarian"]="bg";
$lang_symbol["Chinese traditional"]="zh";
$lang_symbol["Chinese simplified"]="zh";
$lang_symbol["Catalan"]="ca";
$lang_symbol["Czech"]="cs";
$lang_symbol["Danish"]="da";
$lang_symbol["Dutch"]="nl";
$lang_symbol["Estonian"]="et";
$lang_symbol["Finnish"]="fi";
$lang_symbol["Georgian"]="ka";
$lang_symbol["Greek"]="el";
$lang_symbol["Hebrew"]="he";
$lang_symbol["Hungarian"]="hu";
$lang_symbol["Indonesian"]="id";
$lang_symbol["Italian"]="it";
$lang_symbol["Japanese"]="ja";
$lang_symbol["Latvian"]="lv";
$lang_symbol["Lithuanian"]="lt";
$lang_symbol["Malaysian"]="ms";
$lang_symbol["Norwegian"]="no";
$lang_symbol["Persian"]="fa";
$lang_symbol["Polish"]="pl";
$lang_symbol["Portuguese"]="pt";
$lang_symbol["Romanian"]="ro";
$lang_symbol["Serbian"]="sr";
$lang_symbol["Slovakian"]="sk";
$lang_symbol["Slovenian"]="sl";
$lang_symbol["Spanish"]="es";
$lang_symbol["Swedish"]="sv";
$lang_symbol["Thai"]="th";
$lang_symbol["Turkish"]="tr";
$lang_symbol["Ukrainian"]="uk";
$lang_symbol["Croatian"]="hr";
$lang_symbol["Icelandic"]="is";
$lang_symbol["Vietnamese"]="vn";
$lang_symbol["Azerbaijan"]="az";

$lang_symbol_inv["en"]="English";
$lang_symbol_inv["ru"]="Russian";
$lang_symbol_inv["de"]="German";
$lang_symbol_inv["fr"]="French";
$lang_symbol_inv["ar"]="Arabic";
$lang_symbol_inv["af1"]="Afrikaans formal";
$lang_symbol_inv["af2"]="Afrikaans informal";
$lang_symbol_inv["br"]="Brazilian";
$lang_symbol_inv["bg"]="Bulgarian";
$lang_symbol_inv["zh1"]="Chinese traditional";
$lang_symbol_inv["zh2"]="Chinese simplified";
$lang_symbol_inv["ca"]="Catalan";
$lang_symbol_inv["cs"]="Czech";
$lang_symbol_inv["da"]="Danish";
$lang_symbol_inv["nl"]="Dutch";
$lang_symbol_inv["et"]="Estonian";
$lang_symbol_inv["fi"]="Finnish";
$lang_symbol_inv["ka"]="Georgian";
$lang_symbol_inv["el"]="Greek";
$lang_symbol_inv["he"]="Hebrew";
$lang_symbol_inv["hu"]="Hungarian";
$lang_symbol_inv["id"]="Indonesian";
$lang_symbol_inv["it"]="Italian";
$lang_symbol_inv["ja"]="Japanese";
$lang_symbol_inv["lv"]="Latvian";
$lang_symbol_inv["lt"]="Lithuanian";
$lang_symbol_inv["ms"]="Malaysian";
$lang_symbol_inv["no"]="Norwegian";
$lang_symbol_inv["fa"]="Persian";
$lang_symbol_inv["pl"]="Polish";
$lang_symbol_inv["pt"]="Portuguese";
$lang_symbol_inv["ro"]="Romanian";
$lang_symbol_inv["sr"]="Serbian";
$lang_symbol_inv["sk"]="Slovakian";
$lang_symbol_inv["sl"]="Slovenian";
$lang_symbol_inv["es"]="Spanish";
$lang_symbol_inv["sv"]="Swedish";
$lang_symbol_inv["th"]="Thai";
$lang_symbol_inv["tr"]="Turkish";
$lang_symbol_inv["uk"]="Ukrainian";
$lang_symbol_inv["hr"]="Croatian";
$lang_symbol_inv["is"]="Icelandic";
$lang_symbol_inv["vn"]="Vietnamese";
$lang_symbol_inv["az"]="Azerbaijan";


$lng="English";
$mtg="utf-8";






$sql="select name,metatags,activ from languages where display=1 order by name";
$rs->open($sql);
while(!$rs->eof)
{
	if($rs->row["activ"]==1)
	{
		$lng=$rs->row["name"];
		$mtg=$rs->row["metatags"];
	}
	$_SESSION["site_lng"][$rs->row["name"]]=$rs->row["metatags"];
	$rs->movenext();
}


if(!isset($_SESSION["slang"]) and isset($_COOKIE["cookie_lang"]))
{
	$_SESSION["slang"]=result($_COOKIE["cookie_lang"]);
}

if(!isset($_SESSION["slang"]) and isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) and @$global_settings["language_detection"])
{
	foreach ($lang_symbol as $key => $value) 
	{
		if(preg_match("/".$value."/",$_SERVER["HTTP_ACCEPT_LANGUAGE"]))
		{
			$lng=$key;
			$_SESSION["site_lng_activ"]=$lng;
		}
	}
}



if(isset($_SESSION["slang"]) and $_SESSION["slang"]!="")
{
	$lng=$_SESSION["slang"];
	$_SESSION["site_lng_activ"]=$lng;
	$mtg=$_SESSION["site_lng"][$lng];
}

$lng2=$lng;
if($lng=="Chinese traditional"){$lng2="chineset";}
if($lng=="Chinese simplified"){$lng2="chineses";}
if($lng=="Afrikaans formal"){$lng2="afrikaansf";}
if($lng=="Afrikaans informal"){$lng2="afrikaansi";}



if(!isset($nolang))
{
	header("Content-Type: text/html; charset=utf-8");
	include( $DOCUMENT_ROOT."/admin/languages/".strtolower($lng2).".php" );
}


//End languages







//Amazon settings
$amazon_region["REGION_US_E1"]="US Standart";
$amazon_region["REGION_US_W1"]="US West (N. California)";
$amazon_region["REGION_US_W2"]="US West (Oregon)";

$amazon_region["REGION_EU_W1"]="EU (Ireland)";
//$amazon_region["REGION_EU_W2"]="EU (Frankfurt)";

$amazon_region["REGION_APAC_SE1"]="Asia Pacific (Singapore)";
$amazon_region["REGION_APAC_SE2"]="Asia Pacific (Sydney)";

$amazon_region["REGION_APAC_NE1"]="Asia Pacific (Tokyo)";


$amazon_region["REGION_SA_E1"]="South America (Sao Paulo)";


//Auto login
if(!isset($_SESSION["people_login"]) and isset($_COOKIE["cookie_login"]) and isset($_COOKIE["cookie_password"]))
{
	$login=result($_COOKIE["cookie_login"]);
	$password=result($_COOKIE["cookie_password"]);
	user_authorization($login,$password,"site");
}




//caching
$site_cache_header=-1;
$site_cache_footer=-1;
$site_cache_home=-1;
$site_cache_item=-1;
$site_cache_catalog=-1;
$site_cache_components=-1;
$site_cache_stats=-1;

$sql="select id,time_refresh from caching";
$rs->open($sql);
while(!$rs->eof)
{
	if($rs->row["id"]==1)
	{
		$site_cache_header=$rs->row["time_refresh"];
	}
	if($rs->row["id"]==2)
	{
		$site_cache_footer=$rs->row["time_refresh"];
	}
	if($rs->row["id"]==3)
	{
		$site_cache_home=$rs->row["time_refresh"];
	}
	if($rs->row["id"]==4)
	{
		$site_cache_item=$rs->row["time_refresh"];
	}
	if($rs->row["id"]==5)
	{
		$site_cache_catalog=$rs->row["time_refresh"];
	}
	if($rs->row["id"]==6)
	{
		$site_cache_components=$rs->row["time_refresh"];
	}
	if($rs->row["id"]==7)
	{
		$site_cache_stats=$rs->row["time_refresh"];
	}
	$rs->movenext();
}




//Countries
$mcountry=Array("Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antarctica","Antigua","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia/Hercegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burma","Burundi","Cambodia Dem.","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Cocos Islands","Colombia","Comoros","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea, Democratic Peoples Repbulic","Korea, Rep. Of","Kuwait","Laos Peoples Democratic Republic","Latvia","Lebanon","Lesotho","Liberia","Libyan Arab Jamahiriya","Liechtenstein","Lithuania","Luxembourg","Macau","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Neth. Antilles Nevis","Netherlands","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Romania","Russia","Rwanda","Samoa (American)","Samoa (Western)","San Marino","Sao Tome & Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","Spain","Sri Lanka","St. Kitts & Nevis","St. Lucia","St. Pierre & Miquelon","St. Vincent & Grenadines","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syrian Arab Republic","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Trinidad & Tobago","Tunisia","Turkey","Turkmenistan","Turks & Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands (Br.)","Virgin Islands (U.S.)","Wallis & Futuna","Yemen Republic","Zaire","Zambia","Zimbabwe");

//EU Countries
$mcountry_eu=Array("Austria","Belgium","Bulgaria","Croatia","Cyprus","Czech Republic","Denmark","Estonia","Finland","France","Germany","Greece","Hungary","Ireland","Italy","Latvia","Lithuania","Luxembourg","Malta","Netherlands","Poland","Portugal","Romania","Slovakia","Slovenia","Spain","Sweden","United Kingdom");



$mcountry_code["Afghanistan"]="AF";
$mcountry_code["Albania"]="Al";
$mcountry_code["Algeria"]="DZ";
$mcountry_code["Andorra"]="AD";
$mcountry_code["Angola"]="AO";
$mcountry_code["Anguilla"]="AI";
$mcountry_code["Antarctica"]="AQ";
$mcountry_code["Antigua"]="AG";
$mcountry_code["Argentina"]="AR";
$mcountry_code["Armenia"]="AM";
$mcountry_code["Aruba"]="AW";
$mcountry_code["Australia"]="AU";
$mcountry_code["Austria"]="AT";
$mcountry_code["Azerbaijan"]="AZ";
$mcountry_code["Bahamas"]="BS";
$mcountry_code["Bahrain"]="BH";
$mcountry_code["Bangladesh"]="BD";
$mcountry_code["Barbados"]="BB";
$mcountry_code["Belarus"]="BY";
$mcountry_code["Belgium"]="BE";
$mcountry_code["Belize"]="BZ";
$mcountry_code["Benin"]="BJ";
$mcountry_code["Bermuda"]="BM";
$mcountry_code["Bhutan"]="BT";
$mcountry_code["Bolivia"]="BO";
$mcountry_code["Bosnia/Hercegovina"]="BA";
$mcountry_code["Botswana"]="BW";
$mcountry_code["Brazil"]="BR";
$mcountry_code["Brunei"]="BN";
$mcountry_code["Bulgaria"]="BG";
$mcountry_code["Burkina Faso"]="BF";
$mcountry_code["Burma"]="BU";
$mcountry_code["Burundi"]="BI";
$mcountry_code["Cambodia Dem."]="KH";
$mcountry_code["Cameroon"]="CM";
$mcountry_code["Canada"]="CA";
$mcountry_code["Cape Verde"]="CV";
$mcountry_code["Cayman Islands"]="KY";
$mcountry_code["Central African Republic"]="CF";
$mcountry_code["Chad"]="TD";
$mcountry_code["Chile"]="CL";
$mcountry_code["China"]="CN";
$mcountry_code["Cocos Islands"]="CC";
$mcountry_code["Colombia"]="CO";
$mcountry_code["Comoros"]="KM";
$mcountry_code["Congo"]="CG";
$mcountry_code["Cook Islands"]="CK";
$mcountry_code["Costa Rica"]="CR";
$mcountry_code["Cote D Ivoire"]="CI";
$mcountry_code["Croatia"]="HR";
$mcountry_code["Cuba"]="CU";
$mcountry_code["Cyprus"]="CY";
$mcountry_code["Czech Republic"]="CZ";
$mcountry_code["Denmark"]="DK";
$mcountry_code["Djibouti"]="DJ";
$mcountry_code["Dominica"]="DM";
$mcountry_code["Dominican Republic"]="DO";
$mcountry_code["Ecuador"]="EC";
$mcountry_code["Egypt"]="EG";
$mcountry_code["El Salvador"]="SV";
$mcountry_code["Equatorial Guinea"]="GQ";
$mcountry_code["Estonia"]="EE";
$mcountry_code["Ethiopia"]="ET";
$mcountry_code["Falkland Islands"]="FK";
$mcountry_code["Faroe Islands"]="FO";
$mcountry_code["Fiji"]="FJ";
$mcountry_code["Finland"]="FI";
$mcountry_code["France"]="FR";
$mcountry_code["French Guiana"]="GF";
$mcountry_code["French Polynesia"]="PF";
$mcountry_code["Gabon"]="GA";
$mcountry_code["Gambia"]="GM";
$mcountry_code["Georgia"]="GE";
$mcountry_code["Germany"]="DE";
$mcountry_code["Ghana"]="GH";
$mcountry_code["Gibraltar"]="GI";
$mcountry_code["Greece"]="GR";
$mcountry_code["Greenland"]="GL";
$mcountry_code["Grenada"]="GD";
$mcountry_code["Guadeloupe"]="GP";
$mcountry_code["Guam"]="GU";
$mcountry_code["Guatemala"]="GT";
$mcountry_code["Guinea"]="GN";
$mcountry_code["Guinea-Bissau"]="GW";
$mcountry_code["Guyana"]="GY";
$mcountry_code["Haiti"]="HT";
$mcountry_code["Honduras"]="HN";
$mcountry_code["Hong Kong"]="HK";
$mcountry_code["Hungary"]="HU";
$mcountry_code["Iceland"]="IS";
$mcountry_code["India"]="IN";
$mcountry_code["Indonesia"]="ID";
$mcountry_code["Iran"]="IR";
$mcountry_code["Iraq"]="IQ";
$mcountry_code["Ireland"]="IE";
$mcountry_code["Israel"]="IL";
$mcountry_code["Italy"]="IT";
$mcountry_code["Jamaica"]="JM";
$mcountry_code["Japan"]="JP";
$mcountry_code["Jordan"]="JO";
$mcountry_code["Kazakhstan"]="KZ";
$mcountry_code["Kenya"]="KE";
$mcountry_code["Kiribati"]="KI";
$mcountry_code["Korea, Democratic Peoples Repbulic"]="KP";
$mcountry_code["Korea, Rep. Of"]="KR";
$mcountry_code["Kuwait"]="KW";
$mcountry_code["Laos Peoples Democratic Republic"]="LA";
$mcountry_code["Latvia"]="LV";
$mcountry_code["Lebanon"]="LB";
$mcountry_code["Lesotho"]="LS";
$mcountry_code["Liberia"]="LR";
$mcountry_code["Libyan Arab Jamahiriya"]="LY";
$mcountry_code["Liechtenstein"]="LI";
$mcountry_code["Lithuania"]="LT";
$mcountry_code["Luxembourg"]="LU";
$mcountry_code["Macau"]="MO";
$mcountry_code["Madagascar"]="MG";
$mcountry_code["Malawi"]="MW";
$mcountry_code["Malaysia"]="MY";
$mcountry_code["Maldives"]="MV";
$mcountry_code["Mali"]="ML";
$mcountry_code["Malta"]="MT";
$mcountry_code["Marshall Islands"]="MH";
$mcountry_code["Martinique"]="MQ";
$mcountry_code["Mauritania"]="MR";
$mcountry_code["Mauritius"]="MU";
$mcountry_code["Mayotte"]="YT";
$mcountry_code["Mexico"]="MX";
$mcountry_code["Micronesia"]="FM";
$mcountry_code["Moldova"]="MD";
$mcountry_code["Monaco"]="MC";
$mcountry_code["Mongolia"]="MN";
$mcountry_code["Montserrat"]="MS";
$mcountry_code["Morocco"]="MA";
$mcountry_code["Mozambique"]="MZ";
$mcountry_code["Myanmar"]="MM";
$mcountry_code["Namibia"]="NA";
$mcountry_code["Nauru"]="NR";
$mcountry_code["Nepal"]="NP";
$mcountry_code["Neth. Antilles Nevis"]="AN";
$mcountry_code["Netherlands"]="NL";
$mcountry_code["New Caledonia"]="NC";
$mcountry_code["New Zealand"]="NZ";
$mcountry_code["Nicaragua"]="NI";
$mcountry_code["Niger"]="NE";
$mcountry_code["Nigeria"]="NG";
$mcountry_code["Niue"]="NU";
$mcountry_code["Norfolk Island"]="NF";
$mcountry_code["Northern Mariana"]="MP";
$mcountry_code["Norway"]="NO";
$mcountry_code["Oman"]="OM";
$mcountry_code["Pakistan"]="PK";
$mcountry_code["Palau"]="PW";
$mcountry_code["Panama"]="PA";
$mcountry_code["Papua New Guinea"]="PG";
$mcountry_code["Paraguay"]="PY";
$mcountry_code["Peru"]="PE";
$mcountry_code["Philippines"]="PH";
$mcountry_code["Poland"]="PL";
$mcountry_code["Portugal"]="PT";
$mcountry_code["Puerto Rico"]="PR";
$mcountry_code["Qatar"]="QA";
$mcountry_code["Romania"]="RO";
$mcountry_code["Russia"]="RU";
$mcountry_code["Rwanda"]="RW";
$mcountry_code["Samoa (American)"]="WS";
$mcountry_code["Samoa (Western)"]="WS";
$mcountry_code["San Marino"]="SM";
$mcountry_code["Sao Tome & Principe"]="ST";
$mcountry_code["Saudi Arabia"]="SA";
$mcountry_code["Senegal"]="SN";
$mcountry_code["Seychelles"]="SC";
$mcountry_code["Sierra Leone"]="SL";
$mcountry_code["Singapore"]="SG";
$mcountry_code["Slovakia"]="SK";
$mcountry_code["Slovenia"]="SI";
$mcountry_code["Solomon Islands"]="SB";
$mcountry_code["Somalia"]="SO";
$mcountry_code["South Africa"]="ZA";
$mcountry_code["Spain"]="ES";
$mcountry_code["Sri Lanka"]="LK";
$mcountry_code["St. Kitts & Nevis"]="";
$mcountry_code["St. Lucia"]="";
$mcountry_code["St. Pierre & Miquelon"]="";
$mcountry_code["St. Vincent & Grenadines"]="";
$mcountry_code["Sudan"]="SD";
$mcountry_code["Suriname"]="SR";
$mcountry_code["Swaziland"]="SZ";
$mcountry_code["Sweden"]="SE";
$mcountry_code["Switzerland"]="CH";
$mcountry_code["Syrian Arab Republic"]="SY";
$mcountry_code["Taiwan"]="TW";
$mcountry_code["Tajikistan"]="TJ";
$mcountry_code["Tanzania"]="TZ";
$mcountry_code["Thailand"]="TH";
$mcountry_code["Togo"]="TG";
$mcountry_code["Tonga"]="TO";
$mcountry_code["Trinidad & Tobago"]="TT";
$mcountry_code["Tunisia"]="TN";
$mcountry_code["Turkey"]="TR";
$mcountry_code["Turkmenistan"]="TM";
$mcountry_code["Turks & Caicos"]="TC";
$mcountry_code["Tuvalu"]="TV";
$mcountry_code["Uganda"]="UG";
$mcountry_code["Ukraine"]="UA";
$mcountry_code["United Arab Emirates"]="AE";
$mcountry_code["United Kingdom"]="UK";
$mcountry_code["United States"]="US";
$mcountry_code["Uruguay"]="UY";
$mcountry_code["Uzbekistan"]="UZ";
$mcountry_code["Vanuatu"]="VU";
$mcountry_code["Vatican City"]="VA";
$mcountry_code["Venezuela"]="VE";
$mcountry_code["Vietnam"]="VN";
$mcountry_code["Virgin Islands (Br.)"]="VG";
$mcountry_code["Virgin Islands (U.S.)"]="VI";
$mcountry_code["Wallis & Futuna"]="WF";
$mcountry_code["Yemen Republic"]="YE";
$mcountry_code["Yugoslavia"]="YU";
$mcountry_code["Zaire"]="ZR";
$mcountry_code["Zambia"]="ZM";
$mcountry_code["Zimbabwe"]="ZW";




//States of Russia
$mstates["Russia"]=array("Москва", "Санкт-Петербург","Севастополь", "Республика Адыгея (Адыгея)", "Республика Алтай", "Республика Башкортостан", "Республика Бурятия", "Республика Дагестан", "Республика Ингушетия", "Кабардино-Балкарская Республика", "Республика Калмыкия", "Карачаево-Черкесская Республика", "Республика Карелия", "Республика Коми","Республика Крым", "Республика Марий Эл", "Республика Мордовия", "Республика Саха (Якутия)", "Республика Северная Осетия - Алания", "Республика Татарстан (Татарстан)", "Республика Тыва", "Удмуртская Республика", "Республика Хакасия", "Чеченская Республика", "Чувашская Республика - Чувашия", "Алтайский край", "Забайкальский край", "Камчатский край", "Краснодарский край", "Красноярский край", "Пермский край", "Приморский край", "Ставропольский край", "Хабаровский край", "Амурская область", "Архангельская область", "Астраханская область", "Белгородская область", "Брянская область", "Владимирская область", "Волгоградская область", "Вологодская область", "Воронежская область", "Ивановская область", "Иркутская область", "Калининградская область", "Калужская область", "Кемеровская область", "Кировская область", "Костромская область", "Курганская область", "Курская область", "Ленинградская область", "Липецкая область", "Магаданская область", "Московская область", "Мурманская область", "Нижегородская область", "Новгородская область", "Новосибирская область", "Омская область", "Оренбургская область", "Орловская область", "Пензенская область", "Псковская область", "Ростовская область", "Рязанская область", "Самарская область", "Саратовская область", "Сахалинская область", "Свердловская область", "Смоленская область", "Тамбовская область", "Тверская область", "Томская область", "Тульская область", "Тюменская область", "Ульяновская область", "Челябинская область", "Ярославская область", "Еврейская автономная область", "Ненецкий автономный округ", "Ханты-Мансийский автономный округ - Югра", "Чукотский автономный округ", "Ямало-Ненецкий автономный округ");

//States of USA
$mstates["United States"]=array('Alabama', 'Alaska','Arizona','Arkansas','California','Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming');

//States of Canada
$mstates["Canada"]=array("British Columbia","Ontario","Newfoundland","Nova Scotia","Prince Edward Island","New Brunswick","Quebec","Manitoba","Saskatchewan","Alberta","Northwest Territories","Yukon Territory");

//States of UK
$mstates["United Kingdom"]=array('Bedfordshire','Berkshire','Buckinghamshire','Cambridgeshire','Cheshire','Cornwall','Cumberland','Derbyshire','Devon','Dorset','Durham','East Yorkshire','Essex','Gloucestershire','Hampshire','Herefordshire','Hertfordshire','Huntingdonshire','Kent','Lancashire','Leicestershire','Lincolnshire','Middlesex','Norfolk','North Yorkshire','Northamptonshire','Northumberland','Nottinghamshire','Oxfordshire','Rutland','Shropshire','Somerset','Staffordshire','Suffolk','Surrey','Sussex','Warwickshire','West Yorkshire','Westmorland','Wiltshire','Worcestershire','Aberdeenshire','Angus/Forfarshire','Argyllshire','Ayrshire','Banffshire','Berwickshire','Buteshire','Cromartyshire','Caithness','Clackmannanshire','Dumfriesshire','Dunbartonshire/Dumbartonshire','East Lothian/Haddingtonshire','Fife','Inverness-shire','Kincardineshire','Kinross-shire','Kirkcudbrightshire','Lanarkshire','Midlothian/Edinburghshire','Morayshire','Nairnshire','Orkney','Peeblesshire','Perthshire','Renfrewshire','Ross-shire','Roxburghshire','Selkirkshire','Shetland','Stirlingshire','Sutherland','West Lothian/Linlithgowshire','Wigtownshire','Anglesey/Sir Fon','Brecknockshire/Sir Frycheiniog','Caernarfonshire/Sir Gaernarfon','Carmarthenshire/Sir Gaerfyrddin','Cardiganshire/Ceredigion','Denbighshire/Sir Ddinbych','Flintshire/Sir Fflint','Glamorgan/Morgannwg','Merioneth/Meirionnydd','Monmouthshire/Sir Fynwy','Montgomeryshire/Sir Drefaldwyn','Pembrokeshire/Sir Benfro','Radnorshire/Sir Faesyfed','County Antrim','County Armagh','County Down','County Fermanagh','County Tyrone','County Londonderry/Derry');

//Regions de la France
$mstates["France"]=array("Île-de-France", "Rhône-Alpes", "Provence-Alpes-Côte d'Azur", "Nord-Pas-de-Calais", "Pays-de-la-Loire", "Aquitaine", "Brittany", "Midi-Pyrénées", "Centre", "Lorraine", "Languedoc-Roussillon", "Picardy", "Upper Normandy", "Alsace", "Poitou-Charentes", "Burgundy", "Lower Normandy", "Champagne-Ardenne", "Auvergne", "Franche-Comté", "Limousin", "Réunion", "Guadeloupe", "Martinique", "Corsica", "Guiana");

//States of Germany
$mstates["Germany"]=array("Baden-Württemberg","Bavaria","Berlin","Brandenburg","Bremen","Hamburg","Hesse","Mecklenburg-Vorpommern","Lower Saxony","North Rhine-Westphalia","Rhineland-Palatinate","Saarland","Saxony","Saxony-Anhalt","Schleswig-Holstein","Thuringia");


//Months
$m_month[0]="January";
$m_month[1]="February";
$m_month[2]="March";
$m_month[3]="April";
$m_month[4]="May";
$m_month[5]="June";
$m_month[6]="July";
$m_month[7]="August";
$m_month[8]="September";
$m_month[9]="October";
$m_month[10]="November";
$m_month[11]="December";

//Stocks
$mstocks["site"] = word_lang("site");
$mstocks["istockphoto"] = "Getty/iStock";
$mstocks["shutterstock"] = "Shutterstock";
$mstocks["fotolia"] = "Fotolia";
$mstocks["depositphotos"] = "Depositphotos";
$mstocks["rf123"] = "123rf";
$mstocks["bigstockphoto"] = "Bigstockphoto";

//Admin submenu var
$admin_submenu="";
?>