<?
$site="upload_plupload";$site2="";
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}
if($global_settings["userupload"]==0){header("location:profile_home.php");}
include("../inc/header.php");
include("profile_top.php");
include("../admin/function/upload.php");

$formats="";
$sql="select id,photo_type from photos_formats where enabled=1 and photo_type<>'jpg' order by id";
$dr->open($sql);
while(!$dr->eof)
{
	if($formats!=""){$formats.=",";}
	$formats.="*.".$dr->row["photo_type"];
	$dr->movenext();
}
?>

<h1><?=word_lang("plupload photo uploader")?></h1>

<div class="subheader"><?=word_lang("select files")?>. (*.jpg <  <?=$lphoto?>M)</b></div>

<div class="subheader_text">

<?if($formats!=""){?>
<p><?=str_replace("{FORMATS}",$formats,word_lang("If you want to upload additional formats {FORMATS} the files must have the same names as *.jpg."))?></p>
<?}?>

<?
$tmp_folder="user_".(int)$_SESSION["people_id"];
if(file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder))
{
	$dir = opendir ($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder);
	while ($file = readdir ($dir)) 
	{
		if($file <> "." && $file <> "..")
		{
			if(is_file($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file)) 
			{ 
				@unlink($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file);
			}
			if(is_dir($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file))
			{
				$dir2 = opendir ($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file);
				while ($file2 = readdir ($dir2)) 
				{
					if($file2 <> "." && $file2 <> "..")
					{
						if(is_file($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file."/".$file2)) 
						{ 
							@unlink($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file."/".$file2);
						}
					}
				}
				rmdir($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file); 
			}
		}
	}
}
?>







<!-- Load Queue widget CSS and jQuery -->
<style type="text/css">@import url(<?=site_root?>/admin/plugins/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>

<!-- Third party script for BrowserPlus runtime (Google Gears included in Gears runtime now) -->
<script type="text/javascript" src="<?=site_root?>/admin/plugins/plupload/browserplus-min.js"></script>

<!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
<script type="text/javascript" src="<?=site_root?>/admin/plugins/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="<?=site_root?>/admin/plugins/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>

<script type="text/javascript">
// Convert divs to queue widgets when the DOM is ready
$(function() {
	$("#uploader").pluploadQueue({
		// General settings
		runtimes : 'flash,gears,silverlight,browserplus,html5',
		url : 'upload_photo_plupload.php',
		max_file_size : '<?=$lphoto?>mb',

		// Specify what files to browse for
		filters : [
			{title : "Image files", extensions : "jpg,jpeg,gif,png,raw,tif,tiff,eps"}
		],

		// Flash settings
		flash_swf_url : '<?=site_root?>/admin/plugins/plupload/plupload.flash.swf',

		// Silverlight settings
		silverlight_xap_url : '<?=site_root?>/admin/plugins/plupload/plupload.silverlight.xap'
	});

	// Client side form validation
	$('#form_plupload').submit(function(e) {
        var uploader = $('#uploader').pluploadQueue();

        // Files in queue upload them first
        if (uploader.files.length > 0) {
            // When all files are uploaded submit form
            uploader.bind('StateChanged', function() {
                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                    $('#form_plupload')[0].submit();
                }
            });
                
            uploader.start();
        } else {
            alert('You must queue at least one file.');
        }

        return false;
    });
});
</script>


<form style="margin:0px;" id="form_plupload">
	<div id="uploader">
		<p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
	</div>
</form>










</div>






<form method="post" Enctype="multipart/form-data" action="upload_photo_plupload2.php" name="uploadform">

<script>
	
	block_open=false;

	function photo_open()
	{
		if(block_open)
		{
			$('#photo_filters').slideUp('slow');
			block_open=false;
		}
		else
		{
			$('#photo_filters').slideDown('slow');
			block_open=true;
		}
	}
</script>

<div class="subheader"><a href="javascript:photo_open()"><?=word_lang("settings")?></a></div>
<div class="subheader_text" id="photo_filters" style="display:none">
	<?
		$free=0;
		$model=0;
		$editorial=0;
		$adult=0;
		$folderid=0;
		include("filemanager_photo_content.php");
	?>
</div>

<div class="subheader"><?=word_lang("terms and conditions")?></div>

<div class="subheader_text">
	<iframe src="<?=site_root?>/members/agreement.php?type=terms" frameborder="no" scrolling="yes" class="framestyle_terms"></iframe>
	<br><input name="terms" type="radio" value="1" onClick="document.uploadform.subm.disabled=false;"> <?=word_lang("i agree")?>
</div>

<div class="form_field">
	<input type="submit" name="subm" class="isubmit" value="<?=word_lang("next step")?>" disabled>
</div>

</form>



<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>