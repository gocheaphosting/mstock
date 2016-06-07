<div class="home_box col1">
	<div class="t">	
		<a href="{URL}">
			<img src="{IMAGE}" alt="{TITLE}" class="home_preview" {WIDTH} {HEIGHT} {LIGHTBOX} style='{WIDTH2};{HEIGHT2}'></a>
				<ul>
					{if cartflow}<li id="hb_cart{ID}" class="hb_cart" title="{lang.Add to Cart}"  onClick="add_cart_flow({ID},'{SITE_ROOT}')"></li>{/if}
					<li id="hb_lightbox{ID}" class="hb_lightbox" title="{lang.add to favorite list}" onClick="show_lightbox({ID},'{SITE_ROOT}')"></li>
				</ul>
		<span><a href="{URL}">{TITLE}</a></span>
		{DESCRIPTION}
	</div>
</div>

