<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_templates");



if(file_exists($DOCUMENT_ROOT."/".$site_template_url) and !$demo_mode)
{
	$dir = opendir ($DOCUMENT_ROOT."/".$site_template_url);
	$i=0;

	while ($file = readdir ($dir)) 
	{
		if($file <> "." && $file <> ".." && $file <> "images" && $file <> ".DS_Store")
		{
			$sel="";
			if((int)$_GET["file"]==$i)
			{
				$filename = $DOCUMENT_ROOT."/".$site_template_url.$file;
            	if(is_writeable($filename))
            	{
              		$file=fopen($filename,'w');
              		if($file)
              		{
               			fputs($file,stripslashes($_POST["content"]));
               		}
             		fclose($file);
            	}
			}

			$i++;	
		}
	}
	closedir ($dir);
}

            


$db->close();


header("location:index.php?d=1");
?>