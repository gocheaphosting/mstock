<?
if(!defined("site_root")){exit();}

$menu_admin=array();
$menu_admin_name=array();


$menu_admin[]="orders";
$menu_admin_name[]=word_lang("orders");

$menu_admin[]="catalog";
$menu_admin_name[]=word_lang("catalog");

$menu_admin[]="users";
$menu_admin_name[]=word_lang("users");

$menu_admin[]="settings";
$menu_admin_name[]=word_lang("settings");

$menu_admin[]="templates";
$menu_admin_name[]=word_lang("templates");

$menu_admin[]="pages";
$menu_admin_name[]=word_lang("text pages");

if($global_settings["affiliates"]==1)
{
	$menu_admin[]="affiliates";
	$menu_admin_name[]=word_lang("affiliates");
}




$submenu_admin=array();
$submenu_admin_url=array();


//Order submenu

$submenu_admin["orders_orders"]=word_lang("orders");
$submenu_admin_url["orders_orders"]="../orders/";

if($global_settings["credits"]==1)
{
	$submenu_admin["orders_credits"]=word_lang("credits");
	$submenu_admin_url["orders_credits"]="../credits/";
}

if($global_settings["subscription"]==1)
{
	$submenu_admin["orders_subscription"]=word_lang("subscription");
	$submenu_admin_url["orders_subscription"]="../subscription_list/";
}

$submenu_admin["orders_invoices"]=word_lang("Invoices");
$submenu_admin_url["orders_invoices"]="../invoices/";

$submenu_admin["orders_payments"]=word_lang("payments");
$submenu_admin_url["orders_payments"]="../payments/";

$submenu_admin["orders_coupons"]=word_lang("coupons");
$submenu_admin_url["orders_coupons"]="../coupons/";

if($global_settings["userupload"]==1)
{
	$submenu_admin["orders_commission"]=word_lang("commission manager");
	$submenu_admin_url["orders_commission"]="../commission/";
}

$submenu_admin["orders_carts"]=word_lang("Shopping carts");
$submenu_admin_url["orders_carts"]="../shopping_carts/";

if(!$global_settings["printsonly"])
{
	$submenu_admin["orders_downloads"]=word_lang("Downloads");
	$submenu_admin_url["orders_downloads"]="../downloads/";
}

//End. Order submenu

//Catalog submenu

$submenu_admin["catalog_categories"]=word_lang("categories");
$submenu_admin_url["catalog_categories"]="../categories/";

$submenu_admin["catalog_catalog"]=word_lang("catalog");
$submenu_admin_url["catalog_catalog"]="../catalog/";

$submenu_admin["catalog_bulkupload"]=word_lang("bulk upload");
$submenu_admin_url["catalog_bulkupload"]="../bulk_upload/";

if($global_settings["userupload"]==1)
{
	$submenu_admin["catalog_upload"]=word_lang("upload manager");
	$submenu_admin_url["catalog_upload"]="../upload/";
	
	if($global_settings["examination"]==1)
	{
		$submenu_admin["catalog_exam"]=word_lang("seller examination");
		$submenu_admin_url["catalog_exam"]="../exam/";
	}
}



if($global_settings["reviews"]==1)
{
	$submenu_admin["catalog_comments"]=word_lang("reviews");
	$submenu_admin_url["catalog_comments"]="../comments/";
}

if($global_settings["search_history"]==1)
{
	$submenu_admin["catalog_search"]=word_lang("search history");
	$submenu_admin_url["catalog_search"]="../search/";
}

$submenu_admin["catalog_lightboxes"]=word_lang("Lightboxes");
$submenu_admin_url["catalog_lightboxes"]="../lightboxes/";



//End. Catalog submenu


//User submenu

$submenu_admin["users_customers"]=word_lang("customers");
$submenu_admin_url["users_customers"]="../customers/";

$submenu_admin["users_documents"]=word_lang("Documents");
$submenu_admin_url["users_documents"]="../documents/";

$submenu_admin["users_notifications"]=word_lang("notifications");
$submenu_admin_url["users_notifications"]="../notifications/";

if($global_settings["support"]==1)
{
	$submenu_admin["users_support"]=word_lang("support");
	$submenu_admin_url["users_support"]="../support/";
}


