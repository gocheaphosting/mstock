<? include("../function/db.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("catalog_categories|catalog_catalog");



//Change category's priority
if($_POST["formaction"]=="priority")
{
	$sql="select id_parent from category";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if(isset($_POST["priority".$rs->row["id_parent"]]))
		{
			$sql="update category set priority=".(int)$_POST["priority".$rs->row["id_parent"]]." where id_parent=".$rs->row["id_parent"];
			$db->execute($sql);
		}

		$rs->movenext();
	}
	
	header("location:../categories/index.php");
}
//End. Change category's priority









//Get list of IDs
	$res_id=array();
	$res_module=array();
	$res_category=0;
	$res_photo=0;
	$res_video=0;
	$res_audio=0;
	$res_vector=0;
	$nlimit=0;

	
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{

				if($_POST["formaction"]=="thumbs_publication")
				{
					$res_id[]=(int)$res_temp[1];
					$res_module[]=30;
					$res_photo++;
				}
				elseif($_POST["formaction"]=="change_publication" or $_POST["formaction"]=="content_publication" or $_POST["formaction"]=="move_publication" or $_POST["formaction"]=="free_publication" or $_POST["formaction"]=="featured_publication" or $_POST["formaction"]=="editorial_publication" or 
				$_POST["formaction"]=="adult_publication" or 
				$_POST["formaction"]=="contacts_publication" or 
				$_POST["formaction"]=="exclusive_publication" or $_POST["formaction"]=="approve_publication" or $_POST["formaction"]=="rights_managed" or $_POST["formaction"]=="bulk_change_publication" or $_POST["formaction"]=="bulk_keywords_publication")
				{
					$res_id[]=(int)$res_temp[1];
				
					$sql="select module_table from structure where id=".(int)$res_temp[1];
					$dr->open($sql);
					while(!$dr->eof)
					{
						if($dr->row["module_table"]==30)
						{
							$res_module[]=$dr->row["module_table"];
							$res_photo++;
						}
		
						if($dr->row["module_table"]==31)
						{
							$res_module[]=$dr->row["module_table"];
							$res_video++;
						}
		
						if($dr->row["module_table"]==52)
						{
							$res_module[]=$dr->row["module_table"];
							$res_audio++;
						}
		
						if($dr->row["module_table"]==53)
						{
							$res_module[]=$dr->row["module_table"];
							$res_vector++;
						}
						
						$dr->movenext();
					}
				}
				else
				{
					//Publications are included into the category
					$res_id[]=(int)$res_temp[1];
					$res_module[]=34;
					$res_category++;
					$nlimit=0;
					get_included_publications((int)$res_temp[1]);
				}
			}
		}
//End. Get list of IDs








//Delete the category and all included content
if($_POST["formaction"]=="delete_category")
{

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		echo("<h1>".word_lang("categories")."</h1>");
		echo("<p>Do you want to delete the categories and all included publications?</p>");
		
		echo("<ul>");
		echo("<li>".word_lang("categories").": <b>".$res_category."</b></li>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");
		
		echo("<form method='post' action='edit.php'>");
		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		echo("<input type='hidden'  name='formaction' value='delete_category'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit' class='btn btn-primary'  value='".word_lang("Yes")."'>&nbsp;&nbsp;&nbsp;
		<input type='button'  class='btn btn-danger' onClick=\"location.href='index.php'\"  value='".word_lang("No")."'>
		</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==34)
			{
				if(!$demo_mode)
				{
					delete_category($res_id[$i],0);
				}
			}
			else
			{
				if(!$demo_mode)
				{
					publication_delete($res_id[$i]);
				}
			}
		}
		header("location:../categories/index.php");
	}
}
//End. Delete the category and all included content














//Delete publications
if($_POST["formaction"]=="delete_publication")
{
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				if(!$demo_mode)
				{
					publication_delete((int)$res_temp[1]);
				}
			}
		}

	if(isset($_SERVER["HTTP_REFERER"]))
	{
		header("location:".$_SERVER["HTTP_REFERER"]);
	}
	else
	{
		header("location:../catalog/index.php");
	}
}
//End. Delete publications




















