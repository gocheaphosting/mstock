<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

if(isset($_SESSION["people_id"]))
{
	$params["id"]=(int)$_REQUEST["publication"];
	$params["user"]=(int)$_SESSION["people_id"];
	
	$params["lightbox_name"]="";
	if(isset($_REQUEST["new_lightbox"]))
	{
		$params["lightbox_name"]=result($_REQUEST["new"]);
	}
	
	$params["lightboxes"]=array();

	$sql="select id_parent from lightboxes_admin where user=".(int)$_SESSION["people_id"];
	$rs->open($sql);
	while(!$rs->eof)
	{
		if(isset($_REQUEST["chk".$rs->row["id_parent"]]))
		{
			$params["lightboxes"][]=$rs->row["id_parent"];
		}
		$rs->movenext();
	}
	
	lightbox_add($params);
	
	$GLOBALS['_RESULT'] = array(
 	"result_code"     => word_lang("the file was added to the lightbox")
	);
}
else
{
	$GLOBALS['_RESULT'] = array(
 	"result_code"     => word_lang("You should login to use the option")
	);
}

$db->close();
?>