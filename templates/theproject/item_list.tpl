<div class="item_list">
	<div  class="item_list_img">
		<div  class="item_list_img2">
			<a href="{ITEM_URL}"><img src="{ITEM_IMG}" border="0" {ITEM_LIGHTBOX} class="preview_listing"></a>
		</div>
	</div>
	<div class="item_list_text">
		<div>
			<a href="{ITEM_URL}"><i class="fa {CLASS2}"> </i> {ITEM_TITLE}</a>
		</div>
		<div id='cart{ITEM_ID}'>
			{if cart}
				<a href="javascript:{CART_FUNCTION}_cart({ITEM_ID});"  class="ac{CART_CLASS}">{ADD_TO_CART}</a>
			{/if}
		</div>
		<div class="iviewed">
			<i class="glyphicon glyphicon-eye-open"> </i> {ITEM_VIEWED}
		</div>
		<div class="idownloaded">
			<i class="glyphicon glyphicon-download"> </i> {DOWNLOADS}
		</div>
	</div>
</div>

