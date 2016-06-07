<?
$DOCUMENT_ROOT=$_SERVER["DOCUMENT_ROOT"].site_root;

$script_version="16.05";

//Site upload directory
define( "site_upload_directory", "/content" );

//Item's quantity into the page's menu
define( "k_str2", 7 );

//Demo mode. It prevents the content removing in the demo version.
$demo_mode=false;




//Session start
ob_start();
ob_clean();
if(!isset($nosession))
{
	session_start();
}


//Mysql class
include( $_SERVER["DOCUMENT_ROOT"].site_root."/admin/function/mysqldb.php" );

//Connection to the database
$db = new TMySQLConnection;
$rs = new TMySQLQuery;
$ds = new TMySQLQuery;
$dr = new TMySQLQuery;
$dn = new TMySQLQuery;
$dd = new TMySQLQuery;
$dq = new TMySQLQuery;

$db->connect();
$mysqli_db=$db->connection;
mysqli_query($mysqli_db,"SET NAMES 'utf8'");
$rs->connection = $db;
$ds->connection = $db;
$dr->connection = $db;
$dn->connection = $db;
$dd->connection = $db;
$dq->connection = $db;

//Define settings
include( $_SERVER["DOCUMENT_ROOT"].site_root."/admin/function/settings.php" );


//Smarty
include($DOCUMENT_ROOT."/admin/plugins/smarty/Smarty.class.php");
$smarty = new Smarty;  
$smarty->compile_dir  = $DOCUMENT_ROOT."/templates_c";  
$smarty->template_dir = $DOCUMENT_ROOT."/admin/plugins/smarty/templates";
$smarty->cache_dir    = $DOCUMENT_ROOT."/cache";  
$smarty->caching      = @$global_settings["caching"];  


//Mail class
include( $_SERVER["DOCUMENT_ROOT"].site_root."/admin/plugins/phpmailer/class.phpmailer.php" );




//Image fast resize function
function fastimagecopyresampled ($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 3) {
  // Plug-and-Play fastimagecopyresampled function replaces much slower imagecopyresampled.
  // Just include this function and change all "imagecopyresampled" references to "fastimagecopyresampled".
  // Typically from 30 to 60 times faster when reducing high resolution images down to thumbnail size using the default quality setting.
  // Author: Tim Eckel - Date: 09/07/07 - Version: 1.1 - Project: FreeRingers.net - Freely distributable - These comments must remain.
  //
  // Optional "quality" parameter (defaults is 3). Fractional values are allowed, for example 1.5. Must be greater than zero.
  // Between 0 and 1 = Fast, but mosaic results, closer to 0 increases the mosaic effect.
  // 1 = Up to 350 times faster. Poor results, looks very similar to imagecopyresized.
  // 2 = Up to 95 times faster.  Images appear a little sharp, some prefer this over a quality of 3.
  // 3 = Up to 60 times faster.  Will give high quality smooth results very close to imagecopyresampled, just faster.
  // 4 = Up to 25 times faster.  Almost identical to imagecopyresampled for most images.
  // 5 = No speedup. Just uses imagecopyresampled, no advantage over imagecopyresampled.

  if (empty($src_image) || empty($dst_image) || $quality <= 0) { return false; }
  if ($quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h)) {
    $temp = imagecreatetruecolor ($dst_w * $quality + 1, $dst_h * $quality + 1);
    imagecopyresized ($temp, $src_image, 0, 0, $src_x, $src_y, $dst_w * $quality + 1, $dst_h * $quality + 1, $src_w, $src_h);
    imagecopyresampled ($dst_image, $temp, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $dst_w * $quality, $dst_h * $quality);
    imagedestroy ($temp);
  } else imagecopyresampled ($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
  return true;
}








//Images resize function
function easyResize($img_sourse, $save_to, $quality, $width)
{ 
	global $global_settings;
	$size = GetImageSize($img_sourse); 
	$new_height = ($width * $size[1]) / $size[0]; // Generate new height for image
	
	$fextention=strtolower(get_file_info($img_sourse,"extention"));
	$fextention2=strtolower(get_file_info($save_to,"extention"));
	
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
			$im_out = imagecreatetruecolor($width, $new_height); 
			if($width<$global_settings["thumb_width2"]+1)
			{
				fastimagecopyresampled($im_out, $im_in, 0, 0, 0, 0, $width, $new_height, $size[0], $size[1]); 			
			}
			else
			{
				imagecopyresampled($im_out, $im_in, 0, 0, 0, 0, $width, $new_height, $size[0], $size[1]); 
			}
		}
		
		if($fextention=="png")
		{
			$im_in = ImageCreateFromPNG($img_sourse);	
			$im_out = imagecreatetruecolor($width, $new_height); 
			
			if($fextention2=="png")
			{
				imagealphablending($im_out , false);
				imagesavealpha($im_out ,true);
				$transparent = imagecolorallocatealpha($im_out , 255, 255, 255, 	127);
				imagefilledrectangle($im_out, 0, 0, $width, $new_height, $transparent); 
			}
			else
			{
				imagefill($im_out, 0, 0, imagecolorallocate($im_out, 255, 255, 255));
				imagealphablending($im_out, TRUE);
				//imagecopy($im_out, $im_in, 0, 0, 0, 0,$size[0],$size[1]);
			}
			
			imagecopyresampled($im_out, $im_in, 0, 0, 0, 0, $width, $new_height, $size[0], $size[1]); 
		}
		
		if($fextention=="gif")
		{
			$im_in = ImageCreateFromGIF($img_sourse); 
			$im_out = imagecreatetruecolor($width, $new_height); 
			
			$transparency = imagecolortransparent($im_in);
			if($transparency >= 0) 
			{
				$transparent_color = imagecolorsforindex($im_in,$transparency);
				$transparency = imagecolorallocate($im_out , $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
				imagefill($im_out, 0, 0, $transparency);
				imagecolortransparent($im_out, $transparency);
			}
	
			imagecopyresampled($im_out, $im_in, 0, 0, 0, 0, $width, $new_height, $size[0], $size[1]); 
		}
		
		if($fextention2=="jpg" or $fextention2=="jpeg")
		{
			ImageJPEG($im_out, $save_to, 100);
		}
		
		if($fextention2=="png")
		{
			ImagePNG($im_out, $save_to);
		}
		
		if($fextention2=="gif")
		{
			ImageGIF($im_out, $save_to);
		}
				
		ImageDestroy($im_in); 
		ImageDestroy($im_out); 
	}

	if($resize_method=="imagemagick")
	{
		$image = new Imagick($img_sourse);
		$image->resizeImage($width,$new_height,Imagick::FILTER_LANCZOS,0.8);
		$image->writeImage($save_to);
		$image->destroy(); 
	}

}






//The function sets dpi for the photos
function set_dpi($save_to)
{
	global $global_settings;
	
	$fextention=strtolower(get_file_info($save_to,"extention"));
	
	if($fextention=="jpg" or $fextention=="jpeg")
	{
		// Change DPI
		$dpi_x   = (int)$global_settings["resolution_dpi"];
		$dpi_y   = (int)$global_settings["resolution_dpi"];
	
		if($dpi_x>0 and $dpi_y>0)
		{
			$img   = file_get_contents($save_to);
		
			// Update DPI information in the JPG header
			$img[13] = chr(1);
			$img[14] = chr(floor($dpi_x/255));
			$img[15] = chr($dpi_x%255);
			$img[16] = chr(floor($dpi_y/255));
			$img[17] = chr($dpi_y%255);

			// Write the new JPG
			file_put_contents($save_to,$img);
		}
	}
}
//End. The function sets dpi for the photos


//Panorama Images resize function
function resize_panorama($img_sourse, $save_to,$photo_vars)
{ 
	global $global_settings;

	$size = GetImageSize($img_sourse); 
	
	$image_height=$size[1];
	$image_width=round(3*$size[1]/2);
	
	
	if($photo_vars==1)
	{
		$thumb_width=$global_settings["thumb_width"];
		$thumb_height=round(2*$global_settings["thumb_width"]/3);
	}
	if($photo_vars==2)
	{
		$thumb_width=$global_settings["thumb_width2"];
		$thumb_height=round(2*$global_settings["thumb_width2"]/3);
	}
	
	$im_in = ImageCreateFromJPEG($img_sourse); 

	$im_out = imagecreatetruecolor($thumb_width,$thumb_height); 

	fastimagecopyresampled($im_out, $im_in, 0, 0, 0, 0,$thumb_width,$thumb_height,$image_width,$image_height); 
	
	//imageinterlace($im_out, true);

	ImageJPEG($im_out, $save_to, 100); // Create image
	ImageDestroy($im_in); 
	ImageDestroy($im_out); 
}




//Watermark
function watermark($img_sourse,$png_file)
{
	global $global_settings;
	
	$fextention=strtolower(get_file_info($img_sourse,"extention"));

	$sz = getimagesize($img_sourse);
	$wz = getimagesize($png_file);

	$px=0;
	$py=0;
	$wx=0;
	$wy=0;
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
	
	if($wz[0]>=$sz[0] and $wz[1]>=$sz[1])
	{
		$px=0;
		$py=0;
		$wx=($wz[0]-$sz[0])/2;
		$wy=($wz[1]-$sz[1])/2;
	}
	
	if($wz[0]<$sz[0] and $wz[1]>=$sz[1])
	{
		$px=($sz[0]-$wz[0])/2;
		$py=0;
		$wx=0;
		$wy=($wz[1]-$sz[1])/2;
	}
	
	if($wz[0]>=$sz[0] and $wz[1]<$sz[1])
	{
		$px=0;
		$py=($sz[1]-$wz[1])/2;
		$wx=($wz[0]-$sz[0])/2;
		$wy=0;
	}
	
	$resize_method=strtolower($global_settings["image_resize"]);
	
	if($resize_method=="imagemagick" and !class_exists('Imagick'))
	{
		$resize_method="gd";
	}
	
	if($resize_method=="gd")
	{	
		$im1 = imagecreatefrompng($png_file); 
		
		if($fextention=="jpg" or $fextention=="jpeg")
		{
			$im2 = ImageCreateFromJPEG($img_sourse);
		}
		
		if($fextention=="png")
		{
			$im2 = ImageCreateFromPNG($img_sourse);
		}
		
		if($fextention=="gif")
		{
			$im2 = ImageCreateFromGIF($img_sourse);
		}
		
		imagecopy($im2,$im1, (int)$px, (int)$py, (int)$wx,(int)$wy,$wz[0],$wz[1]);

		if($fextention=="jpg" or $fextention=="jpeg")
		{
			ImageJPEG($im2,$img_sourse,100); 
		}
		
		if($fextention=="png")
		{
			ImagePNG($im2,$img_sourse); 
		}
		
		if($fextention=="gif")
		{
			ImageGIF($im2,$img_sourse); 
		}	
		
		ImageDestroy($im1); 
		ImageDestroy($im2);
	}
	
	if($resize_method=="imagemagick")
	{
		$image = new Imagick($img_sourse);
		
		$watermark_img = new Imagick();
		$watermark_img->readImage($png_file);
		$image->compositeImage($watermark_img, imagick::COMPOSITE_OVER, (int)$px, (int)$py);

		$image->writeImage($img_sourse);

		$image->destroy(); 
		$watermark_img->destroy(); 
	}
}






//Get resizes photo thumb1; thumb2
function photo_resize($photo_in,$photo_out,$photo_vars)
{
	global $global_settings;

	if(file_exists($photo_in))
	{
		$size = getimagesize ($photo_in);
		$wd1=$global_settings["thumb_width"];
		$wd2=$global_settings["thumb_width2"];
		if(isset($size[1]))
		{
			if($size[0]<$size[1])
			{
				$wd1=$size[0]*$global_settings["thumb_height"]/$size[1];
				$wd2=$size[0]*$global_settings["thumb_height2"]/$size[1];
			}
		}
		
		$panorama_flag=false;
		if($size[0]/$size[1]>3)
		{
			$panorama_flag=true;
		}
	
	if($photo_vars==1)
	{
		if($panorama_flag)
		{
			resize_panorama($photo_in,$photo_out,1);
		}
		else
		{
			easyResize($photo_in,$photo_out,100,$wd1);
		}
	}
	
	if($photo_vars==2)
	{
		if($panorama_flag)
		{
			resize_panorama($photo_in,$photo_out,2);
		}
		else
		{
			easyResize($photo_in,$photo_out,100,$wd2);
		}
	}
	
	}
}






//Currency function
function currency($param,$cr=true,$method="")
{
	global $currency_code1;
	global $currency_code2;
	global $global_settings;
	
	/*
	if($currency_code1=="RUB")
	{
		$currency_code1="<i class='glyphicon glyphicon-ruble'></i>";
	}
	*/

	if(!$global_settings["credits"] or $cr==false)
	{
		if($param==1 and ($currency_code1=="GBP" or $currency_code1=="JPY" or $currency_code1=="USD" or $currency_code1=="NGN")){return $currency_code2;}
		if($param==2 and $currency_code1!="GBP" and $currency_code1!="JPY" and $currency_code1!="USD" and $currency_code1!="NGN"){return $currency_code1;}
	}
	else
	{
		if($param==1 and $global_settings["credits_currency"] and $method=="currency" and ($currency_code1=="GBP" or $currency_code1=="JPY" or $currency_code1=="USD" or $currency_code1=="NGN"))
		{
			return $currency_code2;
		}
		
		if($param==2)
		{
			if($global_settings["credits_currency"])
			{
				if($method=="")
				{
					if($currency_code1=="GBP" or $currency_code1=="JPY" or $currency_code1=="USD" or $currency_code1=="NGN")
					{
						return $currency_code1."&nbsp;".word_lang("or")."&nbsp;".word_lang("credits");	
					}
					else
					{
						return $currency_code1."&nbsp;".word_lang("or")."&nbsp;".word_lang("credits");	
					}
				}
				elseif($method=="currency")
				{
					if($currency_code1!="GBP" and $currency_code1!="JPY" and $currency_code1!="USD" and $currency_code1!="NGN"){return $currency_code1;}
				}
				else
				{
					return word_lang("credits");
				}
			}
			else
			{
				return word_lang("credits");
			}
		}
	}
	
	/*
	if($currency_code1=="<i class='glyphicon glyphicon-ruble'></i>")
	{
		$currency_code1="RUB";
	}
	*/
}







//Translit for cirillic letters
function make_translit($stroka)
{
	//Russian
	$string_cirillic["а"]="a";
	$string_cirillic["б"]="b";
	$string_cirillic["в"]="v";
	$string_cirillic["г"]="g";
	$string_cirillic["д"]="d";
	$string_cirillic["е"]="e";
	$string_cirillic["ё"]="e";
	$string_cirillic["ж"]="zh";
	$string_cirillic["з"]="z";
	$string_cirillic["и"]="i";
	$string_cirillic["й"]="y";
	$string_cirillic["к"]="k";
	$string_cirillic["л"]="l";
	$string_cirillic["м"]="m";
	$string_cirillic["н"]="n";
	$string_cirillic["о"]="o";
	$string_cirillic["п"]="p";
	$string_cirillic["р"]="r";
	$string_cirillic["с"]="s";
	$string_cirillic["т"]="t";
	$string_cirillic["у"]="u";
	$string_cirillic["ф"]="f";
	$string_cirillic["х"]="h";
	$string_cirillic["ц"]="c";
	$string_cirillic["ч"]="ch";
	$string_cirillic["ш"]="sh";
	$string_cirillic["щ"]="sch";
	$string_cirillic["ъ"]="";
	$string_cirillic["ы"]="y";
	$string_cirillic["ь"]="";
	$string_cirillic["э"]="e";
	$string_cirillic["ю"]="yu";
	$string_cirillic["я"]="ya";
	$string_cirillic["А"]="A";
	$string_cirillic["Б"]="B";
	$string_cirillic["В"]="V";
	$string_cirillic["Г"]="G";
	$string_cirillic["Д"]="D";
	$string_cirillic["Е"]="E";
	$string_cirillic["Ё"]="E";
	$string_cirillic["Ж"]="Zh";
	$string_cirillic["З"]="Z";
	$string_cirillic["И"]="I";
	$string_cirillic["Й"]="Y";
	$string_cirillic["К"]="K";
	$string_cirillic["Л"]="L";
	$string_cirillic["М"]="M";
	$string_cirillic["Н"]="N";
	$string_cirillic["О"]="O";
	$string_cirillic["П"]="P";
	$string_cirillic["Р"]="R";
	$string_cirillic["С"]="S";
	$string_cirillic["Т"]="T";
	$string_cirillic["У"]="U";
	$string_cirillic["Ф"]="F";
	$string_cirillic["Х"]="H";
	$string_cirillic["Ц"]="C";
	$string_cirillic["Ч"]="Ch";
	$string_cirillic["Ш"]="Sh";
	$string_cirillic["Щ"]="Sch";
	$string_cirillic["Ы"]="Y";
	$string_cirillic["Э"]="E";
	$string_cirillic["Ю"]="Yu";
	$string_cirillic["Я"]="Ya";
	
	//French
	$string_cirillic["À"]="A";
   $string_cirillic["à"]="a";
   $string_cirillic["Â"]="A";
   $string_cirillic["â"]="a";
   $string_cirillic["Æ"]="Ae";
   $string_cirillic["æ"]="ae";
   $string_cirillic["Ç"]="C";
   $string_cirillic["ç"]="c";
   $string_cirillic["É"]="E";
   $string_cirillic["é"]="e";
   $string_cirillic["È"]="E";
   $string_cirillic["è"]="e";
   $string_cirillic["Ê"]="E";
   $string_cirillic["ê"]="e";
   $string_cirillic["Ë"]="E";
   $string_cirillic["ë"]="e";
   $string_cirillic["Î"]="I";
   $string_cirillic["î"]="i";
   $string_cirillic["Ï"]="I";
   $string_cirillic["ï"]="i";
   $string_cirillic["Ô"]="O";
   $string_cirillic["ô"]="o";
   $string_cirillic["Œ"]="Oe";
   $string_cirillic["œ"]="oe";
   $string_cirillic["Ù"]="U";
   $string_cirillic["ù"]="u";
   $string_cirillic["Û"]="U";
   $string_cirillic["û"]="u";
   $string_cirillic["Ü"]="U";
   $string_cirillic["ü"]="u";
   $string_cirillic["Ÿ"]="Y";
   $string_cirillic["ÿ"]="y";
   
   
	//Polish
	$string_cirillic["Ą"]="A";
	$string_cirillic["Ć"]="C";
	$string_cirillic["Ę"]="E";
	$string_cirillic["Ł"]="L";
	$string_cirillic["Ń"]="N";
	$string_cirillic["Ó"]="O";
	$string_cirillic["Ś"]="S";
	$string_cirillic["Ź"]="Z";
	$string_cirillic["Ż"]="Z";
	$string_cirillic["ą"]="a";
	$string_cirillic["ć"]="c";
	$string_cirillic["ę"]="e";
	$string_cirillic["ł"]="l";
	$string_cirillic["ń"]="n";
	$string_cirillic["ó"]="o";
	$string_cirillic["ś"]="s";
	$string_cirillic["ź"]="z";
	$string_cirillic["ż"]="z";
	 
	 //Spanish
	$string_cirillic["Ñ"]="N";
    $string_cirillic["ñ"]="n";
    $string_cirillic["á"]="a";
    $string_cirillic["é"]="e";
    $string_cirillic["í"]="i";
    
    //Albanian
    $string_cirillic["ë"]="e";
    $string_cirillic["ç"]="c";
    $string_cirillic["Ë"]="e";
    $string_cirillic["Ç"]="c";
	
	$stroka=strtr($stroka,$string_cirillic);
	return $stroka;
}
//End.Translit for cirillic letters


//Item seo-friendly url
function item_url($id,$product_url="")
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;

	$url="";

	if($product_url=="")
	{

		$module_table=0;
		$sql="select id,name,module_table from structure where id=".(int)$id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$title=make_translit($dp->row["name"]);
			$module_table=$dp->row["module_table"];
			$title_correct=preg_replace('/[^a-z0-9 ]/i', '',$title);
			if($title_correct=="" or !preg_match("/[a-z0-9]/i",$title_correct))
			{
				$title="file-".$id;
			}
			else
			{
				$title=strtolower(str_replace(" ","-",$title_correct))."-".$id;
			}

			if($module_table==30)
			{
				$cfolder="stock-photo";
				$ctable="photos";
			}
			if($module_table==31)
			{
				$cfolder="stock-video";
				$ctable="videos";
			}
			if($module_table==52)
			{
				$cfolder="stock-audio";
				$ctable="audio";
			}
			if($module_table==53)
			{
				$cfolder="stock-vector";
				$ctable="vector";
			}



			$url="/".$cfolder."/".$title.".html";

			$sql="update ".$ctable." set url='".$url."' where id_parent=".(int)$id;
			$db->execute($sql);

			$url=site_root.$url;

		}
	}
	else
	{
		$url=site_root.$product_url;
	}
	return $url;
}






//Get item ID
function item_id($catalog,$ctypes)
{
	global $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$id_return=5;

	if(preg_match("/^[0-9]+$/i",$catalog))
	{
		$id_return=(int)$_GET["catalog"];
	}
	
	return $id_return;
}




//Category seo-friendly url
function category_url($id,$product_url="")
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;

	$url="";

	if($product_url=="")
	{
	$sql="select id_parent,title from category where id_parent=".$id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$title=$dp->row["title"];


		if(preg_match('/[^a-z0-9 ]/i',$title))
		{
			$title=$id;
		}
		elseif($title+0>0)
		{
			$title=$id;
		}
		elseif($title!=stripslashes($title))
		{
			$title=$id;
		}
		elseif(preg_match('/update|union|insert|char|delete|drop|alter|char/i',$title))
		{
			$title=$id;
		}
		else
		{
			$sql="select id_parent,title from category where title='".$title."'";
			$dt->open($sql);
			if($dt->rc>1)
			{
				$title=$dp->row["id_parent"];
			}
			$title=str_replace(" ","-",$title);
			$title=strtolower($title);
		}

		$url="/category/".$title.".html";
		
		$sql="update category set url='".$url."' where id_parent=".(int)$id;
		$db->execute($sql);

		$url=site_root.$url;
	}
	}
	else
	{
		$url=site_root.$product_url;
	}
	return $url;
}



//Lightbox seo-friendly url
function lightbox_url($id,$title="")
{
	global $db;
	$dp = new TMySQLQuery;

	$url="";
	
	if($title=="")
	{
		$sql="select title from lightboxes where id=".$id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$title=str_replace(" ","-",$dp->row["title"]);
		}
	}
	
	$title=make_translit($title);
	$title=preg_replace('/[^a-z0-9 ]/i', '',$title);
	$title=str_replace(" ","-",$title);
	$title=strtolower($title);
	
	$url=site_root."/lightbox/".$title."-".$id.".html";

	return $url;
}






//Page seo-friendly url
function page_url($id,$product_url="")
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;

	$url="";

	if($product_url=="")
	{
		$sql="select id_parent,title from pages where id_parent=".(int)$id;
		$dp->open($sql);
		if(!$dp->eof)
		{

			$title=$dp->row["title"];

			if(preg_match('/[^a-z0-9 ]/i',$title))
			{
				$title=$dp->row["id_parent"];
			}
			elseif($title+0>0)
			{
				$title=$dp->row["id_parent"];
			}
			elseif($title!=stripslashes($title))
			{
				$title=$dp->row["id_parent"];
			}
			elseif(preg_match('/update|union|insert|char|delete|drop|alter|char/',$title))
			{
				$title=$dp->row["id_parent"];
			}
			else
			{
				$sql="select title from pages where title='".$title."'";
				$dt->open($sql);
				if($dt->rc>1)
				{
					$title=$dp->row["id_parent"];
				}
				$title=str_replace(" ","-",$title);
				$title=strtolower($title);
			}
			$url="/pages/".$title.".html";
		}


		$sql="update pages set url='".$url."' where id_parent=".(int)$id;
		$db->execute($sql);

		$url=site_root.$url;
	}
	else
	{
		$url=site_root.$product_url;
	}


return $url;
}







//Get size of remote file
function get_size($hostname, $filename)
{
	$end = false;
	$fp = @fsockopen ($hostname, 80, $_errno, $_errstr, 4);

	if ($fp)
	{
		fputs ($fp, "HEAD $filename HTTP/1.0\r\n");
 		fputs ($fp, "Host: $hostname\r\n");
 		fputs ($fp, "Connection: close\r\n");
		fputs ($fp, "\r\n");

		while (!$end)
		{
			$line = fgets($fp, 2048);
			if (trim($line) == "")
			{
				$end = true;
			}
			else
			{
				$str = explode(": ", $line);
				if ($str[0] == "Content-Length") return $str[1];
			}
		}
	}
}


//Check if remote file exists
function remote_check($remote)
{
	$remote=eregi_replace("http://","",$remote);

	$rmt=explode("/",$remote);
	$url=$rmt[0];
	$file="";
	for($i=0;$i<count($rmt);$i++)
	{
		if($i!=0)
		{
			$file.="/".$rmt[$i];
		}
	}
	$s = "";

	$fp = @fsockopen ($url, 80, $_errno, $_errstr, 4);

	if ($fp)
	{
 		fputs ($fp, "HEAD $file HTTP/1.0\r\n");
		fputs ($fp, "Host: $url\r\n");
		fputs ($fp, "Connection: close\r\n");
 		fputs ($fp, "\r\n");

		while (!feof($fp))
  		{  
  			$s .= fgets ($fp);  
  		}
		fclose ($fp);
	}

	if (strpos($s, 'OK') ==false)
	{
		return false;
	}
	else
	{
		return true;
	}
}




//Add credits to user balance
function credits_add($credits_id)
{
	global $db;
	global $_SESSION;
	global $taxes_info;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;

	$sql="select id_parent,title,quantity,price,priority,days from credits where id_parent=".(int)$credits_id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$expiration_date=0;
		if($dp->row["days"]>0)
		{
			$expiration_date=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+3600*24*$dp->row["days"];
		}
		
		$subtotal=$dp->row["price"];
		$discount=0;
		if(isset($_SESSION["coupon_code"]))
		{
			$discount=order_discount_calculate($_SESSION["coupon_code"],$subtotal);
			coupons_delete($_SESSION["coupon_code"]);
		}
		
		$taxes=order_taxes_calculate($dp->row["price"],true,"credits");
		$total=$subtotal+$taxes-$discount;
		
		$sql="select company,vat,business from users where id_parent=".(int)$_SESSION["people_id"];
		$dt->open($sql);
		if(!$dt->eof)
		{
			$sql="insert into credits_list (title,data,user,quantity,approved,payment,credits,expiration_date,subtotal,discount,taxes,total,billing_firstname,billing_lastname,billing_address,billing_city,billing_zip,billing_country,billing_state,taxes_id,billing_company,billing_vat,billing_business) values ('".$dp->row["title"]."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SESSION["people_login"])."','".$dp->row["quantity"]."',0,0,".(int)$credits_id.",".$expiration_date.",".$subtotal.",".$discount.",".$taxes.",".$total.",'".result(@$_SESSION["billing_firstname"])."','".result(@$_SESSION["billing_lastname"])."','".result(@$_SESSION["billing_address"])."','".result(@$_SESSION["billing_city"])."','".result(@$_SESSION["billing_zip"])."','".result(@$_SESSION["billing_country"])."','".result(@$_SESSION["billing_state"])."',".(int)$taxes_info["id"].",'".$dt->row["company"]."','".$dt->row["vat"]."',".$dt->row["business"].")";
			$db->execute($sql);
		}
		
		unset($_SESSION["coupon_code"]);
		unset($_SESSION["billing_firstname"]);
		unset($_SESSION["billing_lastname"]);
		unset($_SESSION["billing_address"]);
		unset($_SESSION["billing_city"]);
		unset($_SESSION["billing_zip"]);
		unset($_SESSION["billing_country"]);
		unset($_SESSION["billing_state"]);
		
		$sql="select id_parent from credits_list where user='".result($_SESSION["people_login"])."' order by data desc";
		$dt->open($sql);
		$id=$dt->row["id_parent"];

		return $id;
	}
}



//Add subscription to user
function subscription_add($subscription_id)
{
	global $db;
	global $_SESSION;
	global $taxes_info;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;

	$sql="select * from subscription where id_parent=".(int)$subscription_id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$subtotal=$dp->row["price"];
		$discount=0;
		if(isset($_SESSION["coupon_code"]))
		{
			$discount=order_discount_calculate($_SESSION["coupon_code"],$subtotal);
			coupons_delete($_SESSION["coupon_code"]);
		}
		$taxes=order_taxes_calculate($dp->row["price"],true,"subscription");
		$total=$subtotal+$taxes-$discount;

		$sql="select company,vat,business from users where id_parent=".(int)$_SESSION["people_id"];
		$dt->open($sql);
		if(!$dt->eof)
		{
			$sql="insert into subscription_list (title,data1,data2,user,approved,bandwidth,bandwidth_limit,subscription,subtotal,discount,taxes,total,billing_firstname,billing_lastname,billing_address,billing_city,billing_zip,billing_country,recurring,payments,bandwidth_daily,bandwidth_daily_limit,bandwidth_date,taxes_id,billing_company,billing_vat,billing_business) values ('".$dp->row["title"]."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+3600*24*$dp->row["days"]).",'".result($_SESSION["people_login"])."',0,0,".$dp->row["bandwidth"].",".(int)$subscription_id.",".$subtotal.",".$discount.",".$taxes.",".$total.",'".result(@$_SESSION["billing_firstname"])."','".result(@$_SESSION["billing_lastname"])."','".result(@$_SESSION["billing_address"])."','".result(@$_SESSION["billing_city"])."','".result(@$_SESSION["billing_zip"])."','".result(@$_SESSION["billing_country"])."',".$dp->row["recurring"].",0,0,".$dp->row["bandwidth_daily"].",0,".(int)$taxes_info["id"].",'".$dt->row["company"]."','".$dt->row["vat"]."',".$dt->row["business"].")";
			$db->execute($sql);
		}
		
		unset($_SESSION["coupon_code"]);
		unset($_SESSION["billing_firstname"]);
		unset($_SESSION["billing_lastname"]);
		unset($_SESSION["billing_address"]);
		unset($_SESSION["billing_city"]);
		unset($_SESSION["billing_zip"]);
		unset($_SESSION["billing_country"]);
		
		$sql="select id_parent from subscription_list where user='".result($_SESSION["people_login"])."' order by data1 desc";
		$dt->open($sql);
		$id=$dt->row["id_parent"];
		
		return $id;
	}
}



//Remove credits from user's balance
function credits_delete($ttl,$order_id)
{
	global $db;
	global $_SESSION;
	$dt = new TMySQLQuery;
	$dt->connection = $db;

	$sql="insert into credits_list (title,data,user,quantity,approved,payment,expiration_date) values ('Order #".$order_id."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SESSION["people_login"])."','".(-1*$ttl)."',1,0,0)";
	$db->execute($sql);
}


//Add transaction to the database
function transaction_add($processor,$tid,$ptype,$pid)
{
	global $db;
	global $_SESSION;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$id=0;
	$title="transaction";
	$total=0.00;
	$user="unknown";

	if($ptype=="credits")
	{
		$sql="select user,credits,total from credits_list where id_parent=".(int)$pid;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$user=$dp->row["user"];
			$sql="select title,price from credits where id_parent=".$dp->row["credits"];
			$dt->open($sql);
			if(!$dt->eof)
			{
				$title=$dt->row["title"];
				$total=$dp->row["total"];
			}
		}
	}


	if($ptype=="subscription")
	{
		$sql="select user,subscription,total from subscription_list where id_parent=".(int)$pid;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$user=$dp->row["user"];
			$sql="select title,price from subscription where id_parent=".$dp->row["subscription"];
			$dt->open($sql);
			if(!$dt->eof)
			{
				$title=$dt->row["title"];
				$total=$dp->row["total"];
			}
		}
	}


	if($ptype=="order")
	{
		$sql="select id,total,user from orders where id=".(int)$pid;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$title="Order #".$pid;
			$total=$dp->row["total"];
			$sql="select login from users where id_parent='".$dp->row["user"]."' order by id_parent desc";
			$dt->open($sql);
			if(!$dt->eof)
			{
				$user=$dt->row["login"];
			}
		}
	}








	$sql="insert into payments (data,user,total,ip,processor,tnumber,ptype,pid) values (".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".$user."','".$total."','".$_SERVER["REMOTE_ADDR"]."','".$processor."','".$tid."','".$ptype."',".$pid.")";
	$db->execute($sql);
	
	$sql="select id_parent from payments where user='".$user."' order by id_parent desc";
	$dt->open($sql);
	$id=$dt->row['id_parent'];

	

	return $id;
}




