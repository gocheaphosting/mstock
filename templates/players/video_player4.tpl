
<link href="{SITE_ROOT}/inc/js/videojs/video-js.css" rel="stylesheet" type="text/css">
<script src="{SITE_ROOT}/inc/js/videojs/video.js"></script>
<script>
    videojs.options.flash.swf = "{SITE_ROOT}/inc/js/videojs/video-js.swf";
</script>
	
<video id="video_publication_preview" class="video-js vjs-default-skin" controls preload="auto" width="{VIDEO_WIDTH}" height="{VIDEO_HEIGHT}"
      poster="{PREVIEW_PHOTO}"
      data-setup='{"techOrder": ["flash","html5" , "other supported tech"]}'>
    <source src="{PREVIEW_VIDEO}" type='video/mp4' />
</video>