if($global_settings["messages"]==1)
{
	$submenu_admin["users_messages"]=word_lang("messages");
	$submenu_admin_url["users_messages"]="../messages/";
}

$submenu_admin["users_contacts"]=word_lang("contacts");
$submenu_admin_url["users_contacts"]="../contacts/";

$submenu_admin["users_newsletter"]=word_lang("newsletter");
$submenu_admin_url["users_newsletter"]="../newsletter/";

if($global_settings["testimonials"]==1)
{
	$submenu_admin["users_testimonials"]=word_lang("testimonials");
	$submenu_admin_url["users_testimonials"]="../testimonials/";
}

if($global_settings["blog"]==1)
{
	$submenu_admin["users_blogs"]=word_lang("blog");
	$submenu_admin_url["users_blogs"]="../blogs/";
}

$submenu_admin["users_administrators"]=word_lang("administrators");
$submenu_admin_url["users_administrators"]="../administrators/";

$submenu_admin["users_password"]=word_lang("admin password");
$submenu_admin_url["users_password"]="../settings/password.php";

$submenu_admin["users_blockedip"]=word_lang("blocked ip");
$submenu_admin_url["users_blockedip"]="../blockedip/";

//End. User submenu


//Settings submenu

$submenu_admin["settings_site"]=word_lang("site settings");
$submenu_admin_url["settings_site"]="../settings/site.php";

$submenu_admin["settings_emails"]=word_lang("e-mail");
$submenu_admin_url["settings_emails"]="../emails/";

$submenu_admin["settings_storage"]=word_lang("file storage");
$submenu_admin_url["settings_storage"]="../storage/";

$submenu_admin["settings_stockapi"]=word_lang("Stock API");
$submenu_admin_url["settings_stockapi"]="../stock_api/";


$submenu_admin["settings_watermark"]=word_lang("watermark");
$submenu_admin_url["settings_watermark"]="../settings/watermark.php";

$submenu_admin["settings_languages"]=word_lang("languages");
$submenu_admin_url["settings_languages"]="../settings/languages.php";

$submenu_admin["settings_countries"]=word_lang("Countries");
$submenu_admin_url["settings_countries"]="../countries/";

$submenu_admin["settings_payments"]=word_lang("payments");
$submenu_admin_url["settings_payments"]="../settings/payments.php";

$submenu_admin["settings_currency"]=word_lang("currency");
$submenu_admin_url["settings_currency"]="../settings/currency.php";

if($global_settings["royalty_free"])
{
	$submenu_admin["settings_licenses"]=word_lang("royalty free")." - ".word_lang("license");
	$submenu_admin_url["settings_licenses"]="../licenses/";

	$submenu_admin["settings_prices"]=word_lang("royalty free")." - ".word_lang("prices");
	$submenu_admin_url["settings_prices"]="../prices/";
}

if($global_settings["rights_managed"])
{
	$submenu_admin["settings_rightsmanaged"]=word_lang("rights managed")." - ".word_lang("license");
	$submenu_admin_url["settings_rightsmanaged"]="../rights_managed/";
}

$submenu_admin["settings_invoices"]=word_lang("Invoices");
$submenu_admin_url["settings_invoices"]="../invoices_settings/";

$submenu_admin["settings_taxes"]=word_lang("taxes");
$submenu_admin_url["settings_taxes"]="../tax/";

$submenu_admin["settings_taxeseu"]=word_lang("EU VAT law compliance");
$submenu_admin_url["settings_taxeseu"]="../tax_eu/";

$submenu_admin["settings_shipping"]=word_lang("shipping");
$submenu_admin_url["settings_shipping"]="../shipping/";

$submenu_admin["settings_checkout"]=word_lang("checkout");
$submenu_admin_url["settings_checkout"]="../checkout/";

$submenu_admin["settings_documents"]=word_lang("Documents types");
$submenu_admin_url["settings_documents"]="../documents_types/";

$submenu_admin["settings_couponstypes"]=word_lang("types of coupons");
$submenu_admin_url["settings_couponstypes"]="../coupons_types/";

$submenu_admin["settings_payout"]=word_lang("refund");
$submenu_admin_url["settings_payout"]="../payout/";

$submenu_admin["settings_signup"]=word_lang("sign up");
$submenu_admin_url["settings_signup"]="../signup/";