//Approce credits order
function credits_approve($pid,$tid)
{
	global $db;

	$sql="update credits_list set approved=1,payment=".(int)$tid." where id_parent=".(int)$pid;
	$db->execute($sql);

	//Affiliate commission
	affiliate_add_commission($pid,"credits");
	
	//Create invoice
	add_invoice((int)$pid,"credits");
}



//Approve subscription
function subscription_approve($pid)
{
	global $db;

	$sql="update subscription_list set approved=1,payments=1 where id_parent=".(int)$pid;
	$db->execute($sql);

	//Affiliate commission
	affiliate_add_commission($pid,"subscription");
	
	//Create invoice
	add_invoice((int)$pid,"subscription");
}


//Approve order
function order_approve($pid)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;

	$sql="update orders set status=1 where id=".(int)$pid;
	$db->execute($sql);

	//Affiliate commission
	affiliate_add_commission($pid,"orders");

	downloads_create($pid);
	
	//Create invoice
	$sql="select credits from orders where id=".(int)$pid;
	$dp->open($sql);
	if(!$dp->eof and (int)$dp->row["credits"] != 1)
	{
		add_invoice((int)$pid,"orders");
	}
}





//Create download links for approved order
function downloads_create($pid)
{
	global $db;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$dz = new TMySQLQuery;
	$dz->connection = $db;
	$dx = new TMySQLQuery;
	$dx->connection = $db;

		$sql="select status,user from orders where id=".(int)$pid;
		$dz->open($sql);
		if(!$dz->eof)
		{
			$sql="select item from orders_content where id_parent=".(int)$pid." order by id";
			$dp->open($sql);
			while(!$dp->eof)
			{
				$sql="select id,name,price,id_parent,url,shipped from items where id=".$dp->row["item"];
				$dt->open($sql);
				if(!$dt->eof)
				{
					if($dz->row["status"]==1 and $dt->row["shipped"]!=1)
					{
						$sql="select id_parent from downloads where id_parent=".$dt->row["id"]." and order_id=".(int)$pid;
						$dx->open($sql);
						if($dx->eof)
						{
							$sql="insert into downloads (id_parent,link,data,tlimit,ulimit,order_id,user_id,subscription_id,publication_id) values (".$dt->row["id"].",'".md5(strval(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))).strval($dt->row["id"]))."',".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+$global_settings["download_expiration"]*3600*24).",0,".$global_settings["download_limit"].",".(int)$pid.",".$dz->row["user"].",0,".$dt->row["id_parent"].")";
							$db->execute($sql);

							$sql="update photos set downloaded=downloaded+1 where id_parent=".$dt->row["id_parent"];
							$db->execute($sql);

							$sql="update videos set downloaded=downloaded+1 where id_parent=".$dt->row["id_parent"];
							$db->execute($sql);

							$sql="update audio set downloaded=downloaded+1 where id_parent=".$dt->row["id_parent"];
							$db->execute($sql);

							$sql="update vector set downloaded=downloaded+1 where id_parent=".$dt->row["id_parent"];
							$db->execute($sql);
						}
					}
				}
				$dp->movenext();
			}
		}
}



//Create download links for subscription
function downloads_create_subscription($pid,$pid_parent)
{
	global $db;
	global $_SESSION;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$flag_bandwidth_add=false;

	$sql="select id_parent,subscription from subscription_list where user='".result($_SESSION["people_login"])."' and data2>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and bandwidth<bandwidth_limit and approved=1 order by data2 desc";
	$dp->open($sql);
	if(!$dp->eof)
	{
		$sql="select link from downloads where user_id=".(int)$_SESSION["people_id"]." and subscription_id=".$dp->row["id_parent"]." and publication_id=".(int)$pid_parent." and tlimit<ulimit and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and id_parent=".(int)$pid;
		$dt->open($sql);
		if($dt->eof)
		{
			$flag_bandwidth_add=true;
			
			$sql="insert into downloads (id_parent,link,data,tlimit,ulimit,order_id,user_id,subscription_id,publication_id) values (".(int)$pid.",'".md5(strval(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))).strval($pid))."',".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+$global_settings["download_expiration"]*3600*24).",0,".$global_settings["download_limit"].",0,".(int)$_SESSION["people_id"].",".$dp->row["id_parent"].",".(int)$pid_parent.")";
			$db->execute($sql);
		}
		else
		{
			$sql="update downloads set tlimit=tlimit+1 where user_id=".(int)$_SESSION["people_id"]." and subscription_id=".$dp->row["id_parent"]." and publication_id=".(int)$pid_parent." and id_parent=".(int)$pid;
			$db->execute($sql);
		}
	}
	
	return $flag_bandwidth_add;
}


//Create download links for subscription
function create_free_downloads_link($pid,$pid_parent)
{
	global $db;
	global $_SESSION;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	
	$flag_download=false;
	
	$sql="select downloads,downloads_date from users where id_parent=".(int)$_SESSION["people_id"];
	$dp->open($sql);
	if(!$dp->eof)
	{
		if($dp->row["downloads_date"]!=date("j"))
		{
			$sql="update users set downloads=1,downloads_date=".date("j")." where id_parent=".(int)$_SESSION["people_id"];
			$db->execute($sql);
			$flag_download=true;
		}
		else
		{
			if($dp->row["downloads"]<=$global_settings["daily_download_limit"])
			{
				$sql="update users set downloads=downloads+1 where id_parent=".(int)$_SESSION["people_id"];
				$db->execute($sql);
				$flag_download=true;
			}
		}
	}
	
	if($flag_download)
	{
		$sql="select link from downloads where user_id=".(int)$_SESSION["people_id"]." and subscription_id=0 and publication_id=".(int)$pid_parent." and tlimit<ulimit and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and id_parent=".(int)$pid;
		$dt->open($sql);
		if($dt->eof)
		{
			$sql="insert into downloads (id_parent,link,data,tlimit,ulimit,order_id,user_id,subscription_id,publication_id) values (".(int)$pid.",'".md5(strval(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))).strval($pid))."',".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+$global_settings["download_expiration"]*3600*24).",0,".$global_settings["download_limit"].",0,".(int)$_SESSION["people_id"].",0,".(int)$pid_parent.")";
			$db->execute($sql);
		}
		else
		{
			$sql="update downloads set tlimit=tlimit+1 where user_id=".(int)$_SESSION["people_id"]." and subscription_id=0 and publication_id=".(int)$pid_parent." and id_parent=".(int)$pid;
			$db->execute($sql);
		}
	}
	
	return $flag_download;
}



//Get user's credits balance
function credits_balance()
{
	global $db;
	global $_SESSION;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$balance=0;
	if(isset($_SESSION["people_login"]))
	{
		$expiration_date[]=0;
		$sql="select expiration_date from credits_list where user='".result($_SESSION["people_login"])."' and approved=1 and expiration_date<>0 order by expiration_date";
		$dp->open($sql);
		while(!$dp->eof)
		{
			$expiration_date[]=$dp->row["expiration_date"];
			$dp->movenext();
		}
		$current_time=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
		if($current_time>$expiration_date[count($expiration_date)-1])
		{
			$expiration_date[]=$current_time;
		}
	
		for($i=0;$i<count($expiration_date)-1;$i++)
		{
			$sql="select quantity,expiration_date from credits_list where user='".result($_SESSION["people_login"])."' and approved=1 and data>".($expiration_date[$i]-1)." and data<".($expiration_date[$i+1]+1)." order by data";
			$dp->open($sql);
			while(!$dp->eof)
			{
				if($dp->row["expiration_date"]!=0 and $dp->row["expiration_date"]<$current_time)
				{
				
				}
				else
				{
					$balance+=$dp->row["quantity"];
				}		
				$dp->movenext();
			}
			if($balance<0)
			{
				$balance=0;
			}
		}
	}
	return $balance;
}


//Get use's money balance
function user_balance()
{
	global $db;
	global $_SESSION;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$userbalance=0;

	$sql="select user,total from commission where user=".(int)$_SESSION["people_id"]." and status=1";
	$dp->open($sql);
	while(!$dp->eof)
	{
		$userbalance+=$dp->row["total"];
		$dp->movenext();
	}
	return $userbalance;
}


//Order balance
function order_balance($param)
{
	global $db;
	global $_SESSION;
	$dp = new TMySQLQuery;
	$dp->connection = $db;

	$pp=explode("|",$_SESSION["product_id"]);
	$qq=explode("|",$_SESSION["product_qty"]);


	//count total & shipping
	$total=0;
	$shipping=0;
	for($i=1;$i<count($pp);$i++)
	{
		$sql="select id,price from items where id=".(int)$pp[$i];
		$dp->open($sql);
		if(!$dp->eof)
		{
			$total+=$dp->row["price"];
		}
		
		$sql="select id_parent,price,sprice,sprice2,quantity from prints_items where id_parent=".(int)$pp[$i];
		$dp->open($sql);
		if(!$dp->eof)
		{
			$total+=$dp->row["price"]*$qq[$i];
			$shipping+=$dp->row["sprice"]+$dp->row["sprice"]*($qq[$i]-1);
		}
	}

	if($param=="total"){return $total;}
	if($param=="shipping"){return $shipping;}
}







//Add order to the database
function order_add($sbt,$dsc,$ttl,$shipping,$taxes,$shipping_method,$weight)
{
	global $db;
	global $_SESSION;
	global $global_settings;
	$dpp = new TMySQLQuery;
	$dpp->connection = $db;
	$dtt = new TMySQLQuery;
	$dtt->connection = $db;
	$dx = new TMySQLQuery;
	$dx->connection = $db;
	global $taxes_info;
	
	
	if(!$global_settings["credits"] or ($global_settings["credits_currency"] and @$_SESSION["checkout_method"]=="currency"))
	{
		$credits=0;
	}
	else
	{
		$credits=1;
	}
	
	$sql="select company,vat,business from users where id_parent=".(int)$_SESSION["people_id"];
	$dtt->open($sql);
	if(!$dtt->eof)
	{
		$sql="insert into orders (user,subtotal,discount,total,status,payment,data,shipping,tax,shipping_firstname,shipping_lastname,shipping_address,shipping_country,shipping_city,shipping_zip,shipped,billing_firstname,billing_lastname,billing_address,billing_country,billing_city,billing_zip,shipping_method,shipping_state,billing_state,weight,credits,billing_company,billing_vat,billing_business) values (".(int)$_SESSION["people_id"].",".$sbt.",".$dsc.",".$ttl.",0,0,".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".$shipping.",".$taxes.",'".result($_SESSION["shipping_firstname"])."','".result($_SESSION["shipping_lastname"])."','".result($_SESSION["shipping_address"])."','".result($_SESSION["shipping_country"])."','".result($_SESSION["shipping_city"])."','".result($_SESSION["shipping_zip"])."',0,'".result($_SESSION["billing_firstname"])."','".result($_SESSION["billing_lastname"])."','".result($_SESSION["billing_address"])."','".result($_SESSION["billing_country"])."','".result($_SESSION["billing_city"])."','".result($_SESSION["billing_zip"])."',".(int)$shipping_method.",'".result($_SESSION["shipping_state"])."','".result($_SESSION["billing_state"])."',".(float)$_SESSION["weight"].",".$credits.",'".$dtt->row["company"]."','".$dtt->row["vat"]."',".$dtt->row["business"].")";
		$db->execute($sql);
	}
	
	$sql="select id,user from orders where user=".(int)$_SESSION["people_id"]." order by id desc";
	$dpp->open($sql);
	if(!$dpp->eof)
	{
		$order_id=$dpp->row["id"];
	}

	$cart_id=shopping_cart_id();

	$sql="select id,item_id,prints_id,publication_id,quantity,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab,stock,stock_type,stock_id,stock_url,stock_preview,stock_site_url from carts_content where id_parent=".$cart_id;
	$dpp->open($sql);
	while(!$dpp->eof)
	{
		if($dpp->row["item_id"]>0)
		{
			$sql="select id,price,name,shipped from items where id=".$dpp->row["item_id"];
			$dtt->open($sql);
			if(!$dtt->eof)
			{
				$price=$dtt->row["price"];

				if($dpp->row["rights_managed"]!="")
				{
					$rights_mass=explode("|",$dpp->row["rights_managed"]);
					$price=$rights_mass[0];
				}
				
				if($dtt->row["shipped"]!=1)
				{
					order_taxes_calculate($price,false,"order");
				}
				else
				{
					order_taxes_calculate($price,false,"prints");
				}
				
				if($credits==1)
				{
					$taxes_info["id"] = 0;
					$taxes_info["total"] = 0;
				}
				
				$sql="insert into orders_content (id_parent,item,price,quantity,prints,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab,printslab_id,taxes,taxes_id) values (".$order_id.",".$dtt->row["id"].",".$price.",".$dpp->row["quantity"].",0,0,'',0,'',0,'',0,'',0,'',0,'',0,'',0,'',0,'',0,'','".$dpp->row["rights_managed"]."',".$dpp->row["printslab"].",0,".(float)$taxes_info["total"].",".(int)$taxes_info["id"].")";
				$db->execute($sql);
				
				//Remove exclusive files
				$sql="select module_table from structure where id=".$dpp->row["publication_id"];
				$dx->open($sql);
				if(!$dx->eof)
				{
					if($dx->row["module_table"]==30)
					{
						$sql="update photos set published=-1 where exclusive=1 and id_parent=".$dpp->row["publication_id"];
						$db->execute($sql);
					}
					
					if($dx->row["module_table"]==31)
					{
						$sql="update videos set published=-1 where exclusive=1 and id_parent=".$dpp->row["publication_id"];
						$db->execute($sql);
					}
					
					if($dx->row["module_table"]==52)
					{
						$sql="update audio set published=-1 where exclusive=1 and id_parent=".$dpp->row["publication_id"];
						$db->execute($sql);
					}
					
					if($dx->row["module_table"]==53)
					{
						$sql="update vector set published=-1 where exclusive=1 and id_parent=".$dpp->row["publication_id"];
						$db->execute($sql);
					}
				}
			}
		}

		if($dpp->row["prints_id"]>0)
		{
			
			if((int)$dpp->row["stock"] == 0)
			{
				if($dpp->row["printslab"]!=1)
				{
					$sql="select id_parent,price,title from prints_items where id_parent=".$dpp->row["prints_id"];
					$dtt->open($sql);
					if(!$dtt->eof)
					{
						$price=define_prints_price($dtt->row["price"],$dpp->row["option1_id"],$dpp->row["option1_value"],$dpp->row["option2_id"],$dpp->row["option2_value"],$dpp->row["option3_id"],$dpp->row["option3_value"],$dpp->row["option4_id"],$dpp->row["option4_value"],$dpp->row["option5_id"],$dpp->row["option5_value"],$dpp->row["option6_id"],$dpp->row["option6_value"],$dpp->row["option7_id"],$dpp->row["option7_value"],$dpp->row["option8_id"],$dpp->row["option8_value"],$dpp->row["option9_id"],$dpp->row["option9_value"],$dpp->row["option10_id"],$dpp->row["option10_value"]);
						order_taxes_calculate($price,false,"prints");
						
						if($credits==1)
						{
							$taxes_info["id"] = 0;
							$taxes_info["total"] = 0;
						}
						
						$sql="insert into orders_content (id_parent,item,price,quantity,prints,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab,printslab_id,taxes,taxes_id) values (".$order_id.",".$dtt->row["id_parent"].",".$dtt->row["price"].",".$dpp->row["quantity"].",1,".$dpp->row["option1_id"].",'".$dpp->row["option1_value"]."',".$dpp->row["option2_id"].",'".$dpp->row["option2_value"]."',".$dpp->row["option3_id"].",'".$dpp->row["option3_value"]."',".$dpp->row["option4_id"].",'".$dpp->row["option4_value"]."',".$dpp->row["option5_id"].",'".$dpp->row["option5_value"]."',".$dpp->row["option6_id"].",'".$dpp->row["option6_value"]."',".$dpp->row["option7_id"].",'".$dpp->row["option7_value"]."',".$dpp->row["option8_id"].",'".$dpp->row["option8_value"]."',".$dpp->row["option9_id"].",'".$dpp->row["option9_value"]."',".$dpp->row["option10_id"].",'".$dpp->row["option10_value"]."','',".$dpp->row["printslab"].",0,".(float)$taxes_info["total"]*$dpp->row["quantity"].",".(int)$taxes_info["id"].")";
						$db->execute($sql);
					}
				}
				else
				{
					$sql="select id_parent,price,title from prints where id_parent=".$dpp->row["prints_id"];
					$dtt->open($sql);
					if(!$dtt->eof)
					{
						$price=define_prints_price($dtt->row["price"],$dpp->row["option1_id"],$dpp->row["option1_value"],$dpp->row["option2_id"],$dpp->row["option2_value"],$dpp->row["option3_id"],$dpp->row["option3_value"],$dpp->row["option4_id"],$dpp->row["option4_value"],$dpp->row["option5_id"],$dpp->row["option5_value"],$dpp->row["option6_id"],$dpp->row["option6_value"],$dpp->row["option7_id"],$dpp->row["option7_value"],$dpp->row["option8_id"],$dpp->row["option8_value"],$dpp->row["option9_id"],$dpp->row["option9_value"],$dpp->row["option10_id"],$dpp->row["option10_value"]);
						order_taxes_calculate($price,false,"prints");
						
						if($credits==1)
						{
							$taxes_info["id"] = 0;
							$taxes_info["total"] = 0;
						}
						
						$sql="insert into orders_content (id_parent,item,price,quantity,prints,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab,printslab_id,taxes,taxes_id) values (".$order_id.",".$dtt->row["id_parent"].",".$dtt->row["price"].",".$dpp->row["quantity"].",1,".$dpp->row["option1_id"].",'".$dpp->row["option1_value"]."',".$dpp->row["option2_id"].",'".$dpp->row["option2_value"]."',".$dpp->row["option3_id"].",'".$dpp->row["option3_value"]."',".$dpp->row["option4_id"].",'".$dpp->row["option4_value"]."',".$dpp->row["option5_id"].",'".$dpp->row["option5_value"]."',".$dpp->row["option6_id"].",'".$dpp->row["option6_value"]."',".$dpp->row["option7_id"].",'".$dpp->row["option7_value"]."',".$dpp->row["option8_id"].",'".$dpp->row["option8_value"]."',".$dpp->row["option9_id"].",'".$dpp->row["option9_value"]."',".$dpp->row["option10_id"].",'".$dpp->row["option10_value"]."','',".$dpp->row["printslab"].",".$dpp->row["publication_id"].",".(float)$taxes_info["total"]*$dpp->row["quantity"].",".(int)$taxes_info["id"].")";
						$db->execute($sql);
					}
				}
			}
			else
			{
				$sql="select id_parent,price,title from prints where id_parent=".$dpp->row["prints_id"];
				$dtt->open($sql);
				if(!$dtt->eof)
				{
					$price=define_prints_price($dtt->row["price"],$dpp->row["option1_id"],$dpp->row["option1_value"],$dpp->row["option2_id"],$dpp->row["option2_value"],$dpp->row["option3_id"],$dpp->row["option3_value"],$dpp->row["option4_id"],$dpp->row["option4_value"],$dpp->row["option5_id"],$dpp->row["option5_value"],$dpp->row["option6_id"],$dpp->row["option6_value"],$dpp->row["option7_id"],$dpp->row["option7_value"],$dpp->row["option8_id"],$dpp->row["option8_value"],$dpp->row["option9_id"],$dpp->row["option9_value"],$dpp->row["option10_id"],$dpp->row["option10_value"]);
					order_taxes_calculate($price,false,"prints");
					
					if($credits==1)
					{
						$taxes_info["id"] = 0;
						$taxes_info["total"] = 0;
					}
					
					$sql="insert into orders_content (id_parent,item,price,quantity,prints,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab,printslab_id,stock,stock_type,stock_id,stock_url,stock_preview,stock_site_url,taxes,taxes_id) values (".$order_id.",".$dtt->row["id_parent"].",".$dtt->row["price"].",".$dpp->row["quantity"].",1,".$dpp->row["option1_id"].",'".$dpp->row["option1_value"]."',".$dpp->row["option2_id"].",'".$dpp->row["option2_value"]."',".$dpp->row["option3_id"].",'".$dpp->row["option3_value"]."',".$dpp->row["option4_id"].",'".$dpp->row["option4_value"]."',".$dpp->row["option5_id"].",'".$dpp->row["option5_value"]."',".$dpp->row["option6_id"].",'".$dpp->row["option6_value"]."',".$dpp->row["option7_id"].",'".$dpp->row["option7_value"]."',".$dpp->row["option8_id"].",'".$dpp->row["option8_value"]."',".$dpp->row["option9_id"].",'".$dpp->row["option9_value"]."',".$dpp->row["option10_id"].",'".$dpp->row["option10_value"]."','',".$dpp->row["printslab"].",".$dpp->row["publication_id"].",1,'".$dpp->row["stock_type"]."',".$dpp->row["stock_id"].",'".$dpp->row["stock_url"]."','".$dpp->row["stock_preview"]."','".$dpp->row["stock_site_url"]."',".(float)$taxes_info["total"]*$dpp->row["quantity"].",".(int)$taxes_info["id"].")";
					$db->execute($sql);
				}			
			}
		}
		
		$dpp->movenext();
	}
	
	//Update carts
	$sql="update carts set order_id=".$order_id.",user_id=".(int)$_SESSION["people_id"]." where id=".$cart_id;
	$db->execute($sql);

	$_SESSION["shopping_cart_id"]=0;
	$_SESSION["box_shopping_cart"]="";
	$_SESSION["box_shopping_cart_lite"]="";

	//Update coupon
	$sql="update coupons set used=1 where tlimit=ulimit";
	$db->execute($sql);

	return $order_id;
}







//Get a discount
function discount_balance($sbt)
{
	global $db;
	global $_SESSION;
	global $_POST;
	$dp = new TMySQLQuery;
	$dp->connection = $db;

	$dsc=0;
	$tip=0;
	$price=0;
	$coupons=array();
	
	$sql="select * from coupons where user='".result($_SESSION["people_login"])."' and used=0 order by percentage desc";
	$dp->open($sql);
	if(!$dp->eof)
	{
		while(!$dp->eof)
		{
			if($dp->row["percentage"]>0)
			{
				if((int)$_POST["coupons"]==$dp->row["id_parent"])
				{
					$tip=1;$price=$dp->row["percentage"];$coupons[count($coupons)]=$dp->row["id_parent"];

				$sql="update coupons set tlimit=tlimit+1 where id_parent=".$dp->row["id_parent"];
				$db->execute($sql);
				}
			}
			else
			{
				if(isset($_POST["coupons".$dp->row["id_parent"]]) and $tip==0)
				{
					$price+=$dp->row["total"];$coupons[count($coupons)]=$dp->row["id_parent"];

					$sql="update coupons set tlimit=tlimit+1 where id_parent=".$dp->row["id_parent"];
					$db->execute($sql);
				}
			}	

			$dp->movenext();
		}
	}  

	if($tip==1){$dsc=$sbt*$price/100;}
	else{$dsc=$price;}

	return $dsc;
}







//Add commission for order
function commission_add($order_id)
{
	global $db;
	global $_SESSION;
	global $DOCUMENT_ROOT;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$dm = new TMySQLQuery;
	$dm->connection = $db;
	$dx = new TMySQLQuery;
	$dx->connection = $db;

	$user_order="";
	$sql="select user from orders where id=".(int)$order_id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$sql="select login from users where id_parent=".$dp->row["user"];
		$dt->open($sql);
		if(!$dt->eof)
		{
			$user_order=$dt->row["login"];
		}
	}


	$sql="select a.id,a.status,a.data,b.id,b.id_parent,b.price,b.item,b.quantity,b.prints,b.printslab,b.option1_id,b.option1_value,b.option2_id,b.option2_value,b.option3_id,b.option3_value,b.option4_id,b.option4_value,b.option5_id,b.option5_value,b.option6_id,b.option6_value,b.option7_id,b.option7_value,b.option8_id,b.option8_value,b.option9_id,b.option9_value,b.option10_id,b.option10_value from orders a,orders_content b where a.id=b.id_parent and a.id=".$order_id." order by a.data desc";
	$dp->open($sql);
	while(!$dp->eof)
	{
		$flag=false;
		if($dp->row["printslab"]!=1)
		{
			$idp=0;
			$userid=0;
			$userlogin="";
			$url="";
			$title="";
			$types="";

			$sql="select id,id_parent from items where id=".$dp->row["item"];
			$dt->open($sql);
			if(!$dt->eof)
			{
				$idp=$dt->row["id_parent"];
			}

			$sql="select id_parent,userid,title,author from photos where id_parent=".$idp;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$userid=$dt->row["userid"];
				$userlogin=$dt->row["author"];
				$flag=true;
				$types="photos";
			}

			$sql="select id_parent,userid,title,author from videos where id_parent=".$idp;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$userid=$dt->row["userid"];
				$userlogin=$dt->row["author"];
				$flag=true;
				$types="videos";
			}

			$sql="select id_parent,userid,title,author from audio where id_parent=".$idp;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$userid=$dt->row["userid"];
				$userlogin=$dt->row["author"];
				$flag=true;
				$types="audio";
			}

			$sql="select id_parent,userid,title,author from vector where id_parent=".$idp;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$userid=$dt->row["userid"];
				$userlogin=$dt->row["author"];
				$flag=true;
				$types="vector";
			}

			if($dp->row["prints"]==1)
			{
				$sql="select id_parent,itemid from prints_items where id_parent=".$dp->row["item"];
				$dt->open($sql);
				if(!$dt->eof)
				{
					$sql="select id_parent,userid,title,author from photos where id_parent=".$dt->row["itemid"];
					$dm->open($sql);
					if(!$dm->eof)
					{
						$userid=$dm->row["userid"];
						$userlogin=$dm->row["author"];
					}

					$flag=true;
					$types="prints_items";
					$idp=$dp->row["item"];
				}
			}
		}
		else
		{
			if($dp->row["prints"]==1)
			{
				$sql="select id_parent from galleries_photos where id=".$dp->row["item"];
				$dt->open($sql);
				if(!$dt->eof)
				{
					$sql="select user_id from galleries where id_parent=".$dt->row["id_parent"];
					$dm->open($sql);
					if(!$dm->eof)
					{
						$userid=$dm->row["user_id"];
						$userlogin=user_url_back($dm->row["user_id"]);
					}

					$flag=true;
					$types="prints_items";
					$idp=$dp->row["item"];
				}
			}
		}

		if($flag==true and $user_order!=$userlogin)
		{
			$category="";
			$sql="select id_parent,login,category from users where id_parent=".(int)$userid." or login='".$userlogin."'";
			$dt->open($sql);
			if(!$dt->eof)
			{
				$category=$dt->row["category"];
				if($userid==0){$userid=$dt->row["id_parent"];}
			}

			$percentage=0;
			$percentage_prints=0;
			$percentage_type=0;
			$percentage_prints_type=0;

			$sql="select name,percentage,percentage_prints,percentage_type,percentage_prints_type from user_category where name='".$category."'";
			$dt->open($sql);
			if(!$dt->eof)
			{
				$percentage=$dt->row["percentage"];
				$percentage_prints=$dt->row["percentage_prints"];
				$percentage_type=$dt->row["percentage_type"];
				$percentage_prints_type=$dt->row["percentage_prints_type"];
			}
			
			if($dp->row["printslab"]!=1)
			{
				$price_total=define_prints_price($dp->row["price"],$dp->row["option1_id"],$dp->row["option1_value"],$dp->row["option2_id"],$dp->row["option2_value"],$dp->row["option3_id"],$dp->row["option3_value"],$dp->row["option4_id"],$dp->row["option4_value"],$dp->row["option5_id"],$dp->row["option5_value"],$dp->row["option6_id"],$dp->row["option6_value"],$dp->row["option7_id"],$dp->row["option7_value"],$dp->row["option8_id"],$dp->row["option8_value"],$dp->row["option9_id"],$dp->row["option9_value"],$dp->row["option10_id"],$dp->row["option10_value"]);
				
				if($types!="prints_items")
				{			
					if ($percentage_type == 0) {
						$commission_total = $dp->row["quantity"] * $percentage * $price_total / 100;
					} else {
						$commission_total = $percentage;
					}
		
					$sql="insert into commission (total,user,orderid,item,publication,types,data,description,status) values (".float_opt($commission_total,2).",".$userid.",".$dp->row["id_parent"].",".$dp->row["id"].",".$idp.",'".$types."',".$dp->row["data"].",'order',1)";
					$db->execute($sql);
					
					send_notification("commission_to_seller",$userid,"O".$dp->row["id_parent"],$idp,float_opt($commission_total,2));			
				}
				else
				{
					if ($percentage_prints_type == 0) {
						$commission_total = $dp->row["quantity"] * $percentage_prints * $price_total / 100;
					} else {
						$commission_total = $percentage_prints;
					}		
					
					$sql="insert into commission (total,user,orderid,item,publication,types,data,description,status) values (".float_opt($commission_total ,2).",".$userid.",".$dp->row["id_parent"].",".$dp->row["id"].",".$idp.",'".$types."',".$dp->row["data"].",'order',1)";
					$db->execute($sql);
					
					send_notification("commission_to_seller",$userid,"O".$dp->row["id_parent"],$idp,float_opt($commission_total,2));	
				}		
			}
			
			//Affiliate commission
			if($global_settings["affiliates"])
			{
				$sql="select id_parent,aff_referal from users where id_parent=".$userid;
				$dt->open($sql);
				if(!$dt->eof)
				{
					if((int)$dt->row["aff_referal"]>0)
					{
						$sql="select aff_commission_seller from users where id_parent=".$dt->row["aff_referal"];
						$dx->open($sql);
						if(!$dx->eof)
						{
							$price_total=define_prints_price($dp->row["price"],$dp->row["option1_id"],$dp->row["option1_value"],$dp->row["option2_id"],$dp->row["option2_value"],$dp->row["option3_id"],$dp->row["option3_value"],$dp->row["option4_id"],$dp->row["option4_value"],$dp->row["option5_id"],$dp->row["option5_value"],$dp->row["option6_id"],$dp->row["option6_value"],$dp->row["option7_id"],$dp->row["option7_value"],$dp->row["option8_id"],$dp->row["option8_value"],$dp->row["option9_id"],$dp->row["option9_value"],$dp->row["option10_id"],$dp->row["option10_value"]);
							
							$total=$price_total*$dx->row["aff_commission_seller"]/100;
							$sql="insert into affiliates_signups (userid,types,types_id,rates,total,data,aff_referal,status) values (".$userid.",'orders',".$dp->row["id_parent"].",".$dx->row["aff_commission_seller"].",".$total.",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".$dt->row["aff_referal"].",1)";
							$db->execute($sql);
						
							send_notification("commission_to_affiliate",$userid,"O".	$dp->row["id_parent"],"",$total);						
						}
					}
				}			
			}
			//End. Affiliate commission		
		}
	$dp->movenext();
	}
}