//Regeneration thumbs for the photos
if($_POST["formaction"]=="thumbs" or $_POST["formaction"]=="thumbs_publication")
{
	if($_POST["formaction"]=="thumbs")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}
	
	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="thumbs")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		echo("<p>Do you want to regenerate thumbs for the photo's publications?</p>");
		echo("<p><b>Attention!</b> The operation can overload your server because every thumb's generation requires RAM memory especially for the high-resolution photos.</p>");
		

		if($global_settings["allow_photo"]){echo("<p>".word_lang("photo").": <b>".$res_photo."</b></p>");}

		
		echo("<form method=\"post\" action='edit.php'>");
		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		if($res_photo>0)
		{
			echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'>
			<input type='hidden'  name='step' value='2'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
			<input type='submit'  class='btn btn-primary'   value='".word_lang("Yes")."'>&nbsp;&nbsp;&nbsp;
			<input type='button' class='btn btn-primary'  onClick=\"location.href='".$return_url."'\"  value='".word_lang("No")."'>");
		}
		else
		{
			echo("<input type='button' class='btn btn-primary' onClick=\"location.href='".$return_url."'\"  value='".word_lang("Back")."'>");
		}
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==30)
			{
				$sql="select server1 from photos where id_parent=".$res_id[$i];
				$rs->open($sql);
				if(!$rs->eof)
				{
					/*
					$sql="select url from items where id_parent=".$res_id[$i];
					$ds->open($sql);
					if(!$ds->eof)
					{
						if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/".$ds->row["url"]))
						{
							photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/".$ds->row["url"],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/thumb1.jpg",1);

							photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/".$ds->row["url"],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/thumb2.jpg",2);
							publication_watermark_add($res_id[$i],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/thumb2.jpg");
						}
					}
					*/
					$url=get_photo_file($res_id[$i]);
					
					photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/".$url,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/thumb1.jpg",1);

					photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/".$url,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/thumb2.jpg",2);
					publication_watermark_add($res_id[$i],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$res_id[$i]."/thumb2.jpg");
				}
			}
		}
	
		header("location:".$return_url);
	}
}
//End. Regeneration thumbs for the photos




















