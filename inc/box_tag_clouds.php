<?
if(!defined("site_root")){exit();}
$box_tag_clouds="";




if (!$smarty->isCached('tags.tpl',"tags"))
{
	$limit_tags=6;

	$tg=array();

if($global_settings["allow_photo"])
{
	$limit_random="";
	$sql="select count(*) as count_rows from photos where published=1";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$count_random=$ds->row["count_rows"];
		$rnd=rand(0,$count_random);
		if($count_random-$rnd<$limit_tags)
		{
			$rnd=$count_random-$limit_tags;
			if($rnd<0)
			{
				$rnd=0;
			}
		}
		$limit_random=" limit ".$rnd.",".$limit_tags;
	}	

	$sql="select keywords from photos where published=1 ".$limit_random;
	$rs->open($sql);
	while(!$rs->eof)
	{
		$tgg=explode(",",str_replace(";",",",$rs->row["keywords"]));
		for($i=0;$i<count($tgg);$i++)
		{
			$tgg[$i]=trim($tgg[$i]);
			if($tgg[$i]!="")
			{
				$ftg=true;
				for($j=0;$j<count($tg);$j++)
				{
					if($tg[$j]==$tgg[$i]){$ftg=false;}
				}
				if($ftg==true){$tg[count($tg)]=$tgg[$i];}
			}
		}
		$rs->movenext();
	}
}



if($global_settings["allow_video"])
{
	$limit_random="";
	$sql="select count(*) as count_rows from videos where published=1";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$count_random=$ds->row["count_rows"];
		$rnd=rand(0,$count_random);
		if($count_random-$rnd<$limit_tags)
		{
			$rnd=$count_random-$limit_tags;
			if($rnd<0)
			{
				$rnd=0;
			}
		}
		$limit_random=" limit ".$rnd.",".$limit_tags;
	}	

	$sql="select keywords from videos where published=1 ".$limit_random;
	$rs->open($sql);
	while(!$rs->eof)
	{
		$tgg=explode(",",str_replace(";",",",$rs->row["keywords"]));
		for($i=0;$i<count($tgg);$i++)
		{
			$tgg[$i]=trim($tgg[$i]);
			if($tgg[$i]!="")
			{
				$ftg=true;
				for($j=0;$j<count($tg);$j++)
				{
					if($tg[$j]==$tgg[$i]){$ftg=false;}
				}
				if($ftg==true){$tg[count($tg)]=$tgg[$i];}
			}
		}
		$rs->movenext();
	}
}




if($global_settings["allow_audio"])
{
	$limit_random="";
	$sql="select count(*) as count_rows from audio where published=1";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$count_random=$ds->row["count_rows"];
		$rnd=rand(0,$count_random);
		if($count_random-$rnd<$limit_tags)
		{
			$rnd=$count_random-$limit_tags;
			if($rnd<0)
			{
				$rnd=0;
			}
		}
		$limit_random=" limit ".$rnd.",".$limit_tags;
	}	

	$sql="select keywords from audio where published=1 ".$limit_random;
	$rs->open($sql);
	while(!$rs->eof)
	{
		$tgg=explode(",",str_replace(";",",",$rs->row["keywords"]));
		for($i=0;$i<count($tgg);$i++)
		{
			$tgg[$i]=trim($tgg[$i]);
			if($tgg[$i]!="")
			{
				$ftg=true;
				for($j=0;$j<count($tg);$j++)
				{
					if($tg[$j]==$tgg[$i]){$ftg=false;}
				}
				if($ftg==true){$tg[count($tg)]=$tgg[$i];}
			}
		}
	$rs->movenext();
	}
}



if($global_settings["allow_vector"])
{
	$limit_random="";
	$sql="select count(*) as count_rows from vector where published=1";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$count_random=$ds->row["count_rows"];
		$rnd=rand(0,$count_random);
		if($count_random-$rnd<$limit_tags)
		{
			$rnd=$count_random-$limit_tags;
			if($rnd<0)
			{
				$rnd=0;
			}
		}
		$limit_random=" limit ".$rnd.",".$limit_tags;
	}	

	$sql="select keywords from vector where published=1 ".$limit_random;
	$rs->open($sql);
	while(!$rs->eof)
	{
		$tgg=explode(",",str_replace(";",",",$rs->row["keywords"]));
		for($i=0;$i<count($tgg);$i++)
		{
			$tgg[$i]=trim($tgg[$i]);
			if($tgg[$i]!="")
			{
				$ftg=true;
				for($j=0;$j<count($tg);$j++)
				{
					if($tg[$j]==$tgg[$i]){$ftg=false;}
				}
				if($ftg==true){$tg[count($tg)]=$tgg[$i];}
			}
		}
		$rs->movenext();
	}
}



	for($j=0;$j<count($tg);$j++)
	{
		$box_tag_clouds.="<a href='".site_root."/?search=".$tg[$j]."' class='tg".rand(1,4)."'>".$tg[$j]."</a> ";
	}

}
$smarty->cache_lifetime = 3600;
$smarty->assign("tags",$box_tag_clouds);
$box_tag_clouds=$smarty->fetch('tags.tpl',"tags");




$file_template=str_replace("{BOX_TAG_CLOUDS}",$box_tag_clouds,$file_template);
?>