<?
if(!defined("site_root")){exit();}

$flag_stock = false;

//Shutterstock API
if(isset($_GET["shutterstock"]))
{
	$auth=base64_encode ($global_settings["shutterstock_id"].":".$global_settings["shutterstock_secret"]);
	
	$url = 'https://api.shutterstock.com/v2/images/' . (int)$_GET["shutterstock"];
	
	if($_GET["shutterstock_type"] == "video")
	{
		$url = 'https://api.shutterstock.com/v2/videos/' . (int)$_GET["shutterstock"];
	}
	
	if($_GET["shutterstock_type"] == "audio")
	{
		$url = 'https://api.shutterstock.com/v2/audio/' . (int)$_GET["shutterstock"];
	}
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $auth));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	
	$data = curl_exec($ch); 
	if (!curl_errno($ch)) 
	{
		$shutterstock_results=json_decode($data);
		$flag_stock = true;
		
		$shutterstock_keywords = "";
		$shutterstock_keywords_links = "";
		if(isset($shutterstock_results -> keywords))
		{
			foreach ($shutterstock_results -> keywords as $key => $value) 
			{
				if($shutterstock_keywords != "")
				{
					$shutterstock_keywords .= ', ';
				}
				if($shutterstock_keywords_links != "")
				{
					$shutterstock_keywords_links .= ', ';
				}
				$shutterstock_keywords .= $value;
				$shutterstock_keywords_links .= '<a href="' . site_root . '/index.php?stock=shutterstock&search=' . urlencode($value) . '" >' . $value .'</a>';
			}
		}
		
		$shutterstock_categories = "";
		$shutterstock_categories_links = "";
		if(isset($shutterstock_results -> categories))
		{
			foreach ($shutterstock_results -> categories as $key => $value) 
			{
				if($shutterstock_categories != "")
				{
					$shutterstock_categories .= ', ';
				}
				if($shutterstock_categories_links != "")
				{
					$shutterstock_categories_links .= ', ';
				}
				$shutterstock_categories .= @$value -> name;
				$shutterstock_categories_links .= '<a href="' . site_root . '/index.php?stock=shutterstock&category=' . @$value -> id . '" >' . @$value -> name .'</a>';
			}
		}
		
		$meta_title = @$shutterstock_results -> description;
		$meta_keywords.=$shutterstock_keywords;
		$meta_description.=@$shutterstock_results -> description;
		$social_mass["type"]=@$shutterstock_results -> media_type;
		$social_mass["title"]=@$shutterstock_results -> description;
		$social_mass["keywords"]=$shutterstock_keywords;
		$social_mass["description"]=@$shutterstock_results -> description;
		$social_mass["url"]=surl.get_stock_page_url("shutterstock",@$shutterstock_results -> id,@$shutterstock_results -> description,result($_GET["shutterstock_type"]));
		$social_mass["author"]="Shutterstock Contributor ID #".@$shutterstock_results -> contributor -> id ;
		$social_mass["google_x"]="";
		$social_mass["google_y"]="";
		$social_mass["data"]=@$shutterstock_results -> added_date;
		
		if($_GET["shutterstock_type"] == "video")
		{
			$social_mass["image"]=@$shutterstock_results -> assets -> thumb_jpg -> url;
		}
		elseif($_GET["shutterstock_type"] == "audio")
		{
			$social_mass["image"]=@$shutterstock_results -> assets -> waveform -> url;
		}
		else
		{
			$social_mass["image"]=@$shutterstock_results -> assets -> small_thumb -> url;
		}

		$social_mass["category"]=$shutterstock_categories;
	}
}
//End. Shutterstock API





