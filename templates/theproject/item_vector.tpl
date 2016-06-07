<div class="row transitionfx">
    <div class="col-lg-6 col-md-6" style="min-width:500px">
        	{IMAGE} 
        	<div class="file_links row">
        		<div class="col-lg-8 col-md-8">
					<a href="{ADD_TO_FAVORITE_LINK}" class="btn btn-success btn-sm"><i class="fa fa-heart"> </i> {ADD_TO_FAVORITE}</a>

					 {if downloadsample}<a href="{DOWNLOADSAMPLE}" class="btn btn-danger btn-sm"><i class="fa fa-download"> </i>  {lang.Download Sample}</a>{/if}
				</div>
				<div class="col-lg-4 col-md-4 next_previous">
					{if previous}
						<a href="{PREVIOUS_LINK}" title="{lang.Previous}"><i class="fa fa-arrow-circle-left"></i></a>
					{/if}&nbsp;&nbsp;&nbsp;
					{if next}
						<a href="{NEXT_LINK}" title="{lang.Next}"><i class="fa fa-arrow-circle-right"></i></a>
					{/if}
				</div>
			</div>			
			
			<div class='file_details'>
			<h5>{lang.Keywords}</h5>
			{KEYWORDS_LITE}
			
			<hr />
			
			<h5>{lang.File details}</h5>
			<span><b>{lang.Published}:</b> {PUBLISHED}</span>
			<span><b>{lang.Rating}:</b> {ITEM_RATING_NEW}</span>
			<span><b>{lang.Category}:</b> {CATEGORY}</span>
			<span><b>{lang.Viewed}:</b> {VIEWED}</span>
			<span><b>{lang.Downloads}:</b> {DOWNLOADS}</span>	
			{if model}
				{MODEL}
			{/if}
			{if flash_version}
				<span><b>{lang.Flash version}:</b> {FLASH_VERSION}</span>
			{/if}
			{if script_version}
				<span><b>{lang.Script version}:</b> {SCRIPT_VERSION}</span>
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
      	<div class="col-lg-4 col-md-4 col-sm-4">{AUTHOR} </div>
       	<div class="col-lg-2 col-md-2 col-sm-2"><b>ID : {ID}</b></div>
       	<div class="col-lg-6 col-md-6 col-sm-6">
       		<div id="vote_dislike" class="dislike-btn dislike-h">{lang.Dislike} {DISLIKE}</div>
			<div id="vote_like" class="like-btn like-h">{lang.Like} {LIKE}</div>
       	</div>
       </div>
       
       <hr / style="margin-bottom:0px">


		{if exclusive}
		<div class="editorial">{EXCLUSIVE}</div>
		{/if}
		<div class="cart-actions">
			<div class="addto">
				{SIZES}
			</div>
			
			<div style="clear:both"></div><br><br>
		</div>
      

      
        <ul class="nav nav-tabs  style-2" role="tablist">
          <li class="active"><a href="#details" data-toggle="tab">{lang.Description}</a></li>
          <li><a href="#comments_content" data-toggle="tab" onclick="reviews_show({ID});">{lang.Comments}</a></li>
          <li><a href="#tell_content" data-toggle="tab"  onclick="tell_show({ID});">{lang.Tell a friend}</a></li>
          <li><a href="#reviewscontent" data-toggle="tab"  onclick="map_show({GOOGLE_X},{GOOGLE_Y});">{lang.Google map}</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="details">{DESCRIPTION}</div>    
          <div class="tab-pane" id="comments_content"></div>
          <div class="tab-pane" id="tell_content"></div>
          <div class="tab-pane" id="reviewscontent"></div>
          
        </div>
        

      



    
    </div>
    
  </div>
{if related_items}    
 	<h2>{lang.Related items}</h2>
							<div class="separator-2"></div>

  		{RELATED_ITEMS2}
 

 {/if} 

  <div style="clear:both"></div>