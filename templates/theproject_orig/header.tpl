<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="{SITE_ROOT}images/favicon.gif" type="image/gif" rel="icon">
		<link href="{SITE_ROOT}images/favicon.gif" type="image/gif" rel="shortcut icon">
		<title>{SITE_NAME}</title>
		<meta name="description" content="{DESCRIPTION}">
		<meta name="keywords" content="{KEYWORDS}">
		<meta http-equiv="Content-Type" content="text/html; charset={MTG}">
		{META_SOCIAL}
		{META_NOCACHE}
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Raleway:700,400,300' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" >
		<link href="{TEMPLATE_ROOT}assets/fonts/fontello/css/fontello.css" rel="stylesheet">
		<link href="{TEMPLATE_ROOT}assets/css/animations.css" rel="stylesheet">
		<link href="{TEMPLATE_ROOT}assets/css/style.css" rel="stylesheet" >
		<link href="{TEMPLATE_ROOT}assets/css/skins/light_blue.css" rel="stylesheet">
		<link href="{TEMPLATE_ROOT}style.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
		<script src="{TEMPLATE_ROOT}custom.js" type="text/javascript"></script>
		<script src="{SITE_ROOT}members/JsHttpRequest.js" type="text/javascript"></script>
	</head>
<body class="no-trans front-page transparent-header ">
		<div class="scrollToTop circle"><i class="icon-up-open-big"></i></div>
		<div class="page-wrapper">
			<div class="header-container">
				<div class="header-top dark ">
					<div class="container">
						<div class="row">
							<div class="col-xs-3 col-sm-6 col-md-6">
								<div class="header-top-first clearfix">
									<ul class="social-links circle small clearfix hidden-xs">
										<li class="facebook"><a href="{FACEBOOK}"><i class="fa fa-facebook"></i></a></li>
										<li class="twitter"><a href="{TWITTER}"><i class="fa fa-twitter"></i></a></li>
										<li class="googleplus"><a href="{GOOGLE}"><i class="fa fa-google-plus"></i></a></li>
									</ul>
									<div class="social-links hidden-lg hidden-md hidden-sm circle small">
										<div class="btn-group dropdown">
											<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-share-alt"></i></button>
											<ul class="dropdown-menu dropdown-animation">
												<li class="facebook"><a href="{FACEBOOK}"><i class="fa fa-facebook"></i></a></li>
												<li class="twitter"><a href="{TWITTER}"><i class="fa fa-twitter"></i></a></li>
												<li class="googleplus"><a href="{GOOGLE}"><i class="fa fa-google-plus"></i></a></li>
											</ul>
										</div>
									</div>
									<ul class="list-inline hidden-sm hidden-xs">
										<li><i class="fa fa-phone pr-5 pl-10"></i>{TELEPHONE}</li>
										<li><a href="{SITE_ROOT}members/languages_list.php" title="{LANG_NAME}" class="color_white"><img src="{LANG_IMG}" class="lang_img">{LANG_NAME}</a></li>
									</ul>
								</div>
							</div>
							<div class="col-xs-9 col-sm-6 col-md-6">
								<div id="header-top-second"  class="clearfix">
									{BOX_MEMBERS}
								</div>
							</div>
						</div>
					</div>
				</div>
				<header class="header  fixed   clearfix">
					
					<div class="container">
						<div class="row">
							<div class="col-md-3">
								<div class="header-left clearfix">
									<div id="logo" class="logo">
										<a href="{SITE_ROOT}"><img id="logo" src="{TEMPLATE_ROOT}images/logo.png" alt="{SITE_NAME}"></a>
									</div>
									<div class="site-slogan">
										Professional software for photographers
									</div>									
								</div>
							</div>
							<div class="col-md-9">
								<div class="header-right clearfix">
								<div class="main-navigation  animated with-dropdown-buttons">
									<nav class="navbar navbar-default" role="navigation">
										<div class="container-fluid">
											<div class="navbar-header">
												<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
													<span class="sr-only">Toggle navigation</span>
													<span class="icon-bar"></span>
													<span class="icon-bar"></span>
													<span class="icon-bar"></span>
												</button>												
											</div>
											<div class="collapse navbar-collapse" id="navbar-collapse-1">
												{MENU}											
												<!-- -->
												<div class="header-dropdown-buttons hidden-xs ">
													<div class="btn-group dropdown">
														{BOX_SEARCH}
													</div>
													<div class="btn-group dropdown"  id="cart_desktop"></div>
														{BOX_SHOPPING_CART_LITE}
										  <script>
											cart_word='{lang.Cart}';
											cart_word_checkout='{lang.Checkout}';
											cart_word_view='{lang.View Cart}';
											cart_word_subtotal='{lang.Subtotal}';
											cart_word_total='{lang.Total}';
											cart_word_qty='{lang.Quantity}';
											cart_word_item='{lang.Item}';
											cart_word_delete='{lang.Delete}';
											cart_currency1='{CURRENCY_CODE1}';
											cart_currency2='{CURRENCY_CODE2}';
											site_root='{SITE_ROOT}';
										  </script>
												</div>
											</div>
										</div>
									</nav>
								</div>
								</div>
							</div>
						</div>
					</div>					
				</header>
			</div>
			<div class="breadcrumb-container">
				<div class="container">
					<ol class="breadcrumb">
						<li><i class="fa fa-home pr-10"></i><a href="{SITE_ROOT}">{lang.Home}</a></li>
						{PATH}
					</ol>
				</div>
			</div>
			<div class="container second_page">