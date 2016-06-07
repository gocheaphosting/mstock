<?
if(!defined("site_root")){exit();}

if(isset($component_id))
{
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


					$slink[]=surl.item_url($ds->row["id_parent"],$ds->row["url"]);



						if(preg_match("/photo/",$rs->row["content"]))
						{
							$ttt=1;
							if(preg_match("/2/",$rs->row["content"])){$ttt=2;}
							$simage[]=show_preview($ds->row["id_parent"],"photo",$ttt,1,$ds->row["server1"],$ds->row["id_parent"]);
						}
						elseif(preg_match("/vector/",$rs->row["content"]))
						{
							$ttt=1;
							if(preg_match("/2/",$rs->row["content"])){$ttt=2;}
							$simage[]=show_preview($ds->row["id_parent"],"vector",$ttt,1,$ds->row["server1"],$ds->row["id_parent"]);
						}
						elseif(preg_match("/video/",$rs->row["content"]))
						{
							$ttt=1;
							if(preg_match("/2/",$rs->row["content"])){$ttt=3;}
							$simage[]=show_preview($ds->row["id_parent"],"video",$ttt,1,$ds->row["server1"],$ds->row["id_parent"]);
						}
						elseif(preg_match("/audio/",$rs->row["content"]))
						{
							$ttt=1;
							if(preg_match("/2/",$rs->row["content"])){$ttt=3;}
							$simage[]=show_preview($ds->row["id_parent"],"audio",$ttt,1,$ds->row["server1"],$ds->row["id_parent"]);
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

		$box=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."box_home.tpl");




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
					
					if($sdata[$n]+3600*24*7>mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")))
					{
						$flag_new=true;
					}
					else
					{
						$flag_new=false;
					}
				
					$boxcontent=format_layout($boxcontent,"new",$flag_new);

					$str_width="";
					$str_height="";
					
					if($rs->row["content"]=="photo1" or $rs->row["content"]=="photo2")
					{
						
						$remote_width=0;
						$remote_height=0;
						$flag_storage=false;
						$photo_width=0;
						$photo_height=0;
						
						if($rs->row["content"]=="photo1")
						{
							$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$sid[$n]." and filename1 like '%thumb1%'";
							$item_img_preview=show_preview($sid[$n],"photo",1,1,"","",false);
						}
						else
						{
							$sql="select url,filename1,filename2,width,height from filestorage_files where id_parent=".$sid[$n]." and filename1 like '%thumb2%'";
							$item_img_preview=show_preview($sid[$n],"photo",2,1,"","",false);
						}
						
						$ds->open($sql);
						if(!$ds->eof)
						{
							$remote_width=$ds->row["width"];
							$remote_height=$ds->row["height"];
							$flag_storage=true;
						}
						
						if(!$flag_storage and file_exists($_SERVER["DOCUMENT_ROOT"].$item_img_preview))
						{
							$size = getimagesize($_SERVER["DOCUMENT_ROOT"].$item_img_preview);
							$photo_width=$size[0];
							$photo_height=$size[1];
						}
			
						if($remote_width!=0 and $remote_height!=0)
						{
							$photo_width=$remote_width;
							$photo_height=$remote_height;
						}
						
						$width_limit=200;
						if($photo_width>$width_limit or $photo_height>$width_limit)
						{
							$photo_height=round($photo_height*$width_limit/$photo_width);
							$photo_width=$width_limit;
						}
						
						$str_width=" width='".$photo_width."' ";
						$str_height=" height='".$photo_height."' ";
					}
					$boxcontent=str_replace("{WIDTH}",$str_width,$boxcontent);
					$boxcontent=str_replace("{HEIGHT}",$str_height,$boxcontent);
					$boxcontent=str_replace("{URL}",$slink[$n],$boxcontent);
					$boxcontent=str_replace("{IMAGE}",$simage[$n],$boxcontent);


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
}

$db->close();
?>