//Add a commission for subscription
function commission_subscription_add($user_login,$publication_id,$item_id)
{
	global $_SESSION;
	global $db;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$dx = new TMySQLQuery;
	$dx->connection = $db;

	$price=0;
	$userid=0;
	$subscription_id=0;
	
	$sql="select price from items where id=".(int)$item_id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$price=round($dp->row["price"]);
	}
	
	$sql="select id_parent from subscription_list where user='".result($user_login)."' and data2>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and bandwidth<bandwidth_limit and approved=1 order by data2 desc";
	$dp->open($sql);
	if(!$dp->eof)
	{
		$subscription_id=$dp->row["id_parent"];
	}
	
	
	$sql="select userid,author from photos where id_parent=".(int)$publication_id;
	$dt->open($sql);
	if(!$dt->eof)
	{
		$userid=$dt->row["userid"];
		$userlogin=$dt->row["author"];
		$types="photos";
	}


	$sql="select userid,author from videos where id_parent=".(int)$publication_id;
	$dt->open($sql);
	if(!$dt->eof)
	{
		$userid=$dt->row["userid"];
		$userlogin=$dt->row["author"];
		$types="videos";
	}
	
	$sql="select userid,author from audio where id_parent=".(int)$publication_id;
	$dt->open($sql);
	if(!$dt->eof)
	{
		$userid=$dt->row["userid"];
		$userlogin=$dt->row["author"];
		$types="audio";
	}
	
	$sql="select userid,author from vector where id_parent=".(int)$publication_id;
	$dt->open($sql);
	if(!$dt->eof)
	{
		$userid=$dt->row["userid"];
		$userlogin=$dt->row["author"];
		$types="vector";
	}
	
	if($price>0 and $subscription_id>0 and $userlogin!=$_SESSION["people_login"])
	{
	
		$category="";
		$sql="select id_parent,category from users where id_parent=".(int)$userid." or login='".$userlogin."'";
		$dt->open($sql);
		if(!$dt->eof)
		{
			$category=$dt->row["category"];
			if($userid==0){$userid=$dt->row["id_parent"];}
		}

		$percentage_subscription=0;
		$percentage_subscription_type=0;
		$sql="select name,percentage_subscription,percentage_subscription_type from user_category where name='".$category."'";
		$dt->open($sql);
		if(!$dt->eof)
		{
			$percentage_subscription=$dt->row["percentage_subscription"];
			$percentage_subscription_type=$dt->row["percentage_subscription_type"];
		}
		
		if ($percentage_subscription_type == 0) {
			$commission_total = $percentage_subscription * $price / 100;
		} else {
			$commission_total = $percentage_subscription;
		}



		$sql="insert into commission (total,user,orderid,item,publication,types,data,description,status) values (".float_opt($commission_total,2).",".$userid.",".$subscription_id.",".(int)$item_id.",".(int)$publication_id.",'".$types."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'subscription',1)";
		$db->execute($sql);
		
		send_notification("commission_to_seller",$userid,"S".$subscription_id,$publication_id,float_opt($commission_total,2));
		
		//Affiliate commission
		
		if($global_settings["affiliates"])
		{
			$sql="select id_parent,aff_referal from users where id_parent=".$userid;
			$dt->open($sql);
			if(!$dt->eof)
			{
				if((int)$dt->row["aff_referal"]>0)
				{
					$sql="select aff_commission_seller from users where id_parent=".$dt->row["aff_referal"];
					$dx->open($sql);
					if(!$dx->eof)
					{
						$total=$price*$dx->row["aff_commission_seller"]/100;
						$sql="insert into affiliates_signups (userid,types,types_id,rates,total,data,aff_referal,status) values (".$userid.",'subscription',".$subscription_id.",".$dx->row["aff_commission_seller"].",".$total.",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".$dt->row["aff_referal"].",1)";
						$db->execute($sql);
						
						send_notification("commission_to_affiliate",$userid,"S".$subscription_id,"",$total);
						
					}
				}
			}			
		}
		
		//End. Affiliate commission
	
	}



}






//Add coupons
function coupons_add($user,$type="New Order")
{
	global $db;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	
	$coupon_code=md5(create_password().mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));

	$sql="select id_parent,title,days,total,percentage,url,events,ulimit,bonus from coupons_types where events='".result($type)."'";
	$dp->open($sql);
	while(!$dp->eof)
	{
		$sql="select id_parent from coupons where user='".result($user)."' and used=0 and coupon_id=".$dp->row["id_parent"];
		$dt->open($sql);
		if($dt->eof)
		{
			$used=0;
			if($dp->row["bonus"]!=0 and $global_settings["credits"])
			{
				$sql="insert into credits_list (title,data,user,quantity,approved,payment,expiration_date) values ('".$dp->row["title"]."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($user)."','".$dp->row["bonus"]."',1,0,0)";
				$db->execute($sql);
				
				$used=1;
			}
			
			if($used==0)
			{
				$sql="insert into coupons (title,user,data2,total,percentage,url,used,data,ulimit,tlimit,coupon_code,coupon_id) values ('".$dp->row["title"]."','".result($user)."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".$dp->row["total"].",".$dp->row["percentage"].",'".$dp->row["url"]."',".$used.",".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+$dp->row["days"]*3600*24).",".$dp->row["ulimit"].",0,'".$coupon_code."',".$dp->row["id_parent"].")";
				$db->execute($sql);
			}
		}

		$dp->movenext();
	}
}



//Define a user of the order
function order_user($id)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$user="";
	$sql="select user from orders where id=".(int)$id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$sql="select login from users where id_parent=".$dp->row["user"];
		$dt->open($sql);
		if(!$dt->eof)
		{
			$user=$dt->row["login"];
		}
	}
	return $user;
}



//Define if a user has a subscription
function user_subscription($login,$id_parent)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$flag=false;

	$content_type="";

	$sql="select content_type from photos where id_parent=".$id_parent;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$content_type=$dp->row["content_type"];
	}

	$sql="select content_type from videos where id_parent=".$id_parent;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$content_type=$dp->row["content_type"];
	}

	$sql="select content_type from audio where id_parent=".$id_parent;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$content_type=$dp->row["content_type"];
	}

	$sql="select content_type from vector where id_parent=".$id_parent;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$content_type=$dp->row["content_type"];
	}


	$sql="select id_parent,subscription,bandwidth_daily,bandwidth_daily_limit ,bandwidth_date from subscription_list where user='".$login."' and data2>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and bandwidth<bandwidth_limit and approved=1 order by data2 desc";
	$dp->open($sql);
	if(!$dp->eof)
	{
		$sql="select id_parent from subscription where id_parent=".$dp->row["subscription"]." and content_type like '%".$content_type."%'";
		$dt->open($sql);
		if(!$dt->eof)
		{
			if($dp->row["bandwidth_daily_limit"]==0)
			{
				$flag=true;
			}
			else
			{
				if(date("j")==$dp->row["bandwidth_date"])
				{
					if($dp->row["bandwidth_daily"]<=$dp->row["bandwidth_daily_limit"])
					{
						$flag=true;
					}
				}
				else
				{
					$sql="update subscription_list set bandwidth_daily=0,bandwidth_date=".date("j")." where id_parent=".$dp->row["id_parent"];
					$db->execute($sql);
					$flag=true;
				}
			}
		}
	}
	return $flag;
}





//Define subscription limits
function bandwidth_user($login,$param)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$bn=0;
	$bn2=0;

	$sql="select bandwidth,bandwidth_limit from subscription_list where user='".$login."' and data2>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and bandwidth<bandwidth_limit and approved=1";
	$dp->open($sql);
	if(!$dp->eof)
	{
		$bn=$dp->row["bandwidth"];
		$bn2=$dp->row["bandwidth_limit"];
	}

	if($param==0){return $bn;}
	else{return $bn2;}
}




//Increase limits after the download
function bandwidth_add($login,$fsize)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;

	$sql="select id_parent,bandwidth_daily_limit from subscription_list where user='".$login."' and data2>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and bandwidth<bandwidth_limit and approved=1";
	$dp->open($sql);
	if(!$dp->eof)
	{
		$sql="update subscription_list set bandwidth=bandwidth+".$fsize." where id_parent=".$dp->row["id_parent"];
		$db->execute($sql);
		
		if($dp->row["bandwidth_daily_limit"]!=0)
		{
			$sql="update subscription_list set bandwidth_daily=bandwidth_daily+".$fsize." where id_parent=".$dp->row["id_parent"];
			$db->execute($sql);
		}
	}
}



//Remove items
function delete_files($id,$folder_delete=true)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;

	$folder=(int)$id;
	$server1="";

	$sql="select server1 from photos where id_parent=".(int)$id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$server1=$dp->row["server1"];
	}

	$sql="select server1 from videos where id_parent=".(int)$id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$server1=$dp->row["server1"];
	}

	$sql="select server1 from audio where id_parent=".(int)$id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$server1=$dp->row["server1"];
	}

	$sql="select server1 from vector where id_parent=".(int)$id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$server1=$dp->row["server1"];
	}

if($folder!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($server1)."/".$folder))
{
	$dir = opendir ($_SERVER["DOCUMENT_ROOT"].site_root.server_url($server1)."/".$folder);
	while ($file = readdir ($dir)) 
	{
		if($file <> "." && $file <> "..")
		{
			@unlink($_SERVER["DOCUMENT_ROOT"].site_root.server_url($server1)."/".$folder."/".$file);
		
		}
	}
	
	if($folder_delete)
	{
		@rmdir($_SERVER["DOCUMENT_ROOT"].site_root.server_url($server1)."/".$folder);
	}
}

}



//Define a photo's color
function define_color($img)
{
	global $_POST;
	if(!isset($_POST["color"]) or $_POST["color"]=="")
	{
		$color="undefined";

		$size = GetImageSize($img); 
		$im_in = ImageCreateFromJPEG($img); 

		$im_out = imagecreatetruecolor(1,1); 

		fastimagecopyresampled($im_out, $im_in, 0, 0, 0, 0,1,1, $size[0], $size[1],3);
		$rgb=dechex(imagecolorat($im_out,0,0));

		if(strlen($rgb)==6)
		{
			$r=hexdec($rgb[0].$rgb[1]);
			$g=hexdec($rgb[2].$rgb[3]);
			$b=hexdec($rgb[4].$rgb[5]);

			$colors = array();
			$colors["black"]=sqrt(($r-0)*($r-0)+($g-0)*($g-0)+($b-0)*($b-0))+200;
			$colors["white"]=sqrt(($r-256)*($r-256)+($g-256)*($g-256)+($b-256)*($b-256))+200;
			$colors["red"]=sqrt(($r-256)*($r-256)+($g-0)*($g-0)+($b-0)*($b-0));
			$colors["green"]=sqrt(($r-0)*($r-0)+($g-256)*($g-256)+($b-0)*($b-0));
			$colors["blue"]=sqrt(($r-0)*($r-0)+($g-0)*($g-0)+($b-256)*($b-256));
			$colors["magenta"]=sqrt(($r-256)*($r-256)+($g-0)*($g-0)+($b-256)*($b-256));
			$colors["cian"]=sqrt(($r-0)*($r-0)+($g-256)*($g-256)+($b-256)*($b-256));
			$colors["yellow"]=sqrt(($r-256)*($r-256)+($g-256)*($g-256)+($b-0)*($b-0));
			$colors["orange"]=sqrt(($r-256)*($r-256)+($g-128)*($g-128)+($b-0)*($b-0))+150;

			asort($colors);
			reset($colors);

			$i=0;
			foreach ($colors as $key => $value) 
			{
				if($i==0){$color=$key;}
				$i++;
			}
		}
		ImageDestroy($im_in); 
		ImageDestroy($im_out); 
	}
	else
	{
		$color=result($_POST["color"]);
	}

	return $color;
}



//Get a license
function define_license()
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$license="";
	$sql="select name from licenses order by priority";
	$dp->open($sql);
	if(!$dp->eof)
	{
		$license=$dp->row["name"];
	}
	return $license;
}


//Send a notification to a user
function send_notification($evt,$p1="",$p2="",$p3="",$p4="",$preview_test=false)
{
	global $db;
	global $_POST;
	global $_SESSION;
	global $_POST;
	global $_REQUEST;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$dw = new TMySQLQuery;
	$dw->connection = $db;
	$dz = new TMySQLQuery;
	$dz->connection = $db;
	$dx = new TMySQLQuery;
	$dx->connection = $db;

	$sql="select subject,message,html,events,title from notifications where events='".$evt."' and enabled=1";
	$dp->open($sql);
	if(!$dp->eof)
	{
		$from_email=$global_settings["from_email"];
		$to_email=$global_settings["admin_email"];
		$subject=$dp->row["subject"];
		if($dp->row["html"]==1)
		{
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/".$dp->row["events"].".tpl"))
			{
				$textsend=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/".$dp->row["events"].".tpl");
			}
			else
			{
				$textsend=$dp->row["message"];
			}
		}
		else
		{
			$textsend=$dp->row["message"];
		}

		if($evt=="contacts_to_admin")
		{
			$textsend=str_replace("{WORD_NAME}",word_lang("full name"),$textsend);
			$textsend=str_replace("{NAME}",$_POST["name"],$textsend);
			$textsend=str_replace("{WORD_EMAIL}",word_lang("e-mail"),$textsend);
			$textsend=str_replace("{EMAIL}",$_POST["email"],$textsend);
			$textsend=str_replace("{WORD_TELEPHONE}",word_lang("telephone"),$textsend);
			$textsend=str_replace("{TELEPHONE}",$_POST["telephone"],$textsend);
			$textsend=str_replace("{WORD_METHOD}",word_lang("contact method"),$textsend);
			$textsend=str_replace("{METHOD}",$_POST["method"],$textsend);
			$textsend=str_replace("{WORD_QUESTION}",word_lang("question"),$textsend);
			$textsend=str_replace("{QUESTION}",$_POST["question"],$textsend);
			$textsend=str_replace("{WORD_DATE}",word_lang("date"),$textsend);
			$textsend=str_replace("{DATE}",date(datetime_format),$textsend);
		}


		if($evt=="contacts_to_user")
		{
			$to_email=$_POST["email"];
			$textsend=str_replace("{NAME}",$_POST["name"],$textsend);
		}


		if($evt=="fraud_to_user")
		{
			$to_email=$p2;
			$textsend=str_replace("{NEWPASSWORD}",$p1,$textsend);
			$textsend=str_replace("{NAME}",$p3,$textsend);
		}


		if($evt=="neworder_to_user")
		{
			$subject=str_replace("{ORDER_ID}",$p1,$subject);

			$sql="select * from orders where id=".(int)$p1;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$textsend=str_replace("{WORD_ORDER}",word_lang("order"),$textsend);
				$textsend=str_replace("{WORD_SUBTOTAL}",word_lang("subtotal"),$textsend);
				$textsend=str_replace("{WORD_DISCOUNT}",word_lang("discount"),$textsend);
				$textsend=str_replace("{WORD_SHIPPING}",word_lang("shipping"),$textsend);
				$textsend=str_replace("{WORD_TAXES}",word_lang("taxes"),$textsend);
				$textsend=str_replace("{WORD_TOTAL}",word_lang("total"),$textsend);
				$textsend=str_replace("{WORD_DATE}",word_lang("date"),$textsend);
				$textsend=str_replace("{WORD_LOGIN}",word_lang("login"),$textsend);
				$textsend=str_replace("{WORD_NAME}",word_lang("full name"),$textsend);
				$textsend=str_replace("{WORD_EMAIL}",word_lang("email"),$textsend);
				$textsend=str_replace("{WORD_TELEPHONE}",word_lang("telephone"),$textsend);
				$textsend=str_replace("{WORD_BILLING_INFO}",word_lang("billing address"),$textsend);
				$textsend=str_replace("{WORD_SHIPPING_INFO}",word_lang("shipping address"),$textsend);

				$textsend=str_replace("{ORDER}",strval($dt->row["id"]),$textsend);
				$textsend=str_replace("{DATE}",date(datetime_format),$textsend);
				$textsend=str_replace("{SUBTOTAL}",currency(1).strval(float_opt($dt->row["subtotal"],2))." ".currency(2),$textsend);
				$textsend=str_replace("{DISCOUNT}",currency(1).strval(float_opt($dt->row["discount"],2))." ".currency(2),$textsend);
				$textsend=str_replace("{SHIPPING}",currency(1).strval(float_opt($dt->row["shipping"],2))." ".currency(2),$textsend);
				$textsend=str_replace("{TAXES}",currency(1).strval(float_opt($dt->row["tax"],2))." ".currency(2),$textsend);
				$textsend=str_replace("{TOTAL}",currency(1).strval(float_opt($dt->row["total"],2))." ".currency(2),$textsend);

				$textsend=str_replace("{BILLING_FIRSTNAME}",$dt->row["billing_firstname"],$textsend);
				$textsend=str_replace("{BILLING_LASTNAME}",$dt->row["billing_lastname"],$textsend);
				$textsend=str_replace("{BILLING_ADDRESS}",$dt->row["billing_address"],$textsend);
				$textsend=str_replace("{BILLING_CITY}",$dt->row["billing_city"],$textsend);
				$textsend=str_replace("{BILLING_ZIP}",$dt->row["billing_zip"],$textsend);
				$textsend=str_replace("{BILLING_COUNTRY}",$dt->row["billing_country"],$textsend);

				$textsend=str_replace("{SHIPPING_FIRSTNAME}",$dt->row["shipping_firstname"],$textsend);
				$textsend=str_replace("{SHIPPING_LASTNAME}",$dt->row["shipping_lastname"],$textsend);
				$textsend=str_replace("{SHIPPING_ADDRESS}",$dt->row["shipping_address"],$textsend);
				$textsend=str_replace("{SHIPPING_CITY}",$dt->row["shipping_city"],$textsend);
				$textsend=str_replace("{SHIPPING_ZIP}",$dt->row["shipping_zip"],$textsend);
				$textsend=str_replace("{SHIPPING_COUNTRY}",$dt->row["shipping_country"],$textsend);

				$sql="select id_parent,login,name,email,telephone,address,country from users where id_parent=".$dt->row["user"];
				$dw->open($sql);
				if(!$dw->eof)
				{
					$textsend=str_replace("{NAME}",$dw->row["name"],$textsend);
					$to_email=$dw->row["email"];
				}

				$item_list="";
				$i=1;
				$sql="select price,item,quantity,prints,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,printslab,printslab_id from orders_content where id_parent=".(int)$p1;;
				$dz->open($sql);
				while(!$dz->eof)
				{
					if($dz->row["prints"]!=1)
					{
						$sql="select id,id_parent,name,shipped from items where id=".$dz->row["item"];
						$dw->open($sql);
						if(!$dw->eof)
						{
							$item_list.=$i.". ID=".$dw->row["id_parent"]." - ".$dw->row["name"]." - ".currency(1).float_opt($dz->row["price"],2)." ".currency(2)." - ".$dz->row["quantity"];
														
							if($dw->row["shipped"]!=1)
							{
								$sql="select link from downloads where id_parent=".$dw->row["id"]." and  order_id=".(int)$p1;
								$dx->open($sql);
								if(!$dx->eof)
								{
									if($dp->row["html"]==1)
									{
										$item_list.="<br><a href='".surl.site_root."/members/download.php?f=".$dx->row["link"]."'>".surl.site_root."/members/download.php?f=".$dx->row["link"]."</a>";
									}
									else
									{
										$item_list.="\n".surl.site_root."/members/download.php?f=".$dx->row["link"];
									}
								}
							}
							
							if($dp->row["html"]==1)
							{
								$item_list.="<br><br>";
							}
							else
							{
								$item_list.="\n\n";
							}

						}
					}
					else
					{
						if($dz->row["printslab"]!=1)
						{
							$sql="select id_parent,price,title,itemid from prints_items where id_parent=".$dz->row["item"];
							$dw->open($sql);
							if(!$dw->eof)
							{
								$item_list.=$i.". ID=".$dw->row["itemid"]." - ".$dw->row["title"]." - ".currency(1).float_opt($dz->row["price"],2)." ".currency(2)." - ".$dz->row["quantity"]."";
							
								if($dp->row["html"]==1)
								{
									$item_list.="<br>";
								}
								else
								{
									$item_list.="\n";
								}

								for($j=1;$j<4;$j++)
								{
									if($dz->row["option".$j."_id"]!=0 and $dz->row["option".$j."_value"]!="")
									{
										$sql="select title from products_options where id=".$dz->row["option".$j."_id"];
										$dx->open($sql);
										if(!$dx->eof)
										{
											$item_list.=$dx->row["title"].": ".$dz->row["option".$j."_value"].". ";
										}
									}
								}
							}
						
							if($dp->row["html"]==1)
							{
								$item_list.="<br><br>";
							}
							else
							{
								$item_list.="\n\n";
							}
						}
						else
						{
							$sql="select id_parent,price,title from prints where id_parent=".$dz->row["item"];
							$dw->open($sql);
							if(!$dw->eof)
							{
								$item_list.=$i.". ".word_lang("prints lab")." ID=".$dz->row["printslab_id"]." - ".$dw->row["title"]." - ".currency(1).float_opt($dz->row["price"],2)." ".currency(2)." - ".$dz->row["quantity"]."";
							
								if($dp->row["html"]==1)
								{
									$item_list.="<br>";
								}
								else
								{
									$item_list.="\n";
								}

								for($j=1;$j<4;$j++)
								{
									if($dz->row["option".$j."_id"]!=0 and $dz->row["option".$j."_value"]!="")
									{
										$sql="select title from products_options where id=".$dz->row["option".$j."_id"];
										$dx->open($sql);
										if(!$dx->eof)
										{
											$item_list.=$dx->row["title"].": ".$dz->row["option".$j."_value"].". ";
										}
									}
								}
							}
							
							if($dp->row["html"]==1)
							{
								$item_list.="<br><br>";
							}
							else
							{
								$item_list.="\n\n";
							}
						}
					}
					$i++;
					$dz->movenext();
				}
				$textsend=str_replace("{ITEM_LIST}",$item_list,$textsend);
			}
		}


		if($evt=="neworder_to_admin")
		{
			$subject=str_replace("{ORDER_ID}",$p1,$subject);
			
			$sql="select * from orders where id=".(int)$p1;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$textsend=str_replace("{WORD_ORDER}",word_lang("order"),$textsend);
				$textsend=str_replace("{WORD_SUBTOTAL}",word_lang("subtotal"),$textsend);
				$textsend=str_replace("{WORD_DISCOUNT}",word_lang("discount"),$textsend);
				$textsend=str_replace("{WORD_SHIPPING}",word_lang("shipping"),$textsend);
				$textsend=str_replace("{WORD_TAXES}",word_lang("taxes"),$textsend);
				$textsend=str_replace("{WORD_TOTAL}",word_lang("total"),$textsend);
				$textsend=str_replace("{WORD_DATE}",word_lang("date"),$textsend);
				$textsend=str_replace("{WORD_LOGIN}",word_lang("login"),$textsend);
				$textsend=str_replace("{WORD_NAME}",word_lang("full name"),$textsend);
				$textsend=str_replace("{WORD_EMAIL}",word_lang("email"),$textsend);
				$textsend=str_replace("{WORD_TELEPHONE}",word_lang("telephone"),$textsend);
				$textsend=str_replace("{WORD_ADDRESS}",word_lang("address"),$textsend);
				$textsend=str_replace("{WORD_COUNTRY}",word_lang("country"),$textsend);
				$textsend=str_replace("{WORD_BILLING_INFO}",word_lang("billing address"),$textsend);
				$textsend=str_replace("{WORD_SHIPPING_INFO}",word_lang("shipping address"),$textsend);
				$textsend=str_replace("{WORD_CUSTOMER_ID}",word_lang("customer")." ID",$textsend);

				$textsend=str_replace("{ORDER}",strval($dt->row["id"]),$textsend);
				$textsend=str_replace("{DATE}",date(datetime_format),$textsend);
				$textsend=str_replace("{SUBTOTAL}",currency(1).strval(float_opt($dt->row["subtotal"],2))." ".currency(2),$textsend);
				$textsend=str_replace("{DISCOUNT}",currency(1).strval(float_opt($dt->row["discount"],2))." ".currency(2),$textsend);
				$textsend=str_replace("{SHIPPING}",currency(1).strval(float_opt($dt->row["shipping"],2))." ".currency(2),$textsend);
				$textsend=str_replace("{TAXES}",currency(1).strval(float_opt($dt->row["tax"],2))." ".currency(2),$textsend);
				$textsend=str_replace("{TOTAL}",currency(1).strval(float_opt($dt->row["total"],2))." ".currency(2),$textsend);

				$textsend=str_replace("{BILLING_FIRSTNAME}",$dt->row["billing_firstname"],$textsend);
				$textsend=str_replace("{BILLING_LASTNAME}",$dt->row["billing_lastname"],$textsend);
				$textsend=str_replace("{BILLING_ADDRESS}",$dt->row["billing_address"],$textsend);
				$textsend=str_replace("{BILLING_CITY}",$dt->row["billing_city"],$textsend);
				$textsend=str_replace("{BILLING_ZIP}",$dt->row["billing_zip"],$textsend);
				$textsend=str_replace("{BILLING_COUNTRY}",$dt->row["billing_country"],$textsend);

				$textsend=str_replace("{SHIPPING_FIRSTNAME}",$dt->row["shipping_firstname"],$textsend);
				$textsend=str_replace("{SHIPPING_LASTNAME}",$dt->row["shipping_lastname"],$textsend);
				$textsend=str_replace("{SHIPPING_ADDRESS}",$dt->row["shipping_address"],$textsend);
				$textsend=str_replace("{SHIPPING_CITY}",$dt->row["shipping_city"],$textsend);
				$textsend=str_replace("{SHIPPING_ZIP}",$dt->row["shipping_zip"],$textsend);
				$textsend=str_replace("{SHIPPING_COUNTRY}",$dt->row["shipping_country"],$textsend);


				$sql="select id_parent,login,name,email,telephone,address,country from users where id_parent=".$dt->row["user"];
				$dw->open($sql);
				if(!$dw->eof)
				{
					$textsend=str_replace("{CUSTOMERID}",$dw->row["id_parent"],$textsend);
					$textsend=str_replace("{LOGIN}",$dw->row["login"],$textsend);
					$textsend=str_replace("{NAME}",$dw->row["name"],$textsend);
					if($dp->row["html"]==1)
					{
						$textsend=str_replace("{EMAIL}","<a href='mailto:".$dw->row["email"]."'>".$dw->row["email"]."</a>",$textsend);
					}
					else
					{
						$textsend=str_replace("{EMAIL}",$dw->row["email"],$textsend);
					}
					$textsend=str_replace("{TELEPHONE}",$dw->row["telephone"],$textsend);
				}

				$item_list="";
				$i=1;
				$sql="select price,item,quantity,prints,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,printslab,printslab_id from orders_content where id_parent=".(int)$p1;;
				$dz->open($sql);
				while(!$dz->eof)
				{
					if($dz->row["prints"]!=1)
					{
						$sql="select id,id_parent,name,shipped from items where id=".$dz->row["item"];
						$dw->open($sql);
						if(!$dw->eof)
						{
							$item_list.=$i.". ID=".$dw->row["id_parent"]." - ".$dw->row["name"]." - ".currency(1).float_opt($dz->row["price"],2)." ".currency(2)." - ".$dz->row["quantity"];
							

							
							if($dw->row["shipped"]!=1)
							{
								$sql="select link from downloads where id_parent=".$dw->row["id"]." and  order_id=".(int)$p1;
								$dx->open($sql);
								if(!$dx->eof)
								{
									if($dp->row["html"]==1)
									{
										$item_list.="<br><a href='".surl.site_root."/members/download.php?f=".$dx->row["link"]."'>".surl.site_root."/members/download.php?f=".$dx->row["link"]."</a>";
									}
									else
									{
										$item_list.="\n".surl.site_root."/members/download.php?f=".$dx->row["link"];
									}
								}
							}
							
							if($dp->row["html"]==1)
							{
								$item_list.="<br><br>";
							}
							else
							{
								$item_list.="\n\n";
							}

						}
					}
					else
					{
						if($dz->row["printslab"]!=1)
						{
							$sql="select id_parent,price,title,itemid from prints_items where id_parent=".$dz->row["item"];
							$dw->open($sql);
							if(!$dw->eof)
							{
								$item_list.=$i.". ID=".$dw->row["itemid"]." - ".$dw->row["title"]." - ".currency(1).float_opt($dz->row["price"],2)." ".currency(2)." - ".$dz->row["quantity"]."";
							
								if($dp->row["html"]==1)
								{
									$item_list.="<br>";
								}
								else
								{
									$item_list.="\n";
								}

								for($j=1;$j<4;$j++)
								{
									if($dz->row["option".$j."_id"]!=0 and $dz->row["option".$j."_value"]!="")
									{
										$sql="select title from products_options where id=".$dz->row["option".$j."_id"];
										$dx->open($sql);
										if(!$dx->eof)
										{
											$item_list.=$dx->row["title"].": ".$dz->row["option".$j."_value"].". ";
										}
									}
								}
							}
						}
						else
						{
							$sql="select id_parent,price,title from prints where id_parent=".$dz->row["item"];
							$dw->open($sql);
							if(!$dw->eof)
							{
								$item_list.=$i.". ".word_lang("prints lab")." ID=".$dz->row["printslab_id"]." - ".$dw->row["title"]." - ".currency(1).float_opt($dz->row["price"],2)." ".currency(2)." - ".$dz->row["quantity"]."";
							
								if($dp->row["html"]==1)
								{
									$item_list.="<br>";
								}
								else
								{
									$item_list.="\n";
								}

								for($j=1;$j<4;$j++)
								{
									if($dz->row["option".$j."_id"]!=0 and $dz->row["option".$j."_value"]!="")
									{
										$sql="select title from products_options where id=".$dz->row["option".$j."_id"];
										$dx->open($sql);
										if(!$dx->eof)
										{
											$item_list.=$dx->row["title"].": ".$dz->row["option".$j."_value"].". ";
										}
									}
								}
							}						
						}
						
						if($dp->row["html"]==1)
						{
							$item_list.="<br><br>";
						}
						else
						{
							$item_list.="\n\n";
						}
					}
					$i++;
					$dz->movenext();
				}
				$textsend=str_replace("{ITEM_LIST}",$item_list,$textsend);
			}
		}


		if($evt=="signup_to_admin")
		{
			$sql="select login,name,email,city,country,address,telephone from users where id_parent=".(int)$p1;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$textsend=str_replace("{WORD_DATE}",word_lang("date"),$textsend);
				$textsend=str_replace("{DATE}",date(datetime_format),$textsend);
				$textsend=str_replace("{WORD_LOGIN}",word_lang("login"),$textsend);
				$textsend=str_replace("{WORD_NAME}",word_lang("full name"),$textsend);
				$textsend=str_replace("{WORD_EMAIL}",word_lang("e-mail"),$textsend);
				$textsend=str_replace("{WORD_TELEPHONE}",word_lang("telephone"),$textsend);
				$textsend=str_replace("{WORD_ADDRESS}",word_lang("address"),$textsend);
				$textsend=str_replace("{WORD_COUNTRY}",word_lang("country"),$textsend);
				$textsend=str_replace("{WORD_CITY}",word_lang("city"),$textsend);

				$textsend=str_replace("{LOGIN}",$dt->row["login"],$textsend);
				$textsend=str_replace("{NAME}",$dt->row["name"],$textsend);
				if($dp->row["html"]==1)
				{
					$textsend=str_replace("{EMAIL}","<a href='mailto:".$dt->row["email"]."'>".$dt->row["email"]."</a>",$textsend);
				}
				else
				{
					$textsend=str_replace("{EMAIL}",$dt->row["email"],$textsend);
				}
				$textsend=str_replace("{TELEPHONE}",$dt->row["telephone"],$textsend);
				$textsend=str_replace("{ADDRESS}",$dt->row["address"],$textsend);
				$textsend=str_replace("{COUNTRY}",$dt->row["country"],$textsend);
				$textsend=str_replace("{CITY}",$dt->row["city"],$textsend);
			}
		}


		if($evt=="signup_to_user")
		{
			$to_email=$_POST["email"];
			if($dp->row["html"]==1)
			{
				$textsend=str_replace("{CONFIRMATION_LINK}","<a href='".$p1."'>".$p1."</a>",$textsend);
			}
			else
			{
				$textsend=str_replace("{CONFIRMATION_LINK}",$p1,$textsend);
			}
			$textsend=str_replace("{NAME}",$_POST["name"],$textsend);
		}
		
		if($evt=="signup_guest")
		{
			$to_email=result($_POST["guest_email"]);
			$textsend=str_replace("{LOGIN}",$p1,$textsend);
			$textsend=str_replace("{PASSWORD}",$p2,$textsend);
		}


		if($evt=="forgot_password")
		{
			$sql="select name,email,password,login from users where email='".result($_POST["email"])."'";
			$dt->open($sql);
			if(!$dt->eof)
			{
				$newpassword=create_password();

				$to_email=$dt->row["email"];
				$textsend=str_replace("{PASSWORD}",$newpassword,$textsend);
				$textsend=str_replace("{NAME}",$dt->row["name"],$textsend);
				$textsend=str_replace("{LOGIN}",$dt->row["login"],$textsend);
				
				if($preview_test==false)
				{
					$sql="update users set password='".md5($newpassword)."' where email='".result($_POST["email"])."'";
					$db->execute($sql);
				}
			}
		}

		if($evt=="tell_a_friend")
		{
			$to_email=$_REQUEST["email2"];
			$from_email=$global_settings["from_email"];
			$textsend=str_replace("{NAME2}",$_REQUEST["name2"],$textsend);
			$textsend=str_replace("{NAME}",$_REQUEST["name"],$textsend);
			$textsend=str_replace("{EMAIL}",$_REQUEST["email"],$textsend);
			if($dp->row["html"]==1)
			{
				$textsend=str_replace("{URL}","<a href='".$p1."'>".$p1."</a>",$textsend);
			}
			else
			{
				$textsend=str_replace("{URL}",$p1,$textsend);
			}
		}

		if($evt=="subscription_to_admin")
		{
			$sql="select user,data1,data2,title,subscription from subscription_list where id_parent=".(int)$p1;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$details=$dt->row["title"];

				$sql="select price from subscription where id_parent=".$dt->row["subscription"];
				$dw->open($sql);
				if(!$dw->eof)
				{
					$details.=" (".currency(1,false).strval(float_opt($dw->row["price"],2)).currency(2,false).")";
				}

				$subject=str_replace("{SUBSCRIPTION}",strval($p1),$subject);
				$textsend=str_replace("{SUBSCRIPTION_DETAILS}",$details,$textsend);
				$textsend=str_replace("{WORD_DATE}",word_lang("date"),$textsend);
				$textsend=str_replace("{DATE}",date(datetime_format),$textsend);
				$textsend=str_replace("{WORD_LOGIN}",word_lang("login"),$textsend);
				$textsend=str_replace("{WORD_NAME}",word_lang("full name"),$textsend);
				$textsend=str_replace("{WORD_EMAIL}",word_lang("email"),$textsend);
				$textsend=str_replace("{WORD_TELEPHONE}",word_lang("telephone"),$textsend);
				$textsend=str_replace("{WORD_ADDRESS}",word_lang("address"),$textsend);
				$textsend=str_replace("{WORD_COUNTRY}",word_lang("country"),$textsend);

				$sql="select name,email,login,telephone,address,country from users where login='".$dt->row["user"]."'";
				$dw->open($sql);
				if(!$dw->eof)
				{
					$textsend=str_replace("{LOGIN}",$dw->row["login"],$textsend);
					$textsend=str_replace("{NAME}",$dw->row["name"],$textsend);
					if($dp->row["html"]==1)
					{
						$textsend=str_replace("{EMAIL}","<a href='mailto:".$dw->row["email"]."'>".$dw->row["email"]."</a>",$textsend);
					}
					else
					{
						$textsend=str_replace("{EMAIL}",$dw->row["email"],$textsend);
					}
					$textsend=str_replace("{TELEPHONE}",$dw->row["telephone"],$textsend);
					$textsend=str_replace("{ADDRESS}",$dw->row["address"],$textsend);
					$textsend=str_replace("{COUNTRY}",$dw->row["country"],$textsend);
				}
			}
		}

		if($evt=="subscription_to_user")
		{
			$sql="select user,data1,data2,title,subscription from subscription_list where id_parent=".(int)$p1;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$details=$dt->row["title"];

				$sql="select price from subscription where id_parent=".$dt->row["subscription"];
				$dw->open($sql);
				if(!$dw->eof)
				{
					$details.=" (".currency(1,false).strval(float_opt($dw->row["price"],2)).currency(2,false).")";
				}

				$subject=str_replace("{SUBSCRIPTION}",strval($p1),$subject);
				$textsend=str_replace("{SUBSCRIPTION_DETAILS}",$details,$textsend);
				$textsend=str_replace("{WORD_DATE}",word_lang("date"),$textsend);
				$textsend=str_replace("{DATE}",date(datetime_format),$textsend);

				$sql="select name,email from users where login='".$dt->row["user"]."'";
				$dw->open($sql);
				if(!$dw->eof)
				{
					$textsend=str_replace("{NAME}",$dw->row["name"],$textsend);
					$to_email=$dw->row["email"];
				}
			}
		}

		if($evt=="credits_to_admin")
		{
			$sql="select user,data,title,credits from credits_list where id_parent=".(int)$p1;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$details=$dt->row["title"];

				$sql="select price from credits where id_parent=".$dt->row["credits"];
				$dw->open($sql);
				if(!$dw->eof)
				{
					$details.=" (".currency(1,false).strval(float_opt($dw->row["price"],2)).currency(2,false).")";
				}

				$subject=str_replace("{CREDITS}",strval($p1),$subject);
				$textsend=str_replace("{CREDITS_DETAILS}",$details,$textsend);
				$textsend=str_replace("{WORD_DATE}",word_lang("date"),$textsend);
				$textsend=str_replace("{DATE}",date(datetime_format),$textsend);
				$textsend=str_replace("{WORD_LOGIN}",word_lang("login"),$textsend);
				$textsend=str_replace("{WORD_NAME}",word_lang("full name"),$textsend);
				$textsend=str_replace("{WORD_EMAIL}",word_lang("email"),$textsend);
				$textsend=str_replace("{WORD_TELEPHONE}",word_lang("telephone"),$textsend);
				$textsend=str_replace("{WORD_ADDRESS}",word_lang("address"),$textsend);
				$textsend=str_replace("{WORD_COUNTRY}",word_lang("country"),$textsend);

				$sql="select name,email,login,telephone,address,country from users where login='".$dt->row["user"]."'";
				$dw->open($sql);
				if(!$dw->eof)
				{
					$textsend=str_replace("{LOGIN}",$dw->row["login"],$textsend);
					$textsend=str_replace("{NAME}",$dw->row["name"],$textsend);
					if($dp->row["html"]==1)
					{
						$textsend=str_replace("{EMAIL}","<a href='mailto:".$dw->row["email"]."'>".$dw->row["email"]."</a>",$textsend);
					}
					else
					{
						$textsend=str_replace("{EMAIL}",$dw->row["email"],$textsend);
					}
					$textsend=str_replace("{TELEPHONE}",$dw->row["telephone"],$textsend);
					$textsend=str_replace("{ADDRESS}",$dw->row["address"],$textsend);
					$textsend=str_replace("{COUNTRY}",$dw->row["country"],$textsend);
				}
			}
		}

		if($evt=="credits_to_user")
		{
			$sql="select user,data,title,credits from credits_list where id_parent=".(int)$p1;
			$dt->open($sql);
			if(!$dt->eof)
			{
				$details=$dt->row["title"];

				$sql="select price from credits where id_parent=".$dt->row["credits"];
				$dw->open($sql);
				if(!$dp->eof)
				{
					$details.=" (".currency(1,false).strval(float_opt($dw->row["price"],2)).currency(2,false).")";
				}

				$subject=str_replace("{CREDITS}",strval($p1),$subject);
				$textsend=str_replace("{CREDITS_DETAILS}",$details,$textsend);
				$textsend=str_replace("{WORD_DATE}",word_lang("date"),$textsend);
				$textsend=str_replace("{DATE}",date(datetime_format),$textsend);

				$sql="select name,email from users where login='".$dt->row["user"]."'";
				$dw->open($sql);
				if(!$dw->eof)
				{
					$textsend=str_replace("{NAME}",$dw->row["name"],$textsend);
					$to_email=$dw->row["email"];
				}
			}
		}

		if($evt=="commission_to_affiliate" or $evt=="commission_to_seller")
		{
			$sql="select name,email from users where id_parent=".(int)$p1;
			$dw->open($sql);
			if(!$dw->eof)
			{
				$textsend=str_replace("{NAME}",$dw->row["name"],$textsend);
				$to_email=$dw->row["email"];
			}
			
			if($p4>=0)
			{
				$sql="select name from structure where id=".(int)$p3;
				$dw->open($sql);
				if(!$dw->eof)
				{
					$textsend=str_replace("{FILE}","ID=".$p3." - ".$dw->row["name"],$textsend);
				}
				else
				{
					$sql="select title,itemid from prints_items where id_parent=".(int)$p3;
					$dt->open($sql);
					if(!$dt->eof)
					{
						$textsend=str_replace("{FILE}","ID=".$dt->row["itemid"]." - ".$dt->row["title"],$textsend);
					}
				}
			}	
			else
			{
				$sql="select title,itemid from prints_items where id_parent=".(int)$p3;
				$dt->open($sql);
				if(!$dt->eof)
				{
					$textsend=str_replace("{FILE}","ID=".$dt->row["itemid"]." - ".$dt->row["title"],$textsend);
				}
			}
			
			if($p4<0)
			{
				$p4*=-1;
			}
			
			
			$textsend=str_replace("{ORDER_ID}",$p2,$textsend);
			
			if($global_settings["credits"])
			{
				$textsend=str_replace("{EARNING}",currency(1,false).float_opt($global_settings["payout_price"]*$p4,2)." ".currency(2,false),$textsend);
			}
			else
			{
				$textsend=str_replace("{EARNING}",currency(1,false).float_opt($p4,2)." ".currency(2,false),$textsend);
			}
		}
		
		if($evt=="exam_to_admin" or $evt=="exam_to_seller")
		{
			$sql="select name,email,login from users where id_parent=".(int)$p1;
			$dw->open($sql);
			if(!$dw->eof)
			{
				$textsend=str_replace("{NAME}",$dw->row["name"],$textsend);
				$textsend=str_replace("{LOGIN}",$dw->row["login"],$textsend);
				$to_email=$dw->row["email"];
			}		
			
			$sql="select id,user,data,status,comments from examinations where id=".(int)$p2;
			$dw->open($sql);
			if(!$dw->eof)
			{
				$textsend=str_replace("{ID}",$dw->row["id"],$textsend);
				$textsend=str_replace("{DATE}",date(date_format,$dw->row["data"]),$textsend);
				$textsend=str_replace("{COMMENTS}",$dw->row["comments"],$textsend);
				if($dw->row["status"]==0)
				{	
					$textsend=str_replace("{RESULT}",word_lang("pending"),$textsend);
				}
				if($dw->row["status"]==1)
				{	
					$textsend=str_replace("{RESULT}",word_lang("approved"),$textsend);
				}
				if($dw->row["status"]==2)
				{	
					$textsend=str_replace("{RESULT}",word_lang("declined"),$textsend);
				}
			}		
		}
		
		
		
		
		if($evt=="support_to_admin" or $evt=="support_to_user")
		{
					
			
			$sql="select id,id_parent,admin_id,user_id,subject,message,data,viewed_admin,viewed_user,rating,closed from support_tickets where id=".(int)$p1;
			$dw->open($sql);
			if(!$dw->eof)
			{
				if($dw->row["id_parent"]!=0)
				{
					$sql="select subject,user_id from support_tickets where id=".$dw->row["id_parent"];
					$dt->open($sql);
					if(!$dt->eof)
					{
						$subject=str_replace("{SUBJECT}",$dt->row["subject"],$subject);
						
						$sql="select name,email from users where id_parent=".$dt->row["user_id"];
						$dz->open($sql);
						if(!$dw->eof)
						{
							$textsend=str_replace("{NAME}",$dz->row["name"],$textsend);
							$to_email=$dz->row["email"];
						}
						
						if($dw->row["user_id"]==0)
						{
							if($dp->row["html"]!=1)
							{
								$textsend=str_replace("{URL}",surl.site_root."/members/support_content.php?id=".$dw->row["id_parent"],$textsend);
							}
							else
							{
								$textsend=str_replace("{URL}","<a href='".surl.site_root."/members/support_content.php?id=".$dw->row["id_parent"]."'>".surl.site_root."/members/support_content.php?id=".$dw->row["id_parent"]."</a>",$textsend);
							}
						}
						
						if($dw->row["admin_id"]==0)
						{
							if($dp->row["html"]!=1)
							{
								$textsend=str_replace("{URL}",surl.site_root."/admin/support/content.php?id=".$dw->row["id_parent"],$textsend);
							}
							else
							{
								$textsend=str_replace("{URL}","<a href='".surl.site_root."/admin/support/content.php?id=".$dw->row["id_parent"]."'>".surl.site_root."/admin/support/content.php?id=".$dw->row["id_parent"]."</a>",$textsend);
							}
						}
					}
				}
				else
				{
					$subject=str_replace("{SUBJECT}",$dw->row["subject"],$subject);
				}
				
				$subject=str_replace("{ID}",$dw->row["id"],$subject);
				$textsend=str_replace("{MESSAGE}",$dw->row["message"],$textsend);
				
				
				if($dw->row["admin_id"]==0 and $dw->row["id_parent"]==0)
				{
					if($dp->row["html"]!=1)
					{
						$textsend=str_replace("{URL}",surl.site_root."/admin/support/content.php?id=".$dw->row["id"],$textsend);
					}
					else
					{
						$textsend=str_replace("{URL}","<a href='".surl.site_root."/admin/support/content.php?id=".$dw->row["id"]."'>".surl.site_root."/admin/support/content.php?id=".$dw->row["id"]."</a>",$textsend);
					}
				}
				
			}		
		}
		
		
		

		
		
		if($preview_test==true and $dp->row["html"]!=1)
		{
			$textsend="<div class='header_preview'>".$dp->row["title"]."</div>".$textsend;
		}
		
		if($dp->row["html"]==1)
		{
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/header.tpl"))
			{
				$textsend=str_replace("{SITE_ROOT}",surl.site_root,file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/header.tpl")).$textsend;
			}
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/footer.tpl"))
			{
				$textsend.=str_replace("{SITE_ROOT}",surl.site_root,file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/emails/footer.tpl"));
			}
		}
		
		$subject=str_replace("{SITE_NAME}",$global_settings["site_name"],$subject);
		$textsend=str_replace("{SITE_NAME}",$global_settings["site_name"],$textsend);
		$textsend=str_replace("{ADDRESS}",$global_settings["company_address"],$textsend);
		
		$textsend=translate_text($textsend);
		
		if(!$preview_test)
		{	
			$mail = new PHPMailer(true); //New instance, with exceptions enabled

			$body             = $textsend;
			
			if($global_settings["mailtype"]=="smtp")
			{
				$mail->IsSMTP();                           // tell the class to use SMTP
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->Port       = $global_settings["smtp_port"];                    // set the SMTP server port
				$mail->Host       = $global_settings["smtp_server"]; // SMTP server
				$mail->Username   = $global_settings["smtp_user"];     // SMTP server username
				$mail->Password   = $global_settings["smtp_password"];            // SMTP server password
			}

			if($global_settings["mailtype"]=="sendmail")
			{
				$mail->IsSendmail();  // tell the class to use Sendmail
			}

			if($to_email!="")
			{
				$mail->From       = $from_email;
				$mail->FromName   = "";

				$to = $to_email;

				$mail->AddAddress($to);

				$mail->Subject  = $subject;

				//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
				$mail->WordWrap   = 80; // set word wrap
	
				$mail->MsgHTML($body);

				if($dp->row["html"]==1)
				{
					$mail->IsHTML(true); // send as HTML
				}
				else
				{
					$mail->IsHTML(false); // send as TEXT
				}

				$mail->Send();
			}
		}
		
		return $textsend;
	}
}



