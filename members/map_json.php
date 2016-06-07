<?include("../admin/function/db.php");?>

<?				
$sql_mass=array();
$json_string = "";

if($global_settings["allow_photo"])
{
	$sql_mass["photo"]="select id_parent,title,server1,google_x,google_y,description from photos where google_x<>0 and google_y<>0 order by id_parent desc";
}

 if($global_settings["allow_video"])
{
	$sql_mass["video"]="select id_parent,title,server1,google_x,google_y,description from videos where google_x<>0 and google_y<>0 order by id_parent desc";
}

if($global_settings["allow_audio"])
{
	$sql_mass["audio"]="select id_parent,title,server1,google_x,google_y,description from audio where google_x<>0 and google_y<>0 order by id_parent desc";
}

 if($global_settings["allow_vector"])
{
	$sql_mass["vector"]="select id_parent,title,server1,google_x,google_y,description from vector where google_x<>0 and google_y<>0 order by id_parent desc";
}

$n=0;

foreach ($sql_mass as $key => $value) 
{
	$rs->open($value);
	while(!$rs->eof)
	{
		$img_url="";

		$remote_width=0;
		$remote_height=0;
		$flag_storage=false;
	
		if($key=="photo")
		{	
			$img_url=show_preview($rs->row["id_parent"],"photo",1,1,$rs->row["server1"],$rs->row["id_parent"],false);

			$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$rs->row["id_parent"]." and (filename1='thumb1.jpg' or filename1='thumb1.jpeg')";
			$ds->open($sql);
			if(!$ds->eof)
			{
				$remote_width=$ds->row["width"];
				$remote_height=$ds->row["height"];
				$flag_storage=true;
			}
		}
		if($key=="video")
		{
			$img_url=show_preview($rs->row["id_parent"],"video",1,1,$rs->row["server1"],$rs->row["id_parent"],false);

			$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$rs->row["id_parent"]." and (filename1='thumb.jpg' or filename1='thumb.jpeg' or filename1='thumb0.jpg' or filename1='thumb0.jpeg')";
			$ds->open($sql);
			if(!$ds->eof)
			{
				$remote_width=$ds->row["width"];
				$remote_height=$ds->row["height"];
				$flag_storage=true;
			}
		}
		if($key=="audio")
		{
			$img_url=show_preview($rs->row["id_parent"],"audio",1,1,$rs->row["server1"],$rs->row["id_parent"],false);

			$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$rs->row["id_parent"]." and (filename1='thumb.jpg' or filename1='thumb.jpeg')";
			$ds->open($sql);
			if(!$ds->eof)
			{
				$remote_width=$ds->row["width"];
				$remote_height=$ds->row["height"];
				$flag_storage=true;
			}
		}
		if($key=="vector")
		{
			$img_url=show_preview($rs->row["id_parent"],"vector",1,1,$rs->row["server1"],$rs->row["id_parent"],false);

			$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$rs->row["id_parent"]." and (filename1='thumb1.jpg' or filename1='thumb1.jpeg' or filename1='thumb.jpg' or filename1='thumb.jpeg')";
			$ds->open($sql);
			if(!$ds->eof)
			{
				$remote_width=$ds->row["width"];
				$remote_height=$ds->row["height"];
				$flag_storage=true;
			}
		}

		if(!$flag_storage)
		{
			$size = @getimagesize ($_SERVER["DOCUMENT_ROOT"].$img_url);
			$img_width=round($size[0]/2);
			$img_height=round($size[1]/2);
		}
		else
		{
			$img_width=round($remote_width/2);
			$img_height=round($remote_height/2);
		}
		
		if($json_string != '')
		{
			$json_string .= ',';
		}
		
		$json_string .= '{"photo_id": ' .$rs->row["id_parent"]. ', "photo_title": "' . addslashes(str_replace("\r","",str_replace("\n","",$rs->row["title"]))) . '", "photo_url": "' . item_url($rs->row["id_parent"]) . '", "photo_file_url": "' . $img_url . '", "longitude": ' . $rs->row["google_y"] . rand(0,10000)  . ', "latitude": ' . $rs->row["google_x"] . rand(0,10000) . ', "width": ' . $img_width . ', "height": ' . $img_height . ', "description": "' . addslashes(str_replace("\r","",str_replace("\n","",$rs->row["description"]))) . '"}';
		

	$n++;
	$rs->movenext();
}
}
?>

var data = { "count": <?=$n?>,
 "photos": [<?=$json_string?>
]}

