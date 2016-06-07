<?
$site="upload_flash";$site2="";
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}
if($global_settings["userupload"]==0){header("location:profile_home.php");}
include("../inc/header.php");
include("profile_top.php");
include("../admin/function/upload.php");


?>

<h1><?=word_lang("flash photo uploader")?></h1>

<?



  $tmp_folder="user_".(int)$_SESSION["people_id"];
  if(file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder))
  {
  $dir = opendir ($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder);
  while ($file = readdir ($dir)) 
  {
    if($file <> "." && $file <> "..")
    {
@unlink($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file);
    }

 }
}




?>







<div class="subheader"><?=word_lang("select files")?>. (*.jpg <  <?=$lphoto?>M)</b></div>

<div class="subheader_text">







<style>




div.fieldset {
	border:  1px solid #afe14c;
	margin: 10px 0;
	padding: 20px 10px;
}
div.fieldset span.legend {
	position: relative;
	background-color: #FFF;
	padding: 3px;
	top: -30px;
	font: 700 14px Arial, Helvetica, sans-serif;
	color: #73b304;
}

div.flash {
	width: 375px;
	margin: 10px 5px;
	border-color: #D9E4FF;

	-moz-border-radius-topleft : 5px;
	-webkit-border-top-left-radius : 5px;
    -moz-border-radius-topright : 5px;
    -webkit-border-top-right-radius : 5px;
    -moz-border-radius-bottomleft : 5px;
    -webkit-border-bottom-left-radius : 5px;
    -moz-border-radius-bottomright : 5px;
    -webkit-border-bottom-right-radius : 5px;

}





label { 
	width: 150px; 
	text-align: right; 
	display:block;
	margin-right: 5px;
}

#btnSubmit { margin: 0 0 0 155px ; }




.progressWrapper {
	width: 357px;
	overflow: hidden;
}

.progressContainer {
	margin: 5px 0px 5px 0px;
	padding: 4px;
	border: solid 1px #F7B441;
	background-color: #FCE4A9;
	overflow: hidden;
}
/* Message */
.message {
	margin: 5px 0px 5px 0px;
	padding: 10px 20px;
	border: solid 1px #F7B441;
	background-color: #FCE4A9;
	overflow: hidden;
}
/* Error */
.red {
	border: solid 1px #F7B441;
	background-color: #FCE4A9;
}

/* Current */
.green {
	border: solid 1px #F7B441;
	background-color: #FCE4A9;
}

/* Complete */
.blue {
	border: solid 1px #F7B441;
	background-color: #FCE4A9;
}

.progressName {
	font-size: 8pt;
	font-weight: 700;
	color: #555;
	width: 323px;
	height: 14px;
	text-align: left;
	white-space: nowrap;
	overflow: hidden;
}

.progressBarInProgress,
.progressBarComplete,
.progressBarError {
	font-size: 0;
	width: 0%;
	height: 8px;
	background-color: #000000;
	margin-top: 2px;
}

.progressBarComplete {
	width: 100%;
	background-color: green;
	visibility: hidden;
}

.progressBarError {
	width: 100%;
	background-color: red;
	visibility: hidden;
}

.progressBarStatus {
	margin-top: 2px;
	width: 337px;
	font-size: 7pt;
	font-family: Arial;
	text-align: left;
	white-space: nowrap;
}

a.progressCancel {
	font-size: 0;
	display: block;
	height: 14px;
	width: 14px;
	background-image: url(../images/cancelbutton.gif);
	background-repeat: no-repeat;
	background-position: -14px 0px;
	float: right;
}

a.progressCancel:hover {
	background-position: 0px 0px;
}


/* -- SWFUpload Object Styles ------------------------------- */
.swfupload {
	vertical-align: top;
}

</style>



<script type="text/javascript" src="<?=site_root?>/inc/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=site_root?>/inc/swfupload/handlers.js"></script>
<script type="text/javascript">




		var swfu;
		window.onload = function () {
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "<?=site_root?>/members/upload_photo_flash.php",
				post_params: {"PHPSESSID": "<?=session_id()?>"},

				// File Upload Settings
				file_size_limit : "<?=$lphoto?> MB",
				file_types : "*.jpg",
				file_types_description : "JPG Images",
				file_upload_limit : 1000,

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : loadFailed,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "", 
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 200,
				button_height: 26,
				button_text : '<span class="button"><b><?=word_lang("add files")?></b></span>',
				button_text_style : '.button { font-family: Arial; font-size:12px; color:#FFFFFF}',
				button_text_top_padding: 0,
				button_text_left_padding: 20,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "<?=site_root?>/inc/swfupload/swfupload.swf",
				flash9_url : "<?=site_root?>/inc/swfupload/swfupload_fp9.swf",
				
				
				
<?
$sizelist="";
$sql="select * from sizes order by priority";
$rs->open($sql);
while(!$rs->eof)
{
if($rs->row["size"]!=0)
{
//$sizelist.="|".$rs->row["size"];
}
$rs->movenext();
}
?>				
				
				

				custom_settings : {
					upload_target : "divFileProgressContainer",
					thumbnail_height: "<?=$global_settings["thumb_height"]?>|<?=$global_settings["thumb_height2"]?><?=$sizelist?>",
					thumbnail_width: "<?=$global_settings["thumb_width"]?>|<?=$global_settings["thumb_width2"]?><?=$sizelist?>",
					thumbnail_quality: 100
				},
				
				// Debug Settings
				debug: false
			});
		};
		
		

		
		
		
		
		
		
	</script>









<form>






		<div class="add_to_cart" style="width: 200px; height: 16px;  padding: 2px;">
			<span id="spanButtonPlaceholder"></span>
		</div>




</form>


	<div id="divFileProgressContainer"></div>
	<div id="thumbnails">

	</div>




</div>






<form method="post" Enctype="multipart/form-data" action="upload_photo_flash2.php" name="uploadform">

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

<div class="subheader"><a href="javascript:photo_open()"><?=word_lang("photo filters")?></a></div>
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
