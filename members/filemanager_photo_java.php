<?
$site="upload_java";$site2="";
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}
if($global_settings["userupload"]==0){header("location:profile_home.php");}
include("../inc/header.php");
include("profile_top.php");
include("../admin/function/upload.php");



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
else
{
	mkdir($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder);
}


$photo_names="thumb1,thumb2";
$photo_sizes=$global_settings["thumb_width"]."x".$global_settings["thumb_width"].",".$global_settings["thumb_width2"]."x".$global_settings["thumb_width2"];
$photo_quality="1000,1000";
$flag_original=false;
$sql="select id_parent,size from sizes group by size order by size";
$rs->open($sql);
while(!$rs->eof)
{		
	if($rs->row["size"]!=0)
	{
		if($rs->row["size"]!=$global_settings["thumb_width"] and $rs->row["size"]!=$global_settings["thumb_width2"])
		{
			$photo_names.=",photo_".$rs->row["size"];
			$photo_sizes.=",".$rs->row["size"]."x".$rs->row["size"];
			$photo_quality.=",1000";
		}
	}
	else
	{
		if(!$flag_original)
		{
			$photo_names.=",original";
			$photo_sizes.=",100000x100000";
			$photo_quality.=",1000";
		}
		$flag_original=true;
	}
	$rs->movenext();
}
?>

<h1><?=word_lang("java photo uploader")?></h1>


<div class="subheader"><?=word_lang("select files")?>. (*.jpg <  <?=$lphoto?>M)</b></div>

<div class="subheader_text">


 <applet id="jumpLoaderApplet" name="jumpLoaderApplet"
	code="jmaster.jumploader.app.JumpLoaderApplet.class"
	archive="<?=site_root?>/inc/mediautil_z.jar,<?=site_root?>/inc/sanselan_z.jar,<?=site_root?>/inc/jumploader_z.jar"
	width="700"
	height="500"
	mayscript>
    	<param name="uc_sendImageMetadata" value="true"/>
    	<param name="uc_imageEditorEnabled" value="true"/>
        <param name="uc_useLosslessJpegTransformations" value="true"/>
		<param name="uc_uploadUrl" value="<?=site_root?>/members/upload_photo_java.php?client_id=<?=$_SESSION["people_id"]?>"/>
        <param name="uc_uploadScaledImages" value="true"/>


        <param name="uc_scaledInstanceNames" value="<?=$photo_names?>"/>
        <param name="uc_scaledInstanceDimensions" value="<?=$photo_sizes?>"/>
        <param name="uc_scaledInstanceQualityFactors" value="<?=$photo_quality?>"/>

        <param name="ac_fireUploaderFileAdded" value="true"/>
		<param name="ac_fireUploaderFileStatusChanged" value="true"/>
        <param name="uc_fileNamePattern" value="^.+\.(?i)((jpg)|(jpeg))$"/>
        <param name="vc_fileNamePattern" value="^.+\.(?i)((jpg)|(jpeg))$"/>
        <param name="vc_disableLocalFileSystem" value="false"/>
        <param name="vc_mainViewFileTreeViewVisible" value="false"/>
        <param name="vc_mainViewFileListViewVisible" value="false"/>
        <param name="uc_imageRotateEnabled" value="true"/>
        <param name="uc_scaledInstancePreserveMetadata" value="true"/>
        <param name="uc_deleteTempFilesOnRemove" value="true"/>
</applet>
</div>






<form method="post" Enctype="multipart/form-data" action="upload_photo_java2.php" name="uploadform">

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