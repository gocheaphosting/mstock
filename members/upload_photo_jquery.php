<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){exit;}

if($global_settings["userupload"]==0){exit;}
$lphoto=0;
$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$dn->open($sql);
if(!$dn->eof and $dn->row["upload"]==1)
{
	$lphoto=$dn->row["photolimit"];
}


$tmp_folder="user_".(int)$_SESSION["people_id"];
if(!file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder))
{
	mkdir($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder);
}

require('upload_photo_jquery2.php');
$upload_handler = new UploadHandler();
$db->close();
?>