//Generate flv and jpg video thumbs using FFMPEG
function generate_flv($apath,$delete_source,$id)
{
	global $global_settings;

	if(file_exists($apath))
	{
			//Define movie size
			$duration=$global_settings["ffmpeg_duration"];
	
			$wd=$global_settings["ffmpeg_video_width"];
			$ht=$global_settings["ffmpeg_video_height"];

			//Define video file name
			$fln=explode("/",$apath);
			$original_name=$fln[count($fln)-1];


			//Define flv file name
			if($global_settings["ffmpeg_video_format"]=="flv")
			{
				$flv_name="thumb.flv";
			}
			else
			{
				$flv_name="thumb.mp4";
			}


			//Define flv file path
			$flv_path="";
			for($i=0;$i<count($fln)-1;$i++)
			{
				if($i!=0){$flv_path.="/";}
				$flv_path.=$fln[$i];
			}
			$thumb_path=$flv_path;
			$flv_path.="/".$flv_name;


			//FFMPEG command
			if($global_settings["ffmpeg_video_format"]=="flv")
			{
				if($global_settings["ffmpeg_watermark"])
				{
					$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -b 300k -ar 22050 -t ".$duration." -vf 'movie=".$_SERVER["DOCUMENT_ROOT"].site_root."/content/watermark.png [wm];[in][wm] overlay=(main_w-overlay_w-10)/2:(main_h-overlay_h-10)/2 [out]' -f flv -s ".$wd."x".$ht." ".$flv_path;
				}
				else
				{
					$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -b 300k -ar 22050 -t ".$duration." -f flv -s ".$wd."x".$ht." ".$flv_path;
				}
			}
			else
			{
				if($global_settings["ffmpeg_watermark"])
				{
					$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -vcodec libx264 -vpre medium -strict -2  -t ".$duration."  -vf 'movie=".$_SERVER["DOCUMENT_ROOT"].site_root."/content/watermark.png [wm];[in][wm] overlay=(main_w-overlay_w-10)/2:(main_h-overlay_h-10)/2 [out]' ".$flv_path;
					
					//$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -vcodec libx264 -vpre medium -strict -2  -t ".$duration."  -vf 'movie=".$_SERVER["DOCUMENT_ROOT"].site_root."/content/watermark.png [wm];[in][wm] overlay=(main_w-overlay_w-10)/2:(main_h-overlay_h-10)/2 [out]' ".$flv_path;
					
					//$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -vcodec libx264 -preset medium -strict -2  -t ".$duration."  -vf 'movie=".$_SERVER["DOCUMENT_ROOT"].site_root."/content/watermark.png [wm];[in][wm] overlay=(main_w-overlay_w-10)/2:(main_h-overlay_h-10)/2 [out]' ".$flv_path;
				}
				else
				{
					//$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -vcodec libx264  -vpre medium -strict -2  -t ".$duration."  ".$flv_path;
					
					//$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -vcodec libx264  -preset medium -strict -2  -t ".$duration."  ".$flv_path;
					
					$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -vcodec libx264 -strict -2  -t ".$duration."  ".$flv_path;
				}
								
				//$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -b 300k -ar 22050 -t ".$duration." -f mp4 -s ".$wd."x".$ht." ".$flv_path;
				//$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -vcodec mpeg4 -movflags faststart  -an -b 150k -vf scale=400:-1  -t ".$duration."  ".$flv_path;
				//$com=$global_settings["ffmpeg_path"]." -i \"".$apath."\" -vcodec copy  -b 300k -ar 22050  -t ".$duration."  ".$flv_path;
			}
			@exec($com);


			//Do jpg photo preview
			//$step=round($duration/($global_settings["ffmpeg_frequency"]+1));
			$step=1;

			for($i=1;$i<$global_settings["ffmpeg_frequency"]+1;$i++)
			{
				if($duration>$step*$i)
				{
					$preview_time=1+$step*($i-1);
					
					//$com=$global_settings["ffmpeg_path"]." -itsoffset -1 -i \"".$apath."\" -vcodec mjpeg -vframes 1 -an -f rawvideo ".$thumb_path."/thumb".($i-1).".jpg";
					$com=$global_settings["ffmpeg_path"]." -y -i \"".$apath."\" -an -ss ".$preview_time." -an -r 1 -vframes 1 -y -vcodec mjpeg -f mjpeg ".$thumb_path."/thumb".($i-1).".jpg";
					exec($com);
					if(file_exists($thumb_path."/thumb".($i-1).".jpg"))
					{
						if($i==2)
						{
							copy($thumb_path."/thumb".($i-1).".jpg",$thumb_path."/thumb100.jpg");	
							photo_resize($thumb_path."/thumb100.jpg",$thumb_path."/thumb100.jpg",2);
						}
						
						easyResize($thumb_path."/thumb".($i-1).".jpg",$thumb_path."/thumb".($i-1).".jpg",1,$global_settings["ffmpeg_thumb_width"]);
					}
				}
			}

			//Delete source
			if(!preg_match("/.flv$/i",$apath) and !preg_match("/.mp4$/i",$apath))
			{
				if($delete_source==1)
				{
					@unlink($apath);
				}
			}
	}
	return $flv_name;
}





