<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_invoices");

$email_settings=array('invoice_logo_size');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

//Upload photos
$images_types=array("logo_photo");
		
for($i=0;$i<count($images_types);$i++)
{
	if(isset($_FILES[$images_types[$i]]['name']))
	{
		$_FILES[$images_types[$i]]['name']=result_file($_FILES[$images_types[$i]]['name']);
		
		if($_FILES[$images_types[$i]]['size']>0)
		{
			$file_extention=strtolower(get_file_info($_FILES[$images_types[$i]]['name'],"extention"));
			
			if($file_extention == "jpg" and !preg_match("/text/i",$_FILES[$images_types[$i]]['type']))
			{	
				$img=site_root."/content/invoice_logo".".".$file_extention;
				move_uploaded_file($_FILES[$images_types[$i]]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$img);
				easyResize($_SERVER["DOCUMENT_ROOT"].$img,$_SERVER["DOCUMENT_ROOT"].$img,100,(int)$_POST['invoice_logo_size']);
			}
		}
	}
}	

$db->close();

header("location:index.php");
?>
