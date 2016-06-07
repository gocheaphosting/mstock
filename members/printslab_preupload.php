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

if(!$global_settings["prints_lab"]){exit;}

$lphoto=$global_settings["prints_lab_filesize"];




$tmp_folder="user_".(int)$_SESSION["people_id"];
create_temp_folder();

require('upload_photo_jquery2.php');
$upload_handler = new UploadHandler();
$db->close();
?>