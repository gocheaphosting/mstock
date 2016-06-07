<h1>{TITLE}</h1>


<div class="row-fluid">
	<div class="span5 clear_padding">
		<div class="border_box">
			{IMAGE}
		</div>
		<br><br>
		<a href="{DOWNLOADSAMPLE}" class="btn btn-danger" style="color:#FFFFFF;text-decoration:none">{lang.Download Sample}</a>	
		<br><br>
		{if related_items}
			<h2>{lang.Related items}:</h2>
			{RELATED_ITEMS}
		{/if}

	 	
	 	
	</div>
	<div class="span7 clear_padding">
		<div class="row-fluid">
			<div class="span6 clear_padding">
				{AUTHOR} 
			</div>
			<div class="span6 clear_padding">
				<span><b>ID:</b> {ID}</span>
			</div>
		</div>
		<div class="line"></div>
		{if editorial}
		<div class="editorial">{EDITORIAL}</div>
		{/if}
		
		{SIZES}
		<br><br>
		{if depositphotos}
			<h2>{lang.Description}</h2>
			{DESCRIPTION}
			<br><br>
		{/if}
		{if bigstockphoto}
			<h2>{lang.Description}</h2>
			{DESCRIPTION}
			<br><br>
		{/if}
		<h2>{lang.Keywords}</h2>
		{KEYWORDS_LITE}
		
		
		<br><br>
		<h2>{lang.File details}:</h2>
		{if published}
			<p><b>{lang.Published}:</b> {PUBLISHED}</p>
		{/if}
		{if category}
			<p><b>{lang.Category}:</b> {CATEGORY}</p>
		{/if}
		<p><b>{lang.Type}:</b> {TYPE}</p>
		{if duration}
			<p><b>{lang.Duration}:</b> {DURATION} sec.</p>
		{/if}
		{if aspect}
			<p><b>{lang.aspect ratio}:</b> {ASPECT_RATIO}</p>
		{/if}
		{if model}
			<p><b>{lang.Model release}:</b> {MODEL_RELEASE}</p>
		{/if}
		{if property}
			<p><b>{lang.Property release}:</b> {PROPERTY_RELEASE}</p>
		{/if}
		
		{if bpm}
			<p><b>{lang.Beats per minute}:</b> {BPM}</p>
		{/if}
		{if artists}
			<p><b>{lang.Artists}:</b> {ARTISTS}</p>
		{/if}
		{if album}
			<p><b>{lang.Album}:</b> {ALBUM}</p>
		{/if}
		{if genres}
			<p><b>{lang.Genres}:</b> {GENRES}</p>
		{/if}
		{if instruments}
			<p><b>{lang.Instruments}:</b> {INSTRUMENTS}</p>
		{/if}
		{if lyrics}
			<p><b>{lang.Lyrics}:</b> {LYRICS}</p>
		{/if}
		{if moods}
			<p><b>{lang.Moods}:</b> {MOODS}</p>
		{/if}
		{if vocal_description}
			<p><b>{lang.Vocal description}:</b> {VOCAL_DESCRIPTION}</p>
		{/if}
		{if fotolia}
			<p><b>{lang.Viewed}:</b> {VIEWED}</p>
			<p><b>{lang.Downloaded}:</b> {DOWNLOADED}</p>
		{/if}
		
		<div class="share_box">
			<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
		</div>
		
		<div class="share_box" style="margin: 10px 3px 0px 10px">
			<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="{URL}" send="false" layout="box_count" show_faces="true" action="like" font=""></fb:like>
		</div>
		
		<div class="share_box">
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
			<g:plusone size="tall"></g:plusone>
		</div>

		
		
	</div>
</div>




