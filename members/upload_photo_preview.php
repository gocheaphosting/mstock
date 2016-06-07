<?php
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
include("../admin/function/db.php");
	
	$image_id = isset($_GET["id"]) ? $_GET["id"] : false;

	if ($image_id === false) {
		header("HTTP/1.1 500 Internal Server Error");
		echo "No ID";
		exit(0);
	}

	if (!is_array($_SESSION["file_info"]) || !isset($_SESSION["file_info"][$image_id])) {
		header("HTTP/1.1 404 Not found");
		exit(0);
	}
	
	
	
	$tmp_folder="user_".(int)$_SESSION["people_id"];
    ob_clean();
	header("Content-type: image/jpeg") ;
	header("Content-length: " . filesize($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/" . $_SESSION["file_info"][$image_id]."_thumb1.jpg"));
	ob_end_flush();
	readfile($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/" . $_SESSION["file_info"][$image_id]."_thumb1.jpg");
	exit(0);
	
	$db->close();
?>