//Change 'content_type' for the files
if($_POST["formaction"]=="content" or $_POST["formaction"]=="content_publication")
{
	if($_POST["formaction"]=="content")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="content")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		
		echo("<p>Do you want to change <a href='../content_types/'>Content type</a> for the publications?</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		
		echo("<p><select name='content_type' style='width:200'>");
		
		$sql="select name from content_type order by priority";
		$rs->open($sql);
		while(!$rs->eof)
		{
		echo("<option value='".$rs->row["name"]."'>".$rs->row["name"]."</option>");
		$rs->movenext();
		}
		echo("</select></p>");

		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");


		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Yes")."'>&nbsp;&nbsp;&nbsp;
		<input type='button'  class='btn btn-danger' onClick=\"location.href='".$return_url."'\"  value='".word_lang("No")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==30)
			{
				$sql="update photos set content_type='".result($_POST["content_type"])."' where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==31)
			{
				$sql="update videos set content_type='".result($_POST["content_type"])."' where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==52)
			{
				$sql="update audio set content_type='".result($_POST["content_type"])."' where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==53)
			{
				$sql="update vector set content_type='".result($_POST["content_type"])."' where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
		}
		header("location:".$return_url);
	}
}
//End. Change 'content_type' for the files














//Change publications properties
if($_POST["formaction"]=="change_publication")
{
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}
	else
	{
		$return_url="../catalog/index.php";
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		echo("<h1>".word_lang("catalog")."</h1>");
		
		if(count($res_id)>0)
		{
				
			echo("<form method='post' action='edit.php'>");
		


			echo("<div class='table_t2'><div class='table_b'><div class='table_l'><div class='table_r'><div class='table_bl'><div class='table_br'><div class='table_tl'><div class='table_tr'><table border='0' cellpadding='0' cellspacing='1' class='table_admin' style='width:100%'><tr><th width='120'>".word_lang("preview")."</th><th>".word_lang("categories")."</th><th>".word_lang("title")." - ".word_lang("description")." - ".word_lang("keywords")."</th><th width='70'>".word_lang("featured")."</th><th width='70'>".word_lang("free")."</th></tr>");


		
			for($i=0;$i<count($res_id);$i++)
			{
				if($res_module[$i]==30){$type="photo";$table="photos";}
				if($res_module[$i]==31){$type="video";$table="videos";}
				if($res_module[$i]==52){$type="audio";$table="audio";}
				if($res_module[$i]==53){$type="vector";$table="vector";}
				$sql="select id_parent,title,description,keywords,free,featured,category2,category3,server1 from ".$table." where id_parent=".(int)$res_id[$i];
				$rs->open($sql);
				if(!$rs->eof)
				{
					$free="";
					if($rs->row["free"]==1){$free="checked";}
					
					$featured="";
					if($rs->row["featured"]==1){$featured="checked";}
					
					$category="";
					$sql="select id_parent from structure where id=".(int)$res_id[$i];
					$dr->open($sql);
					if(!$dr->eof)
					{
						$itg="";
						$nlimit=0;
						buildmenu2(5,$dr->row["id_parent"],2,0);
						$category=$itg;
					}

					$itg="";
					$nlimit=0;
					buildmenu2(5,$rs->row["category2"],2,0);
					$category2=$itg;

					$itg="";
					$nlimit=0;
					buildmenu2(5,$rs->row["category3"],2,0);
					$category3=$itg;
					$hoverbox_results=get_hoverbox($res_id[$i],$type,$rs->row["server1"],"","");
				
				
					echo("<tr valign='top'>");
					echo("<td>");
						echo("<div style='margin-bottom:3px'><img src='".show_preview($res_id[$i],$type,1,1)."' ".$hoverbox_results["hover"]."></div>");
						echo("<div><small>".word_lang($type)." ID=".$res_id[$i]."</small></div>");
					echo("</td>");
					echo("<td>");
					
					echo("<div><select name='category".$res_id[$i]."' style='width:400px' class='ibox form-control'><option value='5'></option>".$category."</select></div>");
					echo("<div style='margin-top:3px'><select name='category2_".$res_id[$i]."' style='width:400px' class='ibox form-control'><option value='5'></option>".$category2."</select></div>");
					echo("<div style='margin-top:3px'><select name='category3_".$res_id[$i]."' style='width:400px' class='ibox form-control'><option value='5'></option>".$category3."</select></div>");
					
					
					echo("</td>");
					echo("<td>");
						echo("<div><input type='text' name='title".$res_id[$i]."' value='".$rs->row["title"]."' style='width:400px'></div>");
					
						echo("<div style='margin-top:3px'><textarea class='textarea' name='description".$res_id[$i]."' style='width:400px;height:130px'>".$rs->row["description"]."</textarea></div>");
						
						echo("<div style='margin-top:3px'><textarea class='textarea' name='keywords".$res_id[$i]."' style='width:400px;height:130px'>".$rs->row["keywords"]."</textarea></div>");
					
						echo("<input type='hidden' name='sel".$res_id[$i]."' value='1'>");
					echo("</td>");
					echo("<td align='center'>");
						echo("<input type='checkbox' name='featured".$res_id[$i]."' ".$featured.">");
					echo("</td>");
					echo("<td align='center'>");
						echo("<input type='checkbox' name='free".$res_id[$i]."' ".$free.">");
					echo("</td>");
					echo("</tr>");
				}
			}
		
			echo("</table></div></div></div></div></div></div></div></div>");
		
			echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
			<input type='hidden'  name='step' value='2'>
			<input type='submit'  class='btn btn-primary'  value='".word_lang("Change")."' style='margin-top:15px'>");
		
		
			echo("</form>");
			
		}
		else
		{
			echo("<p>".word_lang("not found")."</p>");
			echo("<input type='button' class='btn btn-primary' onClick=\"location.href='".$return_url."'\"  value='".word_lang("Back")."'>");
		}
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
		
			if($res_module[$i]==30){$table="photos";}
			if($res_module[$i]==31){$table="videos";}
			if($res_module[$i]==52){$table="audio";}
			if($res_module[$i]==53){$table="vector";}
			
			$free=0;
			if(isset($_POST["free".$res_id[$i]])){$free=1;}
			
			$featured=0;
			if(isset($_POST["featured".$res_id[$i]])){$featured=1;}
			
			$sql="update structure set id_parent=".(int)$_POST["category".$res_id[$i]].",name='".result($_POST["title".$res_id[$i]])."' where id=".$res_id[$i];
			$db->execute($sql);

			$sql="update ".$table." set title='".result($_POST["title".$res_id[$i]])."',description='".result($_POST["description".$res_id[$i]])."',keywords='".result($_POST["keywords".$res_id[$i]])."',category2=".(int)$_POST["category2_".$res_id[$i]].",category3=".(int)$_POST["category3_".$res_id[$i]].",featured=".$featured.",free=".$free." where id_parent=".$res_id[$i];
			$db->execute($sql);
			
			$smarty->clearCache(null,"item|".$res_id[$i]);
			$smarty->clearCache(null,"share|".$res_id[$i]);
			
			item_url($res_id[$i]);

		}
		header("location:".$return_url);
	}
}
//End. Change publications properties













