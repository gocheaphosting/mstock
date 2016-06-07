<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_notifications");

$sql="select events,message,subject,html from notifications where events='".result($_GET["events"])."'";
$rs->open($sql);
if(!$rs->eof)
{

	$sql="update notifications set subject='".result($_POST["subject"])."',message='".result($_POST["message_text"])."',html=".(int)$_POST["html"]." where events='".$rs->row["events"]."'";
	$db->execute($sql);
	
	$filename = $_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/".$rs->row["events"].".tpl";
    if(file_exists($filename) and is_writeable($filename))
    {
		$file=fopen($filename,'w');
		if($file)
		{
			$content=str_replace("\n","",str_replace("\r","",$_POST["message_html"]));
			fputs($file,$content);
		}
		fclose($file);
    }

}
$db->close();

header("location:index.php");
?>
