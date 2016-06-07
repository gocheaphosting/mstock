<form id="cart_form" style="margin:0px" enctype="multipart/form-data">
<div id="lightbox_header">
	{lang.Rights managed}
</div>
	
<div id="lightbox_content">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr valign="top">
	<td>
		<div class="white_t"><div class="white_b"><div class="white_l"><div class="white_r"><div class="white_bl"><div class="white_br"><div class="white_tl"><div class="white_tr">
				<img src="{IMAGE}" width="{WIDTH}" height="{HEIGHT}">
			</div></div></div></div></div></div></div></div>
	</td>
	<td style="padding-left:15px;">
		<h2>{PUBLICATION_TITLE} &mdash; #{ID}</h2>
		{RIGHTS_MANAGED}
		<div style="margin-left:7px;display:none" id="price_box"><b>{lang.Price}: <div id="rights_managed_price" style="display:inline" class="price">{PRICE}</div></b></div>
	</td>
	</tr>
	</table>
</div>
<div id="lightbox_footer" style="display:none">
	<input type="button" value="{lang.Add to Cart}" class="lightbox_button" onClick="location.href='{SITE_ROOT}/members/shopping_cart_add_rights_managed.php?id={ID}'" style="margin-left:153px;">
</div>
</form>