//Fotolia API
if(isset($_GET["fotolia"]))
{
	$auth=base64_encode ($global_settings["fotolia_id"].":");

	$url = 'http://api.fotolia.com/Rest/1/media/getMediaData?id=' . (int)$_GET["fotolia"];;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $auth));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	
	$data = curl_exec($ch); 
	if (!curl_errno($ch)) 
	{
		$fotolia_results=json_decode($data);
		$flag_stock = true;
		
		$fotolia_keywords = "";
		$fotolia_keywords_links = "";
		if(isset($fotolia_results -> keywords))
		{
			foreach ($fotolia_results -> keywords as $key => $value) 
			{
				if($fotolia_keywords != "")
				{
					$fotolia_keywords .= ', ';
				}
				if($fotolia_keywords_links != "")
				{
					$fotolia_keywords_links .= ', ';
				}
				$fotolia_keywords .= $value->name;
				$fotolia_keywords_links .= '<a href="' . site_root . '/index.php?stock=fotolia&search=' . urlencode($value->name) . '" >' . $value->name .'</a>';
			}
		}
		
		$fotolia_categories = "";
		$fotolia_categories_links = "";
		if(isset($fotolia_results -> cat1_hierarchy))
		{
			foreach ($fotolia_results -> cat1_hierarchy as $key => $value) 
			{
				if($fotolia_categories != "")
				{
					$fotolia_categories .= ', ';
				}
				if($fotolia_categories_links != "")
				{
					$fotolia_categories_links .= ', ';
				}
				$fotolia_categories .= @$value -> name;
				$fotolia_categories_links .= '<a href="' . site_root . '/index.php?stock=fotolia&category=' . @$value -> id . '" >' . @$value -> name .'</a>';
			}
		}
		
		$meta_title = @$fotolia_results -> title;
		$meta_keywords.=$fotolia_keywords;
		$meta_description.=@$fotolia_results -> title;
		if(@$fotolia_results -> media_type_id == 1)
		{
			$social_mass["type"]="photo";
		}
		if(@$fotolia_results -> media_type_id == 2)
		{
			$social_mass["type"]="illustration";
		}
		if(@$fotolia_results -> media_type_id == 3)
		{
			$social_mass["type"]="vector";
		}
		if(@$fotolia_results -> media_type_id == 4)
		{
			$social_mass["type"]="video";
		}
		
		$fotolia_type = "photo";
		if(@$value->media_type_id == 4)
		{
			$fotolia_type = "video";
		}
		
		$social_mass["title"]=@$fotolia_results -> title;
		$social_mass["keywords"]=$fotolia_keywords;
		$social_mass["description"]="";
		$social_mass["url"]=surl.get_stock_page_url("fotolia",@$fotolia_results -> id,@$fotolia_results -> title,$fotolia_type);
		$social_mass["author"]="Fotolia Contributor ".@$fotolia_results -> creator_name ;
		$social_mass["google_x"]="";
		$social_mass["google_y"]="";
		$social_mass["data"]=@$fotolia_results -> creation_date;
		$social_mass["image"]=@$fotolia_results -> thumbnail_url;
		$social_mass["category"]=$fotolia_categories;
	}
}
//End. Fotolia API






//Istockphoto API
if(isset($_GET["istockphoto"]))
{
	if(@$_GET["istockphoto_type"] == 'photo')
	{
		$url = 'https://api.gettyimages.com/v3/images/' . (int)$_GET["istockphoto"]."/?fields=artist,artist_title,asset_family,call_for_image,caption,city,collection_code,collection_id,collection_name,color_type,comp,copyright,country,credit_line,date_created,date_submitted,detail_set,display_set,download_sizes,editorial_segments,event_ids,graphical_style,id,keywords,license_model,links,max_dimensions,orientation,people,prestige,preview,product_types,quality_rank,referral_destinations,thumb,title";
	}
	else
	{
		$url = 'https://api.gettyimages.com/v3/videos/' . (int)$_GET["istockphoto"] . '/?fields=id,allowed_use,artist,asset_family,caption,clip_length,collection_code,collection_id,collection_name,color_type,copyright,comp,date_created,display_set,download_sizes,era,editorial_segments,keywords,license_model,mastered_to,originally_shot_on,preview,product_types,shot_speed,source,thumb,title';
	}
	//echo($url);
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Key: '.$global_settings["istockphoto_id"]));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	
	$data = curl_exec($ch); 
	if (!curl_errno($ch)) 
	{
		$results=json_decode($data);
		$flag_stock = true;
		
		//var_dump($results);exit();
		
		if(@$_GET["istockphoto_type"] == 'photo')
		{
			$data2 = $results->images;
		}
		else
		{
			$data2[0] = $results;
		}
		
		foreach ($data2 as $key => $value) 
		{	
			$istockphoto_results = $value;
			
			$istockphoto_keywords = "";
			$istockphoto_keywords_links = "";
			$istockphoto_keywords_related = "";
			if(isset($istockphoto_results -> keywords))
			{
				$kk = 0;
				foreach ($istockphoto_results -> keywords as $key2 => $value2) 
				{
					if(isset($value2->text))
					{
						if($istockphoto_keywords != "")
						{
							$istockphoto_keywords .= ', ';
						}
						if($istockphoto_keywords_links != "")
						{
							$istockphoto_keywords_links .= ', ';
						}
						$istockphoto_keywords .= @$value2->text;
						$istockphoto_keywords_links .= '<a href="' . site_root . '/index.php?stock=istockphoto&search=' . urlencode(@$value2->text) . '" >' . @$value2->text .'</a>';
						if($kk == 0)
						{
							$istockphoto_keywords_related = @$value2->text;
						}
						
						$kk ++;
					}
				}
			}
			
			if(@$_GET["istockphoto_type"] == 'photo')
			{
				$social_mass["type"]="photo";
				$istockphoto_type = "photo";
			}
			else
			{
				$social_mass["type"]="video";
				$istockphoto_type = "video";
			}
			
			$istockphoto_categories = @$istockphoto_results -> collection_name;
			$istockphoto_categories_links = @$istockphoto_results -> collection_name;
			
			$meta_title = @$istockphoto_results -> title;
			$meta_keywords.=$istockphoto_keywords;
			$meta_description.=@$istockphoto_results -> title;
			
			$social_mass["title"]=@$istockphoto_results -> title;
			$social_mass["keywords"]=$istockphoto_keywords;
			$social_mass["description"]="";
			$social_mass["url"]=surl.get_stock_page_url("istockphoto",@$istockphoto_results -> id,@$istockphoto_results -> title,$istockphoto_type);
			$social_mass["author"]="Istockphoto Artist ".@$istockphoto_results -> artist ;
			$social_mass["google_x"]="";
			$social_mass["google_y"]="";
			$social_mass["data"]=@$istockphoto_results -> date_submitted;
			
			$istockphoto_image = @$istockphoto_results->display_sizes;
			if(@$_GET["istockphoto_type"] == 'photo')
			{				
				$istockphoto_preview = $istockphoto_image[0];
			}
			else
			{
				$istockphoto_preview = $istockphoto_image[2];		
			}
			$social_mass["image"]=$istockphoto_preview -> uri;
			
			
			$social_mass["category"]=$istockphoto_categories;
		}
	}
}
//End. Istockphoto API







