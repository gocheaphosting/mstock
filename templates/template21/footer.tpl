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