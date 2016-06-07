<div class="col-sm-3 col-md-3 isotope-item c{COMPONENT_ID}">
	<div class="image-box style-2 mb-20 shadow bordered light-gray-bg text-center">
		<div class="overlay-container">
			<a href="{URL}"><img src="{IMAGE}" alt="{TITLE}" {LIGHTBOX} class="home_img"></a>
		</div>
		<h3><a href="{URL}">{TITLE}</a></h3>
		<p>{DESCRIPTION}</p>

		{if cartflow2}<a onClick="add_cart_flow({ID},'{SITE_ROOT}')" class="btn btn-default" id="ts_cart{ID}"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> <span class="ts_cart_text{ID}">{lang.Add to cart}</span><span style='display:none' class="ts_cart_text2{ID}">{lang.In your cart}</span> </span> </a>{/if}
	</div>
</div>
