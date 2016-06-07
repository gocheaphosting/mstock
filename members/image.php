<?
$nolang=true;
include("../admin/function/db.php");


ob_clean();
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: image/jpg");


//Define photo
if($_GET["width"]<=$global_settings["thumb_width2"]+300 and $_GET["height"]<=$global_settings["thumb_height2"]+300)
{
	//Define types
	$module_table=30;
	$sql="select module_table from structure where id=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$module_table=$rs->row["module_table"];
	}
	
	if($module_table==30)
	{
		$sql="select server1,id_parent,url_jpg,url_png,url_gif from photos where id_parent=".(int)$_GET["id"];
	}else
	{
		$sql="select server1,id_parent from vector where id_parent=".(int)$_GET["id"];
	}
	
	$rs->open($sql);
	if(!$rs->eof)
	{
		$flag_storage=false;
		$file_url="";
		$file_width=0;
		$file_height=0;
		$file_url_vector="";
		$file_width_vector=0;
		$file_height_vector=0;
		$file_url_thumb="";

		if($global_settings["amazon"] or $global_settings["rackspace"])
		{
			$sql="select url,filename2,filename1,width,height,item_id from filestorage_files where id_parent=".$rs->row["id_parent"];
			$ds->open($sql);
			while(!$ds->eof)
			{
				if($ds->row["item_id"]!=0)
				{
					$file_url=$ds->row["url"]."/".$ds->row["filename2"];
					$file_width=$ds->row["width"];
					$file_height=$ds->row["height"];
				}
			
				if($ds->row["item_id"]==0 and preg_match("/thumb2/",$ds->row["filename1"]))
				{
					$file_url_thumb=$ds->row["url"]."/".$ds->row["filename2"];
				}
						
				if($ds->row["filename1"]=="thumb_original.jpg")
				{
					$file_url_vector=$ds->row["url"]."/".$ds->row["filename2"];
					$file_width_vector=$ds->row["width"];
					$file_height_vector=$ds->row["height"];
				}
			
				$flag_storage=true;
				$ds->movenext();
			}
		}



		$afile="";
		if($module_table==30)
		{
			if($rs->row["url_jpg"]!="")
			{
				$afile=$rs->row["url_jpg"];
			}
			elseif($rs->row["url_png"]!="")
			{
				$afile=$rs->row["url_png"];
			}
			elseif($rs->row["url_gif"]!="")
			{
				$afile=$rs->row["url_gif"];
			}
			else
			{
				$dir = opendir ($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$rs->row["id_parent"]);
  				while ($file = readdir ($dir)) 
 				{
    				if($file <> "." && $file <> "..")
    				{
						if(preg_match("/.jpg$|.jpeg$|.png$|.gif$/i",$file) and !preg_match("/thumb/",$file) and !preg_match("/photo_[0-9]+/",$file)) 
						{
							$afile=$file;
						}
    				}
  				}
 				closedir ($dir);
			}
		}
		else
		{
			$afile="thumb_original.jpg";
		}
		
		$fextention=strtolower(get_file_info($afile,"extention"));


		if($afile!="" or $flag_storage)
		{
			if($flag_storage)
			{
				if($module_table==30)
				{
					$img_sourse=$file_url;
					$size[0]=$file_width;
					$size[1]=$file_height;
				}
				else
				{
					$img_sourse=$file_url_vector;
					$size[0]=$file_width_vector;
					$size[1]=$file_height_vector;
				}
			}
			else
			{
				$img_sourse=$_SERVER["DOCUMENT_ROOT"].site_root.server_url($rs->row["server1"])."/".$rs->row["id_parent"]."/".$afile;
				$size = getimagesize($img_sourse); 
			}
			$png_file=$_SERVER["DOCUMENT_ROOT"].$global_settings["watermark_photo"];

			if($_GET["z"]<16)
			{
				$resize_method=strtolower($global_settings["image_resize"]);
	
				if($resize_method=="imagemagick" and !class_exists('Imagick'))
				{
					$resize_method="gd";
				}
	
				if($resize_method=="gd")
				{		
					if($fextention=="jpg" or $fextention=="jpeg")
					{
						$im_in = ImageCreateFromJPEG($img_sourse); 
					}
					if($fextention=="png")
					{
						$im_in = ImageCreateFromPNG($img_sourse); 
					}
					if($fextention=="gif")
					{
						$im_in = ImageCreateFromGIF($img_sourse); 
					}
	
					$im_out = imagecreatetruecolor($_GET["width"],$_GET["height"]); 
				}

				$w1=round($size[0]/$_GET["z"]);
				$h1=round($size[1]/$_GET["z"]);

				$k1=$size[0]/$_GET["width"];
				$k2=$size[1]/$_GET["height"];

				$xn=round($k1*($_GET["x1"]+$_GET["x0"]));
				$yn=round($k2*($_GET["y1"]+$_GET["y0"]));

				if($xn+$w1>$size[0]){$xn=$size[0]-$w1;}
				if($yn+$h1>$size[1]){$yn=$size[1]-$h1;}
				
				if($resize_method=="gd")
				{
					if($fextention=="jpg" or $fextention=="jpeg")
					{
						fastimagecopyresampled($im_out, $im_in, 0, 0,$xn,$yn,$_GET["width"],$_GET["height"],$w1,$h1); 
					}
					
					if($fextention=="png")
					{
						imagefill($im_out, 0, 0, imagecolorallocate($im_out, 255, 255, 255));
						imagealphablending($im_out, TRUE);
						imagecopy($im_out, $im_in, 0, 0, 0, 0,$size[0],$size[1]);
						imagecopyresampled($im_out, $im_in, 0, 0,$xn,$yn,$_GET["width"],$_GET["height"],$w1,$h1); 
					}
					
					if($fextention=="gif")
					{
						$transparency = imagecolortransparent($im_in);
						if($transparency >= 0) 
						{
							$transparent_color = imagecolorsforindex($im_in,$transparency);
							$transparency = imagecolorallocate($im_out , $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
							imagefill($im_out, 0, 0, $transparency);
							imagecolortransparent($im_out, $transparency);
						}
						imagecopyresampled($im_out, $im_in, 0, 0,$xn,$yn,$_GET["width"],$_GET["height"],$w1,$h1); 
					}
				}
				else
				{
					$image = new Imagick($img_sourse);
					$image->cropImage ($w1,$h1,$xn ,$yn);
					$image->resizeImage($_GET["width"],$_GET["height"],Imagick::FILTER_LANCZOS,0.8);
				}
				if($resize_method=="gd")
				{
					if(file_exists($png_file) and preg_match("/png$/i",$png_file))
					{
						$im1 = imagecreatefrompng($png_file); 
						$sz = array($_GET["width"],$_GET["height"]);
						$wz = getimagesize($png_file);
	
						$px=0;
						$py=0;
						if($wz[0]<$sz[0] and $wz[1]<$sz[1])
						{
							if($global_settings["watermark_position"]==1)
							{
								$px=0;
								$py=0;
							}
							elseif($global_settings["watermark_position"]==2)
							{
								$px=($sz[0]-$wz[0])/2;
								$py=0;
							}
							elseif($global_settings["watermark_position"]==3)
							{
								$px=$sz[0]-$wz[0];
								$py=0;
							}
							elseif($global_settings["watermark_position"]==4)
							{
								$px=0;
								$py=($sz[1]-$wz[1])/2;
							}
							elseif($global_settings["watermark_position"]==5)
							{
								$px=($sz[0]-$wz[0])/2;
								$py=($sz[1]-$wz[1])/2;
							}
							elseif($global_settings["watermark_position"]==6)
							{
								$px=$sz[0]-$wz[0];
								$py=($sz[1]-$wz[1])/2;
							}
							elseif($global_settings["watermark_position"]==7)
							{
								$px=0;
								$py=$sz[1]-$wz[1];
							}
							elseif($global_settings["watermark_position"]==8)
							{
								$px=($sz[0]-$wz[0])/2;
								$py=$sz[1]-$wz[1];
							}
							else
							{
								$px=$sz[0]-$wz[0];
								$py=$sz[1]-$wz[1];
							}
						}
						imagecopy($im_out,$im1, $px, $py, 0, 0,$wz[0],$wz[1]);
					}
					
					ImageJPEG($im_out); 
				}
				else
				{
					if(file_exists($png_file) and preg_match("/png$/i",$png_file))
					{
						$watermark_image = new Imagick();
						$watermark_image->readImage($png_file);
						
						$iWidth = $image->getImageWidth();
						$iHeight = $image->getImageHeight();
						$wWidth = $watermark_image->getImageWidth();
						$wHeight = $watermark_image->getImageHeight();
						
						/*
						if ($iHeight < $wHeight || $iWidth < $wWidth) 
						{
							$watermark_image->scaleImage($iWidth, $iHeight);
							$wWidth = $watermark_image->getImageWidth();
							$wHeight = $watermark_image->getImageHeight();
						}
						*/
						
						$sz = array($iWidth,$iHeight);
						$wz = array($wWidth,$wHeight);
						
						$px=0;
						$py=0;
						if($wz[0]<$sz[0] and $wz[1]<$sz[1])
						{
							if($global_settings["watermark_position"]==1)
							{
								$px=0;
								$py=0;
							}
							elseif($global_settings["watermark_position"]==2)
							{
								$px=($sz[0]-$wz[0])/2;
								$py=0;
							}
							elseif($global_settings["watermark_position"]==3)
							{
								$px=$sz[0]-$wz[0];
								$py=0;
							}
							elseif($global_settings["watermark_position"]==4)
							{
								$px=0;
								$py=($sz[1]-$wz[1])/2;
							}
							elseif($global_settings["watermark_position"]==5)
							{
								$px=($sz[0]-$wz[0])/2;
								$py=($sz[1]-$wz[1])/2;
							}
							elseif($global_settings["watermark_position"]==6)
							{
								$px=$sz[0]-$wz[0];
								$py=($sz[1]-$wz[1])/2;
							}
							elseif($global_settings["watermark_position"]==7)
							{
								$px=0;
								$py=$sz[1]-$wz[1];
							}
							elseif($global_settings["watermark_position"]==8)
							{
								$px=($sz[0]-$wz[0])/2;
								$py=$sz[1]-$wz[1];
							}
							else
							{
								$px=$sz[0]-$wz[0];
								$py=$sz[1]-$wz[1];
							}		
						}
							
						$image->compositeImage($watermark_image, imagick::COMPOSITE_OVER, $px, $py);
					}
					echo($image);
				}
			}
			else
			{
				if($flag_storage)
				{
					readfile($file_url_thumb);
				}
				else
				{
					readfile($_SERVER["DOCUMENT_ROOT"].site_root.server_url($rs->row["server1"])."/".$rs->row["id_parent"]."/thumb2.jpg");
				}
			}
		}
	}
}

$db->close();
?>