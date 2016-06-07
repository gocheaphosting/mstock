<form id="cart_form" style="margin:0px" enctype="multipart/form-data">
<div id="lightbox_header">
	{lang.The item has been added to the cart}
</div>
	
<div id="lightbox_content">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr valign="top">
	<td>
		<div class="white_t"><div class="white_b"><div class="white_l"><div class="white_r"><div class="white_bl"><div class="white_br"><div class="white_tl"><div class="white_tr">
				<img src="{IMAGE}" width="{WIDTH}" height="{HEIGHT}">
			</div></div></div></div></div></div></div></div>
	</td>
	<td style="padding-left:15px">
		<h2>{PUBLICATION_TITLE} &mdash; #{ID}</h2>
		<div class="param"><b>{lang.Type}:</b> {TITLE}</div>
		<div class="param"><b>{lang.Price}:</b> <span class="price">{PRICE}</span></div>
		{PRINTS_OPTIONS}
	</td>
	</tr>
	</table>
</div>

<div id="lightbox_footer">
	<input type="hidden" name="item_id" value="{ITEM_ID}">
	<input type="hidden" name="prints_id" value="{PRINTS_ID}">
	<input type="hidden" name="content_id" value="{CONTENT_ID}">
	<input type="button" value="{lang.Checkout}" class="lightbox_button2" onClick="shopping_cart_add('{SITE_ROOT}',1)">
	<input type="button" value="{lang.Continue Shopping}" class="lightbox_button" onClick="shopping_cart_add('{SITE_ROOT}',0)">
</div>
</form>