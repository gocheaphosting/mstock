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
	
<div id="logo" class="hidden-phone hidden-tablet">
	<a href="{SITE_ROOT}"><img src="{TEMPLATE_ROOT}images/logo.png" alt="{SITE_NAME}"></a>
</div>

<div id="box_cart" class="hidden-phone hidden-tablet">
	{BOX_SHOPPING_CART_LITE}
</div>

<div id="box_languages" class="hidden-phone hidden-tablet">
	{LANGUAGES_LITE2}
</div>

<div id="logo_mobile" class="visible-phone visible-tablet">
	<a href="{SITE_ROOT}"><img src="{TEMPLATE_ROOT}images/logo_second.png" alt="{SITE_NAME}"></a>
	<div id="box_search_mobile">
		<form method='get' action='{SITE_ROOT}index.php'>
			<div class="input-append">
				<input class="span2" name='search' id="appendedInputButton" size="20" type="text"><button class="btn" type="submit">{lang.Search}</button>
			</div>
		</form>
	</div>
	<div id="box_members_mobile">
		{BOX_MEMBERS}
	</div>
</div>

<div id="slide_box" class="hidden-phone hidden-tablet">
    <h1>Join Us Today</h1>

	<p>Photo Video Store is a media stock site and photographers community. Every user has different communuty tools: personal blog, messages, reviews, testimonials, friends, avatars.</p>

	<p>Search for royalty-free stock photography, vector illustrations, stock video footage and audio files. Buy stock with Photo Video  Store by Credits or get a Subscription.</p>
					
	<div class="row-fluid">
		{if noauth}
		<div class="span6">
			<input type="button" value="{lang.Login}" class="home_btn1 lbox">
		</div>
		<div class="span6">
			<input type="button" value="{lang.Sign Up}" class="home_btn2" onClick="location.href='{SITE_ROOT}members/signup.php'">
		</div>
		{/if}
		{if auth}
		<div class="span6">
			<input type="button" value="{lang.My profile}" class="home_btn1" onClick="location.href='{SITE_ROOT}members/profile_about.php'">
		</div>
		<div class="span6">
			<input type="button" value="{lang.Logout}" class="home_btn2" onClick="location.href='{SITE_ROOT}members/logout.php'">
		</div>
		{/if}
	</div>
</div>



<script src="{SITE_ROOT}inc/js/superslides/jquery.easing.1.3.js"></script>
<script src="{SITE_ROOT}inc/js/superslides/jquery.hammer.min.js"></script>
<script src="{SITE_ROOT}inc/js/superslides/jquery.superslides.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{TEMPLATE_ROOT}custom_home.js"></script>	
	
<div id="wrapper_slideshow" class="hidden-phone hidden-tablet">	
	<div id="slides">
    	<ul class="slides-container">
      		<li>
        		<img src="{SITE_ROOT}images/newslide1.jpg" alt="">
      		</li>
      		<li>
       			 <img src="{SITE_ROOT}images/newslide2.jpg" alt="">
      		</li>
    	</ul>
    	<div class="slides-navigation">
      	<a href="#" class="next1">
        	<img src="{TEMPLATE_ROOT}images/nav_next.png">
      	</a>
     	 <a href="#" class="prev1">
        	<img src="{TEMPLATE_ROOT}images/nav_previous.png">
      	</a>
    	</div>
  	</div>
</div>	



<div id="home_content">
	<div class="row-fluid">
		<div class="span4">
			<h2 class="header1">{lang.About us}</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod <a href="">tempor incididunt ut labore</a> et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.  Duis aute irure dolor in reprehenderit in <a href="">voluptate velit esse</a> cillum dolore.</p>
		</div>
		<div class="span4">
			<h2 class="header2">Royalty-Free Photos</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, <a href="">quis nostrud exercitation</a> ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit.</p>
		</div>
		<div class="span4">
			<h2 class="header3">Prices</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor. Ut enim ad minim veniam, <a href="">quis nostrud exercitation</a>.</p>
			<ul>
				<li><div>S</div><span>$1</span></li>
				<li><div>M</div><span>$2</span></li>
				<li><div>L</div><span>$3</span></li>
				<li><div>XL</div><span>$4</span></li>
				<li class="xxl"><div>XXL</div><span>$5</span></li>
			</ul>
		</div>
	</div>
		
</div>

<div id="home_content2">
	{BOX_TAG_CLOUDS}
</div>

<div id="home_content4">
<div class="row-fluid">
		<div class="span6 home_categories">
			<h2 class="hcategories">{lang.Categories}</h2>
			
			{BOX_CATEGORIES}
			<div style="clear:both;"></div>
		</div>
		<div class="span6" style="padding:10px;">
			<h2 class="hnews">{lang.News}</h2>
			{BOX_NEWS}
		</div>
	</div>