//Depositphotos API
if(isset($_GET["depositphotos"]))
{
	$url = 'http://api.depositphotos.com?dp_apikey=' . $global_settings["depositphotos_id"] . '&dp_command=getMediaData&dp_media_id=' . (int)$_GET["depositphotos"];
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	
	$data = curl_exec($ch); 
	if (!curl_errno($ch)) 
	{
		$depositphotos_results=json_decode($data);
		$flag_stock = true;
		
		$depositphotos_keywords = "";
		$depositphotos_keywords_links = "";
		if(isset($depositphotos_results -> tags))
		{
			foreach ($depositphotos_results -> tags as $key => $value) 
			{
				if($depositphotos_keywords != "")
				{
					$depositphotos_keywords .= ', ';
				}
				if($depositphotos_keywords_links != "")
				{
					$depositphotos_keywords_links .= ', ';
				}
				$depositphotos_keywords .= $value;
				$depositphotos_keywords_links .= '<a href="' . site_root . '/index.php?stock=depositphotos&search=' . urlencode($value) . '" >' . $value .'</a>';
			}
		}
		
		$depositphotos_categories = "";
		$depositphotos_categories_links = "";
		if(isset($depositphotos_results -> categories))
		{
			foreach ($depositphotos_results -> categories as $key => $value) 
			{
				if($depositphotos_categories != "")
				{
					$depositphotos_categories .= ', ';
				}
				if($depositphotos_categories_links != "")
				{
					$depositphotos_categories_links .= ', ';
				}
				$depositphotos_categories .= @$value;
				$depositphotos_categories_links .= '<a href="' . site_root . '/index.php?stock=depositphotos&category=' . @$key . '" >' . @$value .'</a>';
			}
		}
		
		$meta_title = @$depositphotos_results -> title;
		$meta_keywords.=$depositphotos_keywords;
		$meta_description.=@$depositphotos_results -> description;
		$social_mass["type"]=@$depositphotos_results -> itype;
		$social_mass["title"]=@$depositphotos_results -> title;
		$social_mass["keywords"]=$depositphotos_keywords;
		$social_mass["description"]=@$depositphotos_results -> description;
		$social_mass["url"]=surl.get_stock_page_url("depositphotos",@$depositphotos_results -> id,@$depositphotos_results -> title,result($_GET["depositphotos_type"]));
		$social_mass["author"]="Depositphotos Contributor -  ".@$depositphotos_results -> username ;
		$social_mass["google_x"]="";
		$social_mass["google_y"]="";
		$social_mass["data"]=@$depositphotos_results -> published;
		
		if($_GET["depositphotos_type"] == "photo")
		{
			$social_mass["image"]=@$depositphotos_results -> huge_thumb;
		}
		else
		{
			$social_mass["image"]=@$depositphotos_results -> huge_thumb;
		}

		$social_mass["category"]=$depositphotos_categories;
	}
}
//End. depositphotos API







