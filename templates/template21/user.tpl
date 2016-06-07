<div class="row-fluid">
	<div class="span3 clear_padding">
		<div class="portfolio_left">

{IMAGE}

<div class="portfolio_title">{WORD_PERSONAL_INFORMATION}</div>
<div class="portfolio_box">
	<div><b>{WORD_NAME}:</b> {NAME}</div>
	<div><b>{WORD_ADDRESS}:</b> {CITY}, {COUNTRY}</div>
	<div><b>{WORD_WEBSITE}:</b> {WEBSITE}</div>
	<div><b>{WORD_DATE}:</b> {DATE}</div>
	<div><b>{WORD_COMPANY}:</b> {COMPANY}</div>
	{if rating}
		{RATING}
	{/if}
</div>


{if notuser}
	<div class="portfolio_title">{WORD_TOOLS}</div>
	<div class="portfolio_box">
		{if friends}<div class="box_members" id="friendbox" name="friendbox"><a href="{FRIEND_LINK}">{WORD_FRIEND}</a></div>{/if}
		{if messages}<div class="box_members"><a href="{MAIL_LINK}">{WORD_MAIL}</a></div>{/if}
		{if testimonials}<div class="box_members"><a href="{TESTIMONIAL_LINK}">{WORD_TESTIMONIAL}</a></div>{/if}
	</div>
{/if}
{if seller}
	<div class="portfolio_title">{WORD_PORTFOLIO}</div>
	<div class="portfolio_box">
		{if sitephoto}<div><b>{WORD_PHOTO}:</b> <a href="{SITE_ROOT}/index.php?user={USERID}&portfolio=1&sphoto=1">{PHOTO}</a></div>{/if}
		{if sitevideo}<div><b>{WORD_VIDEO}:</b> <a href="{SITE_ROOT}/index.php?user={USERID}&portfolio=1&svideo=1">{VIDEO}</a></div>{/if}
		{if siteaudio}<div><b>{WORD_AUDIO}:</b> <a href="{SITE_ROOT}/index.php?user={USERID}&portfolio=1&saudio=1">{AUDIO}</a></div>{/if}
		{if sitevector}<div><b>{WORD_VECTOR}:</b> <a href="{SITE_ROOT}/index.php?user={USERID}&portfolio=1&svector=1">{VECTOR}</a></div>{/if}
		<div><b>{WORD_VIEWED}:</b> {VIEWED}</div>
		<div><b>{WORD_DOWNLOADS}:</b> {DOWNLOADS}</div>
	</div>


{/if}


		</div>
		</div>
		<div class="span9 clear_padding">
			<div class="portfolio_right">
			<h1>{AUTHOR}</h1>