//Generate mp3 preview
function generate_mp3($original_file,$result_file)
{
	global $global_settings;

	if(file_exists($original_file))
	{
		if($global_settings["sox_library"]=="ffmpeg")
		{
			$com=$global_settings["ffmpeg_path"]." -i \"".$original_file."\" -acodec copy -t ".$global_settings["sox_duration"]." ".$result_file;
		}
		else
		{
			$duration=$global_settings["sox_duration"];
				
			/*	
			$com="soxi -D '".$original_file."'";
			exec($com, $output);
			if(isset($output[0]))
			{
				$duration=$output[0];
			}
			*/
			
			if($global_settings["sox_watermark"] and $global_settings["sox_watermark_file"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/watermark.mp3"))
			{
				$com=$global_settings["sox_path"]." -m '|".$global_settings["sox_path"]." ".$_SERVER["DOCUMENT_ROOT"].site_root."/content/watermark.mp3 -p pad 4 repeat 15' \"".$original_file."\" ".$result_file." rate 96k trim 0 ".$duration;					
			}
			else
			{
				$com=$global_settings["sox_path"]." \"".$original_file."\" ".$result_file." rate 96k trim 0 ".$duration;
			}
		}
		//echo($com);exit();
		@exec($com);
	}
}






//Check if a category password protected
function check_password($otkuda,$kuda,$chto)
{
	global $_SESSION;
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$navig=true;
	$idp=0;

	$t_perem=$kuda;
	while($t_perem!=$otkuda)
	{
		$sql="select password from category where id_parent=".(int)$t_perem;
		$dp->open($sql);
		if(!$dp->eof)
		{
			if($dp->row["password"]!="")
			{
				$navig=false;
				$idp=$t_perem;
				if(isset($_SESSION["cprotected"]))
				{
					$cpr=explode("|",$_SESSION["cprotected"]);
					for($i=0;$i<count($cpr);$i++)
					{
						if((int)$t_perem==(int)$cpr[$i]){$navig=true;}
					}
				}
			}
		}

		$sql="select id_parent from structure where id=".$t_perem;
		$dp->open( $sql );
		if(!$dp->eof)
		{
			$t_perem=$dp->row[ "id_parent"];
		}
		else
		{
			break;
		}
	}

	if($chto==0)
	{
		return $navig;
	}
	else
	{
		return $idp;
	}
}



//Get user-friendly url
function user_url($login)
{
	global $_SESSION;
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$sql="select id_parent from users where login='".result($login)."'";
	$dp->open($sql);
	if(!$dp->eof)
	{
		return $dp->row["id_parent"];
	}
	else
	{
		return 0;
	}
}




//Define user login by id
function user_url_back($id)
{
	global $_SESSION;
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$sql="select login from users where id_parent=".(int)$id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		return $dp->row["login"];
	}
	else
	{
		return "";
	}
}


//define model seo-friendly url
function model_url($id)
{
	return site_root."/model/".(int)$id.".html";
}


//Smarty cache ID
function cache_id($fname)
{
	global $site_template_id;
	global $site;
	global $id_parent;
	global $_SESSION;
	global $lng;

	$cacheid=$id_parent."|".$site_template_id."|".$lng."|".$site."|";
	if(isset($_SESSION["people_id"])){$cacheid.="|1";}
	else{$cacheid.="|0";}

	$cacheid=$fname."|".$cacheid;

	return $cacheid;
}




//Define a folder where files are saved
function server_url($snm)
{
	global $site_servers;

	$server_name_url=site_upload_directory;
	$snm=(int)$snm;
	if($snm==0){$snm=1;}
	if(isset($site_servers[$snm]))
	{
		$server_name_url=$site_servers[$snm];
	}
	return $server_name_url;
}





//The function delete a publication and all files
function publication_delete($id)
{
	global $db;

	delete_files((int)$id);

	$sql="delete from structure where id=".(int)$id;
	$db->execute($sql);
	
	$sql="delete from photos where id_parent=".(int)$id;
	$db->execute($sql);
	
	$sql="delete from photos_exif where photo_id=".(int)$id;
	$db->execute($sql);
	
	$sql="delete from videos where id_parent=".(int)$id;
	$db->execute($sql);
	
	$sql="delete from ffmpeg_cron where id=".(int)$id;
	$db->execute($sql);
	
	$sql="delete from audio where id_parent=".(int)$id;
	$db->execute($sql);

	$sql="delete from vector where id_parent=".(int)$id;
	$db->execute($sql);

	$sql="delete from items where id_parent=".(int)$id;
	$db->execute($sql);
	
	$sql="delete from models_files where publication_id=".(int)$id;
	$db->execute($sql);

	$sql="delete from prints_items where itemid=".(int)$id;
	$db->execute($sql);
	
	$sql="delete from downloads where publication_id=".(int)$id;
	$db->execute($sql);
	
	$sql="update filestorage_files set pdelete=1 where id_parent=".(int)$id;
	$db->execute($sql);
}
//End. The function delete a publication and all files


//The function gets social networks logins
function get_social_networks()
{
	global $db;
	global $dr;
	global $_SESSION;
	global $flag_ssl;
	
	$social_result=array();

	$social_networks="";
	$social_networks_horizontal="";

	$sql="select activ,title from users_qauth";
	$dr->open($sql);
	if(!$dr->eof)
	{
		while(!$dr->eof)
		{
			if($dr->row["title"]=="Facebook")
			{
				if($dr->row["activ"]==1)
				{
					$_SESSION["social_result"]["facebook"]=true;
					$social_networks.="<a href='".site_root."/members/check_facebook.php'><img src='".site_root."/images/login_facebook.gif' border='0' style='margin-top:3px'></a><br>";				
					$social_networks_horizontal.="<a href='".site_root."/members/check_facebook.php'><b>Login with Facebook</b></a>&nbsp;&nbsp;&nbsp;";
				}
				else
				{
					$_SESSION["social_result"]["facebook"]=false;
				}
			}
	
			if($dr->row["title"]=="Twitter")
			{
				if($dr->row["activ"]==1)
				{
					$_SESSION["social_result"]["twitter"]=true;
					$social_networks.="<a href='".site_root."/members/check_twitter.php'><img src='".site_root."/images/login_twitter.gif' border='0' style='margin-top:3px'></a><br>";				
					$social_networks_horizontal.="<a href='".site_root."/members/check_twitter.php'><b>Login with Twitter</b></a>&nbsp;&nbsp;&nbsp;";
				}
				else
				{
					$_SESSION["social_result"]["twitter"]=false;
				}
			}
		
			if($dr->row["title"]=="Vkontakte")
			{
				if($dr->row["activ"]==1)
				{
					$_SESSION["social_result"]["vkontakte"]=true;
					$social_networks.="<a href='".site_root."/members/check_vk.php'><img src='".site_root."/images/login_vk.gif' border='0' style='margin-top:3px'></a><br>";				
					$social_networks_horizontal.="<a href='".site_root."/members/check_vk.php'><b>Login with Vkontakte</b></a>&nbsp;&nbsp;&nbsp;";
				}
				else
				{
					$_SESSION["social_result"]["vkontakte"]=false;
				}
			}
			
			if($dr->row["title"]=="Instagram")
			{
				if($dr->row["activ"]==1)
				{
					$_SESSION["social_result"]["instagram"]=true;
					$social_networks.="<a href='".site_root."/members/checkinstagram.php'><img src='".site_root."/images/login_instagram.gif' border='0' style='margin-top:3px'></a><br>";				
					$social_networks_horizontal.="<a href='".site_root."/members/checkinstagram.php'><b>Login with Instagram</b></a>&nbsp;&nbsp;&nbsp;";
				}
				else
				{
					$_SESSION["social_result"]["instagram"]=false;
				}
			}
			$dr->movenext();
		}
	}
	$social_result["vertical"]=$social_networks;
	
	$social_result["horizontal"]=$social_networks_horizontal;
	
		
	return $social_result;
}
//End. The function gets social networks logins


//The function authorizes a user
function user_authorization($login,$password,$network)
{
global $db;
$dp = new TMySQLQuery;
$dp->connection = $db;
$dt = new TMySQLQuery;
$dt->connection = $db;

	if($network=="site")
	{
		$sql="select id_parent,name,login,email,category,utype,examination from users where login='".$login."' and password='".$password."' and accessdenied=0 and authorization='site'";
	}
	else
	{
		$sql="select id_parent,name,login,email,category,utype,examination from users where login='".$login."' and accessdenied=0 and authorization='".$network."'";
	}
	$dp->open($sql);
	if(!$dp->eof)
	{

				$sql="insert into users_access (user,data,ip,bandwidth) values(".$dp->row["id_parent"].",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SERVER["REMOTE_ADDR"])."',0)";
				$db->execute($sql);

				$sql="select user,id from users_access where user=".$dp->row["id_parent"]." order by id desc";
				$dt->open($sql);
				$id=$dt->row['id'];


				$_SESSION["people_id"]=$dp->row[ "id_parent" ];
				$_SESSION["people_name"]=$dp->row[ "name" ];
				$_SESSION["people_login"]=$dp->row[ "login" ];
				$_SESSION["people_email"]=$dp->row[ "email" ];
				$_SESSION["people_category"]=$dp->row[ "category" ];
				$_SESSION["people_active"]=$id;
				$_SESSION["people_type"]=$dp->row["utype"];
				$_SESSION["people_exam"]=$dp->row["examination"];

	}


}



//The function adds a new affiliate into the stats
function affiliate_add($aff_referal,$userid,$type)
{
	global $db;
	$buyer=0;
	$seller=0;
	if($type=="seller")
	{
		$seller=1;
	}
	if($type=="buyer")
	{
		$buyer=1;
	}
	if($type=="common")
	{
		$buyer=1;
		$seller=1;
	}
	if($type=="seller" or $type=="buyer" or $type=="common")
	{
		$sql="insert into affiliates_stats (userid,seller,buyer,data,ip,aff_referal) values (".(int)$userid.",".$seller.",".$buyer.",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SERVER["REMOTE_ADDR"])."',".(int)$aff_referal.")";
		$db->execute($sql);
	}


}
//End. The function adds a new affiliate into the stats


//The function adds a new affiliates commission
function affiliate_add_commission($id,$type)
{
	global $db;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$dx = new TMySQLQuery;
	$dx->connection = $db;
	
	if($global_settings["affiliates"])
	{
		if($type=="subscription")
		{
			$sql="select user,subscription from subscription_list where id_parent=".(int)$id;
			$dp->open($sql);
			if(!$dp->eof)
			{	
				$total=0;
				$sql="select price from subscription where id_parent=".$dp->row["subscription"];
				$dt->open($sql);
				if(!$dt->eof)
				{
					$total=$dt->row["price"];
				}
				
				$sql="select id_parent,aff_referal from users where login='".$dp->row["user"]."'";
				$dt->open($sql);
				if(!$dt->eof)
				{
					if((int)$dt->row["aff_referal"]>0)
					{
						$sql="select aff_commission_buyer from users where id_parent=".$dt->row["aff_referal"];
						$dx->open($sql);
						if(!$dx->eof)
						{
							$total=$total*$dx->row["aff_commission_buyer"]/100;
							$sql="insert into affiliates_signups (userid,types,types_id,rates,total,data,aff_referal,status) values (".$dt->row["id_parent"].",'".$type."',".$id.",".$dx->row["aff_commission_buyer"].",".$total.",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".$dt->row["aff_referal"].",1)";
							$db->execute($sql);
							send_notification("commission_to_affiliate",$dt->row["id_parent"],"S".$id,"",$total);
						}
					}
				}
			}
		}
		
		
		if($type=="credits")
		{
			$sql="select user,credits from credits_list where id_parent=".(int)$id;
			$dp->open($sql);
			if(!$dp->eof)
			{	
				$total=0;
				$sql="select price from credits where id_parent=".$dp->row["credits"];
				$dt->open($sql);
				if(!$dt->eof)
				{
					$total=$dt->row["price"];
				}
				
				$sql="select id_parent,aff_referal from users where login='".$dp->row["user"]."'";
				$dt->open($sql);
				if(!$dt->eof)
				{
					if((int)$dt->row["aff_referal"]>0)
					{
						$sql="select aff_commission_buyer from users where id_parent=".$dt->row["aff_referal"];
						$dx->open($sql);
						if(!$dx->eof)
						{
							$total=$total*$dx->row["aff_commission_buyer"]/100;
							$sql="insert into affiliates_signups (userid,types,types_id,rates,total,data,aff_referal,status) values (".$dt->row["id_parent"].",'".$type."',".$id.",".$dx->row["aff_commission_buyer"].",".$total.",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".$dt->row["aff_referal"].",1)";
							$db->execute($sql);
							
							send_notification("commission_to_affiliate",$dt->row["id_parent"],"C".$id,"",$total);
						}
					}
				}
			}
		}
		
		
		if($type=="orders" and !$global_settings["credits"])
		{
			$sql="select user,total from orders where id=".(int)$id;
			$dp->open($sql);
			if(!$dp->eof)
			{	
				$total=$dp->row["total"];
				
				$sql="select id_parent,aff_referal from users where id_parent=".$dp->row["user"];
				$dt->open($sql);
				if(!$dt->eof)
				{
					if((int)$dt->row["aff_referal"]>0)
					{
						$sql="select aff_commission_buyer from users where id_parent=".$dt->row["aff_referal"];
						$dx->open($sql);
						if(!$dx->eof)
						{
							$total=$total*$dx->row["aff_commission_buyer"]/100;
							$sql="insert into affiliates_signups (userid,types,types_id,rates,total,data,aff_referal,status) values (".$dt->row["id_parent"].",'".$type."',".$id.",".$dx->row["aff_commission_buyer"].",".$total.",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".$dt->row["aff_referal"].",1)";
							$db->execute($sql);
							
							send_notification("commission_to_affiliate",$dt->row["id_parent"],"O".$id,"",$total);
						}
					}
				}
			}
		}
		
		
	}
}
//End. The function adds a new affiliates commission


//The function deletes the affiliates commission
function affiliate_delete_commission($id,$type)
{
	global $db;
	$sql="delete from affiliates_signups where types_id=".(int)$id." and types='".result($type)."'";
	$db->execute($sql);
}
//End. The function deletes the affiliates commission







//The function shows a publication's preview
function show_preview($id,$type,$type_preview,$type_url,$preview_server1="",$preview_folder="",$seo_view=true)
{
global $db;
global $aspect_ratio;
global $_SERVER;
global $global_settings;

$dp = new TMySQLQuery;
$dp->connection = $db;
$dt = new TMySQLQuery;
$dt->connection = $db;
$dz = new TMySQLQuery;
$dz->connection = $db;

$preview="";
$preview_url="";

$previews_remote=array();
$flag_remote=false;


if($global_settings["amazon"] or $global_settings["rackspace"])
{
	$sql="select url,filename1,filename2 from filestorage_files where id_parent=".(int)$id;
	$dz->open($sql);
	while(!$dz->eof)
	{
		$previews_remote[$dz->row["filename1"]]=$dz->row["url"]."/".$dz->row["filename2"];
		$flag_remote=true;
		$dz->movenext();
	}
}



	if($type=="photo")
	{	
	
		if(($preview_server1=="" or $preview_folder=="") and !$flag_remote)
		{
			$sql="select server1,id_parent from photos where id_parent=".(int)$id;
			$dp->open($sql);
			if(!$dp->eof)
			{
				$preview_server1=$dp->row["server1"];
				$preview_folder=$dp->row["id_parent"];
			}
		}
	
		$preview_url=site_root."/images/icon_photo.gif";
		
		
			if($type_preview==1)
			{
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb1.jpg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb1.jpg";
				}
				else
				{
					if(isset($previews_remote["thumb1.jpg"]))
					{
						$preview_url=$previews_remote["thumb1.jpg"];
					}
				}
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb1.jpeg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb1.jpeg";
				}
				else
				{
					if(isset($previews_remote["thumb1.jpeg"]))
					{
						$preview_url=$previews_remote["thumb1.jpeg"];
					}
				}
			}
			else
			{
			 	if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb2.jpg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb2.jpg";
				}
				else
				{
					if(isset($previews_remote["thumb2.jpg"]))
					{
						$preview_url=$previews_remote["thumb2.jpg"];
					}
				}
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb2.jpeg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb2.jpeg";
				}
				else
				{
					if(isset($previews_remote["thumb2.jpeg"]))
					{
						$preview_url=$previews_remote["thumb2.jpeg"];
					}
				}
			}

			$preview="<img src='".$preview_url."' border='0'>";
	}
	
	if($type=="video")
	{
		$sql="select server1,id_parent,ratio from videos where id_parent=".(int)$id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$preview_server1=$dp->row["server1"];
			$preview_folder=$dp->row["id_parent"];
			$preview_ratio=$dp->row["ratio"];
		}
			
		$preview_url=site_root."/images/icon_video.gif";
		$preview_url2="";
		

				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.jpg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.jpg";
				}
				else
				{
					if(isset($previews_remote["thumb.jpg"]))
					{
						$preview_url=$previews_remote["thumb.jpg"];
					}
				}
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.jpeg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.jpeg";
				}
				else
				{
					if(isset($previews_remote["thumb.jpeg"]))
					{
						$preview_url=$previews_remote["thumb.jpeg"];
					}
				}
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb0.jpg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb0.jpg";
				}
				else
				{
					if(isset($previews_remote["thumb0.jpg"]))
					{
						$preview_url=$previews_remote["thumb0.jpg"];
					}
				}
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb100.jpg") and !$flag_remote)
				{
					$preview_url2=site_root.server_url($preview_server1)."/".$preview_folder."/thumb100.jpg";
				}
				else
				{
					if(isset($previews_remote["thumb100.jpg"]))
					{
						$preview_url2=$previews_remote["thumb100.jpg"];
					}
				}
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb100.jpeg") and !$flag_remote)
				{
					$preview_url2=site_root.server_url($preview_server1)."/".$preview_folder."/thumb100.jpeg";
				}
				else
				{
					if(isset($previews_remote["thumb100.jpeg"]))
					{
						$preview_url2=$previews_remote["thumb100.jpeg"];
					}
				}
				
				
				$preview_url_video=$preview_url;

				if($type_preview==3)
				{
					if($preview_url2!="")
					{
						$preview_url_video=$preview_url2;
						$preview_url=$preview_url2;
					}
					else
					{
						$preview_url_video=$preview_url;
					}
				}

				
		
			if($type_preview==1 or $type_preview==3)
			{

				$preview="<img src='".$preview_url."' border='0'>";

			}
			else
			{
				$preview="<img src='".$preview_url."' border='0'>";
				
				if(isset($aspect_ratio[$preview_ratio]))
				{
					$global_settings["ffmpeg_video_height"]=round($global_settings["ffmpeg_video_width"]*$aspect_ratio[$preview_ratio]);
				}
				else
				{
					$global_settings["ffmpeg_video_height"]=round($global_settings["ffmpeg_video_width"]*3/4);
				}
				
				$video_player="<script type=\"text/javascript\" src=\"{SITE_ROOT}/members/swfobject.js\"></script>
				<div id=\"players{ID}\"></div>
				<script type=\"text/javascript\">
				var s{ID} = new SWFObject(\"{SITE_ROOT}/images/mediaplayer.swf\",\"single\",\"{VIDEO_WIDTH}\",\"{VIDEO_HEIGHT}\",\"7\");
				s{ID}.addParam(\"allowfullscreen\",\"true\");
				s{ID}.addVariable(\"file\",\"{PREVIEW_VIDEO}\");
				s{ID}.addVariable(\"image\",\"{PREVIEW_PHOTO}\");
				s{ID}.write(\"players{ID}\");
				</script>";
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player.tpl"))
				{
					$video_player=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player.tpl");
				}
				
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.flv") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.flv";
					$video_player=str_replace("{ID}",strval($id),$video_player);
					$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
					$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
					$video_player=str_replace("{VIDEO_HEIGHT}",$global_settings["ffmpeg_video_height"],$video_player);
					$video_player=str_replace("{PREVIEW_VIDEO}",$preview_url,$video_player);
					$video_player=str_replace("{PREVIEW_PHOTO}",$preview_url2,$video_player);
					$preview=$video_player;
				}
				else
				{
					
					if(isset($previews_remote["thumb.flv"]))
					{
						$preview_url=$previews_remote["thumb.flv"];
						$video_player=str_replace("{ID}",strval($id),$video_player);
						$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
						$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
						$video_player=str_replace("{VIDEO_HEIGHT}",$global_settings["ffmpeg_video_height"],$video_player);
						$video_player=str_replace("{PREVIEW_VIDEO}",$preview_url,$video_player);
						
						$video_player=str_replace("{PREVIEW_PHOTO}",$preview_url2,$video_player);

						$preview=$video_player;
					}
				}
				
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.mp4") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.mp4";
					$video_player=str_replace("{ID}",strval($id),$video_player);
					$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
					$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
					$video_player=str_replace("{VIDEO_HEIGHT}",$global_settings["ffmpeg_video_height"],$video_player);
					$video_player=str_replace("{PREVIEW_VIDEO}",$preview_url,$video_player);
					
					$video_player=str_replace("{PREVIEW_PHOTO}",$preview_url2,$video_player);
					$preview=$video_player;
				}
				else
				{
					
					if(isset($previews_remote["thumb.mp4"]))
					{
						$preview_url=$previews_remote["thumb.mp4"];
						$video_player=str_replace("{ID}",strval($id),$video_player);
						$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
						$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
						$video_player=str_replace("{VIDEO_HEIGHT}",$global_settings["ffmpeg_video_height"],$video_player);
						$video_player=str_replace("{PREVIEW_VIDEO}",$preview_url,$video_player);
						$video_player=str_replace("{PREVIEW_PHOTO}",$preview_url2,$video_player);
						$preview=$video_player;
					}
				}
				
				
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.mov") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.mov";
					$video_player=str_replace("{ID}",strval($id),$video_player);
					$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
					$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
					$video_player=str_replace("{VIDEO_HEIGHT}",$global_settings["ffmpeg_video_height"],$video_player);
					$video_player=str_replace("{PREVIEW_VIDEO}",$preview_url,$video_player);
					$video_player=str_replace("{PREVIEW_PHOTO}",$preview_url2,$video_player);
					$preview=$video_player;
				}
				else
				{
					
					if(isset($previews_remote["thumb.mov"]))
					{
						$preview_url=$previews_remote["thumb.mov"];
						$video_player=str_replace("{ID}",strval($id),$video_player);
						$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
						$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
						$video_player=str_replace("{VIDEO_HEIGHT}",$global_settings["ffmpeg_video_height"],$video_player);
						$video_player=str_replace("{PREVIEW_VIDEO}",$preview_url,$video_player);
						$video_player=str_replace("{PREVIEW_PHOTO}",$preview_url2,$video_player);
						$preview=$video_player;
					}
				}
				
				
				$video_player_wmv="<OBJECT ID=\"MediaPlayer\" WIDTH=\"{VIDEO_WIDTH}\" HEIGHT=\"{VIDEO_HEIGHT}\" CLASSID=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\"
					STANDBY=\"Loading Windows Media Player components...\" TYPE=\"application/x-oleobject\">
					<PARAM NAME=\"FileName\" VALUE=\"{PREVIEW_VIDEO}\">
					<PARAM name=\"ShowControls\" VALUE=\"true\">
					<param name=\"ShowStatusBar\" value=\"false\">
					<PARAM name=\"ShowDisplay\" VALUE=\"false\">
					<PARAM name=\"autostart\" VALUE=\"false\">
					<EMBED TYPE=\"application/x-mplayer2\" SRC=\"{PREVIEW_VIDEO}\" NAME=\"MediaPlayer\"
					WIDTH=\"{VIDEO_WIDTH}\" HEIGHT=\"{VIDEO_HEIGHT}\" ShowControls=\"1\" ShowStatusBar=\"0\" ShowDisplay=\"0\" autostart=\"0\"></EMBED>
					</OBJECT>";
					
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player_wmv.tpl"))
				{
					$video_player_wmv=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player_wmv.tpl");
				}
				
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.wmv") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.wmv";
					$video_player_wmv=str_replace("{ID}",strval($id),$video_player_wmv);
					$video_player_wmv=str_replace("{SITE_ROOT}",site_root,$video_player_wmv);
					$video_player_wmv=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player_wmv);
					$video_player_wmv=str_replace("{VIDEO_HEIGHT}",strval($global_settings["ffmpeg_video_height"]+50),$video_player_wmv);
					$video_player_wmv=str_replace("{PREVIEW_VIDEO}",$preview_url,$video_player_wmv);
					$preview=$video_player_wmv;
				}
				else
				{
					if(isset($previews_remote["thumb.wmv"]))
					{
						$preview_url=$previews_remote["thumb.wmv"];
						$video_player_wmv=str_replace("{ID}",strval($id),$video_player_wmv);
						$video_player_wmv=str_replace("{SITE_ROOT}",site_root,$video_player_wmv);
						$video_player_wmv=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player_wmv);
						$video_player_wmv=str_replace("{VIDEO_HEIGHT}",strval($global_settings["ffmpeg_video_height"]+50),$video_player_wmv);
						$video_player_wmv=str_replace("{PREVIEW_VIDEO}",$preview_url,$video_player_wmv);
						$preview=$video_player_wmv;
					}
				}
			
			}

	}
	
	if($type=="audio")
	{

		if(($preview_server1=="" or $preview_folder=="") and !$flag_remote)
		{
			$sql="select server1,id_parent from audio where id_parent=".(int)$id;
			$dp->open($sql);
			if(!$dp->eof)
			{
				$preview_server1=$dp->row["server1"];
				$preview_folder=$dp->row["id_parent"];
			}
		}

		$preview_url=site_root."/images/icon_audio.gif";
		

		
			if($type_preview==1 or $type_preview==3)
			{
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.jpg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.jpg";
				}
				else
				{
					if(isset($previews_remote["thumb.jpg"]))
					{
						$preview_url=$previews_remote["thumb.jpg"];
					}
				}
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.jpeg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.jpeg";
				}
				else
				{
					if(isset($previews_remote["thumb.jpeg"]))
					{
						$preview_url=$previews_remote["thumb.jpeg"];
					}
				}
				
				if($type_preview==3)
				{
					if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb100.jpg") and !$flag_remote)
					{
						$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb100.jpg";
					}
					else
					{
						if(isset($previews_remote["thumb100.jpg"]))
						{
							$preview_url=$previews_remote["thumb100.jpg"];
						}
					}
				
					if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb100.jpeg") and !$flag_remote)
					{
						$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb100.jpeg";
					}
					else
					{
						if(isset($previews_remote["thumb100.jpeg"]))
						{
							$preview_url=$previews_remote["thumb100.jpeg"];
						}
					}
				}
				
				$preview="<img src='".$preview_url."' border='0'>";
				
			}	
			else
			{
				$preview="<img src='".$preview_url."' border='0'>";
				
				$audio_player="<object type=\"application/x-shockwave-flash\" data=\"{SITE_ROOT}/images/player_mp3_maxi.swf\" width=\"200\" height=\"20\"><param name=\"movie\" value=\"{SITE_ROOT}/images/player_mp3_maxi.swf\" /><param name=\"FlashVars\" value=\"mp3={PREVIEW_AUDIO}&amp;showstop=1&amp;showvolume=1\" /></object>";
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/audio_player.tpl"))
				{
					$audio_player=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/audio_player.tpl");
				}
				
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.mp3") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.mp3";
					
					$audio_player=str_replace("{ID}",strval($id),$audio_player);
					$audio_player=str_replace("{SITE_ROOT}",site_root,$audio_player);
					$audio_player=str_replace("{PREVIEW_AUDIO}",$preview_url,$audio_player);
					$preview=$audio_player;
				}
				else
				{
					if(isset($previews_remote["thumb.mp3"]))
					{
						$preview_url=$previews_remote["thumb.mp3"];
						$audio_player=str_replace("{ID}",strval($id),$audio_player);
						$audio_player=str_replace("{SITE_ROOT}",site_root,$audio_player);
						$audio_player=str_replace("{PREVIEW_AUDIO}",$preview_url,$audio_player);
						$preview=$audio_player;	
					}
				}
			}
		

	}
	
	if($type=="vector")
	{

		$flash_width=$global_settings["flash_width"];
		$flash_height=$global_settings["flash_height"];

		if(($preview_server1=="" or $preview_folder=="") and !$flag_remote)
		{
			$sql="select server1,id_parent,flash_width,flash_height from vector where id_parent=".(int)$id;
			$dp->open($sql);
			if(!$dp->eof)
			{
				$preview_server1=$dp->row["server1"];
				$preview_folder=$dp->row["id_parent"];

				if((int)$dp->row["flash_width"]!=0){$flash_width=$dp->row["flash_width"];}
				if((int)$dp->row["flash_height"]!=0){$flash_height=$dp->row["flash_height"];}
				
			}
		}
		
		$preview_url=site_root."/images/icon_vector.gif";
		

		
			if($type_preview==1)
			{	
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb1.jpg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb1.jpg";
				}
				else
				{
					if(isset($previews_remote["thumb1.jpg"]))
					{
						$preview_url=$previews_remote["thumb1.jpg"];
					}
				}
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb1.jpeg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb1.jpeg";

				}
				else
				{
					if(isset($previews_remote["thumb1.jpeg"]))
					{
						$preview_url=$previews_remote["thumb1.jpeg"];
					}
				}
				
				$preview="<img src='".$preview_url."' border='0'>";
			}
			elseif($type_preview==2)
			{
			 	if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb2.jpg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb2.jpg";
				}
				else
				{
					if(isset($previews_remote["thumb2.jpg"]))
					{
						$preview_url=$previews_remote["thumb2.jpg"];
					}
				}
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb2.jpeg") and !$flag_remote)
				{
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb2.jpeg";
				}
				else
				{
					if(isset($previews_remote["thumb2.jpeg"]))
					{
						$preview_url=$previews_remote["thumb2.jpeg"];
					}
				}
				
				$preview="<img src='".$preview_url."' border='0'>";
			}
			else
			{
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.server_url($preview_server1)."/".$preview_folder."/thumb.swf") and !$flag_remote)
				{
				
					$preview_url=site_root.server_url($preview_server1)."/".$preview_folder."/thumb.swf";
				

				
					$preview="<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"".$flash_width."\" height=\"".$flash_height."\"><param name=\"movie\" value=\"".$preview_url."\"><param name=\"allowScriptAccess\" value=\"sameDomain\" /><param name=\"quality\" value=\"high\"><param name=\"wmode\" value=\"opaque\"><embed src=\"".$preview_url."\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"".$flash_width."\"  allowScriptAccess=\"sameDomain\" height=\"".$flash_height."\" wmode=\"opaque\"></embed></object>";
				}
				else
				{
				
					if(isset($previews_remote["thumb.swf"]))
					{
						$preview_url=$previews_remote["thumb.swf"];
						
						$preview="<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"".$flash_width."\" height=\"".$flash_height."\"><param name=\"movie\" value=\"".$preview_url."\"><param name=\"allowScriptAccess\" value=\"sameDomain\" /><param name=\"quality\" value=\"high\"><param name=\"wmode\" value=\"opaque\"><embed src=\"".$preview_url."\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"".$flash_width."\"  allowScriptAccess=\"sameDomain\" height=\"".$flash_height."\" wmode=\"opaque\"></embed></object>";
					}
				}
			}

	}

		$flag_seo_url=true;
		if(!$flag_remote and $flag_seo_url and !preg_match('/icon_(photo|video|audio|vector)/i',$preview_url) and $seo_view)
		{
			$seo_url="";
			$seo_title="file-".$id;
			$sql="select module_table,name from structure where id=".(int)$id;
			$dp->open($sql);
			if(!$dp->eof)
			{
				if($dp->row["module_table"]==30)
				{
				 	$seo_title="stock-photo-".strtolower(str_replace(" ","-",preg_replace('/[^a-z0-9 ]/i', '',make_translit($dp->row["name"]))))."-".$id;
				}
				if($dp->row["module_table"]==31)
				{
					$seo_title="stock-video-".strtolower(str_replace(" ","-",preg_replace('/[^a-z0-9 ]/i', '',make_translit($dp->row["name"]))))."-".$id;
				}
				if($dp->row["module_table"]==52)
				{
					$seo_title="stock-audio-".strtolower(str_replace(" ","-",preg_replace('/[^a-z0-9 ]/i', '',make_translit($dp->row["name"]))))."-".$id;
				}
				if($dp->row["module_table"]==53)
				{
					$seo_title="stock-vector-".strtolower(str_replace(" ","-",preg_replace('/[^a-z0-9 ]/i', '',make_translit($dp->row["name"]))))."-".$id;
				}
			}
			$seo_mass=array();
			preg_match_all('|content([0-9]*)\/[0-9]*\/thumb([0-9]*)\.([a-z0-9]*)$|Uis',$preview_url,$seo_mass);
			if(isset($seo_mass[1][0]) and isset($seo_mass[2][0]) and isset($seo_mass[3][0]))
			{
				$seo_url=site_root."/static".$seo_mass[1][0]."/preview".$seo_mass[2][0]."/".$seo_title.".".$seo_mass[3][0];
			}
			if($seo_url!="")
			{
				$preview=str_replace($preview_url,$seo_url,$preview);
				$preview_url=$seo_url;
			}
		}
	
	
	

	if($type_url==1)
	{		
		//Return preview URL
		return str_replace("http:","https:",$preview_url);
	}
	else
	{
		//Return preview HTML code
		return str_replace("http:","https:",$preview);
	}
}
//End. The function shows a publication's preview




//The function translates text
function translate_text($text_content)
{
	global $db;
	global $lng;
	global $m_lang;
	global $_SESSION;
	
	//Make {lang.} translation
	preg_match_all("|\{lang\.(.*)}|Uis",$text_content, $find_lang);
	$mass_words=array();
	$mass_code=array();
	foreach($find_lang as $key1 => $value1)
   	{
		foreach($value1 as $key2 => $value2)
   		{
   			if($key1==0)
   			{
   				$mass_code[]=$value2;
   			}
   			else
   			{
   				$mass_words[]=$value2;
   			}
   		}
	}
	for($t=0;$t<count($mass_words);$t++)
	{
		if(isset($m_lang[strtolower($mass_words[$t])]))
		{
			$text_content=str_replace($mass_code[$t],$m_lang[strtolower($mass_words[$t])],$text_content);
		}
		elseif(isset($m_lang[$mass_words[$t]]))
		{
			$text_content=str_replace($mass_code[$t],$m_lang[$mass_words[$t]],$text_content);
		}
		else
		{
			$text_content=str_replace($mass_code[$t],$mass_words[$t],$text_content);
		}
	}	
	//End. Make {lang.} translation


	$page_content=stripslashes($text_content);
	
	$lng_search=$lng;
	
	if(!preg_match("|\{if ".strtolower($lng)."\}(.*)\{/if\}|Uis",$page_content))
	{
		$lng_search="English";
	}

	foreach ($_SESSION["site_lng"] as $key => $value) 
	{
		$alang = array();
		$search_pattern="|\{if ".strtolower($key)."\}(.*)\{/if\}|Uis";
		preg_match_all($search_pattern,$page_content, $alang);
		if(isset($alang[1][0]) and isset($alang[0][0]))
		{
			if($lng_search==$key)
			{
				for($t=0;$t<10;$t++)
				{
					if(isset($alang[1][$t]))
					{
						$page_content=str_replace("{if ".strtolower($key)."}".$alang[1][$t]."{/if}",$alang[1][$t],$page_content);
					}
				}
			}	
		}
		$page_content=preg_replace($search_pattern,"",$page_content);
		
		unset($alang);
	}
	



return $page_content;
}
//End. The function translates text



//The function formats a layout {if}{/if}
function format_layout($layout,$pattern,$flag)
{
	
	$alayout = array();
	preg_match_all("|\{if ".$pattern."\}(.*)\{/if\}|Uis",$layout, $alayout);

	if($flag and isset($alayout[1][0]) and isset($alayout[0][0]))
	{
		$layout=preg_replace("|\{if ".$pattern."\}(.*)\{/if\}|Uis",$alayout[1][0],$layout);
	}
	else
	{
		$layout=preg_replace("|\{if ".$pattern."\}(.*)\{/if\}|Uis","",$layout);
	}
		
	return($layout);
}
//End. The function formats a layout {if}{/if}



//The function gets sql id of the password protected categories
function get_password_protected()
{
	global $db;
	global $dr;
	global $_SESSION;
	
	$sql_command="";
	
	$sql="select id_parent from category where password<>''";
	$dr->open($sql);
	while(!$dr->eof)
	{
		
		$flag_password=true;
		if(isset($_SESSION["cprotected"]))
		{
			if(preg_match("/".$dr->row["id_parent"]."/",$_SESSION["cprotected"]))
			{
				$flag_password=false;
			}
		}
		if($flag_password==true)
		{
			$sql_command.=" and a.id_parent<>".$dr->row["id_parent"]." and b.category2<>".$dr->row["id_parent"]." and b.category3<>".$dr->row["id_parent"]." ";
		}
		
		$dr->movenext();
	}
	

return $sql_command;
}
//End. The function gets sql id of the password protected categories



//The function reads a file by the portions
function readfile_chunked ( $filename ) 
{ 
	$chunksize = 1 *( 1024 * 1024 ); // how many bytes per chunk 
	$buffer = '' ; 
	$handle =  fopen ( $filename , 'rb' ); 
	if ( $handle === false ) 
	{ 
 		return false; 
	} 
	while ( ! feof ( $handle )) 
	{ 
		$buffer =  fread ( $handle , $chunksize ); 
 		print $buffer ; 
	} 
	return fclose ( $handle ); 
}
//End. The function reads a file by the portions


//The function checks if the order's total is correct
function check_order_total($total,$product_type,$product_id)
{
	global $rs;
	global $ds;
	$product_total2=0;

	if($product_type=="order")
	{
		$sql="select total from orders where id=".(int)$product_id;
		$rs->open($sql);
		if(!$rs->eof)
		{
			$product_total2=$rs->row["total"];
		}
	}

	if($product_type=="credits")
	{
		$sql="select total from credits_list where id_parent=".(int)$product_id;
		$rs->open($sql);
		if(!$rs->eof)
		{
			$product_total2=$rs->row["total"];
		}
	}

	if($product_type=="subscription")
	{
		$sql="select total from subscription_list where id_parent=".(int)$product_id;
		$rs->open($sql);
		if(!$rs->eof)
		{
			$product_total2=$rs->row["total"];
		}
	}
	
	if($total!=$product_total2)
	{
		return false;
	}
	else
	{
		return true;
	}
}
//End. The function checks if the order's total is correct


//The function shows an order content
function show_order_content($product_type,$product_id,$coefficient="")
{
	global $db;
	global $global_settings;

	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$product_subtotal=0;
	$product_shipping=0;
	$product_discount=0;
	$product_tax=0;
	$product_total=0;
	$product_name=0;
	$product_tax_id=0;
	$product_tax_name="";
	$product_order_credits=0;
	
	if($product_type=="orders")
	{
		$product_type="order";
	}
	
	if($product_type=="credits")
	{
		$sql="select title,subtotal,discount,taxes,total,taxes_id from credits_list where id_parent=".(int)$product_id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$product_name=word_lang("credits").": ".$dp->row["title"];
			$product_total=$dp->row["total"];
			$product_subtotal=$dp->row["subtotal"];
			$product_tax=$dp->row["taxes"];
			$product_discount=$dp->row["discount"];
			$product_tax_id=$dp->row["taxes_id"];
			
			if($product_tax_id != 0)
			{
				$sql="select title,title,	rates_depend,price_include,rate_all,rate_all_type from tax where id=".(int)$product_tax_id;
				$dp->open($sql);
				if(!$dp->eof)
				{	
					if($dp->row["rate_all_type"] == 1)
					{
						$product_tax_name= $dp->row["rate_all"] . "%";
					}
				}
			}
		}
	}
	
	if($product_type=="subscription")
	{
		$sql="select title,subtotal,discount,taxes,total,taxes_id from subscription_list where id_parent=".(int)$product_id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$product_name=word_lang("subscription").": ".$dp->row["title"];
			$product_total=$dp->row["total"];
			$product_subtotal=$dp->row["subtotal"];
			$product_tax=$dp->row["taxes"];
			$product_discount=$dp->row["discount"];
			$product_tax_id=$dp->row["taxes_id"];
			
			if($product_tax_id != 0)
			{
				$sql="select title,title,	rates_depend,price_include,rate_all,rate_all_type from tax where id=".(int)$product_tax_id;
				$dp->open($sql);
				if(!$dp->eof)
				{	
					if($dp->row["rate_all_type"] == 1)
					{
						$product_tax_name= $dp->row["rate_all"] . "%";
					}
				}
			}
		}
	}
	
	if($product_type=="order")
	{
		$sql="select id,total,subtotal,discount,shipping,tax,credits from orders where id=".(int)$product_id;
		$dp->open($sql);
		if(!$dp->eof)
		{
				$product_name=word_lang("order")."#".$dp->row["id"];
				$product_total=$dp->row["total"];
				$product_subtotal=$dp->row["subtotal"];
				$product_shipping=$dp->row["shipping"];
				$product_tax=$dp->row["tax"];
				$product_discount=$dp->row["discount"];
				$product_order_credits = (int)$dp->row["credits"];
		}
	}
	

	
	
	$order_text="<table border='0' cellpadding='0' cellspacing='0' class='table table-striped' style='width:100%' border='1' cellspacing='0' cellpadding='5'>
	<tr>
	<th width='50%'><b>".word_lang("Items")."</b></th>
	<th><b>".word_lang("quantity")."</b></th>
	
	<th><b>".word_lang("price")."</b></th>
	<th><b>".word_lang("VAT")."</b></th>
	<th><b>".word_lang("Amount")."</b></th>
	</tr>";
	
	
	if($product_type=="credits" or $product_type=="subscription")
	{
		$order_text.="<tr>
		<td><b>".$product_name."</b></td>
		<td>1</td>
		
		<td>".$coefficient.currency(1,false).float_opt($product_total,2)." ".currency(2,false)."</td>
		<td>".$product_tax_name."</td>
		<td>".$coefficient.currency(1,false).float_opt($product_total,2)." ".currency(2,false)."</td>
		</tr>";
	}
	else
	{
		$sql="select price,item,quantity,prints,taxes,taxes_id from orders_content where id_parent=".(int)$product_id;
		$dp->open($sql);
		while(!$dp->eof)
		{
			$order_text.="<tr>
			<td>";
			
			if($dp->row["prints"]==0)
			{
				$sql="select name,id_parent from items where id=".$dp->row["item"];
				$dt->open($sql);
				if(!$dt->eof)
				{
					$order_text.="#".$dt->row["id_parent"]."  &mdash;  ".$dt->row["name"];
				}
			}
			else
			{
				$sql="select title,itemid from prints_items where id_parent=".$dp->row["item"];
				$dt->open($sql);
				if(!$dt->eof)
				{
					$order_text.="#".$dt->row["itemid"]." ".word_lang("prints")." ".$dt->row["title"];
				}
			}
			
			if($dp->row["taxes_id"] != 0)
			{
				$sql="select title,title,	rates_depend,price_include,rate_all,rate_all_type from tax where id=".(int)$dp->row["taxes_id"];
				$dt->open($sql);
				if(!$dt->eof)
				{	
					if($dt->row["rate_all_type"] == 1)
					{
						$product_tax_name= $dt->row["rate_all"] . "%";
					}
				}
			}
			
			$order_text.="</td>
			<td>".$dp->row["quantity"]."</td>
			<td>".$coefficient.currency(1,false).float_opt($dp->row["price"],2)." ".currency(2,false)."</td>
			<td>".$coefficient.currency(1,false).float_opt($dp->row["taxes"],2)." ".currency(2,false). "(".$product_tax_name.")</td>		
			<td>".$coefficient.currency(1,false).float_opt((($dp->row["price"]+$dp->row["taxes"])*$dp->row["quantity"]),2)." ".currency(2,false)."</td>
			</tr>";
			$dp->movenext();
		}
	}
	
	$order_text.="<tr>
	<td style='text-align:right' colspan='4'><b>".word_lang("subtotal").":</b></td>
	<td>".$coefficient.currency(1,false).float_opt($product_subtotal,2)." ".currency(2,false)."</td>
	</tr>";
	
	if($product_type=="credits" or $product_type=="subscription" or ((!$global_settings["credits"]  or $global_settings["credits_currency"]) and $product_type=="order" and !$product_order_credits))
	{
		$order_text.="<tr>
		<td style='text-align:right' colspan='4'><b>".word_lang("discount").":</b></td>
		<td>".$coefficient.currency(1,false).float_opt($product_discount,2)." ".currency(2,false)."</td>
		</tr>";
	}
	
	if($product_type=="order" and (!$global_settings["credits"]  or $global_settings["credits_currency"])  and !$product_order_credits)
	{
		$order_text.="<tr>
		<td style='text-align:right' colspan='4'><b>".word_lang("shipping").":</b></td>
		<td>".$coefficient.currency(1,false).float_opt($product_shipping,2)." ".currency(2,false)."</td>
		</tr>";
	}
	
	if($product_type=="credits" or $product_type=="subscription" or ((!$global_settings["credits"] or $global_settings["credits_currency"]) and $product_type=="order" and !$product_order_credits))
	{
		$order_text.="<tr>
		<td style='text-align:right' colspan='4'><b>".word_lang("taxes").":</b></td>
		<td>".$coefficient.currency(1,false).float_opt($product_tax,2)." ".currency(2,false)."</td>
		</tr>";
	}
	
	
	$order_text.="<tr class='snd'>
	<td style='text-align:right' colspan='4'><b>".word_lang("total").":</b></td>
	<td>".$coefficient.currency(1,false).float_opt($product_total,2)." ".currency(2,false)."</td>
	</tr>

	</tr>
	</table>";
	
	return $order_text;
}
//End. The function shows an order content


