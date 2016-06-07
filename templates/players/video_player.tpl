
<script src="{SITE_ROOT}/inc/js/jwplayer/jwplayer.js"></script>
<script>jwplayer.key="ptjJrvi3YcZfpkwX0KQT7v11POQ7ql+ormMJMg==";</script>
<div id="players{ID}"></div>
<script type="text/javascript">
var playerInstance = jwplayer("players{ID}");
playerInstance.setup({
    file: "{PREVIEW_VIDEO}",
    image: "{PREVIEW_PHOTO}",
    width: {VIDEO_WIDTH},
    height: {VIDEO_HEIGHT},
    title: '',
    description: ''
});
</script>