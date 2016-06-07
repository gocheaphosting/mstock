<div class="home_box col1">
	<div class="t">	
		<a href="{ITEM_URL}">
			<img src="{ITEM_IMG2}" alt="{ITEM_TITLE_FULL}" class="home_preview" {WIDTH} {HEIGHT} {ITEM_LIGHTBOX} style='{WIDTH2};{HEIGHT2}'></a>
				<ul>
					{if cartflow}<li id="hb_cart{ITEM_ID}" class="hb_cart" title="{lang.Add to Cart}" onClick="add_cart_flow({ITEM_ID},'{SITE_ROOT}')"></li>{/if}
					<li id="hb_lightbox{ITEM_ID}" class="hb_lightbox" title="{lang.add to favorite list}" onClick="show_lightbox({ITEM_ID},'{SITE_ROOT}')"></li>
				</ul>
		<span><a href="{ITEM_URL}">{ITEM_TITLE_FULL}</a></span>
		{ITEM_DESCRIPTION}
	</div>
</div>
