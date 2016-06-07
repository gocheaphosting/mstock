</div>
			<div class="dark-bg  default-hovered footer-top animated-text">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="call-to-action text-center">
								<div class="row">
									<div class="col-sm-8">
										<h2>Медиа сток с отличной лицензионной музыкой</h2>
										<h2>Отличный выбор для профессионалов!</h2>
									</div>
									<div class="col-sm-4">
										<p class="mt-10"><a href="{SITE_ROOT}index.php?search=" class="btn btn-animated btn-lg btn-gray-transparent ">Купить<i class="fa fa-cart-arrow-down pl-20"></i></a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
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
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/jquery.browser.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/plugins/SmoothScroll.js"></script>
		<script type="text/javascript" src="{TEMPLATE_ROOT}assets/js/template_second.js"></script>
		<script src="{SITE_ROOT}inc/jquery.colorbox-min.js" type="text/javascript"></script>
	</body>
</html>