//The function gets buyer's info for the order
function get_buyer_info($buyer_id,$order_id,$order_type)
{
	global $db;
	global $buyer_info;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;

	$sql="select name,email,telephone,address,country,lastname,city,zipcode,company,state from users where id_parent=".(int)$buyer_id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$buyer_info["name"]=$dp->row["name"];
		$buyer_info["lastname"]=$dp->row["lastname"];
		$buyer_info["email"]=$dp->row["email"];
		$buyer_info["telephone"]=$dp->row["telephone"];
		$buyer_info["company"]=$dp->row["zipcode"];
		$buyer_info["country"]=$dp->row["country"];
		$buyer_info["address"]=$dp->row["address"];
		$buyer_info["city"]=$dp->row["city"];
		$buyer_info["state"]=$dp->row["state"];
		$buyer_info["zipcode"]=$dp->row["zipcode"];
		
		$buyer_info["billing_name"]=$dp->row["name"];
		$buyer_info["billing_lastname"]=$dp->row["lastname"];
		$buyer_info["billing_country"]=$dp->row["country"];
		$buyer_info["billing_address"]=$dp->row["address"];
		$buyer_info["billing_city"]=$dp->row["city"];
		$buyer_info["billing_state"]=$dp->row["state"];
		$buyer_info["billing_zipcode"]=$dp->row["zipcode"];
				
		$buyer_info["shipping_name"]=$dp->row["name"];
		$buyer_info["shipping_lastname"]=$dp->row["lastname"];
		$buyer_info["shipping_country"]=$dp->row["country"];
		$buyer_info["shipping_address"]=$dp->row["address"];
		$buyer_info["shipping_city"]=$dp->row["city"];
		$buyer_info["shipping_state"]=$dp->row["state"];
		$buyer_info["shipping_zipcode"]=$dp->row["zipcode"];
	}
	
	if($order_type=="order")
	{
		$sql="select shipping_firstname,shipping_lastname,shipping_address,shipping_country,shipping_state,shipping_city,shipping_zip,billing_firstname,billing_lastname,billing_address,billing_country,billing_city,billing_state,billing_zip from orders where id=".(int)$order_id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$buyer_info["billing_name"]=$dp->row["billing_firstname"];
			$buyer_info["billing_lastname"]=$dp->row["billing_lastname"];
			$buyer_info["billing_country"]=$dp->row["billing_country"];
			$buyer_info["billing_address"]=$dp->row["billing_address"];
			$buyer_info["billing_city"]=$dp->row["billing_city"];
			$buyer_info["billing_state"]=$dp->row["billing_state"];
			$buyer_info["billing_zipcode"]=$dp->row["billing_zip"];
				
			$buyer_info["shipping_name"]=$dp->row["shipping_firstname"];
			$buyer_info["shipping_lastname"]=$dp->row["shipping_lastname"];
			$buyer_info["shipping_country"]=$dp->row["shipping_country"];
			$buyer_info["shipping_address"]=$dp->row["shipping_address"];
			$buyer_info["shipping_city"]=$dp->row["shipping_city"];
			$buyer_info["shipping_state"]=$dp->row["shipping_state"];
			$buyer_info["shipping_zipcode"]=$dp->row["shipping_zip"];
		}
	}
	
	
}
//End. The function gets buyer's info for the order



//The function gets order info
function get_order_info($product_id,$product_type)
{
	global $order_info;
	global $db;

	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	$order_info["product_subtotal"]=0;
	$order_info["product_shipping"]=0;
	$order_info["product_discount"]=0;
	$order_info["product_tax"]=0;
	$order_info["product_total"]=0;
	$order_info["product_name"]=0;
	
	if($product_type=="credits")
	{
		$sql="select credits,title from credits_list where id_parent=".(int)$product_id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$sql="select price from credits where id_parent=".$dp->row["credits"];
			$dt->open($sql);
			if(!$dt->eof)
			{
				$order_info["product_total"]=$dt->row["price"];
				$order_info["product_subtotal"]=$order_info["product_total"];
			}
		}
	}
	
	if($product_type=="subscription")
	{
		$sql="select subscription,title from subscription_list where id_parent=".(int)$product_id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$sql="select price from subscription where id_parent=".$dp->row["subscription"];
			$dt->open($sql);
			if(!$dt->eof)
			{
				$order_info["product_total"]=$dt->row["price"];
				$order_info["product_subtotal"]=$order_info["product_total"];
			}
		}
	}
	
	if($product_type=="order")
	{
		$sql="select total,subtotal,discount,shipping,tax,id from orders where id=".(int)$product_id;
		$dp->open($sql);
		if(!$dp->eof)
		{
				$order_info["product_total"]=$dp->row["total"];
				$order_info["product_subtotal"]=$dp->row["subtotal"];
				$order_info["product_shipping"]=$dp->row["shipping"];
				$order_info["product_tax"]=$dp->row["tax"];
				$order_info["product_discount"]=$dp->row["discount"];
		}
	}
	
	

}
//End. The function gets order info



//The function shows exif info
function get_exif($img,$short_info=false,$photo_id=0)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$flag=false;
	$exif_text="";
	$exif_text_short="";
	
	if($photo_id!=0)
	{
		$sql="select id,photo_id,FileName,DateTime,FileSize,Width,Height,IsColor,UserComment,Copyright,Copyright_Photographer,Copyright_Editor,Orientation,XResolution,YResolution,Software,Make,Model,Artist,ExposureTime,FNumber,ISOSpeedRatings,ShutterSpeedValue,ApertureValue,ExposureBiasValue,MeteringMode,Flash,FocalLength,GPSLongitude,GPSLongitudeRef,GPSLatitude,GPSLatitudeRef from photos_exif where photo_id=".(int)$photo_id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$flag=true;
			
			$exif_text.="<b>FileSize:</b> ".$dp->row["Width"]."x".$dp->row["Height"]."px&nbsp&nbsp;&nbsp; ".float_opt($dp->row["FileSize"]/(1024*1024),2)."Mb.<br>";
			
			$exif_text_short=$dp->row["Width"]."x".$dp->row["Height"]."px<br>".float_opt($dp->row["FileSize"]/(1024*1024),2)."Mb.";
			
			if($dp->row["Make"]!="")
			{
				$exif_text.="<b>Make:</b> ".$dp->row["Make"]."<br>";
			}
		
			if($dp->row["Model"]!="")
			{
				$exif_text.="<b>Model:</b> ".$dp->row["Model"]."<br>";
			}
			
			if($dp->row["XResolution"]!="")
			{
				$exif_text.="<b>XResolution:</b> ".$dp->row["XResolution"]."<br>";
			}
			
			if($dp->row["YResolution"]!="")
			{
				$exif_text.="<b>YResolution:</b> ".$dp->row["YResolution"]."<br>";
			}
			
			if($dp->row["DateTime"]!="")
			{
				$exif_text.="<b>DateTime:</b> ".$dp->row["DateTime"]."<br>";
			}
			
			if($dp->row["Artist"]!="")
			{
				$exif_text.="<b>Artist:</b> ".$dp->row["Artist"]."<br>";
			}
			
			if($dp->row["Copyright"]!="")
			{
				$exif_text.="<b>Copyright:</b> ".$dp->row["Copyright"]."<br>";
			}
		
			if($dp->row["ExposureTime"]!="")
			{
				$exif_text.="<b>ExposureTime:</b> ".$dp->row["ExposureTime"]."<br>";
			}
			
			if($dp->row["FNumber"]!="")
			{
				$exif_text.="<b>FNumber:</b> ".$dp->row["FNumber"]."<br>";
			}
		
			if($dp->row["ISOSpeedRatings"]!="")
			{
				$exif_text.="<b>ISOSpeedRatings:</b> ".$dp->row["ISOSpeedRatings"]."<br>";
			}
			
			if($dp->row["ShutterSpeedValue"]!="")
			{
				$exif_text.="<b>ShutterSpeedValue:</b> ".$dp->row["ShutterSpeedValue"]."<br>";
			}
			
			if($dp->row["ApertureValue"]!="")
			{
				$exif_text.="<b>ApertureValue:</b> ".$dp->row["ApertureValue"]."<br>";
			}
			
			if($dp->row["ExposureBiasValue"]!="")
			{
				$exif_text.="<b>ExposureBiasValue:</b> ".$dp->row["ExposureBiasValue"]."<br>";
			}
			
			if($dp->row["MeteringMode"]!="")
			{
				$exif_text.="<b>MeteringMode:</b> ".$dp->row["MeteringMode"]."<br>";
			}
		
			if($dp->row["Flash"]!="")
			{
				$exif_text.="<b>Flash:</b> ".$dp->row["Flash"]."<br>";
			}
			
			if($dp->row["FocalLength"]!="")
			{
				$exif_text.="<b>FocalLength:</b> ".$dp->row["FocalLength"]."<br>";
			}
		}
	}
	else
	{
		$flag=false;
	}
	
	
	if($flag==false)
	{
		$exif_info=@exif_read_data($img,0,true);	
		
		if($photo_id!=0)
		{
			add_exif_to_database($photo_id,$img);
		}

		if(isset($exif_info["FILE"]["FileSize"]) and isset($exif_info["COMPUTED"]["Width"]) and isset($exif_info["COMPUTED"]["Height"]))
		{
			$exif_text.="<b>FileSize:</b> ".$exif_info["COMPUTED"]["Width"]."x".$exif_info["COMPUTED"]["Height"]."px&nbsp&nbsp;&nbsp; ".float_opt($exif_info["FILE"]["FileSize"]/(1024*1024),2)."Mb.<br>";
		
			$exif_text_short=$exif_info["COMPUTED"]["Width"]."x".$exif_info["COMPUTED"]["Height"]."px<br>".float_opt($exif_info["FILE"]["FileSize"]/(1024*1024),2)."Mb.";
		}

		if(isset($exif_info["IFD0"]["Make"]))
		{
			$exif_text.="<b>Make:</b> ".$exif_info["IFD0"]["Make"]."<br>";
		}

		if(isset($exif_info["IFD0"]["Model"]))
		{
			$exif_text.="<b>Model:</b> ".$exif_info["IFD0"]["Model"]."<br>";
		}

		if(isset($exif_info["IFD0"]["XResolution"]))
		{
			$exif_text.="<b>XResolution:</b> ".$exif_info["IFD0"]["XResolution"]."<br>";
		}

		if(isset($exif_info["IFD0"]["YResolution"]))
		{
			$exif_text.="<b>YResolution:</b> ".$exif_info["IFD0"]["YResolution"]."<br>";
		}

		if(isset($exif_info["IFD0"]["DateTime"]))
		{
			$exif_text.="<b>DateTime:</b> ".$exif_info["IFD0"]["DateTime"]."<br>";
		}

		if(isset($exif_info["IFD0"]["Artist"]))
		{
			$exif_text.="<b>Artist:</b> ".$exif_info["IFD0"]["Artist"]."<br>";
		}

		if(isset($exif_info["IFD0"]["Copyright"]))
		{
			$exif_text.="<b>Copyright:</b> ".$exif_info["IFD0"]["Copyright"]."<br>";
		}

		if(isset($exif_info["EXIF"]["ExposureTime"]))
		{
			$exif_text.="<b>ExposureTime:</b> ".$exif_info["EXIF"]["ExposureTime"]."<br>";
		}

		if(isset($exif_info["EXIF"]["FNumber"]))
		{
			$exif_text.="<b>FNumber:</b> ".$exif_info["EXIF"]["FNumber"]."<br>";
		}

		if(isset($exif_info["EXIF"]["ISOSpeedRatings"]))
		{
			$exif_text.="<b>ISOSpeedRatings:</b> ".$exif_info["EXIF"]["ISOSpeedRatings"]."<br>";
		}

		if(isset($exif_info["EXIF"]["ShutterSpeedValue"]))
		{
			$exif_text.="<b>ShutterSpeedValue:</b> ".$exif_info["EXIF"]["ShutterSpeedValue"]."<br>";
		}

		if(isset($exif_info["EXIF"]["ApertureValue"]))
		{
			$exif_text.="<b>ApertureValue:</b> ".$exif_info["EXIF"]["ApertureValue"]."<br>";
		}

		if(isset($exif_info["EXIF"]["ExposureBiasValue"]))
		{
			$exif_text.="<b>ExposureBiasValue:</b> ".$exif_info["EXIF"]["ExposureBiasValue"]."<br>";
		}

		if(isset($exif_info["EXIF"]["MeteringMode"]))
		{
			$exif_text.="<b>MeteringMode:</b> ".$exif_info["EXIF"]["MeteringMode"]."<br>";
		}	

		if(isset($exif_info["EXIF"]["Flash"]))
		{
			$exif_text.="<b>Flash:</b> ".$exif_info["EXIF"]["Flash"]."<br>";
		}

		if(isset($exif_info["EXIF"]["FocalLength"]))
		{
			$exif_text.="<b>FocalLength:</b> ".$exif_info["EXIF"]["FocalLength"]."<br>";
		}
	}

	if($short_info)
	{
		return $exif_text_short;
	}
	else
	{
		return $exif_text;
	}
}
//End. The function shows exif info


//The function adds exif info in the database
function add_exif_to_database($photo_id,$img)
{
	global $db;
	
	$com="insert into photos_exif set photo_id=".(int)$photo_id;
	
	$exif_info=@exif_read_data($img,0,true);	
	
	$com.=",FileName='".result(@$exif_info["FILE"]["FileName"])."'";
	$com.=",DateTime='".result(@$exif_info["IFD0"]["DateTime"])."'";
	$com.=",FileSize='".(int)@$exif_info["FILE"]["FileSize"]."'";
	$com.=",Width='".(int)@$exif_info["COMPUTED"]["Width"]."'";
	$com.=",Height='".(int)@$exif_info["COMPUTED"]["Height"]."'";
	$com.=",IsColor='".result(@$exif_info["COMPUTED"]["IsColor"])."'";
	$com.=",UserComment='".result(@$exif_info["COMPUTED"]["UserComment"])."'";
	$com.=",Copyright='".result(@$exif_info["IFD0"]["Copyright"])."'";
	$com.=",Copyright_Photographer='".result(@$exif_info["COMPUTED"]["Copyright_Photographer"])."'";
	$com.=",Copyright_Editor='".result(@$exif_info["COMPUTED"]["Copyright_Editor"])."'";
	$com.=",Orientation='".result(@$exif_info["IFD0"]["Orientation"])."'";
	$com.=",XResolution='".result(@$exif_info["IFD0"]["XResolution"])."'";
	$com.=",YResolution='".result(@$exif_info["IFD0"]["YResolution"])."'";
	$com.=",Software='".result(@$exif_info["IFD0"]["Software"])."'";
	$com.=",Make='".result(@$exif_info["IFD0"]["Make"])."'";
	$com.=",Model='".result(@$exif_info["IFD0"]["Model"])."'";
	$com.=",Artist='".result(@$exif_info["IFD0"]["Artist"])."'";
	$com.=",ExposureTime='".result(@$exif_info["EXIF"]["ExposureTime"])."'";
	$com.=",FNumber='".result(@$exif_info["EXIF"]["FNumber"])."'";
	$com.=",ISOSpeedRatings='".result(@$exif_info["EXIF"]["ISOSpeedRatings"])."'";
	$com.=",ShutterSpeedValue='".result(@$exif_info["EXIF"]["ShutterSpeedValue"])."'";
	$com.=",ApertureValue='".result(@$exif_info["EXIF"]["ApertureValue"])."'";
	$com.=",ExposureBiasValue='".result(@$exif_info["EXIF"]["ExposureBiasValue"])."'";
	$com.=",MeteringMode='".result(@$exif_info["EXIF"]["MeteringMode"])."'";
	$com.=",Flash='".result(@$exif_info["EXIF"]["Flash"])."'";
	$com.=",FocalLength='".result(@$exif_info["EXIF"]["FocalLength"])."'";
	$com.=",GPSLongitude='".result(@$exif_info["GPS"]["GPSLongitude"])."'";
	$com.=",GPSLongitudeRef='".result(@$exif_info["GPS"]["GPSLongitudeRef"])."'";
	$com.=",GPSLatitude='".result(@$exif_info["GPS"]["GPSLatitude"])."'";
	$com.=",GPSLatitudeRef='".result(@$exif_info["GPS"]["GPSLatitudeRef"])."'";

	$db->execute($com);	
}
//End. The function adds exif info in the database


//The function shows exif info
function get_dpi($img,$photo_id=0)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$flag=false;
	$dpi=36;
	
	if($photo_id!=0)
	{
		$sql="select XResolution from photos_exif where photo_id=".(int)$photo_id;
		$dp->open($sql);
		if(!$dp->eof)
		{
			$flag=true;
			
			if($dp->row["XResolution"]!="")
			{
				$dpi_mass=explode("/",$dp->row["XResolution"]);
				if((int)@$dpi_mass[1]!=0)
				{
					$dpi=round((int)$dpi_mass[0]/(int)$dpi_mass[1]);
				}
			}
		}
	}
	
	if($flag==false)
	{
		$exif_info=@exif_read_data($img,0,true);
		if(isset($exif_info["IFD0"]["XResolution"]))
		{
			$dpi_mass=explode("/",$exif_info["IFD0"]["XResolution"]);
			if((int)$dpi_mass[1]!=0)
			{
				$dpi=round((int)$dpi_mass[0]/(int)$dpi_mass[1]);
			}
		}
		
		if($photo_id!=0)
		{
			add_exif_to_database($photo_id,$img);
		}
	}
	return $dpi;
}



//The function calculates an order's tax
function order_taxes_calculate($total,$tax_zero=false,$type="order")
{
	global $taxes_info;
	global $db;
	global $global_settings;
	global $_SESSION;
	global $mcountry_eu;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	
	$taxes=0;
	$taxes_text="";
	$taxes_id=0;
	$taxes_rate1=0;
	$taxes_rate2=0;
	$taxes_info["included"]=1;
	$flag_tax=false;
	
	//EU taxes
        //KMJ corrected EU Tax calculation bugs - Hey, I know, they made it that stupid, one cannot explain it to non EU people :-) Its made by politicans 
	if($global_settings["eu_tax"])
	{
	    // KMJ START
	    // first lets get things together:

		$seller_country = $global_settings["company_country"]; // we assume seller country is EU if EU Taxes are set
								       // there are also non EU companies which has to tax VAT
								       // but they need to set a base tax rate 

	        // lets find out what country is our buyer from

		$buyer_country = $global_settings["company_country"];	// per default we assume its a llocal private customer, taxed with local VAT

		$sql="select business,country,country_checked,country_checked_date ,vat_checked,vat_checked_date from users where id_parent=".(int)@$_SESSION["people_id"];
		$dt->open($sql);

		if(!$dt->eof)
		{
		    //  ok now lets choose
			$buyer_country = $dt->row["country"];		// settings made by customer
			$buyer_business = $dt->row["business"];		// settings made by customer

			//If country was not checked the client is private from seller's country
			// removed 6 month check for privat customers. with valid vat country is ok anyway
			// people selling to privat customers wouldnt want the extra work to e.g. recheck 100s of customers
			// but if country is not checked by admin its a local sales, EVEN if they say NON-EU

			// WE SHOULD THROW AN ERROR ON THE PAGE LETTING PEOPLE KNOW WE CHANGE THEM!
			// or better deny the possibility of ordering 

			if(!$dt->row["country_checked"] )  
			{
				$buyer_country = $global_settings["company_country"]; // change EU and NON-EU , private and biz
			}

			//If VAT number is not correct or not checked the client is private from seller's country
			// we do not check for country inside the EU because if valid VAT its without taxes in any way.
			// admin must check country too to be valid, then all is good
			// after having a long discussion with lawyer here, we remove the 6 month periode. to much work 
			// for backoffice
			
			if($buyer_business and (in_array($buyer_country, $mcountry_eu)) 
			   and ( (!$dt->row["country_checked"]) or (!$dt->row["vat_checked"]) ) )
			{
				// was set to non EU but not checked or vat invalid
				$buyer_business = 0;
				$buyer_country = $global_settings["company_country"];
			}
			elseif($buyer_business and (! in_array($buyer_country, $mcountry_eu)) 
			   and ( (!$dt->row["country_checked"]) ) )
			{
				// was set to non EU but not checked
				$buyer_business = 0;
				$buyer_country = $global_settings["company_country"];
			}


			if($global_settings["eu_tax_b2b"])              // if B2B Only Biz
			{	
			    if( ! $buyer_business  ) 
			    {
				// we never should come here, B2B doesnt allow Sales to privat!
				// to avoid justice fees we bill as private,local country
				// should throw an error on CHECKOUT page for:
				// unchecked VAT number and biz account
				// b2b and no biz account with valid VAT number / country

				$buyer_business = 0;
				$buyer_country = $global_settings["company_country"];

			    }
			}
			else 
			{
				// mixed sales goes here
			}
		}
		
	    // KMJ END
		
		//Search seller VAT
		$sql="select id_parent from tax_regions where country='".$seller_country."'";
		$dt->open($sql);
		while(!$dt->eof)
		{
			$sql="select * from tax where enabled=1 and id=".$dt->row["id_parent"];
			$dp->open($sql);
			if(!$dp->eof)
			{
				$flag_calculate=false;
				
				if($type=="order" and $dp->row["files"]==1)
				{
					$flag_calculate=true;
				}
				
				if($type=="credits" and $dp->row["credits"]==1)
				{
					$flag_calculate=true;
				}
				
				if($type=="subscription" and $dp->row["subscription"]==1)
				{
					$flag_calculate=true;
				}
				
				if($type=="prints" and $dp->row["prints"]==1)
				{
					$flag_calculate=true;
				}
				
				if($flag_calculate)
				{
					$seller_taxes_id=$dp->row["id"];
					$seller_taxes_text = "";
					
					if($dp->row["price_include"]==1)
					{
						$seller_taxes_text.=word_lang("included")." ";
						$seller_taxes_included=0;
					}
					else
					{
						$seller_taxes_included=1;
					}
					$seller_taxes_text.=$dp->row["title"];
					
					if($dp->row["rate_all_type"]==1)
					{
						if($dp->row["price_include"]!=1)
						{
							$seller_taxes=float_opt($total*$dp->row["rate_all"]/100,2);
						}
						else
						{
							$seller_taxes=float_opt($total*$dp->row["rate_all"]/(100+$dp->row["rate_all"]),2);
						}
						$seller_taxes_text.=" ".$dp->row["rate_all"]."%";
						$seller_taxes_rate1=$dp->row["rate_all"];
					}
					else
					{
						$seller_taxes=float_opt($dp->row["rate_all"],2);
						$seller_taxes_text.=" ".currency(1).$dp->row["rate_all"]." ".currency(2);
						$seller_taxes_rate2=$dp->row["rate_all"];
					}
				}
			}
			
			$dt->movenext();
		}
		//End. Search seller VAT
		
		//Search buyer VAT
		if($buyer_country != $seller_country)
		{
			$sql="select id_parent from tax_regions where country='".$buyer_country."'";
			$dt->open($sql);
			while(!$dt->eof)
			{
				$sql="select * from tax where enabled=1 and id=".$dt->row["id_parent"];
				$dp->open($sql);
				if(!$dp->eof)
				{
					$flag_calculate=false;
					
					if($type=="order" and $dp->row["files"]==1)
					{
						$flag_calculate=true;
					}
					
					if($type=="credits" and $dp->row["credits"]==1)
					{
						$flag_calculate=true;
					}
					
					if($type=="subscription" and $dp->row["subscription"]==1)
					{
						$flag_calculate=true;
					}
					
					if($type=="prints" and $dp->row["prints"]==1)
					{
						$flag_calculate=true;
					}
					
					if($flag_calculate)
					{
						$buyer_taxes_id=$dp->row["id"];
						$buyer_taxes_text="";
						
						if($dp->row["price_include"]==1)
						{
							$buyer_taxes_text.=word_lang("included")." ";
							$buyer_taxes_included=0;
						}
						else
						{
							$buyer_taxes_included=1;
						}
						$buyer_taxes_text.=$dp->row["title"];
						
						if($dp->row["rate_all_type"]==1)
						{
							if($dp->row["price_include"]!=1)
							{
								$buyer_taxes=float_opt($total*$dp->row["rate_all"]/100,2);
							}
							else
							{
								$buyer_taxes=float_opt($total*$dp->row["rate_all"]/(100+$dp->row["rate_all"]),2);
							}
							$buyer_taxes_text.=" ".$dp->row["rate_all"]."%";
							$buyer_taxes_rate1=$dp->row["rate_all"];
						}
						else
						{
							$buyer_taxes=float_opt($dp->row["rate_all"],2);
							$buyer_taxes_text.=" ".currency(1).$dp->row["rate_all"]." ".currency(2);
							$buyer_taxes_rate2=$dp->row["rate_all"];
						}
					}
				}
				
				$dt->movenext();
			}
		}
		//End. Search buyer VAT
		
		//Business EU buyer
		if($buyer_business)
		{
			//The same EU country
			if($buyer_country == $seller_country)
			{
				$taxes_id=@$seller_taxes_id;
				$taxes_info["included"]=@$seller_taxes_included;
				$taxes_text = @$seller_taxes_text;
				$taxes= @$seller_taxes;
			}
			else
			{
				//Other EU country
				if(in_array($buyer_country, $mcountry_eu))
				{
					$taxes_id=0;
					$taxes_info["included"]=1;
					$taxes_text = "0%";
					$taxes= 0;
				}
				//Non EU country
				else
				{
					$taxes_id=0;
					$taxes_info["included"]=1;
					$taxes_text = "0%";
					$taxes= 0;
				}
			}
		}
		//Private EU buyer
		else
		{
			//The same EU country
			if($buyer_country == $seller_country)
			{
				$taxes_id=$seller_taxes_id;
				$taxes_info["included"]=$seller_taxes_included;
				$taxes_text = $seller_taxes_text;
				$taxes= $seller_taxes;			
			}
			else
			{
				//Other EU country
				if(in_array($buyer_country, $mcountry_eu))
				{
					if($type=="prints")
					{
						$taxes_id=$seller_taxes_id;
						$taxes_info["included"]=$seller_taxes_included;
						$taxes_text = $seller_taxes_text;
						$taxes= $seller_taxes;	
					}
					else
					{
						$taxes_id=$buyer_taxes_id;
						$taxes_info["included"]=$buyer_taxes_included;
						$taxes_text = $buyer_taxes_text;
						$taxes= $buyer_taxes;						
					}
				}
				//Non EU country
				else
				{
					$taxes_id=0;
					$taxes_info["included"]=1;
					$taxes_text = "0%";
					$taxes= 0;
				}
			}		
		}	
	}
	//Non-EU taxes
	else
	{
		$sql="select * from tax where enabled=1";
		$dp->open($sql);
		while(!$dp->eof)
		{
			//Regions
			$flag_regions=false;
			if($dp->row["regions"]==0)
			{
				$flag_regions=true;
			}
			else
			{
				$country="";
				$state="";
				
				if($dp->row["rates_depend"]==2)
				{
					$country=result(@$_SESSION["billing_country"]);
					$state=result(@$_SESSION["billing_state"]);
				}
				
				if($dp->row["rates_depend"]==1)
				{
					$country=result(@$_SESSION["shipping_country"]);
					$state=result(@$_SESSION["shipping_state"]);
				}
				
				if($country!="")
				{
					$sql="select country,state from tax_regions where id_parent=".$dp->row["id"]." and country='".$country."'";
					$dt->open($sql);
					while(!$dt->eof)
					{
						if($dt->row["state"]=="")
						{
							$flag_regions=true;
						}
						else
						{
							if($dt->row["state"]==$state)
							{
								$flag_regions=true;
							}
						}
						$dt->movenext();
					}
				}
			}
			//End. Regions
			
			//Calculates
			$flag_calculate=false;
			
			if($type=="order" and $dp->row["files"]==1)
			{
				$flag_calculate=true;
			}
			
			if($type=="credits" and $dp->row["credits"]==1)
			{
				$flag_calculate=true;
			}
			
			if($type=="subscription" and $dp->row["subscription"]==1)
			{
				$flag_calculate=true;
			}
			//End. Calculates
			
			//Business
			$flag_business=false;
			
			$sql="select business from users where id_parent=".(int)@$_SESSION["people_id"];
			$dt->open($sql);
			if(!$dt->eof)
			{
				if($dt->row["business"]==1)
				{
					if($dp->row["customer"]==1 or $dp->row["customer"]==0)
					{
						$flag_business=true;
					}
				}
				else
				{
					if($dp->row["customer"]==2 or $dp->row["customer"]==0)
					{
						$flag_business=true;
					}
				}
			}
			//End. Business
			
	
			if($flag_regions and $flag_calculate and $flag_business and !$flag_tax)
			{
				$flag_tax=true;
				
				$taxes_id=$dp->row["id"];
				
				if($dp->row["price_include"]==1)
				{
					$taxes_text.=word_lang("included")." ";
					$taxes_info["included"]=0;
				}
				else
				{
					$taxes_info["included"]=1;
				}
				$taxes_text.=$dp->row["title"];
				
				if($dp->row["rate_all_type"]==1)
				{
					if($dp->row["price_include"]!=1)
					{
						$taxes=float_opt($total*$dp->row["rate_all"]/100,2);
					}
					else
					{
						$taxes=float_opt($total*$dp->row["rate_all"]/(100+$dp->row["rate_all"]),2);
					}
					$taxes_text.=" ".$dp->row["rate_all"]."%";
					$taxes_rate1=$dp->row["rate_all"];
				}
				else
				{
					$taxes=float_opt($dp->row["rate_all"],2);
					$taxes_text.=" ".currency(1).$dp->row["rate_all"]." ".currency(2);
					$taxes_rate2=$dp->row["rate_all"];
				}
			}
			
			$dp->movenext();
		}
	}
	
	$taxes_info["id"]=$taxes_id;
	$taxes_info["total"]=$taxes;
	$taxes_info["text"]=$taxes_text;
	if($tax_zero)
	{
		return $taxes*$taxes_info["included"];
	}
	else
	{
		return $taxes;
	}
}
//End. The function calculates an order's tax


//The function calculates an order's tax
function order_discount_calculate($coupon,$total)
{
	global $discount_info;
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$sql="select total,percentage from coupons where coupon_code='".result($coupon)."' and (total<>0 or percentage<>0) and used=0 and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
	$dp->open($sql);
	if(!$dp->eof)
	{	
		if($dp->row["total"]!=0)
		{
			$discount_info["total"]=float_opt($dp->row["total"],2);
			$discount_info["text"]="";
		}
		if($dp->row["percentage"]!=0)
		{
			$discount_info["total"]=float_opt($total*$dp->row["percentage"]/100,2);
			$discount_info["text"]=" (".$dp->row["percentage"]."%)";
		}
	}
	return $discount_info["total"];
}
//End. The function calculates an order's tax



//The function removes a used coupon
function coupons_delete($coupon_code)
{
	global $discount_info;
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$sql="select used,tlimit,ulimit from coupons where coupon_code='".result($coupon_code)."'";
	$dp->open($sql);
	if(!$dp->eof)
	{	
		$used=0;
		if($dp->row["tlimit"]+1==$dp->row["ulimit"])
		{
			$used=1;
		}
		$sql="update coupons set used=".$used.",tlimit=tlimit+1  where coupon_code='".result($coupon_code)."'";
		$db->execute($sql);
	}

}
//End. The function removes a used coupon



