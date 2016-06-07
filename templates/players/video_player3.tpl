<script type="text/javascript" src="{SITE_ROOT}/members/swfobject.js"></script>
	<div id="players{ID}"></div>
	<script type="text/javascript">
		var s{ID} = new SWFObject("{SITE_ROOT}/images/mediaplayer.swf","single","{VIDEO_WIDTH}","{VIDEO_HEIGHT}","7");
		s{ID}.addParam("allowfullscreen","true");
		s{ID}.addVariable("file","{PREVIEW_VIDEO}");
		s{ID}.addVariable("image","{PREVIEW_PHOTO}");
		s{ID}.write("players{ID}");
	</script>