$submenu_admin["settings_networks"]=word_lang("social networks");
$submenu_admin_url["settings_networks"]="../networks/";

$submenu_admin["settings_content_types"]=word_lang("content type");
$submenu_admin_url["settings_content_types"]="../content_types/";

if($global_settings["userupload"]==1)
{
	$submenu_admin["settings_sellercategories"]=word_lang("customer categories");
	$submenu_admin_url["settings_sellercategories"]="../seller_categories/";
}

if($global_settings["subscription"]==1)
{
	$submenu_admin["settings_subscription"]=word_lang("subscription");
	$submenu_admin_url["settings_subscription"]="../subscription/";
}

if($global_settings["credits"]==1)
{
	$submenu_admin["settings_creditstypes"]=word_lang("credits");
	$submenu_admin_url["settings_creditstypes"]="../credits_types/";
}

if($global_settings["model"]==1)
{
	$submenu_admin["settings_models"]=word_lang("model property release");
	$submenu_admin_url["settings_models"]="../models/";
}



if($global_settings["prints"]==1)
{
	$submenu_admin["settings_prints"]=word_lang("prints and products");
	$submenu_admin_url["settings_prints"]="../prints_types/";
}



if($global_settings["prints"])
{
	$submenu_admin["settings_productsoptions"]=word_lang("Products options");
	$submenu_admin_url["settings_productsoptions"]="../products_options/";
}

if($global_settings["prints"])
{
	$submenu_admin["settings_pwinty"]=word_lang("pwinty prints service");
	$submenu_admin_url["settings_pwinty"]="../prints_pwinty/";
}

if($global_settings["prints"])
{
	$submenu_admin["settings_fotomoto"]=word_lang("Fotomoto prints service");
	$submenu_admin_url["settings_fotomoto"]="../prints_fotomoto/";
}

if($global_settings["prints"])
{
	$submenu_admin["settings_printful"]=word_lang("Printful prints service");
	$submenu_admin_url["settings_printful"]="../prints_printful/";
}


if($global_settings["allow_video"]==1)
{
	$submenu_admin["settings_video"]=word_lang("video");
	$submenu_admin_url["settings_video"]="../video/";
}

if($global_settings["allow_audio"]==1)
{
	$submenu_admin["settings_audio"]=word_lang("audio");
	$submenu_admin_url["settings_audio"]="../audio/";
}

if($global_settings["allow_video"]==1 or $global_settings["allow_audio"]==1)
{
	$submenu_admin["settings_ffmpeg"]="FFMPEG & Sox";
	$submenu_admin_url["settings_ffmpeg"]="../ffmpeg/";
}

$submenu_admin["settings_phpini"]="PHP.ini";
$submenu_admin_url["settings_phpini"]="../phpini/";

//End. Settings submenu


//Templates submenu

$submenu_admin["templates_skins"]=word_lang("select skin");
$submenu_admin_url["templates_skins"]="../templates/skins.php";

$submenu_admin["templates_templates"]=word_lang("templates");
$submenu_admin_url["templates_templates"]="../templates/";

$submenu_admin["templates_caching"]=word_lang("caching");
$submenu_admin_url["templates_caching"]="../caching/";

$submenu_admin["templates_home"]=word_lang("home page");
$submenu_admin_url["templates_home"]="../home/";

//End. Templates submenu


//Pages submenu

$submenu_admin["pages_textpages"]=word_lang("site info");
$submenu_admin_url["pages_textpages"]="../text_pages/";

$submenu_admin["pages_news"]=word_lang("news");
$submenu_admin_url["pages_news"]="../news/";

//End. Pages submenu

//Affiliates submenu
if($global_settings["affiliates"]==1)
{
	$submenu_admin["affiliates_stats"]=word_lang("stats");
	$submenu_admin_url["affiliates_stats"]="../affiliates/index.php";
	
	$submenu_admin["affiliates_commission"]=word_lang("users earnings");
	$submenu_admin_url["affiliates_commission"]="../affiliates/commission.php";

	$submenu_admin["affiliates_payout"]=word_lang("refund");
	$submenu_admin_url["affiliates_payout"]="../affiliates/payout.php";

	$submenu_admin["affiliates_settings"]=word_lang("settings");
	$submenu_admin_url["affiliates_settings"]="../affiliates/settings.php";
}
//End. Affiliates submenu






?>