</div>



<div id="home_content3">
			<script src="{SITE_ROOT}inc/jquery.masonry.min.js"></script>
		<script src="{TEMPLATE_ROOT}custom_home.js"></script>
			
		<div id="tabs">
			<ul>
				<li id="menu_downloaded" class="tact"><a href="javascript:zcomponent(18,'downloaded',1);"><span>{lang.Most downloaded}</span></a></li>
				<li id="menu_featured" class="tact2"><a href="javascript:zcomponent(19,'featured',1);"><span>{lang.Featured}</span></a></li>
				<li id="menu_popular" class="tact2"><a href="javascript:zcomponent(20,'popular',1);"><span>{lang.Most popular}</span></a></li>
				<li id="menu_new" class="tact2"><a href="javascript:zcomponent(21,'new',1);"><span>{lang.New}</span></a></li>
				<li id="menu_free" class="tact2"><a href="javascript:zcomponent(22,'free',1);"><span>{lang.Free}</span></a></li>
				<li id="menu_random" class="tact2"><a href="javascript:zcomponent(23,'random',1);"><span>{lang.Random}</span></a></li>
			</ul>
		</div>
		
		<div id="home_boxes">		
		</div>
	
		<script>
			zcomponent(18,'downloaded',1);
		</script>

</div>




<div id="footer">
	<div id="footer_content">
		<div id="footer1">
			<div class="hidden-phone">
				<h6>{lang.Media Stock}</h6>
				<ul>
					<li><a href="{SITE_ROOT}">{lang.Home}</a></li>
					{if sitephoto}<li><a href="{SITE_ROOT}index.php?sphoto=1">{lang.Photos}</a></li>{/if}
					{if sitevideo}<li><a href="{SITE_ROOT}index.php?svideo=1">{lang.Video}</a></li>{/if}
					{if siteaudio}<li><a href="{SITE_ROOT}index.php?saudio=1">{lang.Audio}</a></li>{/if}
					{if sitevector}<li><a href="{SITE_ROOT}index.php?svector=1">{lang.Vector}</a></li>{/if}
					<li><a href="{SITE_ROOT}members/categories.php">{lang.Categories}</a></li>
				</ul>
			</div>
			<div id="box_stat2"  class="visible-phone">
				{BOX_STAT}
			</div>
		</div>
		<div id="footer2" class="hidden-phone">
			<h6>{lang.Customers}</h6>
			<ul>
				<li><a href="{SITE_ROOT}members/users_list.php">{lang.Users}</a></li>
				{if sitecredits}<li><a href="{SITE_ROOT}members/credits.php">{lang.Buy Credits}</a></li>{/if}
				{if sitesubscription}<li><a href="{SITE_ROOT}members/subscription.php">{lang.Buy Subscription}</a></li>{/if}
			</ul>
		</div>
		<div id="footer3" class="hidden-phone hidden-tablet">
			<h6>{lang.Site Info}</h6>
			<ul>
				<li><a href="{SITE_ROOT}pages/about.html">{lang.About}</a></li>
				<li><a href="{SITE_ROOT}pages/support.html">{lang.Support}</a></li>
				<li><a href="{SITE_ROOT}pages/privacy-policy.html">{lang.Privacy Policy}</a></li>
				<li><a href="{SITE_ROOT}pages/faq.html">{lang.FAQ}</a></li>
				<li><a href="{SITE_ROOT}news/">{lang.News}</a></li>
			</ul>
		</div>
		<div id="footer4" class="hidden-phone hidden-tablet">
			<h6>{lang.Support}</h6>
			<ul>
				<li><a href="{SITE_ROOT}contacts/">{lang.Contacts}</a></li>
			</ul>
		</div>
		<div id="footer5" class="hidden-phone hidden-tablet">{TELEPHONE}</div>
		<div id="footer6" class="hidden-phone">
			<div id="box_stat">
				{BOX_STAT}
			</div>
		</div>
		<div id="footer7">Copyright &copy; 2013 <a href="http://www.cmsaccount.com/">Photo Video Store</a><span class="hidden-phone"> - {lang.All rights reserved}</span></div>
		<div id="footer8" class="hidden-phone">
			<div id="box_social">
				<ul>
					<li class="facebook" onClick="location.href='{FACEBOOK}'"></li>
					<li class="google" onClick="location.href='{GOOGLE}'"></li>
					<li class="twitter" onClick="location.href='{TWITTER}'"></li>
				</ul>
			</div>
		</div>
	</div>
</div>
</div>
<div id="scroll_box" class="hidden-phone hidden-tablet"></div>
<script src="{SITE_ROOT}inc/jquery.scrollTo-1.4.2-min.js"></script>
</body>
</html>