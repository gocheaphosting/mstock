									<div class="header-top-dropdown text-right">
										<div class="btn-group">
											<a href="{SITE_ROOT}members/signup.php" class="btn btn-default btn-sm"><i class="fa fa-user pr-10"></i> {lang.Sign up}</a>
										</div>
										{if seller}
										<div class="btn-group">
											<a href="{SITE_ROOT}members/signup.php?utype=seller&step=1" class="btn btn-default btn-sm"><i class="fa fa-user pr-10"></i> {lang.Become a seller}</a>
										</div>
										{/if}
										{if affiliate}
										<div class="btn-group">
											<a href="{SITE_ROOT}members/signup.php?utype=affiliate&step=1" class="btn btn-default btn-sm"><i class="fa fa-user pr-10"></i> {lang.Become an affiliate}</a>
										</div>
										{/if}
										<div class="btn-group dropdown">
											<button type="button" class="btn dropdown-toggle btn-default btn-sm" data-toggle="dropdown"><i class="fa fa-lock pr-10"></i> {lang.Login}</button>
											<ul class="dropdown-menu dropdown-menu-right dropdown-animation">
												<li>
													<form class="login-form margin-clear" method="post" action="{SITE_ROOT}members/check.php">
														<div class="form-group has-feedback">
															<label class="control-label">{lang.Username}</label>
															<input name="l"  type="text" class="form-control" placeholder="">
															<i class="fa fa-user form-control-feedback"></i>
														</div>
														<div class="form-group has-feedback">
															<label class="control-label">{lang.Password}</label>
															<input name="p" type="password" class="form-control" placeholder="">
															<i class="fa fa-lock form-control-feedback"></i>
														</div>
														<button type="submit" class="btn btn-gray btn-sm">{lang.Login}</button>
														<ul>
															<li><a href="{SITE_ROOT}members/forgot.php">{lang.Forgot password}?</a></li>
														</ul>
														<span class="text-center">{lang.Login without signup}</span>
														<ul class="social-links circle small colored clearfix">
															{if facebook}
																<li class="facebook"><a href="{SITE_ROOT}members/check_facebook.php"><i class="fa fa-facebook"></i></a></li>
															{/if}
      														{if twitter}
																<li class="twitter"><a href="{SITE_ROOT}members/check_twitter.php"><i class="fa fa-twitter"></i></a></li>
															{/if}
      														{if instagram}
																<!--=li class="instagram"><a href="{SITE_ROOT}members/check_instagram.php"><i class="fa fa-instagram"></i></a></li-->
															{/if}
															{if vk}
																<li class="instagram"><a href="{SITE_ROOT}members/check_vk.php"><i class="fa fa-vk"></i></a></li>
															{/if}
														</ul>
													</form>
												</li>
											</ul>
										</div>
									</div>
