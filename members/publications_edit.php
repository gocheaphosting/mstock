<?$site="publications";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");exit();}?>
<?
if($global_settings["userupload"]==0){header("location:profile.php");exit();}


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

				if($_POST["formaction"]=="edit" or $_POST["formaction"]=="delete")
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
			}
		}
//End. Get list of IDs





//Change publications properties
if($_POST["formaction"]=="edit")
{
	$return_url=$_POST["return_url"];

	if(!isset($_POST["step"]))
	{	
		include("../inc/header.php");
		include("profile_top.php");
		
		echo("<h1>".word_lang("my publications")." &mdash; ".word_lang("edit")."</h1>");	

	
		if(count($res_id)>0)
		{
			echo("<form method='post' action='publications_edit.php'>");
		
			echo("<table border='0' cellpadding='0' cellspacing='1' class='profile_table' width='100%'>");


		
			for($i=0;$i<count($res_id);$i++)
			{
				if($res_module[$i]==30){$type="photo";$table="photos";}
				if($res_module[$i]==31){$type="video";$table="videos";}
				if($res_module[$i]==52){$type="audio";$table="audio";}
				if($res_module[$i]==53){$type="vector";$table="vector";}
				$sql="select id_parent,title,description,keywords,free,featured,adult,category2,category3 from ".$table." where id_parent=".(int)$res_id[$i];
				$rs->open($sql);
				if(!$rs->eof)
				{
					$free="";
					if($rs->row["free"]==1){$free="checked";}
					
					$featured="";
					if($rs->row["featured"]==1){$featured="checked";}
					
					$adult="";
					if($rs->row["adult"]==1){$adult="checked";}
					
					$category="";
					$sql="select id_parent from structure where id=".(int)$res_id[$i];
					$dr->open($sql);
					if(!$dr->eof)
					{
						$itg="";
						$smarty_buildmenu5_id="buildmenu|5|".$dr->row["id_parent"]."|".$lng;
						if (!$smarty->isCached('buildmenu5.tpl',$smarty_buildmenu5_id))
						{
							$nlimit=0;
							buildmenu5(5,$dr->row["id_parent"],2,0);
						}
						$smarty->cache_lifetime = -1;
						$smarty->assign('buildmenu5', $itg);
						$itg=$smarty->fetch('buildmenu5.tpl',$smarty_buildmenu5_id);
						$category=$itg;
					}

										
					$itg="";
					$smarty_buildmenu5_id="buildmenu|5|".(int)$rs->row["category2"]."|".$lng;
					if (!$smarty->isCached('buildmenu5.tpl',$smarty_buildmenu5_id))
					{
						$nlimit=0;
						buildmenu5(5,(int)$rs->row["category2"],2,0);
					}
					$smarty->cache_lifetime = -1;
					$smarty->assign('buildmenu5', $itg);
					$itg=$smarty->fetch('buildmenu5.tpl',$smarty_buildmenu5_id);
					$category2=$itg;
					
					
					$itg="";
					$smarty_buildmenu5_id="buildmenu|5|".(int)$rs->row["category3"]."|".$lng;
					if (!$smarty->isCached('buildmenu5.tpl',$smarty_buildmenu5_id))
					{
						$nlimit=0;
						buildmenu5(5,(int)$rs->row["category3"],2,0);
					}
					$smarty->cache_lifetime = -1;
					$smarty->assign('buildmenu5', $itg);
					$itg=$smarty->fetch('buildmenu5.tpl',$smarty_buildmenu5_id);
					$category3=$itg;
				
				
					echo("<tr><th colspan='2'>".word_lang($type)." ID=".$res_id[$i]."</th></tr><tr valign='top'>");
					echo("<td>");
						echo("<div style='margin-bottom:3px'>".show_preview($res_id[$i],$type,1,0)."</div>");
					echo("</td>");
					echo("<td>");
					
					echo("<div class='form_field'><span><b>".word_lang("category")." 1:</b></span><select name='category".$res_id[$i]."' style='width:400px' class='ibox form-control'><option value='5'></option>".$category."</select></div>");
					echo("<div class='form_field'><span><b>".word_lang("category")." 2:</b></span><select name='category2_".$res_id[$i]."' style='width:400px' class='ibox form-control'><option value='5'></option>".$category2."</select></div>");
					echo("<div class='form_field'><span><b>".word_lang("category")." 3:</b></span><select name='category3_".$res_id[$i]."' style='width:400px' class='ibox form-control'><option value='5'></option>".$category3."</select></div>");
					

						echo("<div class='form_field'><span><b>".word_lang("title").":</b></span><input class='ibox form-control' type='text' name='title".$res_id[$i]."' value='".$rs->row["title"]."' style='width:400px'></div>");
					
						echo("<div class='form_field'><span><b>".word_lang("description").":</b></span><textarea class='ibox form-control' name='description".$res_id[$i]."' style='width:400px;height:50px'>".$rs->row["description"]."</textarea></div>");
					
						echo("<div class='form_field'><span><b>".word_lang("keywords").":</b></span><textarea class='ibox form-control' name='keywords".$res_id[$i]."' style='width:400px;height:50px'>".$rs->row["keywords"]."</textarea></div>");
						echo("<input type='hidden' name='sel".$res_id[$i]."' value='1'>");



						echo("<div class='form_field'><span><b>".word_lang("free").":</b></span><input type='checkbox' name='free".$res_id[$i]."' ".$free."></div>");
						
						if($global_settings["adult_content"])
						{
							echo("<div class='form_field'><span><b>".word_lang("adult content").":</b></span><input type='checkbox' name='adult".$res_id[$i]."' ".$adult."></div>");
						}
					echo("</td>");
					echo("</tr>");
				}
			}
		
			echo("</table>");
		
			echo("<input type='hidden'  name='formaction' value='".result($_POST["formaction"])."'><input type='hidden'  name='return_url' value='".$_SERVER["HTTP_REFERER"]."'>
			<input type='hidden'  name='step' value='2'>
			<input type='submit' class='isubmit' value='".word_lang("Change")."' style='margin-top:15px'>");
		
		
			echo("</form>");
			
		}
		else
		{
			echo("<p>".word_lang("not found")."</p>");
			echo("<input type='button' class='isubmit' onClick=\"location.href='".$return_url."'\"  value='".word_lang("Back")."'>");
		}
		
		


		include("profile_bottom.php");
		include("../inc/footer.php");

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
			
			$adult=0;
			if(isset($_POST["adult".$res_id[$i]])){$adult=1;}

			$sql="select id_parent from ".$table." where (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."') and id_parent=".$res_id[$i];
			$rs->open($sql);
			if(!$rs->eof)
			{
				$sql="update structure set id_parent=".(int)$_POST["category".$res_id[$i]].",name='".result($_POST["title".$res_id[$i]])."' where id=".$res_id[$i];
				$db->execute($sql);echo($sql);

				$sql="update ".$table." set title='".result($_POST["title".$res_id[$i]])."',description='".result($_POST["description".$res_id[$i]])."',keywords='".result($_POST["keywords".$res_id[$i]])."',category2=".(int)$_POST["category2_".$res_id[$i]].",category3=".(int)$_POST["category3_".$res_id[$i]].",free=".$free.",adult=".$adult." where id_parent=".$res_id[$i];
				$db->execute($sql);
			
				$smarty->clearCache(null,"item|".$res_id[$i]);
				$smarty->clearCache(null,"share|".$res_id[$i]);
			
				item_url($res_id[$i]);
			}

		}
		header("location:".$return_url);
	}
}
//End. Change publications properties









//Delete publications
if($_POST["formaction"]=="delete" and !$demo_mode)
{
		for($i=0;$i<count($res_id);$i++)
		{
				if($res_module[$i]==30){$table="photos";}
				if($res_module[$i]==31){$table="videos";}
				if($res_module[$i]==52){$table="audio";}
				if($res_module[$i]==53){$table="vector";}
				
				$sql="select id_parent from ".$table." where (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."') and id_parent=".$res_id[$i]." and published=0";
				$rs->open($sql);
				if(!$rs->eof)
				{
					publication_delete($res_id[$i]);
				}
		}

	header("location:".$_POST["return_url"]);
}
//End. Delete publications

//$db->close();
?>




