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
		<link href="{TEMPLATE_ROOT}assets/plugins/magnific-popup/magnific-popup.css" rel="stylesheet">
		<link href="{TEMPLATE_ROOT}assets/plugins/rs-plugin/css/settings.css" rel="stylesheet">
		<link href="{TEMPLATE_ROOT}assets/css/animations.css" rel="stylesheet">
		<link href="{TEMPLATE_ROOT}assets/plugins/owl-carousel/owl.carousel.css" rel="stylesheet">
		<link href="{TEMPLATE_ROOT}assets/plugins/owl-carousel/owl.transitions.css" rel="stylesheet">
		<link href="{TEMPLATE_ROOT}assets/plugins/hover/hover-min.css" rel="stylesheet">		
		<link href="{TEMPLATE_ROOT}assets/css/style.css" rel="stylesheet" >
		<link href="{TEMPLATE_ROOT}assets/css/skins/blue.css" rel="stylesheet">
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
										<li class="instagram"><a href="{GOOGLE}"><i class="fa fa-vk"></i></a></li>
										<li class="twitter"><a href="{TWITTER}"><i class="fa fa-twitter"></i></a></li>
										<li class="facebook"><a href="{FACEBOOK}"><i class="fa fa-facebook"></i></a></li>
									</ul>
									<div class="social-links hidden-lg hidden-md hidden-sm circle small">
										<div class="btn-group dropdown">
											<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-share-alt"></i></button>
											<ul class="dropdown-menu dropdown-animation">
												<li class="instagram"><a href="{GOOGLE}"><i class="fa fa-vk"></i></a></li>
												<li class="twitter"><a href="{TWITTER}"><i class="fa fa-twitter"></i></a></li>
												<li class="facebook"><a href="{FACEBOOK}"><i class="fa fa-facebook"></i></a></li>
											</ul>
										</div>
									</div>
									<ul class="list-inline hidden-sm hidden-xs">
										<li><i class="fa fa-phone pr-5 pl-10"></i>{TELEPHONE}</li>
										<!--li><a href="{SITE_ROOT}members/languages_list.php" title="{LANG_NAME}" class="color_white"><img src="{LANG_IMG}" class="lang_img">{LANG_NAME}</a></li-->
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
										<a href="{SITE_ROOT}" style="font-size:150%; text-decoration: none"><!--img id="logo" src="{TEMPLATE_ROOT}images/logo.png" alt="{SITE_NAME}"-->  <span class="fa fa-music"></span> MStock.ru</a>
									</div>
									<div class="site-slogan">
										лицензионный медиа сток
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
			<div class="banner clearfix">
				<div class="slideshow">
					<div class="slider-banner-container">
						<div class="slider-banner-fullscreen">
							<ul class="slides">
								<!-- slide 1 start -->
								<li data-transition="random-static" data-slotamount="7" data-masterspeed="500" data-saveperformance="on" data-title="">
								
								<!-- main image -->
								<img src="{TEMPLATE_ROOT}images/slide1.jpg" alt="slidebg1" data-bgposition="center top"  data-bgrepeat="no-repeat" data-bgfit="cover">
								
								<!-- Transparent Background -->
								<div class="tp-caption dark-translucent-bg"
									data-x="center"
									data-y="bottom"
									data-speed="500"
									data-easing="easeOutQuad"
									data-start="0">
								</div>

								<!-- LAYER NR. 1 -->
								<div class="tp-caption sfr stl xlarge_white"
									data-x="center"
									data-y="70"
									data-speed="200"
									data-easing="easeOutQuad"
									data-start="1000"
									data-end="2500"
									data-splitin="chars"
									data-elementdelay="0.1"
									data-endelementdelay="0.1"
									data-splitout="chars">Музыка
								</div>

								<!-- LAYER NR. 2 -->
								<div class="tp-caption sfl str xlarge_white"
									data-x="center"
									data-y="70"
									data-speed="200"
									data-easing="easeOutQuad"
									data-start="2500"
									data-end="4000"
									data-splitin="chars"
									data-elementdelay="0.1"
									data-endelementdelay="0.1"
									data-splitout="chars">Творчество
								</div>

								<!-- LAYER NR. 3 -->
								<div class="tp-caption sfr stt xlarge_white"
									data-x="center"
									data-y="70"
									data-speed="200"
									data-easing="easeOutQuad"
									data-start="4000"
									data-end="6000"
									data-splitin="chars"
									data-elementdelay="0.1"
									data-endelementdelay="0.1"
									data-splitout="chars"
									data-endspeed="400">Успех
								</div>					

								<!-- LAYER NR. 4 -->
								<div class="tp-caption sft fadeout text-center large_white"
									data-x="center"
									data-y="70"
									data-speed="500"
									data-easing="easeOutQuad"
									data-start="6400"
									data-end="10000">Качественная музыка
								</div>	

								<!-- LAYER NR. 5 -->
								<div class="tp-caption sfr fadeout"
									data-x="center"
									data-y="210"
									data-hoffset="-232"
									data-speed="500"
									data-easing="easeOutQuad"
									data-start="1000"
									data-end="5500"><span class="icon large circle"><i class="circle icon-note"></i></span>
								</div>

								<!-- LAYER NR. 6 -->
								<div class="tp-caption sfl fadeout"
									data-x="center"
									data-y="210"
									data-speed="500"
									data-easing="easeOutQuad"
									data-start="2500"
									data-end="5500"><span class="icon large circle"><i class="circle icon-arrows-ccw"></i></span>
								</div>

								<!-- LAYER NR. 7 -->
								<div class="tp-caption sfr fadeout"
									data-x="center"
									data-y="210"
									data-hoffset="232"
									data-speed="500"
									data-easing="easeOutQuad"
									data-start="4000"
									data-end="5500"><span class="icon large circle"><i class="circle icon-chart-line"></i></span>
								</div>

								<!-- LAYER NR. 8 -->
								<div class="tp-caption ZoomIn fadeout text-center tp-resizeme large_white"
									data-x="center"
									data-y="170"
									data-speed="500"
									data-easing="easeOutQuad"
									data-start="6400"
									data-end="10000"><div class="separator light"></div>
								</div>	

								<!-- LAYER NR. 9 -->
								<div class="tp-caption sft fadeout medium_white text-center"
									data-x="center"
									data-y="210"
									data-speed="500"
									data-easing="easeOutQuad"
									data-start="5800"
									data-end="10000"
									data-endspeed="600">Проверка авторских прав
								</div>

								<!-- LAYER NR. 10 -->
								<div class="tp-caption fade fadeout"
									data-x="center"
									data-y="bottom"
									data-voffset="100"
									data-speed="500"
									data-easing="easeOutQuad"
									data-start="2000"
									data-end="10000"
									data-endspeed="200">
									<a href="#page-start" class="btn btn-lg moving smooth-scroll"><i class="icon-down-open-big"></i><i class="icon-down-open-big"></i><i class="icon-down-open-big"></i></a>
								</div>
								</li>
								<!-- slide 1 end -->

								<!-- slide 2 start -->
								<li data-transition="random-static" data-slotamount="7" data-masterspeed="500" data-saveperformance="on" data-title="Premium HTML5 Bootstrap Theme">
								
								<!-- main image -->
								<img src="{TEMPLATE_ROOT}images/slide2.jpg" alt="slidebg2" data-bgposition="center top"  data-bgrepeat="no-repeat" data-bgfit="cover">

								<!-- Transparent Background -->
								<div class="tp-caption dark-translucent-bg"
									data-x="center"
									data-y="bottom"
									data-speed="500"
									data-easing="easeOutQuad"
									data-start="0">
								</div>

								<!-- LAYER NR. 1 -->
								<div class="tp-caption sfb fadeout large_white"
									data-x="left"
									data-y="70"
									data-speed="500"
									data-start="1000"
									data-easing="easeOutQuad"
									data-end="10000">Наш  аудио рынок  лицензионной музыки
								</div>	

								<!-- LAYER NR. 2 -->
								<div class="tp-caption sfb fadeout text-left medium_white"
									data-x="left"
									data-y="200" 
									data-speed="500"
									data-start="1300"
									data-easing="easeOutQuad"
									data-endspeed="600"><span class="icon default-bg circle small hidden-xs"><i class="fa fa-music"></i></span> Регистрация музыкантов
								</div>

								<!-- LAYER NR. 3 -->
								<div class="tp-caption sfb fadeout text-left medium_white"
									data-x="left"
									data-y="260" 
									data-speed="500"
									data-start="1600"
									data-easing="easeOutQuad"
									data-endspeed="600"><span class="icon default-bg circle small hidden-xs"><i class="fa fa-upload"></i></span> Загрузка аудио файлов
								</div>

								<!-- LAYER NR. 4 -->
								<div class="tp-caption sfb fadeout text-left medium_white"
									data-x="left"
									data-y="320" 
									data-speed="500"
									data-start="1900"
									data-easing="easeOutQuad"
									data-endspeed="600"><span class="icon default-bg circle small hidden-xs"><i class="fa fa-rub"></i></span> Достойный заработок
								</div>

								<!-- LAYER NR. 5 -->
								<div class="tp-caption sfb fadeout text-left medium_white"
									data-x="left"
									data-y="380" 
									data-speed="500"
									data-start="2200"
									data-easing="easeOutQuad"
									data-endspeed="600"><span class="icon default-bg circle small hidden-xs"><i class="fa fa-file-audio-o"></i></span> Проверка лицензионных прав
								</div>

								<!-- LAYER NR. 6 -->
								<div class="tp-caption sfb fadeout small_white"
									data-x="left"
									data-y="450"
									data-speed="500"
									data-start="2500"
									data-easing="easeOutQuad"
									data-endspeed="600"><a href="{SITE_ROOT}members/signup.php" class="btn btn-default btn-lg btn-animated">Регистрация <i class="fa fa-user"></i></a>
								</div>
								</li>
								<!-- slide 2 end -->
							</ul>
							<div class="tp-bannertimer"></div>
						</div>
					</div>
				</div>
			</div>

			
			<div id="page-start"></div>
			
			<section class="main-container object-non-visible" data-animation-effect="fadeInUpSmall" data-effect-delay="200">
				<div class="container">
					<div class="row">
						<div class="main col-md-12">
							<h1 class="page-title text-center">Наш аудио сток</h1>
							<div class="separator"></div>
							<p class="lead text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptas nulla suscipit <br> unde rerum mollitia dolorum.</p>
							<div class="filters text-center">
								<ul class="nav nav-pills">
									<li class="active"><a href="#" data-filter=".c18">{lang.Most downloaded}</a></li>
									<li><a href="#" data-filter=".c19">{lang.Featured}</a></li>
									<li><a href="#" data-filter=".c20">{lang.Most popular}</a></li>
									<li><a href="#" data-filter=".c21">{lang.New}</a></li>
									<li><a href="#" data-filter=".c22">{lang.Free}</a></li>
									<li><a href="#" data-filter=".c23">{lang.Random}</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="gray-bg section">
					<div class="container">
						<div class="isotope-container row grid-space-10">
							{COMPONENT_18}{COMPONENT_19}{COMPONENT_20}{COMPONENT_21}{COMPONENT_22}{COMPONENT_23}
						</div>
					</div>
				</div>
			</section>



			<!-- section start -->
			<!-- ================ -->
			<section class="section default-bg clearfix">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="call-to-action text-center">
								<div class="row">
									<div class="col-sm-8">
										<h1 class="title">Don't Miss Out Our Offers</h1>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem quasi explicabo consequatur consectetur, a atque voluptate officiis eligendi nostrum.</p>
									</div>
									<div class="col-sm-4">
										<br>
										<p><a href="#" class="btn btn-lg btn-gray-transparent btn-animated">Learn More<i class="fa fa-arrow-right pl-20"></i></a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			
			
				<!-- section start -->
			<!-- ================ -->
			<section class="light-gray-bg pv-30 clearfix">
				<div class="container">
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<h2 class="text-center">Core <strong>Features</strong></h2>
							<div class="separator"></div>
							<p class="large text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam voluptas facere vero ex tempora saepe perspiciatis ducimus sequi animi.</p>
						</div>
						<div class="col-md-4 ">
							<div class="pv-30 ph-20 feature-box bordered shadow text-center object-non-visible" data-animation-effect="fadeInDownSmall" data-effect-delay="100">
								<span class="icon default-bg circle"><i class="fa fa-diamond"></i></span>
								<h3>Clean Code & Design</h3>
								<div class="separator clearfix"></div>
								<p>Voluptatem ad provident non repudiandae beatae cupiditate amet reiciendis lorem ipsum dolor sit amet, consectetur.</p>
								<a href="page-services.html">Read More <i class="pl-5 fa fa-angle-double-right"></i></a>
							</div>
						</div>
						<div class="col-md-4 ">
							<div class="pv-30 ph-20 feature-box bordered shadow text-center object-non-visible" data-animation-effect="fadeInDownSmall" data-effect-delay="150">
								<span class="icon default-bg circle"><i class="fa fa-connectdevelop"></i></span>
								<h3>Extremely Flexible</h3>
								<div class="separator clearfix"></div>
								<p>Iure sequi unde hic. Sapiente quaerat sequi inventore veritatis cumque lorem ipsum dolor sit amet, consectetur.</p>
								<a href="page-services.html">Read More <i class="pl-5 fa fa-angle-double-right"></i></a>
							</div>
						</div>
						<div class="col-md-4 ">
							<div class="pv-30 ph-20 feature-box bordered shadow text-center object-non-visible" data-animation-effect="fadeInDownSmall" data-effect-delay="200">
								<span class="icon default-bg circle"><i class="fa icon-snow"></i></span>
								<h3>Latest Technologies</h3>
								<div class="separator clearfix"></div>
								<p>Inventore dolores aut laboriosam cum consequuntur delectus sequi lorem ipsum dolor sit amet, consectetur.</p>
								<a href="page-services.html">Read More <i class="pl-5 fa fa-angle-double-right"></i></a>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- section end -->		
			
			
			<section class="pv-40 stats padding-bottom-clear dark-translucent-bg hovered background-img-7" style="background-position: 50% 50%;">
				<div class="clearfix">
					<div class="col-md-3 col-xs-6 text-center">
						<div class="feature-box object-non-visible" data-animation-effect="fadeIn" data-effect-delay="300">
							<span class="icon dark-bg large circle"><i class="fa fa-photo"></i></span>
							<h3><strong>{lang.Photos}</strong></h3>
							<span class="counter" data-to="{PHOTOS}" data-speed="5000">0</span>
						</div>
					</div>
					<div class="col-md-3 col-xs-6 text-center">
						<div class="feature-box object-non-visible" data-animation-effect="fadeIn" data-effect-delay="300">
							<span class="icon dark-bg large circle"><i class="fa fa-video-camera"></i></span>
							<h3><strong>{lang.Videos}</strong></h3>
							<span class="counter" data-to="{VIDEOS}" data-speed="5000">0</span>
						</div>
					</div>
					<div class="col-md-3 col-xs-6 text-center">
						<div class="feature-box object-non-visible" data-animation-effect="fadeIn" data-effect-delay="300">
							<span class="icon dark-bg large circle"><i class="fa fa-music"></i></span>
							<h3><strong>{lang.Audio}</strong></h3>
							<span class="counter" data-to="{AUDIO}" data-speed="5000">0</span>
						</div>
					</div>
					<div class="col-md-3 col-xs-6 text-center">
						<div class="feature-box object-non-visible" data-animation-effect="fadeIn" data-effect-delay="300">
							<span class="icon dark-bg large circle"><i class="fa fa-cubes"></i></span>
							<h3><strong>{lang.Vector}</strong></h3>
							<span class="counter" data-to="{VECTOR}" data-speed="5000">0</span>
						</div>
					</div>
				</div>
				<div class="footer-top animated-text" style="background-color:rgba(0,0,0,0.3);">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="call-to-action text-center">
									<div class="row">
										<div class="col-sm-8">
											<h2>Stock photos, royalty-free images</h2>
											<h2>Perfect stock for you</h2>
										</div>
										<div class="col-sm-4">
											<p class="mt-10"><a href="{SITE_ROOT}index.php?search=" class="btn btn-animated btn-lg btn-gray-transparent">{lang.Purchase}<i class="fa fa-cart-arrow-down pl-20"></i></a></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<footer id="footer" class="clearfix ">
				<div class="footer">
					<div class="container">
						<div class="footer-inner">
							<div class="row">
								<div class="col-md-3">
									<div class="footer-content">
										<h2 class="title">{lang.Media Stock}</h2>
										<div class="separator-2"></div>
										<nav>
										<ul class="nav nav-pills nav-stacked">
											{if sitephoto}<li><a href="{SITE_ROOT}index.php?sphoto=1">{lang.Photos}</a></li>{/if}
											{if sitevideo}<li><a href="{SITE_ROOT}index.php?svideo=1">{lang.Video}</a></li>{/if}
											{if siteaudio}<li><a href="{SITE_ROOT}index.php?saudio=1">{lang.Audio}</a></li>{/if}
											{if sitevector}<li><a href="{SITE_ROOT}index.php?svector=1">{lang.Vector}</a></li>{/if}
											<li><a href="{SITE_ROOT}members/categories.php">{lang.Categories}</a></li>
										</ul>
									</nav>
									</div>
								</div>
								<div class="col-md-3">
									<div class="footer-content">
										<h2 class="title">{lang.Customers}</h2>
										<div class="separator-2"></div>
										<nav>
										<ul class="nav nav-pills nav-stacked">
           								 <li><a href="{SITE_ROOT}members/users_list.php">{lang.Users}</a></li>
										{if sitecredits}<li><a href="{SITE_ROOT}members/credits.php">{lang.Buy Credits}</a></li>{/if}
										{if sitesubscription}<li><a href="{SITE_ROOT}members/subscription.php">{lang.Buy Subscription}</a></li>{/if}
          								</ul>
          							</nav>
									</div>
								</div>
								<div class="col-md-3">
									<div class="footer-content">
										<h2 class="title">{lang.Stats}</h2>
										<div class="separator-2"></div>
										<nav>
										<ul class="nav nav-pills nav-stacked">
           								 {BOX_STAT}
          								</ul>
          							</nav>
									</div>
								</div>
								<div class="col-md-3">
									<div class="footer-content">
										<h2 class="title">{lang.Support}</h2>
										<div class="separator-2"></div>
										<p>На нашем лицензионном аудио рынке мы разбираемся во всех тонкостях и нюансах, помогая клиентам на нашем языке. Звоните и пишите!</p>
										<ul class="social-links circle animated-effect-1">
											<li class="instagram"><a target="_blank" href="{GOOGLE}"><i class="fa fa-vk"></i></a></li>
											<li class="twitter"><a target="_blank" href="{TWITTER}"><i class="fa fa-twitter"></i></a></li>
											<li class="facebook"><a target="_blank" href="{FACEBOOK}"><i class="fa fa-facebook"></i></a></li>
										</ul>
										<div class="separator-2"></div>
										<ul class="list-icons">
											<li><i class="fa fa-phone pr-10 text-default"></i> {TELEPHONE}</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="subfooter">
					<div class="container">
						<div class="subfooter-inner">
							<div class="row">
								<div class="col-md-12">
									<p class="text-center">Copyright © 2016 <a href="http://www.mstock.ru/">MStock.ru - лицензионный медиа сток</a> - {lang.All rights reserved}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</footer>		
		</div>
		<script>check_carts('');</script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/modernizr.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/isotope/isotope.pkgd.min.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/waypoints/jquery.waypoints.min.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/jquery.countTo.js"></script>
		<script src="{TEMPLATE_ROOT}assets/plugins/jquery.parallax-1.1.3.js"></script>
		<script src="{TEMPLATE_ROOT}assets/plugins/jquery.validate.js"></script>
		<script src="{TEMPLATE_ROOT}assets/plugins/vide/jquery.vide.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/owl-carousel/owl.carousel.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/jquery.browser.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/SmoothScroll.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/js/template.js"></script>
		<script src="{SITE_ROOT}inc/jquery.colorbox-min.js" type="text/javascript"></script>
	</body>
</html>