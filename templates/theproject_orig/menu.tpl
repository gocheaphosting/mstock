
												<ul class="nav navbar-nav">
													<li class="dropdown mega-menu">
														<a href="#" class="dropdown-toggle" data-toggle="dropdown">{lang.Files}</a>
														<ul class="dropdown-menu">
															<li>

              												<div class="row">
              													<div class="col-lg-3  col-sm-3 col-md-3">
              													<h4 class="hidden-xs">{lang.Stock}</h4>
              													<ul class="menu">
																  {if sitephoto}
																	<li><a href='{SITE_ROOT}index.php?sphoto=1'> {lang.Photo}</a></li>
																  {/if}
																  {if sitevideo}
																	<li><a href='{SITE_ROOT}index.php?svideo=1'> {lang.Video}</a></li>
																  {/if}
																  {if siteaudio}
																	<li><a href='{SITE_ROOT}index.php?saudio=1'> {lang.Audio}</a></li>
																  {/if}
																  {if sitevector}
																	<li><a href='{SITE_ROOT}index.php?svector=1'> {lang.Vector}</a></li>
																  {/if}
																  </ul>
																  </div>
																  {if prints}
																  <div class="col-lg-3  col-sm-3 col-md-3">
																  <h4 class="hidden-xs">{lang.Prints and Products}</h4>
																  <ul class="menu">
																	{PRINTS_LIST}
																  </ul>
																  </div>
																  {/if}
																  <div class="col-lg-3  col-sm-3 col-md-3">
																  <h4 class="hidden-xs">{lang.Licenses}</h4>
																  <ul class="menu">				
																	<li><a href='{SITE_ROOT}index.php?royalty_free=1'>{lang.Royalty free}</a></li>
																	<li><a href='{SITE_ROOT}index.php?rights_managed=1'>{lang.Rights managed}</a></li>
																	<li><a href='{SITE_ROOT}index.php?creative=on'>{lang.Creative}</a></li>
																	<li><a href='{SITE_ROOT}index.php?editorial=on'>{lang.Editorial}</a></li>
																  </ul>
																  </div>
																  <div class="col-lg-3  col-sm-3 col-md-3">
																  <h4 class="hidden-xs">{lang.sort by}</h4>
																  <ul class="menu">
																	<li><a href='{SITE_ROOT}index.php?vd=downloaded'>{lang.Most downloaded}</a></li>
																				<li><a href='{SITE_ROOT}index.php?c=featured'>{lang.Featured}</a></li>
																				<li><a href='{SITE_ROOT}index.php?vd=popular'>{lang.Most popular}</a></li>
																				<li><a href='{SITE_ROOT}index.php?vd=date'>{lang.New}</a></li>
																				<li><a href='{SITE_ROOT}index.php?c=free'>{lang.Free}</a></li>
													
																  </ul>
																  </div>
															</div>
															</li>
														</ul>
													</li>
													<li class="dropdown mega-menu">
														<a href="{SITE_ROOT}members/categories.php" class="dropdown-toggle" data-toggle="dropdown">{lang.Categories}</a>
														<ul class="dropdown-menu">
															<li>
																  <h4 class="hidden-xs" style="margin-left:15px"> {lang.Browse categories} </h4>
															  
																  <div class="col-lg-2  col-sm-2 col-md-2">
																  	<ul class="menu">
																	{CATEGORY_LIST_1_3}
																  	</ul>
																  </div>
																  <div class="col-lg-2  col-sm-2 col-md-2">
																  	<ul class="menu">
																	{CATEGORY_LIST_2_3}
																  	</ul>
																  </div>
																  <div class="col-lg-2  col-sm-2 col-md-2">
																  	<ul class="menu">
																	{CATEGORY_LIST_3_3}
																  	</ul>
																  </div>
																  <div class="col-lg-3  col-sm-3 col-md-3  col-xs-6">
																	<a class="newProductMenuBlock" href="{CATEGORY_FEATURED_URL_0}"> <img class="img-responsive" src="{CATEGORY_FEATURED_PHOTO_0}" alt="product"> <span class="ProductMenuCaption"> {CATEGORY_FEATURED_0} </span> </a>
																  </div>
																  <div class="col-lg-3  col-sm-3 col-md-3  col-xs-6">
																	<a class="newProductMenuBlock" href="{CATEGORY_FEATURED_URL_1}"> <img class="img-responsive" src="{CATEGORY_FEATURED_PHOTO_1}" alt="product"> <span class="ProductMenuCaption"> {CATEGORY_FEATURED_1} </span> </a>
																  </div>
															</li>
														</ul>
													</li>
													<li class="dropdown mega-menu">
														<a href="#" class="dropdown-toggle" data-toggle="dropdown">{lang.Site info}</a>
														<ul class="dropdown-menu">
															<li>
																<div class="col-lg-4  col-sm-4 col-md-4">
																	<h4 class="hidden-xs">{lang.Site info}</h4>
																	<ul class="menu">
																		{SITE_INFO_LINKS}
																  	</ul>
																  </div>
																  <div class="col-lg-4  col-sm-4 col-md-4">
																  	<h4 class="hidden-xs">{lang.Customers}</h4>
																  	<ul class="menu">
																		<li><a href="{SITE_ROOT}members/users_list.php">{lang.Users}</a></li>
																		{if sitecredits}<li><a href="{SITE_ROOT}members/credits.php">{lang.Credits}</a></li>{/if}
																		{if sitesubscription}<li><a href="{SITE_ROOT}members/subscription.php">{lang.Subscription}</a></li>{/if}
																  	</ul>
																  </div>
																  <div class="col-lg-4  col-sm-4 col-md-4">
																  	<h4 class="hidden-xs">{lang.Photographers}</h4>
																  	<ul class="menu">
																		{BOX_PHOTOGRAPHERS}
																  	</ul>
																  </div>
															</li>
														</ul>
													</li>
												</ulÞ