//The function shows captcha
function show_captcha()
{
	global $global_settings;
	
	$captcha_text="";
	
	if($global_settings["google_captcha"])
	{
		$captcha_text=recaptcha_get_html($global_settings["google_captcha_public"]);
	}
	
	if(preg_match("/error/i",$captcha_text) or !$global_settings["google_captcha"])
	{
		$rr=rand(0,9);
		$captcha_text="<table border='0' cellpadding='0' cellspacing='0'><tr><td><img src='".site_root."/images/c".$rr.".gif' width='80' height='30'>&nbsp;&nbsp;&nbsp;</td><td>
		<input name='rn1' id='rn1' type='text' value='' class='ibox form-control' style='width:100px'><input name='rn2' id='rn2' type='hidden' value='".$rr."'></td></tr></table><div id='error_rn1' name='error_rn1'></div>";
	}
	else
	{
		$captcha_text="<script type='text/javascript'>var RecaptchaOptions = {theme : 'white'};</script>".$captcha_text;
	}

	return $captcha_text;
}
//End. The function shows captcha


//The function checks captcha
function check_captcha()
{
	global $global_settings;
	global $_POST;
	global $_SERVER;
	$captcha_result=false;
	
	if(isset($_POST["rn1"]) and isset($_POST["rn2"]))
	{
		$rn=array("d3w5","26wy","g3z9","a4n8","7fq2","5n6s","g6mz","6ct9","v8z2","b43j");
		if($rn[(int)$_POST["rn2"]]==strtolower($_POST["rn1"]))
		{
			$captcha_result=true;
		}
	}
	else
	{
		$resp = recaptcha_check_answer ($global_settings["google_captcha_private"],
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
         if ($resp->is_valid) 
         {
         		$captcha_result=true;
         }
	}
	
	return $captcha_result;
}
//End. The function checks captcha


//The function builds a color's palette
function color_set($default_color)
{
	$color_mass=array("black","white","red","green","blue","magenta","cian","yellow","orange");
	$color_result="<div class='color_set'>";		
	
		for($t=0;$t<count($color_mass);$t++)
		{
			if($color_mass[$t]!="cian")
			{
				$color_bg=$color_mass[$t];
			}
			else
			{
				$color_bg="#0CEEF1";
			}
			$color_class="";
			if($color_mass[$t]==$default_color)
			{
				$color_class="2";
			}
			$color_result.="<div id='color_".$color_mass[$t]."' style='background-color:".$color_bg."' class='box_color".$color_class."' onClick=\"change_color('".$color_mass[$t]."')\">&nbsp;</div>";
		}
		$color_result.="</div><input type='hidden' name='color' id='color' value='".$default_color."'>";
	return $color_result;
}
//End. The function builds a color's palette


//The function builds duration like 00:00:00
function duration_format($duration)
{
	$form_hours=floor($duration/3600);
	$form_minutes=floor(($duration-$form_hours*3600)/60);
	$form_seconds=$duration-$form_hours*3600-$form_minutes*60;
	if($form_minutes<10)
	{
		$form_minutes="0".$form_minutes;
	}
	if($form_hours<10)
	{
		$form_hours="0".$form_hours;
	}
	if($form_seconds<10)
	{
		$form_seconds="0".$form_seconds;
	}
	return $form_hours.":".$form_minutes.":".$form_seconds;
}
//End. The function builds duration like 00:00:00


//The function shows an user's avatar
function show_user_avatar($param,$type)
{
	global $db;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$res="";
	
	if($type=="login")
	{
		$sql="select id_parent,avatar,login,name,lastname from users where login='".result($param)."'";
	}
	else
	{
		$sql="select id_parent,avatar,login from users where id_parent=".(int)$param;
	}
	$dp->open($sql);
	if(!$dp->eof)
	{
		if($dp->row["avatar"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$dp->row["avatar"]))
		{
			$res.="<a href='".site_root."/users/".$dp->row["id_parent"].".html'><img src='".$dp->row["avatar"]."' align='absMiddle' width='".$global_settings["avatarwidth"]."' border='0'></a>&nbsp;&nbsp;";
			//$res.="<a href='".site_root."/index.php?user=".$dp->row["id_parent"]."&portfolio=1'><img src='".$dp->row["avatar"]."' align='absMiddle' width='".$global_settings["avatarwidth"]."' border='0'></a>&nbsp;&nbsp;";
		}
		else
		{
			$res.="<img src='".site_root."/images/avatar.gif' align='absMiddle' width='".$global_settings["avatarwidth"]."' border='0'>&nbsp;&nbsp;";
		}
		
		$res.="<a href='".site_root."/users/".$dp->row["id_parent"].".html'>".show_user_name($dp->row["login"])."</a>";
		//$res.="<a href='".site_root."/index.php?user=".$dp->row["id_parent"]."&portfolio=1'>".show_user_name($dp->row["login"])."</a>";
	}
	
	return $res;
}
//End.The function shows an user's avatar


//The function shows an user's name
function show_user_name($login)
{
	global $db;
	global $global_settings;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$res=$login;
	if($global_settings["show_users_type"]=="name")
	{
		$sql="select name,lastname from users where login='".result($login)."'";
		$dp->open($sql);
		if(!$dp->eof)
		{
			if($dp->row["name"]!="" or $dp->row["lastname"]!="")
			{
				$res=$dp->row["name"]."&nbsp;".$dp->row["lastname"];
			}
		}
	}
	return $res;
}
//End. The function shows an user's name




//Settings for admin
if(isset($_SESSION['entry_admin']))
{
	include($_SERVER["DOCUMENT_ROOT"].site_root."/admin/inc/settings.php");
}
//End. Settings for admin

//The function checkes admin access.
function admin_panel_access($param)
{
	global $_SESSION;
	global $admin_submenu;
	
	$admin_submenu = $param;

	if(!isset($_SESSION['entry_admin']))
	{
		redirect("../auth/fullaccess.php");
		exit();
	}
	else
	{
		$flag=false;
		$param_list=explode("|",$param);
		for($i=0;$i<count($param_list);$i++)
		{
			if(isset($_SESSION["rights"][$param_list[$i]]))
			{
				$flag=true;
			}
		}
		if($flag==false)
		{
			redirect("../auth/fullaccess.php");
			exit();
		}
	}
}
//End. The function checkes admin access.


//The function gets mata tags for Facebook and Twitter Like buttons
function get_social_meta_tags($social_mass)
{
	global $global_settings;
	$meta_tags="";
	
	if(isset($social_mass["type"]))
	{
		$meta_key=strtolower(preg_replace('/[^a-z0-9_]/i', '',str_replace(" ","_",$global_settings["site_name"])));
		$meta_tags.="<meta content=\"".$meta_key.":".$social_mass["type"]."\" property=\"og:type\" />\n";
		$meta_tags.="<meta content=\"".$social_mass["url"]."\" property=\"og:url\" />\n";
		$meta_tags.="<meta content=\"".$social_mass["title"]."\" property=\"og:title\" />\n";
		$meta_tags.="<meta content=\"".$social_mass["description"]."\" property=\"og:description\" />\n";
		$meta_tags.="<meta content=\"".$social_mass["image"]."\" property=\"og:image\" />\n";
		
		if($social_mass["author"]!="")
		{
			$meta_tags.="<meta content=\"".$social_mass["author"]."\" property=\"".$meta_key.":author\" />\n";
		}
		
		$meta_tags.="<meta content=\"".$social_mass["category"]."\" property=\"".$meta_key.":category\" />\n";
		
		if($social_mass["google_x"]!=0)
		{
			$meta_tags.="<meta content=\"".$social_mass["google_x"]."\" property=\"".$meta_key.":location:latitude\" />\n";
		}
		
		if($social_mass["google_y"]!=0)
		{
			$meta_tags.="<meta content=\"".$social_mass["google_y"]."\" property=\"".$meta_key.":location:longitude\" />\n";
		}
		
		if(is_int($social_mass["data"]))
		{
			if($social_mass["data"]!=0)
			{
				$meta_tags.="<meta content=\"".date(date_short,$social_mass["data"])."\" property=\"".$meta_key.":uploaded\" />\n";
			}
		}
		else
		{
			$meta_tags.="<meta content=\"".$social_mass["data"]."\" property=\"".$meta_key.":uploaded\" />\n";
		}
		
		$keywords=explode(",",str_replace(";",",",$social_mass["keywords"]));
		for($i=0;$i<count($keywords);$i++)
		{
			$keywords[$i]=trim($keywords[$i]);
			if($keywords[$i]!="")
			{
				$meta_tags.="<meta content=\"".$keywords[$i]."\" property=\"".$meta_key.":tags\" />\n";
			}
		}
			
		$meta_tags.="<meta property=\"twitter:card\" value=\"photo\" />\n";
		$meta_tags.="<meta property=\"twitter:site\" value=\"@".$global_settings["site_name"]."\" />\n";
		
		if($social_mass["author"]!="")
		{
			$meta_tags.="<meta property=\"twitter:creator\" value=\"@".$social_mass["author"]."\" />\n";
		}
		
		$meta_tags.="<meta property=\"twitter:url\" value=\"".$social_mass["url"]."\" />\n";
		$meta_tags.="<meta property=\"twitter:title\" value=\"".$social_mass["title"]."\" />\n";
		$meta_tags.="<meta property=\"twitter:image\" value=\"".$social_mass["image"]."\" />\n";
	}
	
	return $meta_tags;
}
//End. The function gets mata tags for Facebook and Twitter Like buttons


//The function approves a payout 
function payout_approve($id,$type)
{
	global $db;
	if($type=="payout_seller")
	{
		$sql="update commission set status=1 where id=".(int)$id;
	}
	if($type=="payout_affiliate")
	{
		$payout_mass=explode("-",$id);
		$sql="update affiliates_signups set status=1 where data=".(int)$payout_mass[1]." and affiliates_signups=".(int)$payout_mass[2];
	}
	$db->execute($sql);
}
//End. The function approves a payout 


//The function gets a shopping cart ID
function shopping_cart_id($flag_add=false)
{
	global $_SESSION;
	global $global_settings;
	global $db;
	global $rs;
	global $ds;
	
	if(!isset($_SESSION["shopping_cart_id"]))
	{
		$_SESSION["shopping_cart_id"]=0;
	}

	if($flag_add and $_SESSION["shopping_cart_id"]==0)
	{
		//Remove old carts
		$sql="select id from carts where id<>".(int)$_SESSION["shopping_cart_id"]." and data<".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-2*3600)." and checked<>1";
		$rs->open($sql);
		while(!$rs->eof)
		{
			$sql="select id_parent from carts_content where id_parent=".$rs->row["id"];
			$ds->open($sql);
			if($ds->eof)
			{
				$sql="delete from carts where id=".$rs->row["id"];
				$db->execute($sql);
			}
			else
			{
				$sql="update carts set checked=1 where id=".$rs->row["id"];
				$db->execute($sql);
			}
		
			$rs->movenext();
		}
		
		
		if(isset($_SESSION["people_id"]))
		{
			$sql="select id from carts where (user_id=".(int)$_SESSION["people_id"]." or session_id='".session_id()."') and order_id=0 order by id desc";
			$rs->open($sql);
			if(!$rs->eof)
			{
				$_SESSION["shopping_cart_id"]=$rs->row["id"];
			
				$sql="update carts set user_id=".(int)$_SESSION["people_id"]." where id=".$rs->row["id"];
				$db->execute($sql);
			}
			else
			{
				$sql="insert into carts (session_id,data,user_id,order_id,ip) values ('".session_id()."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".(int)$_SESSION["people_id"].",0,'".result($_SERVER["REMOTE_ADDR"])."')";
				$db->execute($sql);
			
				$sql="select id from carts where user_id=".(int)$_SESSION["people_id"]." and session_id='".session_id()."' and order_id=0 order by id desc";
				$ds->open($sql);
				if(!$ds->eof)
				{
					$_SESSION["shopping_cart_id"]=$ds->row["id"];
				}
			}
		}
		else
		{
			$sql="select id from carts where session_id='".session_id()."' and order_id=0 order by id desc";
			$rs->open($sql);
			if(!$rs->eof)
			{
				$_SESSION["shopping_cart_id"]=$rs->row["id"];
			}
			else
			{
				$sql="insert into carts (session_id,data,user_id,order_id,ip) values ('".session_id()."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,0,'".result($_SERVER["REMOTE_ADDR"])."')";
				$db->execute($sql);
			
				$sql="select id from carts where session_id='".session_id()."' and order_id=0 order by id desc";
				$ds->open($sql);
				if(!$ds->eof)
				{
					$_SESSION["shopping_cart_id"]=$ds->row["id"];
				}
			}
		}
	}
	return $_SESSION["shopping_cart_id"];
}
//End. The function gets a shopping cart ID


//The function adds an item to the shopping cart
function shopping_cart_add($params)
{
	global $_SESSION;
	global $global_settings;
	global $db;
	global $rs;
	global $ds;

	if(!isset($params["printslab"]))
	{
		$params["printslab"]=0;
	}
	
	$cart_id=shopping_cart_id(true);

	if($cart_id!=0)
	{
		if(isset($params["rights_managed"]))
		{
			//Rights managed
			$rights_managed="";

			if(isset($_SESSION["rights_managed_price".$params["publication_id"]]))
			{			
				foreach ($_SESSION["rights_managed".$params["publication_id"]] as $key => $value) 
				{
					if(isset($_SESSION["rights_managed_value".$params["publication_id"]][$key]))
					{
						$rights_managed.="|".(int)$key."-".(int)$_SESSION["rights_managed_value".$params["publication_id"]][$key];
						
						unset($_SESSION["rights_managed".$params["publication_id"]][$key]);
						unset($_SESSION["rights_managed_value".$params["publication_id"]][$key]);
					}
				}
				
				$rights_managed=$_SESSION["rights_managed_price".$params["publication_id"]].$rights_managed;
				
				$sql="insert into carts_content (id_parent,item_id,prints_id,publication_id,quantity,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab) values (".$cart_id.",".$params["item_id"].",".$params["prints_id"].",".$params["publication_id"].",".$params["quantity"].",".$params["option1_id"].",'".$params["option1_value"]."',".$params["option2_id"].",'".$params["option2_value"]."',".$params["option3_id"].",'".$params["option3_value"]."',".$params["option4_id"].",'".$params["option4_value"]."',".$params["option5_id"].",'".$params["option5_value"]."',".$params["option6_id"].",'".$params["option6_value"]."',".$params["option7_id"].",'".$params["option7_value"]."',".$params["option8_id"].",'".$params["option8_value"]."',".$params["option9_id"].",'".$params["option9_value"]."',".$params["option10_id"].",'".$params["option10_value"]."','".$rights_managed."',".$params["printslab"].")";
				$db->execute($sql);
				
				unset($_SESSION["rights_managed_price".$params["publication_id"]]);
			}
		}
		else
		{
			//Files
			if($params["item_id"]!=0)
			{
				$sql="select id_parent from carts_content where id_parent=".$cart_id." and item_id=".$params["item_id"];
				$rs->open($sql);
				if($rs->eof)
				{
					$sql="insert into carts_content (id_parent,item_id,prints_id,publication_id,quantity,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab) values (".$cart_id.",".$params["item_id"].",".$params["prints_id"].",".$params["publication_id"].",".$params["quantity"].",".$params["option1_id"].",'".$params["option1_value"]."',".$params["option2_id"].",'".$params["option2_value"]."',".$params["option3_id"].",'".$params["option3_value"]."',".$params["option4_id"].",'".$params["option4_value"]."',".$params["option5_id"].",'".$params["option5_value"]."',".$params["option6_id"].",'".$params["option6_value"]."',".$params["option7_id"].",'".$params["option7_value"]."',".$params["option8_id"].",'".$params["option8_value"]."',".$params["option9_id"].",'".$params["option9_value"]."',".$params["option10_id"].",'".$params["option10_value"]."','',".$params["printslab"].")";
					$db->execute($sql);
				}
			}
		
			//Prints
			if($params["prints_id"]!=0)
			{
				$sql="insert into carts_content (id_parent,item_id,prints_id,publication_id,quantity,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab,stock,stock_type,stock_id,stock_url,stock_preview,stock_site_url) values (".$cart_id.",".$params["item_id"].",".$params["prints_id"].",".$params["publication_id"].",".$params["quantity"].",".$params["option1_id"].",'".$params["option1_value"]."',".$params["option2_id"].",'".$params["option2_value"]."',".$params["option3_id"].",'".$params["option3_value"]."',".$params["option4_id"].",'".$params["option4_value"]."',".$params["option5_id"].",'".$params["option5_value"]."',".$params["option6_id"].",'".$params["option6_value"]."',".$params["option7_id"].",'".$params["option7_value"]."',".$params["option8_id"].",'".$params["option8_value"]."',".$params["option9_id"].",'".$params["option9_value"]."',".$params["option10_id"].",'".$params["option10_value"]."','',".$params["printslab"].",".(int)@$params["stock"].",'".@$params["stock_type"]."',".(int)@$params["stock_id"].",'".@$params["stock_url"]."','".@$params["stock_preview"]."','".@$params["stock_site_url"]."')";
				$db->execute($sql);
			}	
		}
	}
	
	return $cart_id;
}
//End. The function adds an item to the shopping cart









//The function adds a new lightbox
function lightbox_add($params)
{
	global $global_settings;
	global $db;
	global $dn;
	global $dr;
	global $rs;

	if($params["user"]!=0)
	{
		if($params["lightbox_name"]!="")
		{
			//Create a new lightbox
			$sql="select a.id,a.title,b.id_parent,b.user from lightboxes a,lightboxes_admin b where a.id=b.id_parent and a.title='".$params["lightbox_name"]."' and b.user=".$params["user"];
			$dn->open($sql);
			if($dn->eof)
			{
				$sql="insert into lightboxes (title,description) values ('".$params["lightbox_name"]."','')";
				$db->execute($sql);
				
				$sql="select id from lightboxes where title='".$params["lightbox_name"]."' order by id desc";
				$dr->open($sql);
				if(!$dr->eof)
				{
					$params["lightboxes"][]=$dr->row["id"];
				
					//Add a user to the lightbox
					$sql="insert into lightboxes_admin (id_parent,user,user_owner) values (".$dr->row["id"].",".$params["user"].",1)";
					$db->execute($sql);
				}
			}
		}
		
		$sql="select id_parent from lightboxes_admin where user=".$params["user"];
		$rs->open($sql);
		while(!$rs->eof)
		{
			//Remove files from lightbox
			$sql="delete from lightboxes_files where item=".$params["id"]." and id_parent=".$rs->row["id_parent"];
			$db->execute($sql);
			
			$rs->movenext();
		}
		
		//Add files for lightbox
		foreach ($params["lightboxes"] as $key => $value) 	
		{
			$sql="insert into lightboxes_files (id_parent,item) values (".$value.",".$params["id"].")";
			$db->execute($sql);
		}
	}
}
//End. The function adds a new lightbox



//The function defines an image size
function define_thumb_size($id)
{
	global $global_settings;
	global $db;
	global $dr;
	global $ds;
	global $_SERVER;
	
	$size_result=array();
	$img_preview="";
	$img_preview2="";
	$width=0;
	$height=0;
	$sql="select name,module_table from structure where id=".$id;
	$dr->open($sql);
	if(!$dr->eof)
	{
		$sql="";
		if($dr->row["module_table"]==30)
		{
			$img_preview=show_preview($id,"photo",1,1,"","");
			$img_preview2=show_preview($id,"photo",1,1,"","",false);
			$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$id." and filename1 like '%thumb1%'";
		}
		if($dr->row["module_table"]==31)
		{
			$img_preview=show_preview($id,"video",1,1,"","");
			$img_preview2=show_preview($id,"video",1,1,"","",false);
			$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$id." and filename1 like '%thumb%' order by filename1";
		}
		if($dr->row["module_table"]==52)
		{
			$img_preview=show_preview($id,"audio",1,1,"","");
			$img_preview2=show_preview($id,"audio",1,1,"","",false);
			$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$id." and filename1 like '%thumb%' order by filename1";
		}
		if($dr->row["module_table"]==53)
		{
			$img_preview=show_preview($id,"vector",1,1,"","");
			$img_preview2=show_preview($id,"vector",1,1,"","",false);
			$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$id." and (filename1 like '%thumb1%' or filename1 like '%thumb%')";
		}
		
		
		if($sql!="" and $img_preview2!="")
		{
			if($global_settings["amazon"] or $global_settings["rackspace"])
			{
				$ds->open($sql);
				if(!$ds->eof)
				{
					$width=$ds->row["width"];
					$height=$ds->row["height"];
				}
			}
			else
			{
				if(file_exists($_SERVER["DOCUMENT_ROOT"].$img_preview2))
				{
					$size = getimagesize($_SERVER["DOCUMENT_ROOT"].$img_preview2);
					$width=$size[0];
					$height=$size[1];
				}
			}
		}
	}
	
	$size_result["width"]=$width;
	$size_result["height"]=$height;
	$size_result["thumb"]=$img_preview;
	
	return $size_result;
}
//End. The function defines an image size


//The function defines a prints price depening on the options
function define_prints_price($price,$option1_id,$option1_value,$option2_id,$option2_value,$option3_id,$option3_value,$option4_id,$option4_value,$option5_id,$option5_value,$option6_id,$option6_value,$option7_id,$option7_value,$option8_id,$option8_value,$option9_id,$option9_value,$option10_id,$option10_value)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	for($i=1;$i<11;$i++)
	{
		$option_id="option".$i."_id";
		$option_value="option".$i."_value";
		
		if($$option_id!=0)
		{
			$sql="select title,price,adjust from products_options_items where id_parent=".$$option_id;
			$dp->open($sql);
			while(!$dp->eof)
			{
				if($dp->row["title"]==$$option_value)
				{
					$price+=$dp->row["price"]*$dp->row["adjust"];
				}
				$dp->movenext();
			}
		}
	}
	
	return $price;
}
//End. The function defines a prints price depening on the options


//The function gets a hover box for a small preview
function get_hoverbox($id,$type,$server,$title,$user)
{
	global $global_settings;
	global $db;
	global $aspect_ratio;
	
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	
	$hoverbox_results=array();
	$hoverbox_results["hover"]="";
	$hoverbox_results["image"]="";
	$hoverbox_results["width"]=0;
	$hoverbox_results["height"]=0;
	$hoverbox_results["flow_image"]="";
	$hoverbox_results["flow_width"]=0;
	$hoverbox_results["flow_height"]=0;
	
	$remote_width=0;
	$remote_height=0;
	$remote_width_videoaudio=0;
	$remote_height_videoaudio=0;
	$flow_width=0;
	$flow_height=0;
	$flag_storage=false;
	
	if($global_settings["amazon"] or $global_settings["rackspace"])
	{
		$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".(int)$id." and (filename1 like '%thumb2%' or filename1 like '%thumb100%')";
		$dp->open($sql);
		while(!$dp->eof)
		{
			if(preg_match("/thumb2/i",$dp->row["filename1"]))
			{
				$remote_width=$dp->row["width"];
				$remote_height=$dp->row["height"];
			}
			if(preg_match("/thumb100/i",$dp->row["filename1"]))
			{
				$remote_width_videoaudio=$dp->row["width"];
				$remote_height_videoaudio=$dp->row["height"];
			}
			$flag_storage=true;
				
			$dp->movenext();
		}
	}
	
	if($type=="photo")
	{
		$hoverbox_results["image"]=show_preview($id,"photo",2,1,$server,$id,true);
		$hoverbox_results["flow_image"]=$hoverbox_results["image"];
		$item_img_lightbox=show_preview($id,"photo",2,1,$server,$id,true);
		$item_img_lightbox2=show_preview($id,"photo",2,1,$server,$id,false);
		
		if(!$flag_storage and file_exists($_SERVER["DOCUMENT_ROOT"].$item_img_lightbox2))
		{
			$size = getimagesize($_SERVER["DOCUMENT_ROOT"].$item_img_lightbox2);
			$hoverbox_results["width"]=$size[0];
			$hoverbox_results["height"]=$size[1];
			$hoverbox_results["flow_width"]=$size[0];
			$hoverbox_results["flow_height"]=$size[1];
		}
			
		if($remote_width!=0 and $remote_height!=0)
		{
			$hoverbox_results["width"]=$remote_width;
			$hoverbox_results["height"]=$remote_height;
			$hoverbox_results["flow_width"]=$remote_width;
			$hoverbox_results["flow_height"]=$remote_height;
		}
					
		if($global_settings["lightbox_photo"] and !preg_match("/icon_photo/",$item_img_lightbox))
		{				
			if($hoverbox_results["width"]!=0 and $hoverbox_results["height"]!=0)
			{
				$lightbox_width=$hoverbox_results["width"];
				$lightbox_height=$hoverbox_results["height"];
				
				if($lightbox_width>$lightbox_height)
				{
					if($lightbox_width>$global_settings["max_hover_size"])
					{
						
						$lightbox_height=round($lightbox_height*$global_settings["max_hover_size"]/$lightbox_width);
						$lightbox_width=$global_settings["max_hover_size"];
					}
				}
				else
				{
					if($lightbox_height>$global_settings["max_hover_size"])
					{
						$lightbox_width=round($lightbox_width*$global_settings["max_hover_size"]/$lightbox_height);
						$lightbox_height=$global_settings["max_hover_size"];
					}				
				}
				
				$hoverbox_results["hover"]="onMouseover=\"lightboxon('".$hoverbox_results["image"]."',".$lightbox_width.",".$lightbox_height.",event,'".site_root."','".$title."','".word_lang("author").": ".$user."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$lightbox_width.",".$lightbox_height.",event)\"";
			}
		}
	}
	if($type=="video")
	{
		$hoverbox_results["flow_image"]=show_preview($id,"video",3,1,$server,$id);
		$item_img2_path=show_preview($id,"video",3,1,$server,$id,false);
		$hoverbox_results["image"]=show_preview($id,"video",2,1,$server,$id);

		$hoverbox_results["width"]=$global_settings["video_width"];
		$hoverbox_results["height"]=$global_settings["video_height"];
		$sql="select ratio from videos where id_parent=".$id;
		$dt->open($sql);
		if(!$dt->eof)
		{
			if(isset($aspect_ratio[$dt->row["ratio"]]))
			{
				$hoverbox_results["height"]=round($global_settings["video_width"]*$aspect_ratio[$dt->row["ratio"]]);
			}
			else
			{
				$hoverbox_results["height"]=round($global_settings["video_width"]*3/4);
			}
		}
	
		if($hoverbox_results["image"]!="" and preg_match("/wmv$/",$hoverbox_results["image"]) and $global_settings["lightbox_video"])
		{
			$hoverbox_results["hover"]="onMouseover=\"lightboxon2('".$hoverbox_results["image"]."',".$hoverbox_results["width"].",".$hoverbox_results["height"].",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$hoverbox_results["width"].",".$hoverbox_results["height"].",event)\"";
		}
		if($hoverbox_results["image"]!="" and preg_match("/flv$/",$hoverbox_results["image"]) and $global_settings["lightbox_video"])
		{
			$hoverbox_results["hover"]="onMouseover=\"lightboxon3('".$hoverbox_results["image"]."',".$hoverbox_results["width"].",".$hoverbox_results["height"].",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$hoverbox_results["width"].",".$hoverbox_results["height"].",event)\"";
		}	
		if($hoverbox_results["image"]!="" and (preg_match("/mp4$/",$hoverbox_results["image"]) or preg_match("/mov$/",$hoverbox_results["image"])) and $global_settings["lightbox_video"])
		{
			$hoverbox_results["hover"]="onMouseover=\"lightboxon5('".$hoverbox_results["image"]."',".$hoverbox_results["width"].",".$hoverbox_results["height"].",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$hoverbox_results["width"].",".$hoverbox_results["height"].",event)\"";
		}	

		if(!$flag_storage and file_exists($_SERVER["DOCUMENT_ROOT"].$item_img2_path))
		{
			$size = getimagesize($_SERVER["DOCUMENT_ROOT"].$item_img2_path);
			$hoverbox_results["flow_width"]=$size[0];
			$hoverbox_results["flow_height"]=$size[1];
			$hoverbox_results["width"]=$size[0];
			$hoverbox_results["height"]=$size[1];
		}
		else
		{
			$hoverbox_results["flow_width"]=$remote_width_videoaudio;
			$hoverbox_results["flow_height"]=$remote_height_videoaudio;
			$hoverbox_results["width"]=$remote_width_videoaudio;
			$hoverbox_results["height"]=$remote_height_videoaudio;
		}
	}
	if($type=="audio")
	{
		$hoverbox_results["flow_image"]=show_preview($id,"audio",3,1,$server,$id);
		$item_img2_path=show_preview($id,"audio",3,1,$server,$id,false);
		$hoverbox_results["image"]=show_preview($id,"audio",2,1,$server,$id);
	
		if($hoverbox_results["image"]!="" and $global_settings["lightbox_video"] and preg_match("/mp3$/",$hoverbox_results["image"]))
		{
			$hoverbox_results["hover"]="onMouseover=\"lightboxon4('".$hoverbox_results["image"]."',200,20,event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(200,20,event)\"";
		}

		if(!$flag_storage and file_exists($_SERVER["DOCUMENT_ROOT"].$item_img2_path))
		{
			$size = getimagesize($_SERVER["DOCUMENT_ROOT"].$item_img2_path);
			$hoverbox_results["flow_width"]=$size[0];
			$hoverbox_results["flow_height"]=$size[1];
			$hoverbox_results["width"]=$size[0];
			$hoverbox_results["height"]=$size[1];
		}
		else
		{
			$hoverbox_results["flow_width"]=$remote_width_videoaudio;
			$hoverbox_results["flow_height"]=$remote_height_videoaudio;
			$hoverbox_results["width"]=$remote_width_videoaudio;
			$hoverbox_results["height"]=$remote_height_videoaudio;
		}
	}
	if($type=="vector")
	{
		$hoverbox_results["image"]=show_preview($id,"vector",2,1,$server,$id,true);
		$hoverbox_results["flow_image"]=$hoverbox_results["image"];
		
		$item_img_lightbox2=show_preview($id,"vector",2,1,$server,$id,false);
			
		if(!$flag_storage and file_exists($_SERVER["DOCUMENT_ROOT"].$item_img_lightbox2))
		{
			$size = getimagesize($_SERVER["DOCUMENT_ROOT"].$item_img_lightbox2);
			$hoverbox_results["width"]=$size[0];
			$hoverbox_results["height"]=$size[1];
			$hoverbox_results["flow_width"]=$size[0];
			$hoverbox_results["flow_height"]=$size[1];
		}
			
		if($remote_width!=0 and $remote_height!=0)
		{
			$hoverbox_results["width"]=$remote_width;
			$hoverbox_results["height"]=$remote_height;
			$hoverbox_results["flow_width"]=$remote_width;
			$hoverbox_results["flow_height"]=$remote_height;
		}
					
		if($hoverbox_results["image"]!="" and $global_settings["lightbox_photo"] and !preg_match("/icon_vector/",$hoverbox_results["image"]))
		{
			if($hoverbox_results["width"]!=0 and $hoverbox_results["height"]!=0)
			{				
				$lightbox_width=$hoverbox_results["width"];
				$lightbox_height=$hoverbox_results["height"];
				
				if($lightbox_width>$lightbox_height)
				{
					if($lightbox_width>$global_settings["max_hover_size"])
					{
						
						$lightbox_height=round($lightbox_height*$global_settings["max_hover_size"]/$lightbox_width);
						$lightbox_width=$global_settings["max_hover_size"];
					}
				}
				else
				{
					if($lightbox_height>$global_settings["max_hover_size"])
					{
						$lightbox_width=round($lightbox_width*$global_settings["max_hover_size"]/$lightbox_height);
						$lightbox_height=$global_settings["max_hover_size"];
					}				
				}
				
				$hoverbox_results["hover"]="onMouseover=\"lightboxon('".$hoverbox_results["image"]."',".$lightbox_width.",".$lightbox_height.",event,'".site_root."','".$title."','".word_lang("author").": ".$user."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$lightbox_width.",".$lightbox_height.",event)\"";
			}
		}
	}
	
	$width_limit=$global_settings["width_flow"];
	if(($hoverbox_results["flow_width"]>$width_limit or $hoverbox_results["flow_height"]>$width_limit) and $hoverbox_results["flow_width"]!=0)
	{
		$hoverbox_results["flow_height"]=round($hoverbox_results["flow_height"]*$width_limit/$hoverbox_results["flow_width"]);
		$hoverbox_results["flow_width"]=$width_limit;
	}
	
	return $hoverbox_results; 
}
//End. The function gets a hover box for a small preview


