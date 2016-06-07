<h1>{TITLE}</h1>

<div class="row">
    <div class="col-lg-6 col-md-6" style="min-width:500px">
        	{IMAGE} <br><br>
        	<a href="{DOWNLOADSAMPLE}" class="btn btn-danger btn-sm"><i class="fa fa-download"> </i>  {lang.Download Sample}</a>
		
			
			<div class='file_details'>

			
			<h5>{lang.File details}</h5>
			{if published}
				<span><b>{lang.Published}:</b> {PUBLISHED}</span>
			{/if}
			{if category}
				<span><b>{lang.Category}:</b> {CATEGORY}</span>
			{/if}
			<span><b>{lang.Type}:</b> {TYPE}</span>
			{if duration}
				<span><b>{lang.Duration}:</b> {DURATION} sec.</span>
			{/if}
			{if aspect}
				<span><b>{lang.aspect ratio}:</b> {ASPECT_RATIO}</span>
			{/if}
			{if model}
				<span><b>{lang.Model release}:</b> {MODEL_RELEASE}</span>
			{/if}
			{if property}
				<span><b>{lang.Property release}:</b> {PROPERTY_RELEASE}</span>
			{/if}
			
			{if bpm}
				<span><b>{lang.Beats per minute}:</b> {BPM}</span>
			{/if}
			{if artists}
				<span><b>{lang.Artists}:</b> {ARTISTS}</span>
			{/if}
			{if album}
				<span><b>{lang.Album}:</b> {ALBUM}</span>
			{/if}
			{if genres}
				<span><b>{lang.Genres}:</b> {GENRES}</span>
			{/if}
			{if instruments}
				<span><b>{lang.Instruments}:</b> {INSTRUMENTS}</span>
			{/if}
			{if lyrics}
				<span><b>{lang.Lyrics}:</b> {LYRICS}</span>
			{/if}
			{if moods}
				<span><b>{lang.Moods}:</b> {MOODS}</span>
			{/if}
			{if vocal_description}
				<span><b>{lang.Vocal description}:</b> {VOCAL_DESCRIPTION}</span>
			{/if}
			{if fotolia}
				<span><b>{lang.Viewed}:</b> {VIEWED}</span>
				<span><b>{lang.Downloaded}:</b> {DOWNLOADED}</span>
			{/if}

			
			<hr />
			
			           <h5>{lang.Share}</h5>
        <ul class="social-links colored circle" style="margin-top:7px"> 
        	<li><a href="http://www.facebook.com/sharer.php?s=100&p[title]={SHARE_TITLE}&p[summary]={SHARE_TITLE}&p[url]={SHARE_URL}&&p[images][0]={SHARE_IMAGE}" target="_blank"> <i  class="fa fa-facebook"></i></a></li> 
            <li><a href="http://twitter.com/home?status={SHARE_URL}&title={SHARE_TITLE}" target="_blank"> <i  class="fa fa-twitter"></i></a></li> 
            <li><a href="http://www.google.com/bookmarks/mark?op=edit&bkmk={SHARE_URL}&title={SHARE_TITLE}" target="_blank"> <i  class="fa fa-google-plus"></i></a></li> 
            <li><a href="http://pinterest.com/pin/create/button/?url={SHARE_URL}&media={SHARE_IMAGE}&description={SHARE_TITLE}" target="_blank"> <i  class="fa fa-pinterest"></i></a></li>
      </ul>
			
		</div>
    </div>   

    <div class="col-lg-6 col-md-6">
      <div class="row">
      	<div class="col-lg-6 col-md-6 col-sm-6">{AUTHOR} </div>
       	<div class="col-lg-6 col-md-6 col-sm-6"><b>ID : {ID}</b></div>
       </div>
       
       <hr / style="margin-bottom:0px">

      	{if editorial}
			<div class="editorial">{EDITORIAL}</div>
		{/if}

		<div class="cart-actions">
			<div class="addto" style='margin:20px 0px 40px 0px'>
				{SIZES}
			</div>
			
			{if depositphotos}
				<h5>{lang.Description}</h5>
				{DESCRIPTION}
				
			{/if}
			{if bigstockphoto}
				<h5>{lang.Description}</h5>
				{DESCRIPTION}
				
			{/if}
			<hr />
			<h5>{lang.Keywords}</h5>
			{KEYWORDS_LITE}
			<hr />

			
			<div style="clear:both"></div><br><br>
		</div>

    
    </div>
    
  </div>
{if related_items}
 	<h5>{lang.Related items}</h5>
							<div class="separator-2"></div>

  		{RELATED_ITEMS2}
{/if}

  <div style="clear:both"></div>