//Bigstockphoto API
if(isset($_GET["bigstockphoto"]))
{
	$url = 'https://api.bigstockphoto.com/2/' . $global_settings["bigstockphoto_id"] . '/image/' . (int)$_GET["bigstockphoto"];
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

	$data = curl_exec($ch); 
	if (!curl_errno($ch)) 
	{
		$bigstockphoto_results2=json_decode($data);
		$bigstockphoto_results = $bigstockphoto_results2->data->image;
		
		$flag_stock = true;
		
		$bigstockphoto_keywords = $bigstockphoto_results -> keywords;
		$kw = explode(",",$bigstockphoto_keywords);
		$bigstockphoto_keywords_links = "";
		foreach ($kw as $key => $value) 
		{
			if($bigstockphoto_keywords_links != "")
			{
				$bigstockphoto_keywords_links .= ', ';
			}
			$bigstockphoto_keywords_links .= '<a href="' . site_root . '/index.php?stock=bigstockphoto&search=' . urlencode($value) . '" >' . $value .'</a>';
		}
		
		$bigstockphoto_categories = "";
		$bigstockphoto_categories_links = "";
		if(isset($bigstockphoto_results -> categories))
		{
			foreach ($bigstockphoto_results -> categories as $key => $value) 
			{
				if($bigstockphoto_categories != "")
				{
					$bigstockphoto_categories .= ', ';
				}
				if($bigstockphoto_categories_links != "")
				{
					$bigstockphoto_categories_links .= ', ';
				}
				$bigstockphoto_categories .= @$value->name;
				$bigstockphoto_categories_links .= '<a href="' . site_root . '/index.php?stock=bigstockphoto&category=' . urlencode(@$value->name) . '" >' . @$value->name .'</a>';
			}
		}
		
		$meta_title = @$bigstockphoto_results -> title;
		$meta_keywords.=$bigstockphoto_keywords;
		$meta_description.=@$bigstockphoto_results -> description;
		$social_mass["type"]="photo";
		$social_mass["title"]=@$bigstockphoto_results -> title;
		$social_mass["keywords"]=$bigstockphoto_keywords;
		$social_mass["description"]=@$bigstockphoto_results -> description;
		$social_mass["url"]=surl.get_stock_page_url("bigstockphoto",@$bigstockphoto_results -> id,@$bigstockphoto_results -> title,"photo");
		$social_mass["author"]="Bigstockphoto Contributor -  ".@$bigstockphoto_results -> contributor ;
		$social_mass["google_x"]="";
		$social_mass["google_y"]="";
		$social_mass["data"]="";
		$social_mass["image"]=@$bigstockphoto_results -> preview -> url;
		$social_mass["category"]=$bigstockphoto_categories;
	}
}
//End. Bigstockphoto API







//123rf API
if(isset($_GET["rf123"]))
{
	$url = 'http://api.123rf.com/rest/?method=123rf.images.getInfo.V2&apikey=' . $global_settings["rf123_id"] . '&imageid=' . (int)$_GET["rf123"] . '&ies=1';
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

	$data = curl_exec($ch); 
	if (!curl_errno($ch)) 
	{
		$rf123_results2=json_decode(json_encode(simplexml_load_string($data)));

		$rf123_results = $rf123_results2->image;
		
		$flag_stock = true;
		
		$rf123_keywords = "";
		$rf123_keywords_links = "";
		$rf123_keywords_similar = "";
		$kk=0;
		foreach ($rf123_results->keywords->keyword as $key => $value) 
		{
			if($rf123_keywords != "")
			{
				$rf123_keywords .= ', ';
			}
			if($rf123_keywords_links != "")
			{
				$rf123_keywords_links .= ', ';
			}
			$rf123_keywords_links .= '<a href="' . site_root . '/index.php?stock=rf123&search=' . urlencode($value) . '" >' . $value .'</a>';
			$rf123_keywords .=$value; 
			
			if($kk<2)
			{
				$rf123_keywords_similar .=$value . " "; 
			}
			
			$kk++;
		}
		
		$rf123_categories = "";
		$rf123_categories_links = "";
		
		$meta_title = @$rf123_results -> description;
		$meta_keywords.=$rf123_keywords;
		$meta_description.=@$rf123_results -> description;
		$social_mass["type"]="photo";
		$social_mass["title"]=@$rf123_results -> description;
		$social_mass["keywords"]=$rf123_keywords;
		$social_mass["description"]=@$rf123_results -> description;
		$social_mass["url"]=surl.get_stock_page_url("123rf",@$rf123_results -> {"@attributes"}->id,@$rf123_results -> description,"photo");
		$social_mass["author"]="123rf Contributor -  ".@$rf123_results -> contributor ->{"@attributes"}-> id ;
		$social_mass["google_x"]="";
		$social_mass["google_y"]="";
		$social_mass["data"]="";
		
		$preview = 'http://images.assetsdelivery.com/compings/' . @$rf123_results -> contributor ->{"@attributes"}-> id . '/' . @$rf123_results->{"@attributes"}->folder . '/' . @$rf123_results->{"@attributes"}->filename . '.jpg';
		
		$social_mass["image"]=$preview;
		$social_mass["category"]=$rf123_categories;
	}
}
//End. 123rf API





if(!$flag_stock)
{
	echo("Sorry. The site cannot connect to Stock API");exit();
}
?>