//The function translates a category
function translate_category($id,$title,$description,$keywords)
{
	global $global_settings;
	global $db;
	global $lng;
	global $lang_symbol;
	
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$translate_results=array();
	$translate_results["title"]=$title;
	$translate_results["description"]=$description;
	$translate_results["keywords"]=$keywords;
	
	if($global_settings["multilingual_categories"])
	{
		$lng_symbol=$lang_symbol[$lng];
		if($lng=="Chinese traditional"){$lng_symbol="zh1";}
		if($lng=="Chinese simplified"){$lng_symbol="zh2";}
		if($lng=="Afrikaans formal"){$lng_symbol="af1";}
		if($lng=="Afrikaans informal"){$lng_symbol="af2";}
		
		$sql="select title,keywords,description from translations where id=".(int)$id." and lang='".$lng_symbol."'";
		$dp->open($sql);
		if(!$dp->eof)
		{
			if($dp->row["title"]!=""){$translate_results["title"]=$dp->row["title"];}
			if($dp->row["description"]!=""){$translate_results["description"]=$dp->row["description"];}
			if($dp->row["keywords"]!=""){$translate_results["keywords"]=$dp->row["keywords"];}
		}
	}
	
	return $translate_results;
}
//End. The function translates a category



//The function translates a publication
function translate_publication($id,$title,$description,$keywords)
{
	global $global_settings;
	global $db;
	global $lng;
	global $lang_symbol;
	
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$translate_results=array();
	$translate_results["title"]=$title;
	$translate_results["description"]=$description;
	$translate_results["keywords"]=$keywords;
	
	if($global_settings["multilingual_publications"])
	{
		$lng_symbol=$lang_symbol[$lng];
		if($lng=="Chinese traditional"){$lng_symbol="zh1";}
		if($lng=="Chinese simplified"){$lng_symbol="zh2";}
		if($lng=="Afrikaans formal"){$lng_symbol="af1";}
		if($lng=="Afrikaans informal"){$lng_symbol="af2";}
		
		$sql="select title,keywords,description from translations where id=".(int)$id." and lang='".$lng_symbol."'";
		$dp->open($sql);
		if(!$dp->eof)
		{
			if($dp->row["title"]!=""){$translate_results["title"]=$dp->row["title"];}
			if($dp->row["description"]!=""){$translate_results["description"]=$dp->row["description"];}
			if($dp->row["keywords"]!=""){$translate_results["keywords"]=$dp->row["keywords"];}
		}
	}
	
	return $translate_results;
}
//End. The function translates a publication


//The function defines filename and file extention
function get_file_info($filename,$type)
{
	$fname="";	
	$nf=explode(".",$filename);
	$fext=$nf[count($nf)-1];
	
	for($i=0;$i<count($nf)-1;$i++)
	{
		if($fname!="")
		{
			$fname.=".";
		}
		$fname.=$nf[$i];
	}
	
	if($type=="filename")
	{
		return $fname;
	}
	if($type=="extention")
	{
		return $fext;
	}
}





//Redirect function
function redirect( $s )
{
	ob_end_clean();
	header( "location: " . $s );
	exit;
}



//Check variables functions
function result($entry)
{	
	global $mysqli_db;
	
	$entry=htmlspecialchars($entry);
	$entry=str_replace("‘","_qt_",$entry);
	$entry=str_replace("'","_qt_",$entry);
	$entry=str_replace("\/\*","x",$entry);
	$entry=str_replace("--","  ",$entry);
	$entry=preg_replace("/char\s*\(/i","_char_",$entry);
	$entry=str_replace("delete","_delete_",$entry);
	$entry=str_replace("drop","_drop_",$entry);
	$entry=str_replace("update","_update_",$entry);
	$entry=str_replace("insert","_insert_",$entry);
	$entry=str_replace("alter","_alter_",$entry);
	$entry=str_replace("select","_select_",$entry);

	return mysqli_real_escape_string($mysqli_db,$entry);
}



function result3($entry)
{
	global $mysqli_db;
	
	$entry=result($entry);
	$entry=preg_replace('/[^a-z0-9-_:\. ]/i', '', $entry);
	return mysqli_real_escape_string($mysqli_db,$entry);
}


function result_html($entry)
{
	global $mysqli_db;
	
	$entry=str_replace("‘","_qt_",$entry);
	$entry=str_replace("'","_qt_",$entry);
	$entry=str_replace("\/\*","x",$entry);
	$entry=str_replace("--","",$entry);
	$entry=preg_replace("/char\s*\(/i","_char_",$entry);
	$entry=str_replace("delete","_delete_",$entry);
	$entry=str_replace("drop","_drop_",$entry);
	$entry=str_replace("update","_update_",$entry);
	$entry=str_replace("insert","_insert_",$entry);
	$entry=str_replace("alter","_alter_",$entry);
	$entry=str_replace("select","_select_",$entry);
	return mysqli_real_escape_string($mysqli_db,$entry);
}


//Check uploaded files
function result_file($entry)
{
	if(preg_match("/php|txt|html|js|phtml/i",$entry))
	{
		echo("Error. The filename is not permitted. Please rename the file.");
		exit();
	}

	$entry=str_replace(array('&','?','#','/','%00'),'',$entry);
	$entry=str_replace(".php","",$entry);
	return $entry;
}


function result_html_forward($entry)
{
	$content=$entry;
	$content=nl2br($content);
	$content=result_html($content);
	$content=strip_tags($content,'<br><br />');
	$content=preg_replace("/\[b\]/i","<b>",$content);
	$content=preg_replace("/\[\/b\]/i","</b>",$content);

	return $content;
}


function result_html_back($entry)
{
	$content=$entry;
	$content=str_replace("<b>","[b]",$content);
	$content=preg_replace("/<\/b>/i","[/b]",$content);
	$content=preg_replace("/<br \/>/i","\n",$content);
	$content=strip_tags($content);
	return $content;
}










//Generate a new password
function create_password()
{
	$stroka="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789[]{}?%#@$!&";
	$password="";
	for($i=0;$i<9;$i++)
	{
		$password.=$stroka[rand(0,strlen($stroka)-1)];
	}
	return $password;
}





//Make float format 2.34
function float_opt($t_entry,$t_kolvo,$cr=false)
{
	global $global_settings;
	if($cr==true and $global_settings["credits"])
	{
		return $t_entry;
	}
	else
	{
		$t_entry=number_format((float)$t_entry, 2, '.', '');
		return $t_entry;
	}
}













function get_title_path($otkuda,$kuda,$t_table,$t_column,$t_var,$t_file,$t_show=false)
{
	global $ds;
	$navig = '';
	$navig2 = '';
	$t_perem=$kuda;
	$k=0;
	while ($t_perem != $otkuda) {
		if ($k < 10) {
			$sql='select ' . $t_column . ',id,id_parent,module_table from ' . $t_table . ' where id=' . $t_perem;
			$ds->open( $sql );
			$translate_results=translate_category($t_perem,$ds->row[ $t_column ],"","");

			$navig.=$translate_results[ 'title' ] . ' - ';
			
			if ($t_perem == $kuda) {
				$navig2 = '<li class="last">' . $translate_results[ 'title' ] . '</li>' . $navig2;
			} else {
				$navig2 = '<li><a href="' .category_url($t_perem). '">' . $translate_results[ 'title' ] . '</a></li>' . $navig2;
			}

			$t_perem=$ds->row[ 'id_parent' ];
		} else {
			$t_perem=$otkuda;
			$navig='';
			$navig2='';
		}
	$k++;
	}
	
	if ($navig2 == '') {
		$navig2 = '<li>' . word_lang('catalog') . '</li>' . $navig2;
	} else {
		$navig2 = '<li><a href="' . site_root . '/index.php?search=">' . word_lang('catalog') . '</a></li>' . $navig2;
	}
	
	if (!$t_show) {
		return $navig;
	} else {
		return $navig2;
	}
}






//Paging
function paging($r_rows,$r_page,$r_kolvo1,$r_kolvo2,$r_file,$r_perem,$show_qty=true,$show_last_page=true)
{
	$vivod="";
	
	if($r_rows>0)
	{
		if($show_qty)
		{
			$vivod="<b>".word_lang("results").":</b> ".$r_rows."&nbsp;&nbsp;&nbsp;";
		}

		$predel=round($r_rows/$r_kolvo1);
		
		if($predel*$r_kolvo1<$r_rows){$predel++;}
		
		if($predel>1)
		{
			$vivod.="<b> ".word_lang("pages").":</b>&nbsp;";
			
			if($r_page>$r_kolvo2)
			{
				$vivod.="<a href='".$r_file."?str=1".$r_perem."' class='paging'>1</a>&nbsp;&nbsp;&nbsp;...&nbsp;&nbsp;&nbsp;";
			}
			
			if($predel>=$r_page)
			{
				$predel2=round($predel/$r_kolvo2);
				
				if($predel2<$predel*$r_kolvo2){$predel2++;}
				
				for($p=1;$p<$predel2+1;$p++)
				{
					if(($p-1)*$r_kolvo2<$r_page and $p*$r_kolvo2>=$r_page)
					{
						if($r_page>1){$vivod.="<a href='".$r_file."?str=".($r_page-1).$r_perem."' class='paging'>&#171; ".word_lang("previous")."</a> ";}

						for($s=($p-1)*$r_kolvo2+1;$s<$p*$r_kolvo2+1;$s++)
						{
							if($s<=$predel)
							{
								if($r_page==$s){$vivod.="<span class='paging2'>".$r_page."</span> ";}
								else{$vivod.="<a href='".$r_file."?str=".$s.$r_perem."' class='paging'>".$s."</a> ";}
							}
						}
						
						if($r_page+1<=$predel){$vivod.="<a href='".$r_file."?str=".($r_page+1).$r_perem."' class='paging'>".word_lang("next")." &#187;</a> ";}									
					}
				}
				
				if($r_page<($predel-$r_kolvo2) and $show_last_page)
				{
					$vivod.="&nbsp;&nbsp;&nbsp;...&nbsp;&nbsp;&nbsp;<a href='".$r_file."?str=".$predel.$r_perem."' class='paging'>".$predel."</a>";
				}
			}
		}
		
		return $vivod;
	}
}















//Build select categories menu - admin panel
function buildmenu2($t_id,$t_select,$otstup,$iid)
{
	global $db;
	global $dr;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	global $itg;
	global $nlimit;

	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.priority from structure a,category b where a.id=b.id_parent and a.id_parent=".$t_id." order by b.priority,b.title";
	$dp->open($sql);
	while(!$dp->eof)
	{
		if($nlimit<1000)
		{
			$sel="";
			if($t_select==$dp->row["id"]){$sel="selected";}

			$otp="";
			for($i=0;$i<$otstup;$i++)
			{
				$otp.="&nbsp;&nbsp;";
			}
			
			$translate_results=translate_category($dp->row["id"],$dp->row["title"],"","");

			if($dp->row["id"]!=$iid)
			{
				$itg.="<option value='".$dp->row["id"]."' ".$sel.">".$otp.$translate_results["title"]."</option>";
			}

			buildmenu2($dp->row["id"],$t_select,$otstup+2,$iid);
		}
		$nlimit++;
		$dp->movenext();
	}
}







//Build categories upload menu - /members/filemanager_photo.php
function buildmenu5($t_id,$t_select,$otstup,$iid)
{
	global $db;
	global $dr;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	global $itg;
	global $nlimit;

	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.priority,b.upload from structure a,category b where a.id=b.id_parent and a.id_parent=".$t_id." order by b.priority,b.title";
	$dp->open($sql);
	while(!$dp->eof)
	{
		if($nlimit<1000)
		{
			$sel="";
			if($t_select==$dp->row["id"]){$sel="selected";}

			$otp="";
			$fnt=17;
			for($i=0;$i<$otstup;$i++)
			{
				$otp.="&nbsp;&nbsp;";
				$fnt--;
			}
			
			$translate_results=translate_category($dp->row["id"],$dp->row["title"],"","");
			
			$style="style='font:".$fnt."px Arial'";
			$style="";

			if($dp->row["upload"]==1)
			{
				$itg.="<option class='upload_ok' value='".$dp->row["id"]."' ".$sel." ".$style.">".$otp.$translate_results["title"]."</option>";
			}
			else
			{
				$itg.="<option class='upload_error' value='' ".$sel." ".$style.">".$otp.$translate_results["title"]."</option>";
			}

			buildmenu5($dp->row["id"],$t_select,$otstup+2,$iid);
		}
		$nlimit++;
		$dp->movenext();
	}
}




//Build <ul> categories menu - /inc/box_categories.php
function buildmenu6($t_id)
{
	global $db;
	global $dr;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	global $itg;
	global $nlimit;
	$nn=0;
	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.priority,b.published ,b.url from structure a,category b where a.id=b.id_parent and a.id_parent=".$t_id." and b.published=1 order by b.priority,b.title";
	$dp->open($sql);
	if(!$dp->eof)
	{
		while(!$dp->eof)
		{
			$title=category_url($dp->row["id"],$dp->row["url"]);

			$zp=false;
			$sql="select a.id,a.id_parent,b.id_parent,b.published from structure a,category b where a.id=b.id_parent and a.id_parent=".$dp->row["id"]." and b.published=1 order by b.priority,b.title";
			$dr->open($sql);
			if(!$dr->eof)
			{
				$zp=true;
			}
			/*
			$zpn=0;
			$sql="select count(id) as count_id from structure where id_parent=".$dp->row["id"]." group by id_parent";
			$dr->open($sql);
			if(!$dr->eof)
			{
				$zpn=$dr->row["count_id"];
			}
			*/

			if($nlimit<10000)
			{
				$translate_results=translate_category($dp->row["id"],$dp->row["title"],"","");
				
				$ttl=addslashes($translate_results["title"]);
				//if($zpn!=0 and $zp){$ttl.=" (".$zpn.")";}
				
				if($nn==0){$itg.="<ul>\n";}
				//if($nn==0){$itg.="<ul class='dropdown-menu'>\n";}
				
				$class_dropdown = "";
				$class_dropdown2 ="";
				
				if($zp)
				{
					 //$class_dropdown = ' class="dropdown"';
					 //$class_dropdown2 = ' class="dropdown-toggle" data-toggle="dropdown"';
				}

				$itg.="<li ".$class_dropdown."><a href=\"".category_url($dp->row["id"],$dp->row["url"])."\" title=\"".$ttl."\" ".$class_dropdown2.">".$ttl."</a>\n";
				if(!$zp){$itg.="</li>";}
				buildmenu6($dp->row["id"]);
				if($zp){$itg.="</li>";}
			}
			$nlimit++;
			$nn++;
			$dp->movenext();
		}
		$itg.="</ul>\n";
	}
}








//Build sql query for the included subcategories
function buildmenu8($t_id)
{
	global $db;
	global $dr;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	global $itg;
	global $nlimit;
	$sql="select a.id,a.id_parent,b.id_parent,b.published from structure a,category b where a.id=b.id_parent and a.id_parent=".$t_id." and b.published=1";
	$dp->open($sql);
	if(!$dp->eof)
	{
		while(!$dp->eof)
		{
			if($nlimit<1000)
			{
				$itg.=" or (a.id_parent=".$dp->row["id"]." or b.category2=".$dp->row["id"]." or b.category3=".$dp->row["id"].") ";
				buildmenu8($dp->row["id"]);
			}
			$nlimit++;
			$dp->movenext();
		}
	}
}







//Translation function
function word_lang($t_entry)
{
	global $m_lang;
	if(isset($m_lang[$t_entry]))
	{
		return $m_lang[$t_entry];
	}
	elseif(isset($m_lang[strtolower($t_entry)]))
	{
		return $m_lang[strtolower($t_entry)];
	}
	else
	{
		return $t_entry;
	}
}

//Remove unnecessary words from the search query
function remove_words($str_remove)
{
	$rem_words=array('and','the','with','in','at','of','above','or','a','an','while','most','more','all','not','nor','either','neither','should','could','has','he','she','be','off','can','this','that', 'if', 'at', 'is', 'why', 'how', 'what', 'when', 'any', 'etc', 'e.t.c', 'ish', 'as', 'which', 'on', 'i', 'o', 'e', 'u');

	foreach ($rem_words as $key => $value)
	{
		$str_remove=preg_replace("/\s".$value."\s/Uis"," ",$str_remove);
		$str_remove=preg_replace("/^".$value."\s/Uis","",$str_remove);
		$str_remove=preg_replace("/\s".$value."$/Uis","",$str_remove);
		$str_remove=preg_replace("/\|".$value."\s/Uis","|",$str_remove);
		$str_remove=preg_replace("/\s".$value."\|/Uis","|",$str_remove);
	}
	
	return $str_remove;
}


//Show {COMPONENT_ID} code.
function show_component($component_id)
{
	global $rs;
	global $ds;
	global $dn;
	global $smarty;
	global $site_cache_components;
	global $lng;
	global $site_template_url;
	global $site_template_id;
	
	$component_body="";
	
	$smarty_component_id="component|".$component_id."|".$lng."|".$site_template_id;
	if(!$smarty->isCached('component.tpl',$smarty_component_id))
	{
		$stitle=array();
		$sdescription=array();
		$sauthor=array();
		$slink=array();
		$simage=array();
		$sid=array();
		$sdata=array();
		$sfree=array();
		$sserver=array();
		$stype=array();

		$sql="select * from components where id=".(int)$component_id;
		$rs->open($sql);
		if(!$rs->eof)
		{
			if(preg_match("/photo/",$rs->row["content"]))
			{
				$sql_categories="select a.id,a.id_parent,b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free,b.data,b.featured from structure a, photos b where a.id=b.id_parent and b.published=1";			
				$sql_lite="select b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free,b.data,b.featured from photos b where b.published=1";
			}

			if(preg_match("/video/",$rs->row["content"]))
			{
				$sql_categories="select a.id,a.id_parent,b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free,b.data,b.featured from structure a, videos b where a.id=b.id_parent and b.published=1";			
				$sql_lite="select b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free,b.data,b.featured from videos b where b.published=1";
			}

			if(preg_match("/audio/",$rs->row["content"]))
			{
				$sql_categories="select a.id,a.id_parent,b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free,b.data,b.featured from structure a, audio b where a.id=b.id_parent and b.published=1";			
				$sql_lite="select b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free,b.data,b.featured from audio b where b.published=1";
			}

			if(preg_match("/vector/",$rs->row["content"]))
			{
				$sql_categories="select a.id,a.id_parent,b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free,b.data,b.featured from structure a, vector b where a.id=b.id_parent and b.published=1";			
				$sql_lite="select b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free,b.data,b.featured from vector b where b.published=1";
			}
		
			$password_protected=get_password_protected();
		
			if($password_protected!="" or $rs->row["category"]!=0)
			{
				$sql=$sql_categories;
			}
			else
			{
		 		$sql=$sql_lite;
			}		

			if($rs->row["category"]!=0)
			{
				$sql.=" and a.id_parent=".$rs->row["category"];
			}
		
			$sql.=$password_protected;

			if($rs->row["user"]!="")
			{
				$sql.=" and b.author='".$rs->row["user"]."'";
			}

			if($rs->row["types"]=="featured")
			{
				$sql.=" and b.featured=1";
			}

			if($rs->row["types"]=="free")
			{
				$sql.=" and b.free=1";
			}	

			if($rs->row["types"]=="new")
			{
				$sql.=" order by b.data desc";
			}
			if($rs->row["types"]=="popular")
			{
				$sql.=" order by b.viewed desc";
			}
			if($rs->row["types"]=="downloaded" or $rs->row["types"]=="featured" or $rs->row["types"]=="free")
			{
				$sql.=" order by b.downloaded desc";
			}
		
			$limit_random="";
		
			if($rs->row["types"]=="random")
			{
				$count_random=0;
				$sql_random="";
				if($password_protected!="" or $rs->row["category"]!=0)
				{
					$sql_random=str_replace("a.id,a.id_parent,b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free"," count(*) as count_rows ",$sql);
				}
				else
				{
		 			$sql_random=str_replace("b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free"," count(*) as count_rows ",$sql);
				}
				$ds->open($sql_random);
				if(!$ds->eof)
				{
					$count_random=$ds->row["count_rows"];
					$rnd=rand(0,$count_random);
					if($count_random-$rnd<$rs->row["quantity"])
					{
						$rnd=$count_random-$rs->row["quantity"];
						if($rnd<0)
						{
							$rnd=0;
						}
					}
					$limit_random=" limit ".$rnd.",".$rs->row["quantity"];
				}		
			}

			if($limit_random!="")
			{
				$sql.=$limit_random;
			}
			else
			{
				$sql.=" limit 0,".$rs->row["quantity"];
			}
			//echo($sql);

			$ds->open($sql);
			$tt=0;
			while(!$ds->eof)
			{
				if(isset($ds->row["id_parent"]))
				{
					if($tt<$rs->row["quantity"])
					{
						$stitle[]=$ds->row["title"];
						$sdescription[]=$ds->row["description"];
						$sid[]=$ds->row["id_parent"];
						$sauthor[]=$ds->row["author"];
						$sdata[]=$ds->row["data"];
						$sfeatured[]=$ds->row["featured"];
						$sserver[]=$ds->row["server1"];
						$slink[]=surl.item_url($ds->row["id_parent"],$ds->row["url"]);
						$sfree[]=$ds->row["free"];

						if(preg_match("/photo/",$rs->row["content"]))
						{
							$ttt=1;
							if(preg_match("/2/",$rs->row["content"])){$ttt=2;}
							$simage[]=show_preview($ds->row["id_parent"],"photo",$ttt,1,$ds->row["server1"],$ds->row["id_parent"]);
							
							$stype[]="photo";
						}
						elseif(preg_match("/vector/",$rs->row["content"]))
						{
							$ttt=1;
							if(preg_match("/2/",$rs->row["content"])){$ttt=2;}
							$simage[]=show_preview($ds->row["id_parent"],"vector",$ttt,1,$ds->row["server1"],$ds->row["id_parent"]);
							
							$stype[]="vector";
						}
						elseif(preg_match("/video/",$rs->row["content"]))
						{
							$ttt=1;
							if(preg_match("/2/",$rs->row["content"])){$ttt=3;}
							$simage[]=show_preview($ds->row["id_parent"],"video",$ttt,1,$ds->row["server1"],$ds->row["id_parent"]);
							
							$stype[]="video";
						}
						elseif(preg_match("/audio/",$rs->row["content"]))
						{
							$ttt=1;
							if(preg_match("/2/",$rs->row["content"])){$ttt=3;}
							$simage[]=show_preview($ds->row["id_parent"],"audio",$ttt,1,$ds->row["server1"],$ds->row["id_parent"]);
							
							$stype[]="audio";
						}
						else
						{
							$simage[]="";
						}
					}
					$tt++;
				}
				$ds->movenext();
			}

			$box=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."box_home.tpl");

			$n=0;
			for($i=0;$i<$rs->row["arows"];$i++)
			{
				for($j=0;$j<$rs->row["acells"];$j++)
				{
					if($n<count($stitle) and $n<count($slink) and $n<count($simage))
					{
						$boxcontent=$box;
						$boxcontent=str_replace("{TITLE}",$stitle[$n],$boxcontent);
						$boxcontent=str_replace("{DESCRIPTION}",$sdescription[$n],$boxcontent);
						
						$boxcontent=str_replace("{COMPONENT_ID}",$component_id,$boxcontent);
						
						$boxcontent=str_replace("{ID}",$sid[$n],$boxcontent);
					
						if($sdata[$n]+3600*24*7>mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")))
						{
							$flag_new=true;
						}
						else
						{
							$flag_new=false;
						}
				
						$boxcontent=format_layout($boxcontent,"new",$flag_new);
						$boxcontent=format_layout($boxcontent,"featured",$sfeatured[$n]);

						$hoverbox_results=get_hoverbox($sid[$n],$stype[$n],$sserver[$n],$stitle[$n],show_user_name($sauthor[$n]));

						$boxcontent=str_replace("{WIDTH}",$hoverbox_results["flow_width"],$boxcontent);
						$boxcontent=str_replace("{HEIGHT}",$hoverbox_results["flow_height"],$boxcontent);
						$boxcontent=str_replace("{LIGHTBOX}",$hoverbox_results["hover"],$boxcontent);
						
						$boxcontent=str_replace("{URL}",$slink[$n],$boxcontent);
						$boxcontent=str_replace("{IMAGE}",$simage[$n],$boxcontent);
												
						$acartflow2 = array();
						preg_match_all('|\{if cartflow2\}(.*)\{/if\}|Uis', $boxcontent, $acartflow2);
						if($sfree[$n]!=1 and  isset($acartflow2[1][0]) and isset($acartflow2[0][0]))
						{
							$boxcontent=preg_replace('|\{if cartflow2\}(.*)\{/if\}|Uis',$acartflow2[1][0],$boxcontent);
						}
						else
						{
							if($sfree[$n]==1)
							{
								$sql="select id from items where id_parent=".$sid[$n]." and shipped<>1 order by priority desc";
								$dn->open($sql);
								if(!$dn->eof)
								{
									$boxcontent=preg_replace('|\{if cartflow2\}(.*)\{/if\}|Uis',"<a href='".site_root."/members/count.php?id=".$dn->row["id"]."&id_parent=".$sid[$n]."&type=".$stype[$n]."&server=".$sserver[$n]."' class='btn btn-primary' title='{lang.Free download}'> <span class='add2cart'><i class='glyphicon glyphicon-download'> </i> {lang.Free download}</span> </a>",$boxcontent);
								}
								else
								{
									$boxcontent=preg_replace('|\{if cartflow2\}(.*)\{/if\}|Uis',"<a class='btn btn-primary' title='{lang.Free download}'> <span class='add2cart'><i class='glyphicon glyphicon-download'> </i> {lang.Free download}</span> </a>",$boxcontent);
								}
							}
						}
						
						$component_body=str_replace("{SITE_ROOT}",site_root,$component_body);
		
						$component_body.=$boxcontent;
					}
					$n++;
				}
			}
		}
	}
	$smarty->cache_lifetime = 3600*$site_cache_components;
	$smarty->assign("component", $component_body);
	$component_body=$smarty->fetch('component.tpl',$smarty_component_id);

	return $component_body;
}


//The function formats a date in '2 hours ago' style.
function show_time_ago($time_ago)
{
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return word_lang("one minute ago");
        }
        else{
            return $minutes." ".word_lang("minutes ago");
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return word_lang("an hour ago");
        }else{
            return $hours." ". word_lang("hours ago");
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return word_lang("yesterday");
        }else{
            return $days." ". word_lang("days ago");
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return word_lang("a week ago");
        }else{
            return $weeks." ". word_lang("weeks ago");
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return word_lang("a month ago");
        }else{
            return $months." ". word_lang("months ago");
        }
    }
    //Years
    else{
        if($years==1){
            return word_lang("one year ago");
        }else{
            //return $years." ". word_lang("years ago");
            return(date(date_format,$time_ago));
        }
    }
}
//End. The function formats a date in '2 hours ago' style.



//The function creates URL for internal stock pages
function get_stock_page_url($stock_type,$id,$title,$content_type)
{
	$stock_url="";
	
	$title=make_translit($title);
	$title_correct=preg_replace('/[^a-z0-9 ]/i', '',$title);
	if($title_correct=="" or !preg_match("/[a-z0-9]/i",$title_correct))
	{
		$title="file-".$id;
	}
	else
	{
		$title=strtolower(str_replace(" ","-",$title_correct))."-".$id;
	}
	
	$stock_url=site_root."/".$stock_type."-".$content_type."/".$title.".html";

	return $stock_url;
}


//The function creates URL with aff link to a stock site
function get_stock_affiliate_url($stock_type,$id,$type,$aff_url = '',$aff_url2 = '')
{
	global $global_settings;
	
	$stock_url="";
	
	if($stock_type=="shutterstock")
	{		
		if($type == 'audio')
		{
			$stock_url=$global_settings["shutterstock_affiliate"]."?u=".urlencode("http://www.shutterstock.com/music/track/clip/".$id);
		}
		elseif($type == 'video')
		{
			$stock_url=$global_settings["shutterstock_affiliate"]."?u=".urlencode("http://www.shutterstock.com/video/clip-".$id.".html");
		}
		else
		{
			$stock_url=$global_settings["shutterstock_affiliate"]."?u=http%3A%2F%2Fwww.shutterstock.com%2Fpic.mhtml%3Fid%3D".$id;
		}
	}
	
	if($stock_type=="fotolia")
	{
		if($aff_url != '')
		{
			$stock_url = $aff_url;
		}
		else
		{
			$stock_url = 'http://www.fotolia.com/id/' . $id . '/partner/' . $global_settings["fotolia_account"];
		}
	}
	
	if($stock_type=="istockphoto")
	{
		$stock_url = $global_settings['istockphoto_affiliate'];
		
		if($type == 'photo')
		{
			if($aff_url2 != '' and $global_settings['istockphoto_site'] == 'istockphoto')
			{
				$ref_url = $aff_url2;
			}
			else
			{
				$ref_url = $aff_url;
			}
		}
		else
		{
			if($global_settings['istockphoto_site'] == 'istockphoto')
			{
				$ref_url = 'http://www.istockphoto.com/video/video-' . $id;
			}
			else
			{
				$ref_url = 'http://www.gettyimages.com/detail/video/video/' . $id;
			}
		}
		
		$stock_url = str_replace("{ID}",$id,$stock_url);
		$stock_url = str_replace("{URL}",$ref_url,$stock_url);
		$stock_url = str_replace("{URL_ENCODED}",urlencode($ref_url),$stock_url);
	}
	
	if($stock_type=="depositphotos")
	{
		$stock_url = $global_settings['depositphotos_affiliate'];
		
		$ref_url = 'http://www.depositphotos.com/' . $id . '/';

		$stock_url = str_replace("{ID}",$id,$stock_url);
		$stock_url = str_replace("{URL}",$ref_url,$stock_url);
		$stock_url = str_replace("{URL_ENCODED}",urlencode($ref_url),$stock_url);
	}
	
	if($stock_type=="bigstockphoto")
	{
		$stock_url = $global_settings['bigstockphoto_affiliate'];
		
		$ref_url = 'http://www.bigstockphoto.com/image-' . $id . '/';

		$stock_url = str_replace("{ID}",$id,$stock_url);
		$stock_url = str_replace("{URL}",$ref_url,$stock_url);
		$stock_url = str_replace("{URL_ENCODED}",urlencode($ref_url),$stock_url);
	}
	
	if($stock_type=="123rf")
	{
		$stock_url = $global_settings['rf123_affiliate'];
		
		$ref_url = 'http://www.123rf.com/photo_' . $id . '.html';

		$stock_url = str_replace("{ID}",$id,$stock_url);
		$stock_url = str_replace("{URL}",$ref_url,$stock_url);
		$stock_url = str_replace("{URL_ENCODED}",urlencode($ref_url),$stock_url);
	}

	return $stock_url;
}

//Create invoice
function add_invoice($order_id,$order_type)
{
	global $rs;
	global $db;
	global $global_settings;
	
	$sql="select id from invoices where order_id=".$order_id." and order_type='".$order_type."'";
	$rs->open($sql);
	if($rs->eof)
	{
		$invoice_number = $global_settings["invoice_number"] + 1;
		
		$sql="insert into invoices (invoice_number,order_id,order_type,status,comments,refund) values (".$invoice_number .",".$order_id.",'".$order_type."',".$global_settings["invoice_publish"].",'',0)";
		$db->execute($sql);
	}
	
	$sql="update settings set svalue=".$invoice_number." where setting_key='invoice_number'";
	$db->execute($sql);
}
//End. Create invoice

//Define if install folder exists
if(!preg_match("/install/",$_SERVER["PHP_SELF"]))
{
	if(file_exists($DOCUMENT_ROOT."/install/"))
	{
		echo("<p align=center><font color=red><b>You should delete /install/ directory.</b></font></p>");
		exit();
	}
}

?>
