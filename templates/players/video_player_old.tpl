<script type="text/javascript" src="{SITE_ROOT}/inc/jwplayer.js"></script>
					<div id="players{ID}"></div>
					<script type="text/javascript">
		jwplayer("players{ID}").setup({
			flashplayer: "{SITE_ROOT}/images/player_new.swf",
			file: "{PREVIEW_VIDEO}",
			image: "{PREVIEW_PHOTO}",
			width: "{VIDEO_WIDTH}",
			  height:"{VIDEO_HEIGHT}",
			  stretching: "fill",
			  autoplay: false,
			  repeat: 'none',
			  skin: "{SITE_ROOT}/images/glow.zip"
		});
	</script>