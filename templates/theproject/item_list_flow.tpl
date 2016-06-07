
<div class="listing-item white-bg bordered mb-20 home_box">
	<div class="overlay-container">
		<a href="{ITEM_URL}"><img src="{ITEM_IMG2}" alt="{ITEM_TITLE_FULL}" class="home_preview" {WIDTH} {HEIGHT} {ITEM_LIGHTBOX} style='{WIDTH2};{HEIGHT2}'></a>
		{if new}<span class="badge">{lang.New}</span>{/if}
		{if featured}<span class="badge">{lang.Featured}</span>{/if}
	</div>
	<div class="body">
		<a href="javascript:show_lightbox({ITEM_ID},'{SITE_ROOT}')" title="{lang.add to favorite list}"  class="btn btn-lg" style="margin:-10px -30px 0px 0px;float:right"><i class="fa fa-heart-o"></i></a>
		<h3><a href="{ITEM_URL}">{ITEM_TITLE_FULL}</a></h3>
		<p class="small">{ITEM_DESCRIPTION}</p>
		<div class="elements-list clearfix">
			{if cartflow2}<a href="javascript:add_cart_flow({ITEM_ID},'{SITE_ROOT}')" title="{lang.Add to Cart}" id="ts_cart{ITEM_ID}" class="pull-left margin-clear btn btn-sm btn-default btn-animated"><span class="ts_cart_text{ITEM_ID}">{lang.Add to cart}</span><i class="fa fa-shopping-cart"></i><span style='display:none' class="ts_cart_text2{ITEM_ID}">{lang.In your cart}</span></a>{/if}
			
		</div>
	</div>
</div>
