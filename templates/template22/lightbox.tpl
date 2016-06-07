<form id="lightbox_form" style="margin:0px" enctype="multipart/form-data">
<div id="lightbox_header">
	{lang.Lightboxes}
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
		<div>
			<b>{lang.Select a lightbox}:</b>
		</div>
		{LIGHTBOXES}
	</td>
	</tr>
	</table>
</div>

<div id="lightbox_footer">
	<input type="hidden" name="publication" value="{ID}">
	<input type="button" value="{lang.Save}" class="lightbox_button" onClick="lightbox_add('{SITE_ROOT}')">
</div>
</form>