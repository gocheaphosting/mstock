<div class="item_list">
	<div  class="item_list_img">
		<div  class="item_list_img2">
			<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2">
				<a href="{ITEM_URL}"><img src="{ITEM_IMG}" border="0" {ITEM_LIGHTBOX} class="preview_listing"></a>
			</div></div></div></div></div></div></div></div>
		</div>
	</div>
	<div  class="item_list_text{CLASS}">
		<div><a href="{ITEM_URL}">{ITEM_TITLE}</a></div>
		<div id='cart{ITEM_ID}'>{if cart}<a href="javascript:{CART_FUNCTION}_cart({ITEM_ID});" class="ac{CART_CLASS}">{ADD_TO_CART}</a>{/if}</div>
		<div class="iviewed">{ITEM_VIEWED}</div>
		<div class="idownloaded">{DOWNLOADS}</div>
	</div>
</div>