//Move files to category
if($_POST["formaction"]=="move_publication")
{
	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		

		echo("<h1>".word_lang("catalog")."</h1>");
		
		echo("<p>Do you want to move the publications to the new category?</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		
		echo("<div class='admin_field'><span>".word_lang("category")." 1:</span><select name='category' style='width:200'><option value='-1'>".word_lang("not to change")."</option><option value='5'></option>");
		
		$itg="";
		$nlimit=0;
		buildmenu2(5,0,2,0);
		echo($itg);

		echo("</select></div>");
		
		echo("<div class='admin_field'><span>".word_lang("category")." 2:</span><select name='category2' style='width:200'><option value='-1'>".word_lang("not to change")."</option><option value='5'></option>");

		echo($itg);

		echo("</select></div>");
		
		echo("<div class='admin_field'><span>".word_lang("category")." 3:</span><select name='category3' style='width:200'><option value='-1'>".word_lang("not to change")."</option><option value='5'></option>");
		
		echo($itg);

		echo("</select></div>");

		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");


		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("change")."'>
		");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($_POST["category"]!=-1)
			{
				$sql="update structure set id_parent=".(int)$_POST["category"]." where id=".$res_id[$i];
				$db->execute($sql);
			}
			
			if($res_module[$i]==30){$table="photos";}
			if($res_module[$i]==31){$table="videos";}
			if($res_module[$i]==52){$table="audio";}
			if($res_module[$i]==53){$table="vector";}
			
			if($_POST["category2"]!=-1)
			{
				$sql="update ".$table." set category2=".(int)$_POST["category2"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			
			if($_POST["category3"]!=-1)
			{
				$sql="update ".$table." set category3=".(int)$_POST["category3"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
		}
		if($_POST["category"]!=-1)
		{
			header("location:../catalog/index.php?category_id=".(int)$_POST["category"]);
		}
		else
		{
			header("location:../catalog/index.php");
		}
	}
}
//End. Move files to category













//Regenerate URLs
if($_POST["formaction"]=="regenerate_urls")
{

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		echo("<h1>".word_lang("regenerate urls")."</h1>");
		echo("<p>The script uses Apache mod-rewrite URLs. They are created one time when you add a publication or edit its properties. The URLs are virtual. So the files like /photo/photo-title.html don't exist on the server. You can find all mod-rewite instructions in the file .htaccess into the root directory.</p>");
		
		echo("<p>Sometimes it is necessary to regenerate all URLs because .htaccess file was changed. For example until version 12.05 a publication had URL: /photo/photo-title.html or /photo/[photo ID].html but we made it more seo-friendly: /stock-photo/photo-title-[photo ID].html</p>");
		
		echo("<p>You should notice that old URLs (which were probably indexed by google) will work too.</p>");
		
		echo("<p>The tool will regenerate all URLs - not only selected.</p>");
		
		echo("<form method='post' action='edit.php'>");
		
		
		echo("<input type='hidden'  name='formaction' value='regenerate_urls'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Yes")."'>&nbsp;&nbsp;&nbsp;
		<input type='button'  class='btn btn-danger' onClick=\"location.href='index.php'\"  value='".word_lang("No")."'>
		</form>");
		
		include("../inc/end.php");
	}
	else
	{
		$sql="select id_parent from photos";
		$rs->open($sql);
		while(!$rs->eof)
		{
			item_url($rs->row["id_parent"]);
			$rs->movenext();
		}

		$sql="select id_parent from videos";
		$rs->open($sql);
		while(!$rs->eof)
		{
			item_url($rs->row["id_parent"]);
			$rs->movenext();
		}

		$sql="select id_parent from audio";
		$rs->open($sql);
		while(!$rs->eof)
		{
			item_url($rs->row["id_parent"]);
			$rs->movenext();
		}

		$sql="select id_parent from vector";
		$rs->open($sql);
		while(!$rs->eof)
		{
			item_url($rs->row["id_parent"]);
			$rs->movenext();
		}

		header("location:../catalog/index.php");
	}
}
//End. Regenerate URLs

















//Change files to free/paid
if($_POST["formaction"]=="free" or $_POST["formaction"]=="free_publication")
{
	if($_POST["formaction"]=="free")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="free")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		
		echo("<p>Do you want to change the files to free/paid?</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		
		echo("<p><input type='radio' name='free' value='1' checked> ".word_lang("free")."</p>");
		
		echo("<p><input type='radio' name='free' value='0'> ".word_lang("paid")."</p>");


		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");


		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==30)
			{
				$sql="update photos set free=".(int)$_POST["free"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==31)
			{
				$sql="update videos set free=".(int)$_POST["free"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==52)
			{
				$sql="update audio set free=".(int)$_POST["free"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==53)
			{
				$sql="update vector set free=".(int)$_POST["free"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
		}
		header("location:".$return_url);
	}
}
//End. Change files to free/paid









//Change rights-managed price
if($_POST["formaction"]=="rights_managed" or $_POST["formaction"]=="rights_managed_categories")
{
	if($_POST["formaction"]=="rights_managed_categories")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="rights_managed_categories")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		
		echo("<p>Please select rights-managed price for the publications:</p>");
		
		echo("<p><b>Attention!</b> You won't be able to change rights-managed files back to Royalty-free.</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		
		echo("<select name='rights_managed' style='width:400px'>");
		
		$sql="select id,title from rights_managed";
		$ds->open($sql);
		while(!$ds->eof)
		{
			echo("<option value='".$ds->row["id"]."'>".$ds->row["title"]."</option>");
			$ds->movenext();
		}
		
		echo("</select><br><br>");


		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");


		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		$sql="select id,title,price,photo,video,audio,vector from rights_managed where id=".(int)$_POST["rights_managed"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			for($i=0;$i<count($res_id);$i++)
			{
				if($res_module[$i]==30 and $ds->row["photo"]==1)
				{
					$sql="update photos set rights_managed=".(int)$_POST["rights_managed"]." where id_parent=".$res_id[$i];
					$db->execute($sql);
					
					$sql="update items set name='".$ds->row["title"]."',price=".$ds->row["price"].",price_id=".(int)$_POST["rights_managed"]." where id_parent=".$res_id[$i];
					$db->execute($sql);
				}
				if($res_module[$i]==31 and $ds->row["video"]==1)
				{
					$sql="update videos set rights_managed=".(int)$_POST["rights_managed"]." where id_parent=".$res_id[$i];
					$db->execute($sql);
					
					$sql="update items set name='".$ds->row["title"]."',price=".$ds->row["price"].",price_id=".(int)$_POST["rights_managed"]." where id_parent=".$res_id[$i];
					$db->execute($sql);
				}
				if($res_module[$i]==52 and $ds->row["audio"]==1)
				{
					$sql="update audio set rights_managed=".(int)$_POST["rights_managed"]." where id_parent=".$res_id[$i];
					$db->execute($sql);
					
					$sql="update items set name='".$ds->row["title"]."',price=".$ds->row["price"].",price_id=".(int)$_POST["rights_managed"]." where id_parent=".$res_id[$i];
					$db->execute($sql);
				}
				if($res_module[$i]==53 and $ds->row["vector"]==1)
				{
					$sql="update vector set rights_managed=".(int)$_POST["rights_managed"]." where id_parent=".$res_id[$i];
					$db->execute($sql);
					
					$sql="update items set name='".$ds->row["title"]."',price=".$ds->row["price"].",price_id=".(int)$_POST["rights_managed"]." where id_parent=".$res_id[$i];
					$db->execute($sql);
				}
			}
		}
		header("location:".$return_url);
	}
}
//End. Change rights-managed price









//Change files to featured
if($_POST["formaction"]=="featured" or $_POST["formaction"]=="featured_publication")
{
	if($_POST["formaction"]=="featured")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="featured")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		
		echo("<p>Do you want to change the files to Featured/Common?</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		
		echo("<p><input type='radio' name='featured' value='1' checked> ".word_lang("featured")."</p>");
		
		echo("<p><input type='radio' name='featured' value='0'> ".word_lang("common")."</p>");


		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");


		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary' value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==30)
			{
				$sql="update photos set featured=".(int)$_POST["featured"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==31)
			{
				$sql="update videos set featured=".(int)$_POST["featured"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==52)
			{
				$sql="update audio set featured=".(int)$_POST["featured"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==53)
			{
				$sql="update vector set featured=".(int)$_POST["featured"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
		}
		header("location:".$return_url);
	}
}
//End. Change files to featured








//Change files to adult
if($_POST["formaction"]=="adult" or $_POST["formaction"]=="adult_publication")
{
	if($_POST["formaction"]=="adult")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="adult")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		
		echo("<p>Do you want to change the files to Adult/Common?</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		
		echo("<p><input type='radio' name='adult' value='1' checked> ".word_lang("adult content")."</p>");
		
		echo("<p><input type='radio' name='adult' value='0'> ".word_lang("common")."</p>");


		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");


		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==30)
			{
				$sql="update photos set adult=".(int)$_POST["adult"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==31)
			{
				$sql="update videos set adult=".(int)$_POST["adult"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==52)
			{
				$sql="update audio set adult=".(int)$_POST["adult"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==53)
			{
				$sql="update vector set adult=".(int)$_POST["adult"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
		}
		header("location:".$return_url);
	}
}
//End. Change files to adult










//Change files to exclusive
if($_POST["formaction"]=="exclusive" or $_POST["formaction"]=="exclusive_publication")
{
	if($_POST["formaction"]=="exclusive")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="exclusive")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		
		echo("<p>Do you want to change the files to Exclusive/Common?</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		
		echo("<p><input type='radio' name='exclusive' value='1' checked> ".word_lang("exclusive price")."</p>");
		
		echo("<p><input type='radio' name='exclusive' value='0'> ".word_lang("common")."</p>");


		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");


		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==30)
			{
				$sql="update photos set exclusive=".(int)$_POST["exclusive"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==31)
			{
				$sql="update videos set exclusive=".(int)$_POST["exclusive"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==52)
			{
				$sql="update audio set exclusive=".(int)$_POST["exclusive"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==53)
			{
				$sql="update vector set exclusive=".(int)$_POST["exclusive"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
		}
		header("location:".$return_url);
	}
}
//End. Change files to exclusive









//Change files to contacts
if($_POST["formaction"]=="contacts" or $_POST["formaction"]=="contacts_publication")
{
	if($_POST["formaction"]=="contacts")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="contacts")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		
		echo("<p>Do you want to change the files to 'Contacts us to get the price'/Common?</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		
		echo("<p><input type='radio' name='contacts' value='1' checked> ".word_lang("Contacts us to get the price")."</p>");
		
		echo("<p><input type='radio' name='contacts' value='0'> ".word_lang("common")."</p>");


		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");


		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==30)
			{
				$sql="update photos set contacts=".(int)$_POST["contacts"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==31)
			{
				$sql="update videos set contacts=".(int)$_POST["contacts"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==52)
			{
				$sql="update audio set contacts=".(int)$_POST["contacts"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==53)
			{
				$sql="update vector set contacts=".(int)$_POST["contacts"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
		}
		header("location:".$return_url);
	}
}
//End. Change files to contacts










//Bulk change titles, keywords, description
if($_POST["formaction"]=="bulk_change" or $_POST["formaction"]=="bulk_change_publication")
{
	if($_POST["formaction"]=="bulk_change")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		echo("<h1>".word_lang("Bulk change titles, keywords, description")."</h1>");
		
		echo("<p>You should select a field which you want to change and write a new meaning. Attention! The old value will be replaced with the new one for all selected publications.</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		



		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");
		
		echo("<div class='admin_field'><span>".word_lang("property").":</span><select name='field_type' style='width:300px'><option value='title'>".word_lang("title")."</option><option value='keywords'>".word_lang("keywords")."</option><option value='description'>".word_lang("description")."</option></select></div>");
		
		echo("<div class='admin_field'><span>".word_lang("value").":</span><textarea name='field_value' style='width:500px;height:150px'></textarea></div>");



		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			$field_name="title";
			if($_POST["field_type"]=="title"){$field_name="title";}
			if($_POST["field_type"]=="keywords"){$field_name="keywords";}
			if($_POST["field_type"]=="description"){$field_name="description";}
			
			if(result($_POST["field_value"])!="")
			{
				if($res_module[$i]==30)
				{
					$sql="update photos set ".$field_name."='".result($_POST["field_value"])."' where id_parent=".$res_id[$i];
					$db->execute($sql);
				}
				if($res_module[$i]==31)
				{
					$sql="update videos set ".$field_name."='".result($_POST["field_value"])."' where id_parent=".$res_id[$i];
					$db->execute($sql);
				}
				if($res_module[$i]==52)
				{
					$sql="update audio set ".$field_name."='".result($_POST["field_value"])."' where id_parent=".$res_id[$i];
					$db->execute($sql);
				}
				if($res_module[$i]==53)
				{
					$sql="update vector set ".$field_name."='".result($_POST["field_value"])."' where id_parent=".$res_id[$i];
					$db->execute($sql);
				}
				
				if($field_name=="title")
				{
					$sql="update structure set name='".result($_POST["field_value"])."' where id=".$res_id[$i]." and module_table<>34";
					$db->execute($sql);
				}
			}
		}
		header("location:".$return_url);
	}
}
//End. Bulk change titles, keywords, description












//Bulk add/remove keywords
if($_POST["formaction"]=="bulk_keywords" or $_POST["formaction"]=="bulk_keywords_publication")
{
	if($_POST["formaction"]=="bulk_keywords")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		echo("<h1>".word_lang("Bulk add/remove keywords")."</h1>");
		
		echo("<p>You should write keywords (, - separator) and select an appropriate action</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		



		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");
		
		echo("<div class='admin_field'><span>".word_lang("select action").":</span><select name='keywords_action' style='width:300px'><option value='add'>".word_lang("add new keywords")."</option><option value='remove'>".word_lang("remove keywords")."</option></select></div>");
		
		echo("<div class='admin_field'><span>".word_lang("keywords").":</span><textarea name='keywords' style='width:500px;height:150px'></textarea></div>");



		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		
		if(result($_POST["keywords"])!="")
		{	
			$keywords_new=explode(",",str_replace(";",",",result($_POST["keywords"])));
			foreach ($keywords_new as $key => $value) 
			{
				$keywords_new[$key]=trim($value);
				if($keywords_new[$key]=="")
				{
					unset($keywords_new[$key]);
				}
			}
		
			for($i=0;$i<count($res_id);$i++)
			{		
				$keywords_table="photos";
				
				if($res_module[$i]==30)
				{
					$keywords_table="photos";
				}
				if($res_module[$i]==31)
				{
					$keywords_table="videos";
				}
				if($res_module[$i]==52)
				{
					$keywords_table="audio";
				}
				if($res_module[$i]==53)
				{
					$keywords_table="vector";
				}
				
				$sql="select keywords from ".$keywords_table." where id_parent=".$res_id[$i];
				$dr->open($sql);
				if(!$dr->eof)
				{				
					$keywords_old=explode(",",str_replace(";",",",$dr->row["keywords"]));
					foreach ($keywords_old as $key => $value) 
					{
						$keywords_old[$key]=trim($value);
						if($keywords_old[$key]=="")
						{
							unset($keywords_old[$key]);
						}
					}	
					
					if($_POST["keywords_action"]=="add")
					{
						foreach ($keywords_new as $key => $value) 
						{
							if(!in_array($value,$keywords_old))
							{
								$keywords_old[]=$value;
							}
						}
					}
					else
					{
						foreach ($keywords_old as $key => $value) 
						{
							if(in_array($value,$keywords_new))
							{
								unset($keywords_old[$key]);
							}
						}
					}
					
					$keywords_list="";
					foreach ($keywords_old as $key => $value) 
					{
						if($keywords_list!="")
						{
							$keywords_list.=",";
						}
						$keywords_list.=$value;
					}
					
					$sql="update ".$keywords_table." set keywords='".$keywords_list."' where id_parent=".$res_id[$i];
					$db->execute($sql);
				}
			}
		}
		header("location:".$return_url);
	}
}
//End. Bulk add/remove keywords












//Change files to editorial
if($_POST["formaction"]=="editorial" or $_POST["formaction"]=="editorial_publication")
{
	if($_POST["formaction"]=="editorial")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="editorial")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		
		echo("<p>Do you want to change the photos <b>(".$res_photo.")</b> to Editorial/Creative?</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		
		echo("<p><input type='radio' name='editorial' value='1' checked> ".word_lang("editorial")."</p>");
		
		echo("<p><input type='radio' name='editorial' value='0'> ".word_lang("creative")."</p>");




		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary'  value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==30)
			{
				$sql="update photos set editorial=".(int)$_POST["editorial"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
		}
		header("location:".$return_url);
	}
}
//End. Change files to editorial












//Change files to approve
if($_POST["formaction"]=="approve" or $_POST["formaction"]=="approve_publication")
{
	if($_POST["formaction"]=="approve")
	{
		$return_url="../categories/index.php";
	}
	else
	{
		$return_url="../catalog/index.php";
	}
	if(isset($_POST["return_url"]))
	{
		$return_url=$_POST["return_url"];
	}

	if(!isset($_POST["step"]))
	{
		include("../inc/begin.php");
		
		if($_POST["formaction"]=="approve")
		{
			echo("<h1>".word_lang("categories")."</h1>");
		}
		else
		{
			echo("<h1>".word_lang("catalog")."</h1>");
		}
		
		echo("<p>Do you want to approve/decline the files?</p>");
		
				
		echo("<form method='post' action='edit.php'>");
		

		
		echo("<p><input type='radio' name='approve' value='1' checked> ".word_lang("approve")."</p>");
		
		echo("<p><input type='radio' name='approve' value='0'> ".word_lang("pending")."</p>");
		
		echo("<p><input type='radio' name='approve' value='-1'> ".word_lang("decline")."</p>");


		echo("<ul>");
		if($global_settings["allow_photo"]){echo("<li>".word_lang("photo").": <b>".$res_photo."</b></li>");}
		if($global_settings["allow_video"]){echo("<li>".word_lang("video").": <b>".$res_video."</b></li>");}
		if($global_settings["allow_audio"]){echo("<li>".word_lang("audio").": <b>".$res_audio."</b></li>");}
		if($global_settings["allow_vector"]){echo("<li>".word_lang("vector").": <b>".$res_vector."</b></li>");}
		echo("</ul>");


		
		foreach ($_POST as $key => $value) 
		{
			$res_temp=explode("sel",$key);
			
			if(count($res_temp)==2 and (int)$res_temp[1]>0)
			{
				echo("<input type='hidden' name='".$key."' value='1'>");
			}
		}
		
		
		echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
		<input type='hidden'  name='step' value='2'>
		<input type='submit'  class='btn btn-primary' value='".word_lang("Ok")."'>");
		
		
		echo("</form>");
		
		include("../inc/end.php");
	}
	else
	{
		for($i=0;$i<count($res_id);$i++)
		{
			if($res_module[$i]==30)
			{
				$sql="update photos set published=".(int)$_POST["approve"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==31)
			{
				$sql="update videos set published=".(int)$_POST["approve"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==52)
			{
				$sql="update audio set published=".(int)$_POST["approve"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
			if($res_module[$i]==53)
			{
				$sql="update vector set published=".(int)$_POST["approve"]." where id_parent=".$res_id[$i];
				$db->execute($sql);
			}
		}
		header("location:".$return_url);
	}
}
//End. Change files to approve





//$db->close();
?>