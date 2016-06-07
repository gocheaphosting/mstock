<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);




$component_id=(int)@$_REQUEST['id'];
$component_body="";

if(isset($component_id))
{
	$smarty_component_id="component|lite|".$component_id."|".$lng."|".$site_template_id;
	if(!$smarty->isCached('component_lite.tpl',$smarty_component_id))
	{
		
		$sid=array();
		$stitle=array();
		$slink=array();
		$simage=array();
		$stype=array();
		$sserver=array();


		$sql="select * from components where id=".(int)$component_id;
		$rs->open($sql);
		if(!$rs->eof)
		{

		if(preg_match("/photo/",$rs->row["content"]))
		{
			$sql_categories="select a.id,a.id_parent,b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free from structure a, photos b where a.id=b.id_parent and b.published=1";
			
			$sql_lite="select b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free from photos b where b.published=1";
		}

		if(preg_match("/video/",$rs->row["content"]))
		{
			$sql_categories="select a.id,a.id_parent,b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free from structure a, videos b where a.id=b.id_parent and b.published=1";
			
			$sql_lite="select b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free from videos b where b.published=1";
		}

		if(preg_match("/audio/",$rs->row["content"]))
		{
			$sql_categories="select a.id,a.id_parent,b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free from structure a, audio b where a.id=b.id_parent and b.published=1";
			
			$sql_lite="select b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free from audio b where b.published=1";
		}

		if(preg_match("/vector/",$rs->row["content"]))
		{
			$sql_categories="select a.id,a.id_parent,b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free from structure a, vector b where a.id=b.id_parent and b.published=1";
			
			$sql_lite="select b.id_parent,b.title,b.description,b.author,b.server1,b.url,b.free from vector b where b.published=1";
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
						$sid[]=$ds->row["id_parent"];
						$stitle[]=$ds->row["title"];
						$sauthor[]=$ds->row["author"];
						$sserver[]=$ds->row["server1"];

						$slink[]=surl.item_url($ds->row["id_parent"],$ds->row["url"]);


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

			$box="<a href=\"{URL}\" class=\"tabs_home_link\"><img src=\"{IMAGE}\" border=\"0\" alt=\"{TITLE}\" {LIGHTBOX}></a>";

			$component_body.="<table border='0' cellpadding='0' cellspacing='3' class='component_table'>";

			$n=0;
			for($i=0;$i<$rs->row["arows"];$i++)
			{
				$component_body.="<tr valign='top'>";
				for($j=0;$j<$rs->row["acells"];$j++)
				{
					if($n<count($stitle) and $n<count($slink) and $n<count($simage))
					{
						$boxcontent=$box;
						$boxcontent=str_replace("{TITLE}",$stitle[$n],$boxcontent);
						
						$hoverbox_results=get_hoverbox($sid[$n],$stype[$n],$sserver[$n],$stitle[$n],show_user_name($sauthor[$n]));	
			
						$str_width=" width='".$hoverbox_results["flow_width"]."' ";
						$str_height=" height='".$hoverbox_results["flow_height"]."' ";

						$boxcontent=str_replace("{WIDTH}",$str_width,$boxcontent);
						$boxcontent=str_replace("{HEIGHT}",$str_height,$boxcontent);
						$boxcontent=str_replace("{LIGHTBOX}",$hoverbox_results["hover"],$boxcontent);
						$boxcontent=str_replace("{URL}",$slink[$n],$boxcontent);
						$boxcontent=str_replace("{IMAGE}",site_root."/images/e.gif",$boxcontent);
						$boxcontent=str_replace("{LINKNAME}","link_".strval($component_id)."_".strval($i)."_".strval($j),$boxcontent);
						$boxcontent=str_replace("{IMGNAME}","img_".strval($component_id)."_".strval($i)."_".strval($j),$boxcontent);

						$component_body.="<td background='".$simage[$n]."'>".$boxcontent."</td>";
					}
					$n++;
				}
				$component_body.="</tr>";
			}
			$component_body.="</table>";
		}
	}
}
$smarty->cache_lifetime = 3600*$site_cache_components;
$smarty->assign("component_lite", $component_body);
$component_body=$smarty->fetch('component_lite.tpl',$smarty_component_id);
echo($component_body);

$db->close();
?>