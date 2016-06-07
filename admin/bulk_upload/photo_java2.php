<?
//Check access
admin_panel_access("catalog_bulkupload");
?>
<?if(!defined("site_root")){exit();}?>

<?
if(!isset($_POST["author"]))
{
	exit();
}
else
{
	$_SESSION["author_id"]=user_url($_POST["author"]);
}


//Create a temporary folder for the preupload
$tmp_folder="user_".user_url($_POST["author"]);
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


//Create a list of the photo's sizes
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

<div class="subheader">You should select *.jpg images and then click "Upload". </div>
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
		<param name="uc_uploadUrl" value="photo_java_preupload.php?clientId=<?=user_url($_POST["author"])?>"/>
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


<form method="post" action="photo_java_upload.php">
<?
foreach ($_POST as $key => $value)
{
	?>
	<input type="hidden" name="<?=$key?>" value="<?=result($value)?>">
	<?
}
?>

<div id="java_bulk"></div>
	<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">
			<input type="submit" value="<?=word_lang("next step")?>" class="btn btn-primary" style="margin-top:20px">
		</div>
	</div>

</form>