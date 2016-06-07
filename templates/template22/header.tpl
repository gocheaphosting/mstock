<!DOCTYPE html>
<html>
<head>
<title>{SITE_NAME}</title>
<link rel="stylesheet" type="text/css" href="{SITE_ROOT}inc/bootstrap/css/bootstrap.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="{SITE_ROOT}inc/bootstrap/css/bootstrap-responsive.css">
<link rel="stylesheet" type="text/css" href="{TEMPLATE_ROOT}style.css{RAND_NOCACHE}">
<script language="javascript" src="{SITE_ROOT}inc/scripts.js"></script>
<script src="{SITE_ROOT}inc/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="{SITE_ROOT}inc/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script src="{SITE_ROOT}inc/jquery.colorbox-min.js" type="text/javascript"></script>
<script src="{SITE_ROOT}members/JsHttpRequest.js" type="text/javascript"></script>
<script src="{TEMPLATE_ROOT}custom.js" type="text/javascript"></script>
<script language="javascript" src="{SITE_ROOT}inc/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
      <script src="{SITE_ROOT}inc/bootstrap/js/html5shiv.js"></script>
<![endif]-->
<meta name="description" content="{DESCRIPTION}">
<meta name="keywords" content="{KEYWORDS}">
<meta http-equiv="Content-Type" content="text/html; charset={MTG}">
{META_SOCIAL}
{META_NOCACHE}
<link href="{SITE_ROOT}images/favicon.gif" type="image/gif" rel="icon">
<link href="{SITE_ROOT}images/favicon.gif" type="image/gif" rel="shortcut icon">
</head>
<body>

<div id="wrapper_menu" class="content-fluid">
	<div class="container-fluid">
		<div class="span10 clear_padding">
			<div class="hidden-phone hidden-tablet">
				{HORIZONTAL_MENU}
			</div>
			<div id="menu_mobile" class="visible-phone visible-tablet">
				<ul>
    				<li class="home_link"><a href="{SITE_ROOT}members/shopping_cart.php">{lang.Shopping cart}</a></li>
    				<li><a href="{SITE_ROOT}members/categories.php">{lang.Categories}</a></li>
    				<li><a href="{SITE_ROOT}members/languages_list.php">{lang.Languages}</a></li>
    			</ul>
			</div>
		</div>
		<div class="span2 clear_padding">
			<div id="box_search" class="hidden-phone hidden-tablet">
				{BOX_SEARCH}
			</div>
		</div>
	</div>
</div>








<div id="header_second">
	<div id="header_second2"></div>
	<div id="box_members">
		{BOX_MEMBERS}
	</div>
</div>


	
<div id="logo">
	<a href="{SITE_ROOT}"><img src="{TEMPLATE_ROOT}images/logo_second.png" alt="{SITE_NAME}"></a>
	<div id="box_search_mobile2" class="visible-phone visible-tablet">
		<form method='get' action='{SITE_ROOT}index.php'>
			<div class="input-append">
				<input class="span2" name='search' id="appendedInputButton" size="20" type="text"><button class="btn" type="submit">{lang.Search}</button>
			</div>
		</form>
	</div>
</div>

<div id="box_cart2" class="hidden-phone hidden-tablet">
	{BOX_SHOPPING_CART_LITE}
</div>

<div id="box_languages2" class="hidden-phone hidden-tablet">
	{LANGUAGES_LITE2}
</div>






	<div class="body_content">