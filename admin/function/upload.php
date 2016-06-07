<?
if(!defined("site_root")){exit();}



//Build categorie table - /admin/categories/
function buildmenu3($t_id,$otstup)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	global $itg;
	global $nlimit;
	global $_COOKIE;

	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.priority from structure a,category b where a.id=b.id_parent and a.id_parent=".$t_id."  order by b.priority,b.title";
	$dp->open($sql);
	while(!$dp->eof)
	{
	if($nlimit<1000)
	{
		//padding-left
		$otp="";
		for($i=0;$i<$otstup;$i++)
		{
			$otp.="&nbsp;&nbsp;";
		}

		//If included subcategories exist
		$zp=false;
		$sql="select a.id,a.id_parent,b.id_parent from structure a,category b where a.id=b.id_parent and a.id_parent=".$dp->row["id"];
		$dt->open($sql);
		if(!$dt->eof)
		{
			$zp=true;
		}

		//if parent closed
		$vis="";
		$sql="select id_parent from structure where id=".$dp->row["id"];
		$dt->open($sql);
		if(!$dt->eof)
		{
			if(isset($_COOKIE["sub_".$dt->row["id_parent"]]) and (int)$_COOKIE["sub_".$dt->row["id_parent"]]==0)
			{
				$vis="style='display:none'";
			}
		}

		//Plus-minus icon
		$img_marker="minus";

		if(isset($_COOKIE["sub_".$dp->row["id"]]) and (int)$_COOKIE["sub_".$dp->row["id"]]==0)
		{
			$img_marker="plus";
		}

		if($zp)
		{
			$img="<a href='javascript:subopen(".$dp->row["id"].");'><img id='plus".$dp->row["id"]."' src='../images/design/".$img_marker.".gif' width='13' height='13' border='0'></a>&nbsp;";
		}
		else
		{
			$img="<img src='../images/design/e.gif' width='13' height='13' border='0'>&nbsp;";
		}


		$itg.="<tr id='row".$dp->row["id"]."' ".$vis.">
		<td><input type='checkbox' id='sel".$dp->row["id"]."' name='sel".$dp->row["id"]."'></td>
		<td><input type='text' name='priority".$dp->row["id"]."' value='".$dp->row["priority"]."' class='ibox form-control' style='width:40px'></td>
		<td nowrap width='80%'>".$otp.$img."<a href='content.php?id=".$dp->row["id"]."'>".$dp->row["title"]."</a></td>
		<td><div class='link_edit'><a href='content.php?id=".$dp->row["id"]."'>".word_lang("edit")."</a></div></td>
		<td><div class='link_delete'><a href='delete.php?id=".$dp->row["id"]."' onClick=\"return confirm('".word_lang("delete")."?');\">".word_lang("delete")."</a></div></td>
		</tr>";


		buildmenu3($dp->row["id"],$otstup+2);
	}
	$nlimit++;
	$dp->movenext();
	}
}




//Select fields for date
function admin_date($data,$field)
{
	$res="";
	global $m_month;

	$res.="<table border='0' cellpadding='0' cellspacing='0'>
	<tr valign='top'>
	<td><select name='".$field."_day' style='width:70px;margin-right:5px' class='ibox form-control'>";

	for($j=1;$j<32;$j++)
	{
		if($j<10){$ji="0".$j;}
		else{$ji=$j;}

		$sel="";
		if(date("d",$data)==$j){$sel="selected";}

		$res.="<option value='".$j."' ".$sel.">".$ji."</option>";
	}

	$res.="</select>&nbsp;</td>
	<td><select name='".$field."_month' style='width:120px;margin-right:5px' class='ibox form-control'>";

	for($j=0;$j<12;$j++)
	{
		$sel="";
		if(date("m",$data)==$j+1){$sel="selected";}

		$res.="<option value='".($j+1)."' ".$sel.">".word_lang(strtolower($m_month[$j]))."</option>";
	}

	$res.="</select>&nbsp;</td>
	<td><select name='".$field."_year' style='width:90px;margin-right:15px' class='ibox form-control'>";

	for($j=date("Y")-5;$j<date("Y")+15;$j++)
	{
		$sel="";
		if(date("Y",$data)==$j){$sel="selected";}

	$res.="<option value='".$j."' ".$sel.">".$j."</option>";
	}

	$res.="</select>&nbsp;&nbsp;</td>
	<td><select name='".$field."_hour' style='width:70px;margin-right:5px' class='ibox form-control'>";

	for($j=0;$j<24;$j++)
	{
		if($j<10){$ji="0".$j;}
		else{$ji=$j;}

		$sel="";
		if(date("G",$data)==$j){$sel="selected";}

		$res.="<option value='".$j."' ".$sel.">".$ji."</option>";
	}

	$res.="</select></td>
	<td><select name='".$field."_minute' style='width:70px;margin-right:5px' class='ibox form-control'>";

	for($j=0;$j<60;$j++)
	{
		if($j<10){$ji="0".$j;}
		else{$ji=$j;}

		$sel="";
		if(date("i",$data)==$j){$sel="selected";}

		$res.="<option value='".$j."' ".$sel.">".$ji."</option>";
	}


	$res.="</select></td>
	<td><select name='".$field."_second' style='width:70px' class='ibox form-control'>";

	for($j=0;$j<60;$j++)
	{
		if($j<10){$ji="0".$j;}
		else{$ji=$j;}
		
		$sel="";
		if(date("s",$data)==$j){$sel="selected";}

		$res.="<option value='".$j."' ".$sel.">".$ji."</option>";
	}

	$res.="</select></td>
	</tr>
	</table>";

	return $res;
}


//The function builds a duration form
function duration_form($data,$field)
{
	$res="";
	
	$res.="<table border='0' cellpadding='0' cellspacing='0'><tr>";
	$res.="<td nowrap><select name='".$field."_hour' style='width:70px;margin-right:5px' class='ibox form-control'>";
	
	$form_hours=floor($data/3600);
	$form_minutes=floor(($data-$form_hours*3600)/60);
	$form_seconds=$data-$form_hours*3600-$form_minutes*60;
	
	for($j=0;$j<100;$j++)
	{
		if($j<10){$ji="0".$j;}
		else{$ji=$j;}
		$sel="";
		if($form_hours==$j){$sel="selected";}
		$res.="<option value='".$j."' ".$sel.">".$ji."</option>";
	}

	$res.="</select></td><td nowrap><select name='".$field."_minute' style='width:70px;margin-right:5px' class='ibox form-control'>";

	for($j=0;$j<60;$j++)
	{
		if($j<10){$ji="0".$j;}
		else{$ji=$j;}
		$sel="";
		if($form_minutes==$j){$sel="selected";}
		$res.="<option value='".$j."' ".$sel.">".$ji."</option>";
	}

	$res.="</select></td><td><select name='".$field."_second' style='width:70px' class='ibox form-control'>";

	for($j=0;$j<60;$j++)
	{
		if($j<10){$ji="0".$j;}
		else{$ji=$j;}
		$sel="";
		if($form_seconds==$j){$sel="selected";}
		$res.="<option value='".$j."' ".$sel.">".$ji."</option>";
	}

	$res.="</select></td></tr></table>";
	return $res;
}
//End. The function builds a duration form




//Build rights-managed upload form
function rights_managed_upload_form($type,$rights_managed,$id,$admin_session)
{
	global $ds;
	global $rs;
	global $dn;
	global $dd;
	global $file_form;
	global $global_settings;
	global $_SESSION;
	global $site_servers;
	global $site_server_activ;
	global $lphoto;
	global $lvideo;
	global $lpreviewvideo;
	global $laudio;
	global $lpreviewaudio;
	global $lvector;
	global $flag_jquery;
	$res="";
	
	if(($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader")or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
	{
		create_temp_folder();
	}
	
	$sql="select * from rights_managed where ".$type."=1";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$res.="<table border='0' cellpadding='5' cellspacing='1' class='profile_table table table-striped table-hover'>";
		if($file_form)
		{
			$res.="<tr>
			<th colspan=2><b>".word_lang("file").":</b></th>
			</tr>";
			
			$res.="<tr class='snd'>
			<td colspan='2'>";
			
			if(($type=="photo" and $global_settings["photo_uploader"]=="usual uploader") or ($type=="video" and $global_settings["video_uploader"]=="usual uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="usual uploader")or ($type=="vector" and $global_settings["vector_uploader"]=="usual uploader"))
			{
				$res.="<input name='video_rights' type='file' style='width:400px' class='ibox form-control'>";
			}
			
			if(($type=="photo" and $global_settings["photo_uploader"]=="jquery uploader") or ($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader")or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
			{	
				$res.="<span class=\"btn btn-success fileinput-button\">
        					<i class=\"icon-plus icon-white\" style=\"float:left\"></i> ".word_lang("select files")."...
        					<!-- The file input field used as target for the file upload widget -->
        					<input type=\"file\" name=\"files[]\" onClick='change_bar(100)'>
        					<input type=\"hidden\" id='file_sale100' name='file_sale100' value=''>
    					</span>
    					<div id=\"progress\" class=\"progress progress-striped active\" style=\"margin-top:15px\">
        					<div id=\"bar100\" class=\"bar\" style=\"width:0%\"></div>
    					</div>
    					<div id=\"files100\" class=\"files\"></div>
    					";
				
			}	
						
			$res.="</td>
			</tr>";
			
			if($type=="photo")
			{
				$res.="<tr>
				<td colspan='2'>".word_lang("use iptc info").": <input name='".$type."_iptc_rights' type='checkbox' checked></td>
				</tr>";
			}
		}
		
			$res.="<tr>
			<th style='width:70%'><b>".word_lang("price").":</b></th>
			<th><b>".word_lang("type").":</b></th>
			</tr>";
			
			if($rights_managed==0)
			{
				$sel="checked";
			}
			else
			{
				$sel="";
			}
			
			$filetypes2="jpe?g|mp4|wmv|mov|flv|zip|swf|mp3";
			$sql="select * from rights_managed where ".$type."=1";
			$rs->open($sql);
			while(!$rs->eof)
			{
				if($rights_managed!=0 and $rights_managed==$rs->row["id"])
				{
					$sel="checked";
				}
				
				$res.="<tr><td><input type='radio' name='rights_id' value='".$rs->row["id"]."' ".$sel.">&nbsp;".$rs->row["title"]."</td><td>".$rs->row["formats"]."</td></tr>";
				
				if($sel=="checked")
				{
					$sel="";
				}
				
				$filetypes2.="|".str_replace(",","|",str_replace(" ","",strtolower($rs->row["formats"])));
				
				$rs->movenext();
			}
		
			if(($type=="photo" and $global_settings["photo_uploader"]=="jquery uploader")  or ($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader")or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
			{
				if(!$flag_jquery)
				{
					if($admin_session)
					{
						$filelimit=1000000;
					}
					else
					{
						if($type=="photo")
						{
							$filelimit=$lphoto;
						}
						if($type=="video")
						{
							$filelimit=$lvideo;
						}
						if($type=="audio")
						{
							$filelimit=$laudio;
						}
						if($type=="vector")
						{
							$filelimit=$lvector;
						}
					}
			
					if(!isset($filetypes) or $filetypes=="")
					{
						$filetypes=$filetypes2;
					}
					if(!$flag_jquery)
					{
						$res.=get_jquery_uploader_code($filelimit,$filetypes);
						$flag_jquery=true;
					}					
				}
			}
		
		
		
		if($id!=0)
		{
			//Define server
			$server1=$site_server_activ;
			if($type=="photo")
			{
				$sql="select server1 from photos where id_parent=".(int)$id;
				$dd->open($sql);
				if(!$dd->eof)
				{
					$server1=$dd->row["server1"];
				}
			}
			if($type=="video")
			{
				$sql="select server1 from videos where id_parent=".(int)$id;
				$dd->open($sql);
				if(!$dd->eof)
				{
					$server1=$dd->row["server1"];
				}
			}
			if($type=="audio")
			{
				$sql="select server1 from audio where id_parent=".(int)$id;
				$dd->open($sql);
				if(!$dd->eof)
				{
					$server1=$dd->row["server1"];
				}
			}
			if($type=="vector")
			{
				$sql="select server1 from vector where id_parent=".(int)$id;
				$dd->open($sql);
				if(!$dd->eof)
				{
					$server1=$dd->row["server1"];
				}
			}
			
			
			$sql="select url,shipped from items where id_parent=".(int)$id." and price_id=".$rights_managed;
			$dd->open($sql);
			if(!$dd->eof)
			{
				if($dd->row["shipped"]!=1)
				{
					$remote_filesize=0;
					$flag_storage=false;
					$remote_file="";
					
					$sql="select url,filename1,filename2,width,height,item_id,filesize from filestorage_files where id_parent=".(int)$id." and filename1='".$dd->row["url"]."'";
					$dn->open($sql);
					if(!$dn->eof)
					{
						$flag_storage=true;
						$remote_filesize=$dn->row["filesize"];
						$remote_file=$dn->row["url"]."/".$dn->row["filename2"];
					}
					
					if(!$flag_storage)
					{
						
						$url=site_root.$site_servers[$server1]."/".(int)$id."/".$dd->row["url"];
						if(file_exists($_SERVER["DOCUMENT_ROOT"].$url))
						{
							if($type!="photo")
							{
								$res.="<tr><td colspan='2'><a href='".$url."' class='btn btn-default'><i class='icon-download fa fa-download'></i> ".word_lang("download")." " .$dd->row["url"]."</a><div style='margin:7px 0px 0px 12px'>".float_opt((filesize($_SERVER["DOCUMENT_ROOT"].$url)/1024),2)."Kb.</div></td></tr>";
							}
							else
							{
								$res.="<tr><td colspan='2'><div><a href='".$url."' class='btn btn-default'><i class='icon-download fa fa-download'></i> ".word_lang("download")." ".$dd->row["url"]."</a><div style='margin:7px 0px 0px 12px'>".get_exif($_SERVER["DOCUMENT_ROOT"].$url,false,(int)$id)."</div></div></td></tr>";
							}
						}
					}
					else
					{
						if($type!="photo")
						{
							$res.="<tr><td colspan='2'><a href='".$remote_file."' class='btn'><i class='icon-download'></i> ".word_lang("download")." ".$dd->row["url"]."</a><div style='margin:7px 0px 0px 12px'>".float_opt(($remote_filesize/1024),2)."Kb.</div>";
						}
						else
						{
							$res.="<tr><td colspan='2'><div style='margin-top:2px'><a href='".$remote_file."' class='btn'><i class='icon-download'></i> ".word_lang("download")." ".$dd->row["url"]."</a><div style='margin:7px 0px 0px 12px'>[".float_opt(($remote_filesize/1024),2)."Kb.]</div></div></td></tr>";
						}
					}
				}
			}
		}
		
		$res.=get_preview_form($type,true);

		$res.="</table>";
	}
	else
	{
		$res.="<div style='padding:20px;width:630px'>There are no available rights-managed prices for <b>".$type."</b>.</div>";
	}
return $res;
}
//End rights-managed upload form





//The function creates temp folder for jquery uploader
function create_temp_folder()
{
	global $_SESSION;
	global $DOCUMENT_ROOT;
	
	if(isset($_SESSION["user_id"]))
	{
   		$tmp_folder="admin_".(int)$_SESSION["user_id"];
	}
	
	if(isset($_SESSION["people_id"]))
	{
   		$tmp_folder="user_".(int)$_SESSION["people_id"];
	}

	if(file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder))
	{
		remove_files_from_folder($tmp_folder);
	}
	else
	{
		mkdir($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder);
	}
}
//End. The function creates temp folder for jquery uploader



//Build photo upload form
function photo_upload_form($id,$admin_session)
{
	global $ds;
	global $rs;
	global $dr;
	global $dd;
	global $dn;
	global $file_form;
	global $global_settings;
	global $_SESSION;
	global $lphoto;
	global $site_server_activ;
	global $site_servers;
	global $flag_jquery;
	
	$res="";
	$filetypes="jpe?g|png|gif|raw|tif?f|eps";
	
	$photo_formats=array();
	$sql="select id,photo_type from photos_formats where enabled=1 order by id";
	$dr->open($sql);
	while(!$dr->eof)
	{
		$photo_formats[$dr->row["id"]]=$dr->row["photo_type"];
		$dr->movenext();
	}
	
	if($global_settings["rights_managed"]==1)
	{
		$sql="select formats from rights_managed";
		$dr->open($sql);
		while(!$dr->eof)
		{
			$filetypes.="|".str_replace(",","|",str_replace(" ","",strtolower($dr->row["formats"])));
			$dr->movenext();
		}
	}

	$server1=$site_server_activ;
	$rurl="";
	if($id!=0 and $admin_session)
	{
		$sql="select server1,url_jpg,url_png,url_gif,url_raw,url_tiff,url_eps from photos where id_parent=".(int)$id;
		$dd->open($sql);
		if(!$dd->eof)
		{
			$server1=$dd->row["server1"];
			
			foreach ($photo_formats as $key => $value) 
			{
				if($dd->row["url_".$value]!="")
				{
					$remote_filesize=0;
					$flag_storage=false;
					$remote_file="";
					
					$sql="select url,filename1,filename2,width,height,item_id,filesize from filestorage_files where id_parent=".(int)$id." and filename1='".$dd->row["url_".$value]."'";
					$dn->open($sql);
					if(!$dn->eof)
					{
						$flag_storage=true;
						$remote_filesize=$dn->row["filesize"];
						$remote_file=$dn->row["url"]."/".$dn->row["filename2"];
					}
			
					if(!$flag_storage)
					{
						$url=site_root.$site_servers[$server1]."/".(int)$id."/".$dd->row["url_".$value];
						
						$size = @getimagesize($_SERVER["DOCUMENT_ROOT"].$url);
						$file_details="";
						
						if($size[0]!="" and $size[1]!="")
						{
							$file_details=$size[0]."x".$size[1]."px&nbsp;@&nbsp;";
						}
						$file_details.=float_opt(filesize($_SERVER["DOCUMENT_ROOT"].$url)/(1024*1024),2)."Mb.";
						
						$rurl.="<div style='margin-bottom:20px'><a href='".$url."' class='btn btn-default'><i class='icon-download fa  fa-download'></i> ".word_lang("download")." <b>".$dd->row["url_".$value]."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>[".$file_details."]</small></a> ";
						
						if(isset($_SESSION["entry_admin"]))
						{
							$rurl.="<a href='photo_delete.php?id=".(int)$id."&file=".$value."' class='btn btn-danger'><i class='icon-remove icon-white'></i> ".word_lang("delete")."</a>";
						}
						$rurl.="</div>";
					}
					else
					{
						$rurl.="<div style='margin-bottom:20px'><a href='".$remote_file."' class='btn btn-default'><i class='icon-download fa fa-download'></i> ".word_lang("download")." ".$dd->row["url_".$value]." <small>[".float_opt(($remote_filesize/1024),2)."Kb.]</small></a> ";
											
						if(isset($_SESSION["entry_admin"]))
						{
							$rurl.="<a href='photo_delete.php?id=".(int)$id."&file=".$value."' class='btn btn-danger'><i class='icon-remove icon-white'></i> ".word_lang("delete")."</a>";
						}
						$rurl.="</div>";
					}
				}
			}
		}
	}
	
		$res.="<table border='0' cellpadding='5' cellspacing='1' class='profile_table table table-striped table-hover'>";
		if($file_form)
		{
			$res.="<tr class='snd'>
			<td colspan='5'>";
			
			if($global_settings["photo_uploader"]=="usual uploader")
			{
				foreach ($photo_formats as $key => $value) 
				{
					$margin="20";
					if($value=="jpg")
					{
						$margin="0";
					}					
					
					$res.="<div style='margin-top:".$margin."px'><b>".strtoupper($value).":</b><br><input name='photo_".strtolower($value)."' type='file' style='width:400px' class='ibox form-control'></div>";
				}
			}
			
			if($global_settings["photo_uploader"]=="jquery uploader")
			{
				foreach ($photo_formats as $key => $value) 
				{
					$margin="20";
					if($value=="jpg")
					{
						$margin="0";
					}
					
					$res.="<div style='margin-top:".$margin."px'><b>".strtoupper($value).":</b><br><span class=\"btn btn-success fileinput-button\">
        					<i class=\"icon-plus icon-white\" style=\"float:left\"></i> ".word_lang("select files")."...
        					<!-- The file input field used as target for the file upload widget -->
        					<input type=\"file\" name=\"files[]\" onClick='change_bar(".$key.")'>
        					<input type=\"hidden\" id='file_sale".$key."' name='file_sale".$key."' value=''>
    					</span>
    					<div id=\"progress\" class=\"progress progress-striped active\" style=\"margin-top:15px\">
        					<div id=\"bar".$key."\" class=\"bar\" style=\"width:0%\"></div>
    					</div>
    					<div id=\"files".$key."\" class=\"files\"></div></div>
    					";
    				}
			}
			
			$res.="</td>
			</tr>";
			
			if($rurl!="")
			{
				$res.="<tr>
				<td colspan='5'>".$rurl."</td>
				</tr>";
			}
			
			$res.="<tr>
			<td colspan='5'>".word_lang("use iptc info").": <input name='photo_iptc' type='checkbox' checked></td>
			</tr>";
		}
		if(!$global_settings["printsonly"])
		{
			$res.="<tr>
			<th>".word_lang("enabled").":</th>
			<th><b>".word_lang("title").":</b></th>
			<th><b>".word_lang("file types").":</b></th>
			<th><b>Max ".word_lang("width")."/".word_lang("height").":</b></th>
			<th><b>".word_lang("price").":</b></th>
			</tr>";


			$sql="select id_parent,name from licenses order by priority";
			$ds->open($sql);
			while(!$ds->eof)
			{
				$res.="<tr class='snd'><td colspan='5'><b>".word_lang("license").": </b>".$ds->row["name"]."</td></tr>";

				$sql="select id_parent,size,title,price,jpg,png,gif,raw,tiff,eps from sizes where license=".$ds->row["id_parent"]." order by priority";
				$rs->open($sql);
				while(!$rs->eof)
				{
					$price=$rs->row["price"];
					$checked="";
					
					if($id!=0)
					{
						$sql="select price from items where id_parent=".(int)$id." and price_id=".$rs->row["id_parent"];
						$dd->open($sql);
						if(!$dd->eof)
						{
							$price=$dd->row["price"];
							$checked="checked";
						}
					}
					else
					{
						$checked="checked";
					}
					
					
					$res.="<tr>
					<td><input name='photo_chk".$rs->row["id_parent"]."' type='checkbox' ".$checked."></td>
					<td><b>".$rs->row["title"]."</b></td>
					<td>";
					
					$formats="";
					foreach ($photo_formats as $key => $value) 
					{					
						if($rs->row[$value]==1)
						{
							if($formats!=""){$formats.=", ";}
							$formats.=strtoupper($value);
						}
					}
					$res.=$formats;
					
					$res.="</td>
					<td>";

					if($rs->row["size"]!=0)
					{
						$res.=$rs->row["size"]."px";
					}
					else
					{
						$res.=word_lang("Original size");
					}

					$readonly="readonly";
					if($admin_session or $global_settings["seller_prices"])
					{
						$readonly="";
					}
		
					$res.="</td>
					<td><input name='photo_price".$rs->row["id_parent"]."' value='".float_opt($price,2)."' type='text' style='width:60px' ".$readonly." class='ibox form-control'></td>
					</tr>";

					$rs->movenext();
				}
				$ds->movenext();
			}
		}


		$res.="</table>";
		
	if($global_settings["photo_uploader"]=="jquery uploader")
	{
		$filelimit=$lphoto;
		if($admin_session)
		{
			$filelimit=1000;
		}
		
		if(!$flag_jquery)
		{
			$res.=get_jquery_uploader_code($filelimit,$filetypes);
			$flag_jquery=true;
		}
	}
	
	return $res;
}
//End photo upload form




//Build video,audio,vector upload form
function files_upload_form($id,$type,$admin_session)
{
	global $ds;
	global $dr;
	global $dd;
	global $dn;
	global $dq;
	global $_SESSION;
	global $global_settings;
	global $flag_jquery;
	global $site_servers;
	global $site_server_activ;
	global $lvideo;
	global $lpreviewvideo;
	global $laudio;
	global $lpreviewaudio;
	global $lvector;
	
	$badge_colors=array();
	
	$random_colors=array("#d83838","#5e9de4","#fd7405","#f7e40d","#30b716","#4dc717","#77480b","#b80cc7","#22aa9f","#f4a445","56b5d8");
	$random_colors_number=0;
	
	$res="";

	if(($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader")or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
	{
		create_temp_folder();
	}
	
	if($type=="video")
	{
		$filetypes="jpe?g|mp4|wmv|mov|flv";
	}
	
	if($type=="audio")
	{
		$filetypes="jpe?g|mp3";
	}
	
	if($type=="vector")
	{
		$filetypes="jpe?g|zip|swf";
	}
	
	if($global_settings["rights_managed"]==1)
	{
		$sql="select formats from rights_managed";
		$dr->open($sql);
		while(!$dr->eof)
		{
			$filetypes.="|".str_replace(",","|",str_replace(" ","",strtolower($dr->row["formats"])));
			$dr->movenext();
		}
	}

	$res.="<table border='0' cellpadding='5' cellspacing='1' class='profile_table table table-striped table-hover'>";

	$res.="
	<tr>
	<th></td>
	<th><b>".word_lang("title").":</b></th>
	<th><b>".word_lang("price").":</b></th>
	<th><b>".word_lang("file").":</b></th>
	</tr>";


	$sql="select id_parent,name from licenses order by priority";
	$dr->open($sql);
	while(!$dr->eof)
	{
		$res.="<tr class='snd'><td colspan='4'><b>".word_lang("license").": </b>".$dr->row["name"]."</td></tr>";

		if($type=="video")
		{
			$sql="select * from video_types where license=".$dr->row["id_parent"]." order by priority";
		}
		if($type=="audio")
		{
			$sql="select * from audio_types where license=".$dr->row["id_parent"]." order by priority";
		}
		if($type=="vector")
		{
			$sql="select * from vector_types where license=".$dr->row["id_parent"]." order by priority";
		}
		$ds->open($sql);
		while(!$ds->eof)
		{						
			$price=$ds->row["price"];
			$uploaded_file="";
			$checked="";
						
			if($id!=0)
			{
				$sql="select price,url,shipped from items where id_parent=".(int)$id." and price_id=".$ds->row["id_parent"];
				$dd->open($sql);
				if(!$dd->eof)
				{
					$price=$dd->row["price"];
					$checked="checked";
					
					if($dd->row["shipped"]!=1)
					{
						//Define server
						$server1=$site_server_activ;

						if($type=="video")
						{
							$sql="select server1 from videos where id_parent=".(int)$id;
							$dq->open($sql);
							if(!$dq->eof)
							{
								$server1=$dq->row["server1"];
							}
						}
						if($type=="audio")
						{
							$sql="select server1 from audio where id_parent=".(int)$id;
							$dq->open($sql);
							if(!$dq->eof)
							{
								$server1=$dq->row["server1"];
							}
						}
						if($type=="vector")
						{
							$sql="select server1 from vector where id_parent=".(int)$id;
							$dq->open($sql);
							if(!$dq->eof)
							{
								$server1=$dq->row["server1"];
							}
						}
					
						$remote_filesize=0;
						$flag_storage=false;
						$remote_file="";
					
						$sql="select url,filename1,filename2,width,height,item_id,filesize from filestorage_files where id_parent=".(int)$id." and filename1='".$dd->row["url"]."'";
						$dn->open($sql);
						if(!$dn->eof)
						{
							$flag_storage=true;
							$remote_filesize=$dn->row["filesize"];
							$remote_file=$dn->row["url"]."/".$dn->row["filename2"];
						}
					
						if(!$flag_storage)
						{
							$url=site_root.$site_servers[$server1]."/".(int)$id."/".$dd->row["url"];
							if(file_exists($_SERVER["DOCUMENT_ROOT"].$url))
							{
								$uploaded_file="<br><a href='".$url."' class='btn btn-default' style='margin:7px 0px 0px -12px'><i class='icon-download fa fa-download'></i> ".word_lang("download")." " .$dd->row["url"]." - ".float_opt((filesize($_SERVER["DOCUMENT_ROOT"].$url)/1024),2)."Kb.</a>";
							}
						}
						else
						{
							$uploaded_file="<br><a href='".$remote_file."' class='btn btn-default'><i class='icon-download fa fa-download'></i> ".word_lang("download")." ".$dd->row["url"]." - ".float_opt(($remote_filesize/1024),2)."Kb.</a>";
						}
					}
				}
			}
			else
			{
				$checked="";
			}
			
			$res.="<tr>
			<td><input name='".$type."_chk".$ds->row["id_parent"]."' type='checkbox' ".$checked."></td>
			<td nowrap>";
			
			$badge="";
			if($ds->row["thesame"]>0)
			{
				if(isset($badge_colors[$ds->row["thesame"]]))
				{
					$bcolor=$badge_colors[$ds->row["thesame"]];
				}
				else
				{
					if($random_colors_number>count($random_colors)-1)
					{
						$random_colors_number=0;
					}
					$bcolor=$random_colors[$random_colors_number];
					$random_colors_number++;
					$badge_colors[$ds->row["thesame"]]=$bcolor;
				}
				
				$badge="<span class='label' style='display:inline;background-color:".$bcolor.";'>&nbsp;&nbsp;</span> ";
			}
			
			$res.=$badge.$ds->row["title"];

			if($ds->row["shipped"]!=1)
			{
				$res.=" (";
				$uphoto=explode(",",str_replace(" ","",$ds->row["types"]));
				for($i=0;$i<count($uphoto);$i++)
				{
					if($i!=0){$res.=",";}
					$res.="*.".$uphoto[$i];
					$filetypes.="|".$uphoto[$i];
				}

				$res.=")";
			}
			
			$res.=$uploaded_file;

			$readonly="readonly";
			if($admin_session or $global_settings["seller_prices"])
			{
				$readonly="";
			}
			

			$res.="</td>
			<td><input class='ibox form-control' name='".$type."_price".$ds->row["id_parent"]."' value='".float_opt($price,2)."' type='text' ".$readonly." style='width:70px'></td>
			<td>";

			if($ds->row["shipped"]!=1)
			{
				if(($type=="video" and $global_settings["video_uploader"]=="usual uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="usual uploader")or ($type=="vector" and $global_settings["vector_uploader"]=="usual uploader"))
				{
					$res.="<input name='video_sale".$ds->row["id_parent"]."' type='file' style='width:200px' class='ibox form-control'>";
				}
			
				if(($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader")or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
				{	
					$res.="<span class=\"btn btn-success fileinput-button\">
        					<i class=\"icon-plus icon-white\" style=\"float:left\"></i> ".word_lang("select files")."...
        					<!-- The file input field used as target for the file upload widget -->
        					<input type=\"file\" name=\"files[]\" onClick='change_bar(".$ds->row["id_parent"].")'>
        					<input type=\"hidden\" id='file_sale".$ds->row["id_parent"]."' name='file_sale".$ds->row["id_parent"]."' value=''>
    					</span>
    					<div id=\"progress\" class=\"progress progress-striped active\" style=\"margin-top:15px\">
        					<div id=\"bar".$ds->row["id_parent"]."\" class=\"bar\" style=\"width:0%\"></div>
    					</div>
    					<div id=\"files".$ds->row["id_parent"]."\" class=\"files\"></div>
    					";
				
				}	
			}
			else
			{
				$res.=word_lang("shipped");
			}

			$res.="</td>
			</tr>";

			$ds->movenext();
		}
		$dr->movenext();
	}

	if(($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader")or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
	{
		if($type=="video")
		{
			$filelimit=$lvideo;
		}
		if($type=="audio")
		{
			$filelimit=$laudio;
		}
		if($type=="vector")
		{
			$filelimit=$lvector;
		}
		if($admin_session)
		{
			$filelimit=100000;
		}
		
		if(!$flag_jquery)
		{
			$res.=get_jquery_uploader_code($filelimit,$filetypes);
			$flag_jquery=true;
		}
	}


	$res.=get_preview_form($type,false);
	
	if(count($badge_colors)>0)
	{
		$res.="<tr class='snd'><td colspan='4'>";
		
		foreach ($badge_colors as $key => $value) 
		{
			$res.="<span class='label' style='display:inline;background-color:".$value.";'>&nbsp;&nbsp;</span> ";
		}
		
		$res.=" &mdash; ".word_lang("one color files are the same")."</td></tr>";
	}
	
	$res.="</table>";

	return $res;
}
//End video,audio,vector upload form







//The function update prices
function price_update($id,$type)
{
global $ds;
global $dr;
global $db;
global $dd;
global $site_servers;
global $site_server_activ;

if($type=="photo"){$table_name="sizes";}
if($type=="video"){$table_name="video_types";}
if($type=="audio"){$table_name="audio_types";}
if($type=="vector"){$table_name="vector_types";}

	$sql="select id_parent,name from licenses order by priority";
	$dr->open($sql);
	while(!$dr->eof)
	{
		$sql="select * from ".$table_name."  where license=".$dr->row["id_parent"]."  order by priority";
		$ds->open($sql);
		while(!$ds->eof)
		{
			$sql="select price,url,shipped from items where id_parent=".(int)$id." and price_id=".$ds->row["id_parent"];
			$dd->open($sql);
			if(!$dd->eof)
			{
				if(isset($_POST[$type."_chk".$ds->row["id_parent"]]))
				{
					$sql="update items set price=".(float)$_POST[$type."_price".$ds->row["id_parent"]]." where id_parent=".(int)$id." and price_id=".$ds->row["id_parent"];
					$db->execute($sql);
				}
				else
				{
					$sql="delete from items where id_parent=".(int)$id." and price_id=".$ds->row["id_parent"];
					$db->execute($sql);
				
					if($dd->row["shipped"]!=1)
					{
						if($type!="photo")
						{
							$url=site_root.$site_servers[$site_server_activ]."/".(int)$id."/".$dd->row["url"];
							if(file_exists($_SERVER["DOCUMENT_ROOT"].$url))
							{
								@unlink($_SERVER["DOCUMENT_ROOT"].$url);
							}
						}
					}
				}
			}
			else
			{	
					if($type=="photo")
					{
						$photo_file=get_photo_file($id);
						if($photo_file!="" and isset($_POST[$type."_chk".$ds->row["id_parent"]]))
						{
							$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$ds->row["title"]."','".$photo_file."',".(float)$_POST[$type."_price".$ds->row["id_parent"]].",".$ds->row["priority"].",0,".$ds->row["id_parent"].")";
							$db->execute($sql);
						}
					}
			}

		$ds->movenext();
		}
	$dr->movenext();
	}
}
//End. The function update prices


//The function gets file name of the photo publication
function get_photo_file($id)
{
global $db;
$dp = new TMySQLQuery;
$dp->connection = $db;
$photo_file="";

	$sql="select url from items where url<>'' and id_parent=".$id;
	$dp->open($sql);
	if(!$dp->eof)
	{
		$photo_file=$dp->row["url"];
	}
	else
	{
		$sql="select server1 from photos where id_parent=".(int)$id;
		$dp->open($sql);
		if(!$dp->eof)
		{
				$dir = opendir ($_SERVER["DOCUMENT_ROOT"].site_root.server_url($dp->row["server1"])."/".$id);
  				while ($file = readdir ($dir)) 
 				{
    				if($file <> "." && $file <> ".." && $file <> ".DS_Store")
    				{
						if(preg_match("/.jpg$|.jpeg$/i",$file) and !preg_match("/thumb/",$file) and !preg_match("/photo_[0-9]+/",$file)) 
						{
							$photo_file=$file;
						}
    				}
  				}
 				closedir ($dir);
		}
	}

return $photo_file;
}
//End. The function gets file name of the photo publication



//Build prints upload form
function prints_upload_form()
{
	global $ds;
	global $_SESSION;
	$res="";

	$res.="<table border='0' cellpadding='5' cellspacing='1' class='profile_table table table-striped table-hover'>
	<tr>
	<th></th>
	<th><b>".word_lang("title").":</b></th>
	<th><b>".word_lang("price").":</b></th>
	</tr>";

	$sql="select id_parent,title,price,priority from prints where photo=1 order by priority";
	$ds->open($sql);
	$tr=1;
	while(!$ds->eof)
	{
		$readonly="readonly";
		if(isset($_SESSION["entry_admin"]))
		{
			$readonly="";
		}

		$snd="";
		if($tr%2==0)
		{
			$snd="class='snd'";
		}
			
		$res.="<tr ".$snd.">
		<td><input name='prints_chk".$ds->row["id_parent"]."' type='checkbox' checked></td>
		<td>".$ds->row["title"]."</td>
		<td><input class='ibox form-control' name='prints_price".$ds->row["id_parent"]."' value='".float_opt($ds->row["price"],2)."' type='text' ".$readonly." style='width:50px'></td>
		</tr>";
			
		$tr++;
		$ds->movenext();
	}

	$res.="</table>";

	return $res;
}
//End prints upload form




//Build a table of current prints for the ID
function prints_live($id)
{
	global $ds;
	global $dr;
	global $_SESSION;
	$res="";

	$sql="select id_parent,title,price,priority from prints where photo=1 order by priority";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$res.="<table border='0' cellpadding='5' cellspacing='1' class='profile_table table table-striped table-hover'>
		<tr>
		<th></th>
		<th><b>".word_lang("title").":</b></th>
		<th><b>".word_lang("price").":</b></th>
		</tr>";

		$tr=1;
		while(!$ds->eof)
		{			
			$checked="";
			$price=$ds->row["price"];

			$sql="select id_parent,title,price,priority from prints_items where itemid=".(int)$id." and printsid=".$ds->row["id_parent"]." order by priority";
			$dr->open($sql);
			if(!$dr->eof)
			{
				$checked="checked";
				$price=$dr->row["price"];
			}

			$readonly="readonly";
			if(isset($_SESSION["entry_admin"]))
			{
				$readonly="";
			}
		
			$snd="";
			if($tr%2==0)
			{
				$snd="class='snd'";
			}

			$res.="<tr  ".$snd.">
			<td><input name='prints_chk".$ds->row["id_parent"]."' type='checkbox' ".$checked."></td>
			<td>".$ds->row["title"]."</td>
			<td><input class='ibox form-control' name='prints_price".$ds->row["id_parent"]."' ".$readonly." value='".float_opt($price,2)."' type='text' style='width:50px'></td>
			</tr>";

			$tr++;
			$ds->movenext();
		}

		$res.="</table>";
	}
	
	return $res;
}
//End. Build a table of current prints for the ID


//The function updates prints
function prints_update($id)
{
global $ds;
global $dr;
global $db;
global $_POST;


	$sql="select id_parent,title,priority from prints where photo=1 order by priority";
	$ds->open($sql);
	while(!$ds->eof)
	{
		$sql="select id_parent,title,price,priority from prints_items where itemid=".(int)$id." and printsid=".$ds->row["id_parent"]." order by priority";
		$dr->open($sql);

		if(!$dr->eof)
		{
			if(isset($_POST["prints_chk".$ds->row["id_parent"]]))
			{
				$sql="update prints_items set price=".(float)$_POST["prints_price".$ds->row["id_parent"]]." where itemid=".(int)$id." and printsid=".$ds->row["id_parent"];
				$db->execute($sql);
			}
			else
			{
				$sql="delete from prints_items where itemid=".(int)$id." and printsid=".$ds->row["id_parent"];
				$db->execute($sql);
			}
		}
		else
		{
			if(isset($_POST["prints_chk".$ds->row["id_parent"]]))
			{
				$sql="insert into prints_items (title,price,itemid,priority,printsid) values ('".$ds->row["title"]."',".(float)$_POST["prints_price".$ds->row["id_parent"]].",".$id.",".$ds->row["priority"].",".$ds->row["id_parent"].")";
				$db->execute($sql);
			}
		}

	$ds->movenext();
	}


}
//End The function updates prints



//Build <form> in admin panel
function build_admin_form($url,$type)
{
global $admin_fields;
global $admin_meanings;
global $admin_types;
global $admin_names;
global $id;
global $dd;
global $dn;
global $lvideo;
global $lpreviewvideo;
global $laudio;
global $lpreviewaudio;
global $lvector;
global $global_settings;
global $lng;
global $lang_name;
global $lang_symbol;
global $currency_code1;


$form_result="";



$border_header="<div class='table_t'><div class='table_b'><div class='table_l'><div class='table_r'><div class='table_bl'><div class='table_br'><div class='table_tl'><div class='table_tr'>";

$border_footer="</div></div></div></div></div></div></div></div>";


$id=0;
if(isset($_GET["id"])){$id=(int)$_GET["id"];}



if(isset($_SERVER["HTTP_REFERER"]) and $_SERVER["HTTP_REFERER"]!="")
{	
	$return_url=$_SERVER["HTTP_REFERER"];
}
else
{
	$return_url="";
}

$form_result.="<form method='post' Enctype='multipart/form-data' id='uploadform' name='uploadform' action='".$url."&type=".$type."' style='margin:0' class='gllpLatlonPicker'><div class='content_edit'><input type='hidden' name='return_url' value='".$return_url."'>";

if($type=="photo" or $type=="video" or $type=="audio" or $type=="vector")
{
	if((int)@$_COOKIE["p_panel_common"]==0)
	{
		$panel_style="block";
		$panel_marker="minus";
	}
	else
	{
		$panel_style="none";
		$panel_marker="plus";
	}
	
	$form_result.="<div class='subheader'><a class='btn btn-mini btn-sm btn-default' href=\"javascript:collapse_panel('panel_common')\"><i class='icon-".$panel_marker." fa fa-".$panel_marker."' id='marker_panel_common'></i></a>&nbsp;&nbsp;".word_lang("common information")."</div><div class='subheader_text' id='panel_common' style='display:".$panel_style."'>";
	
	//Show thumb
	if($id!=0 and isset($_SESSION['entry_admin']))
	{
		$form_result.="<div class='content_preview'>".show_preview($id,$type,2,0)."</div>";
	}
}


for($i=0;$i<count($admin_fields);$i++)
{
	if(($type=="photo" or $type=="video" or $type=="audio" or $type=="vector") and $admin_types[$i]=="file")
	{
		if((int)@$_COOKIE["p_panel_file_for_sale"]==0)
		{
			$panel_style="block";
			$panel_marker="minus";
		}
		else
		{
			$panel_style="none";
			$panel_marker="plus";
		}
		
		$form_result.="</div><div class='subheader'><a class='btn btn-mini btn-sm btn-default' href=\"javascript:collapse_panel('panel_file_for_sale')\"><i class='icon-".$panel_marker." fa fa-".$panel_marker."' id='marker_panel_file_for_sale'></i></a>&nbsp;&nbsp;".word_lang("file for sale")."</div><div class='subheader_text' id='panel_file_for_sale' style='display:".$panel_style."'>";
	}	


	$form_result.="<div class='admin_field'>";
	
	if(($type=="photo" or $type=="video" or $type=="audio" or $type=="vector") and $admin_types[$i]=="file")
	{
	
	}
	else
	{
		$form_result.="<span>".word_lang($admin_names[$i]).":</span>";
	}
	
	if($admin_types[$i]=="text")
	{
		$form_result.="<input type='text' name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control' value='".$admin_meanings[$i]."'>";
	}
	
	if($admin_types[$i]=="text_translation" or $admin_types[$i]=="textarea_translation")
	{
		$lng_translation=$lng;
		$lng_list="";
		$input_list="";
		$sql="select name,activ from languages where display=1 order by name";
		$dd->open($sql);
		while(!$dd->eof)
		{
			if($dd->row["activ"]==1)
			{
				$lng_translation=$dd->row["name"];
			}
			else
			{
				$lng3=strtolower($dd->row["name"]);
				$lng_symbol=$lang_symbol[$dd->row["name"]];
				if($lng3=="chinese traditional"){$lng3="chinese";$lng_symbol="zh1";}
				if($lng3=="chinese simplified"){$lng3="chinese";$lng_symbol="zh2";}
				if($lng3=="afrikaans formal"){$lng3="afrikaans";$lng_symbol="af1";}
				if($lng3=="afrikaans informal"){$lng3="afrikaans";$lng_symbol="af2";}
				
				$sql="select title,keywords,description from translations where id=".$id." and lang='".$lng_symbol."'";
				$dn->open($sql);
				if(!$dn->eof)
				{
					if($dn->row[$admin_fields[$i]]!="")
					{
							if($admin_types[$i]=="text_translation")
							{
								$input_code="<input type='text' name='translate_".$admin_fields[$i]."_".$lng_symbol."' style='width:400px' class='ibox form-control' value='".$dn->row[$admin_fields[$i]]."'>";
							}
							else
							{
								$input_code="<textarea name='translate_".$admin_fields[$i]."_".$lng_symbol."' style='width:400px;height:120px' class='ibox form-control'>".$dn->row[$admin_fields[$i]]."</textarea>";
							}
	
							$input_list.="<div class='clear' id='div_".$admin_fields[$i]."_".$lng_symbol."' style='padding-top:20px'><div class='input-append' style='float:left;margin-right:4px'>".$input_code."<span class='add-on' style='width:120px;text-align:left'><img src='".site_root."/admin/images/languages/".$lng3.".gif'>&nbsp;<font class='langtext'>".$dd->row["name"]."</font></span></div><button class='btn btn-danger' type='button' onClick=\"translation_delete('".$admin_fields[$i]."','".$lng_symbol."');\">".word_lang("delete")."</button></div>";
					}
					else
					{
						$lng_list.="<li id='li_".$admin_fields[$i]."_".$lng_symbol."' style='float:left;width:170px'><a href=\"javascript:translation_add('".$admin_fields[$i]."','".$dd->row["name"]."','".$lng3."','".$lng_symbol."','".$admin_types[$i]."');\"><img src='".site_root."/admin/images/languages/".$lng3.".gif'>&nbsp;".$dd->row["name"]."</a></li>";
					}
				}
				else
				{
					$lng_list.="<li id='li_".$admin_fields[$i]."_".$lng_symbol."' style='float:left;width:170px'><a href=\"javascript:translation_add('".$admin_fields[$i]."','".$dd->row["name"]."','".$lng3."','".$lng_symbol."','".$admin_types[$i]."');\"><img src='".site_root."/admin/images/languages/".$lng3.".gif'>&nbsp;".$dd->row["name"]."</a></li>";
				}
			}
			$dd->movenext();
		}
		
		$lng3=strtolower($lng_translation);
		if($lng3=="chinese traditional"){$lng3="chinese";}
		if($lng3=="chinese simplified"){$lng3="chinese";}
		if($lng3=="afrikaans formal"){$lng3="afrikaans";}
		if($lng3=="afrikaans informal"){$lng3="afrikaans";}
		
		if($admin_types[$i]=="text_translation")
		{
			$input_code="<input type='text' name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control' value='".$admin_meanings[$i]."'>";
		}
		else
		{
			$input_code="<textarea name='".$admin_fields[$i]."' style='width:400px;height:120px' class='ibox form-control'>".$admin_meanings[$i]."</textarea>";
		}
		
		$form_result.="
		<div class='clear '>
			<div class='input-append' style='float:left;margin-right:4px'>
				".$input_code."
				<span class='add-on' style='width:120px;text-align:left'><img src='".site_root."/admin/images/languages/".$lng3.".gif'>&nbsp;<font class='langtext'>".$lang_name[$lng_translation]."</font></span>
			</div>

			<div class='btn-group'>
    			<a class='btn btn-success' href='#'>".word_lang("add language")."</a>
    			<a class='btn btn-success dropdown-toggle' data-toggle='dropdown' href='#'><span class='caret' style='margin:8px 2px 8px 2px'></span></a>
    			<ul class='dropdown-menu' style='width:520px'>
    				".$lng_list."
   				</ul>
    		</div>
		</div>
		<div class='clear' id='trans_".$admin_fields[$i]."'>
			".$input_list."
		</div><div class='clear'></div>";
	}
	
	if($admin_types[$i]=="filepdf")
	{
		$form_result.="<input type='file' name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'><br>(*.jpg or *.pdf or *.zip)";
		if($admin_meanings[$i].""!="")
		{
			$form_result.="<div style='padding-top:3px'><a 	href='".$admin_meanings[$i]."'>".word_lang("download")."</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='delete_thumb.php?id=".$id."&type=file'>".word_lang("delete")."</a></div>";
		}
	}
	
	if($admin_types[$i]=="file")
	{	
		if($type=="category")
		{
			$form_result.="<input type='file' name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'><br>(*.jpg)";
			

			if($admin_meanings[$i].""!="")
			{
				$form_result.="<div style='padding-top:3px'><div id='preview' style='display:inline'><a 	href='".$admin_meanings[$i]."'>".word_lang("preview")."</a></div>&nbsp;&nbsp;&nbsp;&nbsp;<a href='delete_thumb.php?id=".$id."'>".word_lang("delete")."</a></div>";
			}
		}
		
		
		
		
		if($type=="photo" or $type=="video" or $type=="audio" or $type=="vector")
		{	
			$rights_managed=0;
			$active1="active";
			$active1_style=' class="active"';
			$active2="";
			$active2_style='';
			
			$flag_jquery=false;
			
			if($id!=0)
			{
				if($type=="photo")
				{
					$sql="select rights_managed from photos where id_parent=".(int)$id;
				}
				if($type=="video")
				{
					$sql="select rights_managed from videos where id_parent=".(int)$id;
				}
				if($type=="audio")
				{
					$sql="select rights_managed from audio where id_parent=".(int)$id;
				}
				if($type=="vector")
				{
					$sql="select rights_managed from vector where id_parent=".(int)$id;
				}
				$dd->open($sql);
				if(!$dd->eof)
				{
					$rights_managed=$dd->row["rights_managed"];
					if($global_settings["rights_managed"] and $rights_managed!=0)
					{
						$active1="";
						$active1_style='';
						$active2="active";
						$active2_style=' class="active"';
					}
				}
			}
			else
			{
				if(!$global_settings["royalty_free"])
				{
					$rights_managed=1;
					$active1="";
					$active1_style='';
					$active2="active";
					$active2_style=' class="active"';
				}
			}
			
			$form_result.='<input type="hidden" id="license_type" name="license_type" value="'.$rights_managed.'">';
			
			if($global_settings["royalty_free"] and $global_settings["rights_managed"])
			{			
				$form_result.='<script>
			 
				 function change_license(value)
				 {
					document.getElementById("license_type").value=value;
				 }
				 
				</script>
				<div class="tabbable nav-tabs-custom" style="width:675px">
					<ul class="nav nav-tabs" style="margin:5px 0px 5px 0px">
						<li '.$active1_style.' onClick="change_license(0)"><a  href="#tab1" data-toggle="tab">'.word_lang("royalty free").'</a></li>
						<li '.$active2_style.' onClick="change_license(1)"><a  href="#tab2" data-toggle="tab">'.word_lang("rights managed").'</a></li>
					</ul>
				</div>
				<div class="tab-content">';
			}
		}	
			
		if($type=="photo")
		{	
			if($global_settings["royalty_free"])
			{
				$form_result.='<div class="tab-pane '.$active1.'" id="tab1">';
				
				$form_result.=$border_header.photo_upload_form($id,true).$border_footer;
				
				$form_result.='</div>';
			}
			
			if($global_settings["rights_managed"])
			{
				$form_result.='<div class="tab-pane '.$active2.'" id="tab2">';
				
				$form_result.=$border_header.rights_managed_upload_form($type,$rights_managed,$id,true).$border_footer;			
				
				$form_result.='</div>';
			}
			
			if($global_settings["royalty_free"] and $global_settings["rights_managed"])
			{
				$form_result.="</div>";
			}
			
			if($global_settings["prints"])
			{
				if((int)@$_COOKIE["p_panel_prints"]==0)
				{
					$panel_style="block";
					$panel_marker="minus";
				}
				else
				{
					$panel_style="none";
					$panel_marker="plus";
				}
				
				$form_result.="</div></div><div class='subheader'><a class='btn btn-mini btn-sm btn-default' href=\"javascript:collapse_panel('panel_prints')\"><i class='icon-".$panel_marker." fa fa-".$panel_marker."' id='marker_panel_prints'></i></a>&nbsp;&nbsp;".word_lang("prints and products")."</div><div class='subheader_text'><div class='admin_field' id='panel_prints' style='display:".$panel_style."'>";
				if($id==0)
				{
					$form_result.=$border_header.prints_upload_form().$border_footer;
				}
				else
				{
					$form_result.=$border_header.prints_live($id).$border_footer;
				}
			}
		}
		
		if($type=="video")
		{
			if($global_settings["royalty_free"])
			{
				$form_result.='<div class="tab-pane '.$active1.'" id="tab1">';
				$form_result.=$border_header.files_upload_form($id,"video",true).$border_footer;				
				$form_result.='</div>';
			}
			
			if($global_settings["rights_managed"])
			{
				$form_result.='<div class="tab-pane '.$active2.'" id="tab2">';				
				$form_result.=$border_header.rights_managed_upload_form($type,$rights_managed,$id,true).$border_footer;							
				$form_result.='</div>';
			}
			
			if($global_settings["royalty_free"] and $global_settings["rights_managed"])
			{
				$form_result.="</div>";
			}
		}
		
		if($type=="audio")
		{
			if($global_settings["royalty_free"])
			{
				$form_result.='<div class="tab-pane '.$active1.'" id="tab1">';
				$form_result.=$border_header.files_upload_form($id,"audio",true).$border_footer;				
				$form_result.='</div>';
			}
			
			if($global_settings["rights_managed"])
			{
				$form_result.='<div class="tab-pane '.$active2.'" id="tab2">';				
				$form_result.=$border_header.rights_managed_upload_form($type,$rights_managed,$id,true).$border_footer;							
				$form_result.='</div>';
			}
			
			if($global_settings["royalty_free"] and $global_settings["rights_managed"])
			{
				$form_result.="</div>";
			}
		}
		
		if($type=="vector")
		{
			if($global_settings["royalty_free"])
			{
				$form_result.='<div class="tab-pane '.$active1.'" id="tab1">';			
				$form_result.=$border_header.files_upload_form($id,"vector",true).$border_footer;				
				$form_result.='</div>';
			}
			
			if($global_settings["rights_managed"])
			{
				$form_result.='<div class="tab-pane '.$active2.'" id="tab2">';				
				$form_result.=$border_header.rights_managed_upload_form($type,$rights_managed,$id,true).$border_footer;							
				$form_result.='</div>';
			}
			
			if($global_settings["royalty_free"] and $global_settings["rights_managed"])
			{
				$form_result.="</div>";
			}
		}		
	}
	
	if($admin_types[$i]=="int")
	{
		$form_result.="<input type='text' name='".$admin_fields[$i]."' style='width:200px' class='ibox form-control' value='".$admin_meanings[$i]."'>";
	}
	
	if($admin_types[$i]=="float")
	{
		if($admin_fields[$i] == "google_x")
		{
			$form_result.="<input type='text' name='".$admin_fields[$i]."' style='width:200px' class='ibox form-control gllpLatitude' value='".$admin_meanings[$i]."'>";
		}
		elseif($admin_fields[$i] == "google_y")
		{
			$form_result.="<input type='text' name='".$admin_fields[$i]."' style='width:200px' class='ibox form-control gllpLongitude' value='".$admin_meanings[$i]."'><div class='gllpMap' style='width: 500px; height: 250px;margin-top:10px'></div><input type='hidden' class='gllpZoom' value='8'/><input type='hidden' class='gllpUpdateButton' value='update map'><div style='margin-top:10px'><input type='text' class='gllpSearchField ibox form-control' style='width:200px;display:inline'>
			<input type='button' class='gllpSearchButton btn btn-default' value='".word_lang("search")."'></div><script src='https://maps.googleapis.com/maps/api/js?sensor=false'></script><script src='".site_root."/inc/js/gmap_picker/jquery-gmaps-latlon-picker.js'></script>";
		}
		else
		{
			$form_result.="<input type='text' name='".$admin_fields[$i]."' style='width:200px' class='ibox form-control' value='".$admin_meanings[$i]."'>";
		}
	}
	
	if($admin_types[$i]=="textarea")
	{
		$form_result.="<textarea name='".$admin_fields[$i]."' style='width:400px;height:200px' class='ibox form-control'>".$admin_meanings[$i]."</textarea>";
	}
	
	if($admin_types[$i]=="data")
	{
		$form_result.=admin_date($admin_meanings[$i],$admin_fields[$i]);
	}
	
	if($admin_types[$i]=="checkbox")
	{
		$sel="";
		if($admin_meanings[$i]==1){$sel="checked";}
		$form_result.="<input type='checkbox' name='".$admin_fields[$i]."'   ".$sel.">";
	}
	
	if($admin_types[$i]=="category")
	{
		$form_result.="<select name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'><option value='5'></option>".$admin_meanings[$i]."</select>";
	}
	
	if($admin_types[$i]=="commission")
	{
		$commission_value = round($admin_meanings[$i]);
		$commission_type1 = "selected";
		$commission_type2 = "";
		$sql="select " . $admin_fields[$i] . "_type from user_category where id_parent=" . $id;
		$dn->open($sql);
		if (!$dn->eof) {
			if ($dn->row[$admin_fields[$i] . "_type"] == 1) {
				$commission_value = float_opt($admin_meanings[$i], 2);
				$commission_type2 = "selected";
				$commission_type1 = "";
			}
		}
		
		
		$form_result.="<input name='" . $admin_fields[$i] . "' type='text' style='width:50px' value='" . $commission_value . "'><select name='" . $admin_fields[$i] . "_type' style='width:70px'>
				<option value='0' " . $commission_type1 . ">%</option>
				<option value='1' " . $commission_type2 . ">" . $currency_code1 . "</option>
			</select>";
	}
	
	if($admin_types[$i]=="model")
	{	
		$model_list="";
		$model_ids=array();
		
		$form_result.="";
		
		$sql="select publication_id,model_id,models from models_files where publication_id=".$id;
		$dn->open($sql);
		while(!$dn->eof)
		{
			$sql="select name from models where id_parent=".$dn->row["model_id"];
			$dd->open($sql);
			if(!$dd->eof)
			{
				$form_result.="<div class='clear' id='div_".$dn->row["model_id"]."' style='margin-bottom:5px'><div class='input-append' style='float:left;margin-right:4px'><a href='../models/content.php?id=".$dn->row["model_id"]."' class='btn btn-default btn-small'>";
				
				if($dn->row["models"]==0)
				{
					$form_result.="<b>".word_lang("Model release").":</b> ";
				}
				else
				{
					$form_result.="<b>".word_lang("Property release").":</b> ";
				}
				
				$form_result.=$dd->row["name"]."</a>";
				
				$form_result.="</div><button class='btn btn-danger btn-small' type='button' onClick=\"model_delete('".$dn->row["model_id"]."');\">".word_lang("delete")."</button><input type='hidden' name='model".$dn->row["model_id"]."' value='".$dn->row["models"]."'></div>";
				
				$model_ids[$dn->row["model_id"]]=1;
			}
			
			$dn->movenext();
		}
		
		$sql="select name,id_parent from models order by name";
		$dd->open($sql);
		while(!$dd->eof)
		{
			if(isset($model_ids[$dd->row["id_parent"]]))
			{
				$model_style="style='display:none'";
			}
			else
			{
				$model_style="style='display:block'";
			}
			
			$model_list.="<li id='model{TYPE}_".$dd->row["id_parent"]."' ".$model_style."><a href=\"javascript:model_add(".$dd->row["id_parent"].",{TYPE},'".addslashes($dd->row["name"])."');\">".$dd->row["name"]."</a></li>";
			
			$dd->movenext();
		}
		
		
		$form_result.="<div id='models_list'></div><div class='clear'></div><div class='btn-group'>
    			<a class='btn btn-default btn-small' href='#'><i class='icon-plus-sign'></i> ".word_lang("Model release")."</a>
    			<a class='btn btn-default dropdown-toggle btn-small' data-toggle='dropdown' href='#'><span class='caret' style='margin:8px 2px 8px 2px'></span></a>
    			<ul class='dropdown-menu' style='width:250px'>
    				".str_replace("{TYPE}","0",$model_list)."
   				</ul>
    		</div>&nbsp;<div class='btn-group'>
    			<a class='btn btn-default btn-small' href='#'><i class='icon-plus-sign'></i> ".word_lang("Property release")."</a>
    			<a class='btn btn-default dropdown-toggle btn-small' data-toggle='dropdown' href='#'><span class='caret' style='margin:8px 2px 8px 2px'></span></a>
    			<ul class='dropdown-menu' style='width:250px'>
    				".str_replace("{TYPE}","1",$model_list)."
   				</ul>
    		</div>";
	}
	
	if($admin_types[$i]=="author")
	{
		$form_result.="<select name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'>";
		$sql="select login from users where utype='seller' or utype='common' order by login";
		$dd->open($sql);
		while(!$dd->eof)
		{
			$sel="";
			if($dd->row["login"]==$admin_meanings[$i]){$sel="selected";}
			$form_result.="<option value='".$dd->row["login"]."' ".$sel.">".$dd->row["login"]."</option>";
			$dd->movenext();
		}
		$form_result.="</select>";
	}
	
	if($admin_types[$i]=="content_type")
	{
		$form_result.="<select name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'>";
		$sql="select id_parent,name from content_type order by priority";
		$dd->open($sql);
		while(!$dd->eof)
		{
			$sel="";
			if($dd->row["name"]==$admin_meanings[$i]){$sel="selected";}
			$form_result.="<option value='".$dd->row["name"]."' ".$sel.">".$dd->row["name"]."</option>";
			$dd->movenext();
		}
		$form_result.="</select>";
	}
	
	if($admin_types[$i]=="format")
	{
		$form_result.="<select name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'>";
		$sql="select name from video_format";
		$dd->open($sql);
		while(!$dd->eof)
		{
			$sel="";
			if($dd->row["name"]==$admin_meanings[$i]){$sel="selected";}
			$form_result.="<option value='".$dd->row["name"]."' ".$sel.">".$dd->row["name"]."</option>";
			$dd->movenext();
		}
		$form_result.="</select>";
	}
	
	if($admin_types[$i]=="ratio")
	{
		$form_result.="<select name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'>";
		$sql="select name from video_ratio";
		$dd->open($sql);
		while(!$dd->eof)
		{
			$sel="";
			if($dd->row["name"]==$admin_meanings[$i]){$sel="selected";}
			$form_result.="<option value='".$dd->row["name"]."' ".$sel.">".$dd->row["name"]."</option>";
			$dd->movenext();
		}
		$form_result.="</select>";
	}
	
	if($admin_types[$i]=="rendering")
	{
		$form_result.="<select name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'>";
		$sql="select name from video_rendering";
		$dd->open($sql);
		while(!$dd->eof)
		{
			$sel="";
			if($dd->row["name"]==$admin_meanings[$i]){$sel="selected";}
			$form_result.="<option value='".$dd->row["name"]."' ".$sel.">".$dd->row["name"]."</option>";
			$dd->movenext();
		}
		$form_result.="</select>";
	}
	
	if($admin_types[$i]=="frames")
	{
		$form_result.="<select name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'>";
		$sql="select name from video_frames";
		$dd->open($sql);
		while(!$dd->eof)
		{
			$sel="";
			if($dd->row["name"]==$admin_meanings[$i]){$sel="selected";}
			$form_result.="<option value='".$dd->row["name"]."' ".$sel.">".$dd->row["name"]."</option>";
			$dd->movenext();
		}
		$form_result.="</select>";
	}
	
	if($admin_types[$i]=="source")
	{
		$form_result.="<select name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'>";
		$sql="select name from audio_source";
		$dd->open($sql);
		while(!$dd->eof)
		{
			$sel="";
			if($dd->row["name"]==$admin_meanings[$i]){$sel="selected";}
			$form_result.="<option value='".$dd->row["name"]."' ".$sel.">".$dd->row["name"]."</option>";
			$dd->movenext();
		}
		$form_result.="</select>";
	}
	
	if($admin_types[$i]=="track_format")
	{
		$form_result.="<select name='".$admin_fields[$i]."' style='width:400px' class='ibox form-control'>";
		$sql="select name from audio_format";
		$dd->open($sql);
		while(!$dd->eof)
		{
			$sel="";
			if($dd->row["name"]==$admin_meanings[$i]){$sel="selected";}
			$form_result.="<option value='".$dd->row["name"]."' ".$sel.">".$dd->row["name"]."</option>";
			$dd->movenext();
		}
		$form_result.="</select>";
	}
	
	if($admin_types[$i]=="color")
	{
		$form_result.=color_set($admin_meanings[$i]);
	}
	
	if($admin_types[$i]=="duration")
	{
		$form_result.=duration_form($admin_meanings[$i],$admin_fields[$i]);
	}
	
	
	if($admin_types[$i]=="editor")
	{
		$form_result.="<script type='text/javascript' src='../plugins/tiny_mce/tiny_mce.js'></script>
		<script type='text/javascript'>
	tinyMCE.init({
		// General options
		mode : 'exact',
		elements : '".$admin_fields[$i]."',
		theme : 'advanced',
		plugins : 'autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks',
		document_base_url : '".surl.site_root."/',
		convert_urls : false,
		relative_urls : false,

		// Theme options
		theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect',
		theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor',
		theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen',
		theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		theme_advanced_statusbar_location : 'bottom',
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : '../plugins/tiny_mce/css/content.css',

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : '../plugins/tiny_mce/lists/template_list.js',
		external_link_list_url : '../plugins/tiny_mce/lists/link_list.js',
		external_image_list_url : '../plugins/tiny_mce/lists/image_list.js',
		media_external_list_url : '../plugins/tiny_mce/lists/media_list.js',

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'}
		],


	});
	</script>
	<textarea name='".$admin_fields[$i]."' style='width:800px;height:600px'>".$admin_meanings[$i]."</textarea>
	";
	}
	
	$form_result.="</div>";
	
	if(($type=="photo" or $type=="video" or $type=="audio" or $type=="vector") and $admin_types[$i]=="file")
	{
		if((int)@$_COOKIE["p_panel_settings"]==0)
		{
			$panel_style="block";
			$panel_marker="minus";
		}
		else
		{
			$panel_style="none";
			$panel_marker="plus";
		}
		
		$form_result.="</div><div class='subheader'><a class='btn btn-mini btn-sm btn-default' href=\"javascript:collapse_panel('panel_settings')\"><i class='icon-".$panel_marker." fa fa-".$panel_marker."' id='marker_panel_settings'></i></a>&nbsp;&nbsp;".word_lang("settings")."</div><div class='subheader_text'  id='panel_settings' style='display:".$panel_style."'>";
	}	
}

if($type=="photo" or $type=="video" or $type=="audio" or $type=="vector")
{
	$form_result.="</div>";
}

$form_result.="<div id='button_bottom_static'>
		<div id='button_bottom_layout'></div>
		<div id='button_bottom'><input type='submit' value='".word_lang("Save")."' class='btn btn-primary' id='savebutton'></div></div>";

$form_result.="</div></form>";


return $form_result;
}







//Redirect function when a file is being uploaded
function redirect_file($s,$swait)
{
ob_end_clean();

	if($swait==false)
	{
		header("location:".$s);
		exit();
	}
	else
	{
		echo("<html>
		<head>
		<title>".word_lang("upload")."</title>
		</head>
		<body bgcolor='#525151'>
		<script language='javascript'>
		function ff()
		{
		location.href='".$s."';
		}
		function cc()
		{
		hid = setTimeout('ff();',5000);
		} 
		cc()
		</script><div style='margin:250px auto 0px auto;width:310px;background-color:#373737;border: #4a4a4a 4px solid;padding:20px;font: 15pt Arial;color:#ffffff'>".word_lang("wait")."<div><center><img src='".site_root."/images/upload_loading.gif'></center></div></div>
		</body>
		</html>");
	}

}





//Add/update category
function add_update_category($id,$userid,$upload,$published)
{
global $_POST;
global $_SERVER;
global $db;
global $dr;
global $global_settings;


$category_upload=$upload;
if(isset($_POST["upload"])){$category_upload=1;}

$category_published=$published;
if(isset($_POST["published"])){$category_published=1;}

$category_priority=0;
if(isset($_POST["priority"])){$category_priority=(int)$_POST["priority"];}

$category_featured=0;
if(isset($_POST["featured"]))
{
	$category_featured=1;
}

$flag_new=false;


//Get ID for a new category
if($id==0)
{
		$flag_new=true;
		
		$sql="insert into structure (id_parent,name,module_table) values (".(int)$_POST["category"].",'".result($_POST["title"])."',34)";
		$db->execute($sql);

		$sql="select id from structure where name='".result($_POST["title"])."' order by id desc";
		$dr->open($sql);
		$id=$dr->row['id'];

}




//Upload photo
$photo="";
$swait=false;
$flag=true;	
	
if(preg_match("/text/i",$_FILES["photo"]["type"]))
{
	$flag=false;
}
if(!preg_match("/\.jpg$/i",$_FILES["photo"]["name"]))
{
	$flag=false;
}

$_FILES["photo"]['name']=result_file($_FILES["photo"]['name']);


if($_FILES["photo"]['size']>0 and $_FILES["photo"]['size']<10048*1024)
{
	if($flag==true)
	{
		$photo=site_root."/content/categories/category_".$id.".jpg";
		move_uploaded_file($_FILES["photo"]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$photo);

		//make thumb
		easyResize($_SERVER["DOCUMENT_ROOT"].$photo,$_SERVER["DOCUMENT_ROOT"].$photo,100,(int)$global_settings["category_preview"]);

		$swait=true;
	}
}



//Change database
	if($flag_new)
	{
	//Add a new category

	
		$sql="insert into category (id_parent,title,description,keywords,photo,upload,userid,published,priority,password,featured) values (".$id.",'".result($_POST["title"])."','".result($_POST["description"])."','".result($_POST["keywords"])."','".$photo."',".$category_upload.",".(int)$userid.",".$category_published.",".$category_priority.",'".result($_POST["password"])."',".$category_featured.")";
		$db->execute($sql);
	
		category_url($id);
	}
	else
	{
	//Update the category
	
		$com="";
		if($userid!=0)
		{
			$com=" and userid=".(int)$userid;
		}
	
		$sql="update category set title='".result($_POST["title"])."',description='".result($_POST["description"])."',keywords='".result($_POST["keywords"])."',password='".result($_POST["password"])."',priority=".$category_priority.",upload=".$category_upload.",published=".$category_published.",featured=".$category_featured." where id_parent=".$id.$com;
		$db->execute($sql);
		
		if($photo!="")
		{
			$sql="update category set photo='".result($photo)."' where id_parent=".$id.$com;
			$db->execute($sql);
		}
			
		$sql="update structure set name='".result($_POST["title"])."',id_parent=".(int)$_POST["category"]." where id=".$id;
		$db->execute($sql);
	}
		
	//Update translation
	if($global_settings["multilingual_categories"])
	{
		$sql="delete from translations where id=".$id;
		$db->execute($sql);
	}
	
	foreach ($_POST as $key => $value) 
	{
		if(preg_match("/translate/i",$key))
		{
			$temp_mass=explode("_",$key);
			if(isset($temp_mass[1]) and isset($temp_mass[2]))
			{
				$sql="select id from translations where id=".$id." and lang='".result($temp_mass[2])."'";
				$dr->open($sql);
				if($dr->eof)
				{
					$sql="insert into translations (id,title,keywords,description,lang,types) values (".$id.",'','','','".result($temp_mass[2])."',0)";
					$db->execute($sql);	
				}
				
				$sql="update translations set ".result($temp_mass[1])."='".result($value)."' where id=".$id." and lang='".result($temp_mass[2])."'";
				$db->execute($sql);
			}
		}
	}

return $swait;
}








//Delete category
function delete_category($id,$userid)
{
	global $db;
	global $rs;
	global $_SERVER;

	$com="";
	if($userid!=0)
	{
		$com=" and userid=".(int)$userid;
	}

	$sql="select id_parent,photo from category where id_parent=".(int)$id.$com;
	$rs->open($sql);
	if(!$rs->eof)
	{
		if($rs->row["photo"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"]))
		{
			unlink($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"]);
		}

		$sql="delete from structure where id=".(int)$id;
		$db->execute($sql);

		$sql="delete from category where id_parent=".(int)$id;
		$db->execute($sql);
	
		$sql="delete from translations where id=".(int)$id;
		$db->execute($sql);
	}
}



//The function uploads model property release
function model_upload($id)
{
	global $_FILES;
	global $_SERVER;
	global $global_settings;
	global $db;
	
	$swait=false;
	
	//upload photo
	$_FILES["modelphoto"]['name']=result_file($_FILES["modelphoto"]['name']);
	$nf=explode(".",$_FILES["modelphoto"]['name']);
	if($_FILES["modelphoto"]['size']>0 and $_FILES["modelphoto"]['size']<1024*1024*1 and !preg_match("/text/i",$_FILES["modelphoto"]['type']) and preg_match("/.jpg$|.jpeg$/i",$_FILES["modelphoto"]['name']))
	{
		$swait=true;
		$photo=site_root."/content/models/modelphoto".$id.".".$nf[count($nf)-1];
		move_uploaded_file($_FILES["modelphoto"]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$photo);

		$size = getimagesize ($_SERVER["DOCUMENT_ROOT"].$photo);

		$wd1=$global_settings["thumb_width"];
		if(isset($size[1]))
		{
			if($size[0]<$size[1]){$wd1=$size[0]*$global_settings["thumb_height"]/$size[1];}
		}
	
		easyResize($_SERVER["DOCUMENT_ROOT"].$photo,$_SERVER["DOCUMENT_ROOT"].$photo,100,$wd1);

		$sql="update models set modelphoto='".$photo."' where id_parent=".$id;
		$db->execute($sql);
	}

	//upload release
	$_FILES["model"]['name']=result_file($_FILES["model"]['name']);
	$nf=explode(".",$_FILES["model"]['name']);
	if($_FILES["model"]['size']>0 and $_FILES["model"]['size']<1024*1024*5 and !preg_match("/text/i",$_FILES["model"]['type']) and preg_match("/.pdf$|.zip$|.jpg$|.jpeg$/i",$_FILES["model"]['name']))
	{
		$swait=true;
		$photo=site_root."/content/models/model".$id.".".$nf[count($nf)-1];
		move_uploaded_file($_FILES["model"]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$photo);

		$sql="update models set model='".$photo."' where id_parent=".$id;
		$db->execute($sql);
	}
	
	return $swait;
}
//End. The function uploads model property release


//The function deletes model property release
function model_delete($id,$user)
{
	global $db;
	global $rs;
	
	if($user=="")
	{
		$sql="select * from models where id_parent=".(int)$id;
	}
	else
	{
		$sql="select * from models where id_parent=".(int)$id." and user='".result($user)."'";
	}
	$rs->open($sql);
	if(!$rs->eof)
	{
		$sql="delete from models where id_parent=".(int)$id;
		$db->execute($sql);

		if($rs->row["modelphoto"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["modelphoto"]))
		{
			@unlink($_SERVER["DOCUMENT_ROOT"].$rs->row["modelphoto"]);
		}

		if($rs->row["model"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["model"]))
		{
			@unlink($_SERVER["DOCUMENT_ROOT"].$rs->row["model"]);
		}
	}
}
//End. The function deletes model property release



//The function deletes files of model property release
function model_delete_file($id,$type,$user)
{
	global $db;
	global $rs;

	if($user=="")
	{
		$sql="select * from models where id_parent=".(int)$id;
	}
	else
	{
		$sql="select * from models where id_parent=".(int)$id." and user='".result($user)."'";
	}
	$rs->open($sql);
	if(!$rs->eof)
	{
		if($type=="photo")
		{
			$sql="update models set modelphoto='' where id_parent=".(int)$id;
			$db->execute($sql);
			if($rs->row["modelphoto"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["modelphoto"]))
			{
				@unlink($_SERVER["DOCUMENT_ROOT"].$rs->row["modelphoto"]);
			}
		}
		else
		{
			$sql="update models set model='' where id_parent=".(int)$id;
			$db->execute($sql);
			if($rs->row["model"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["model"]))
			{
				@unlink($_SERVER["DOCUMENT_ROOT"].$rs->row["model"]);
			}
		}
	}
}
//End. The function deletes files of model property release


//The function adds a new photo to the database
function publication_photo_add()
{
	global $site_servers;
	global $site_server_activ;
	global $pub_vars;
	global $dr;
	global $db;
	$id=0;
	
	//add to structure database
	$sql="insert into structure (id_parent,name,module_table) values (".$pub_vars["category"].",'".$pub_vars["title"]."',30)";
	$db->execute($sql);

	//define id
	$sql="select id from structure where name='".$pub_vars["title"]."' order by id desc";
	$dr->open($sql);
	$id=$dr->row['id'];

	//create new folder
	if(!file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id))
	{
		mkdir($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id);
		file_put_contents($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id."/index.html","");
	}

	if(!isset($pub_vars["featured"]))
	{
		$pub_vars["featured"]=0;
	}
	
	if(!isset($pub_vars["color"]))
	{
		$pub_vars["color"]='';
	}
	
	if(isset($_POST["adult"]))
	{
		$pub_vars["adult"]=1;
	}
	else
	{
		$pub_vars["adult"]=0;
	}
	
	if(isset($_POST["contacts"]))
	{
		$pub_vars["contacts"]=1;
	}
	else
	{
		$pub_vars["contacts"]=0;
	}
	
	if(isset($_POST["exclusive"]))
	{
		$pub_vars["exclusive"]=1;
	}
	else
	{
		$pub_vars["exclusive"]=0;
	}

	//add to photo database
	$sql="insert into photos (id_parent,title,description,keywords,userid,published,viewed,data,author,content_type,downloaded,model,examination,server1,free,category2,category3,featured,google_x,google_y,server2,color,editorial,adult,rights_managed,contacts,exclusive,vote_like,vote_dislike) values (".$id.",'".$pub_vars["title"]."','".$pub_vars["description"]."','".$pub_vars["keywords"]."',".$pub_vars["userid"].",".$pub_vars["published"].",".$pub_vars["viewed"].",".$pub_vars["data"].",'".$pub_vars["author"]."','".$pub_vars["content_type"]."',".$pub_vars["downloaded"].",".$pub_vars["model"].",'".$pub_vars["examination"]."','".$pub_vars["server1"]."',".$pub_vars["free"].",".$pub_vars["category2"].",".$pub_vars["category3"].",".$pub_vars["featured"].",".$pub_vars["google_x"].",".$pub_vars["google_y"].",0,'".$pub_vars["color"]."',".$pub_vars["editorial"].",".$pub_vars["adult"].",0,".$pub_vars["contacts"].",".$pub_vars["exclusive"].",".(int)@$pub_vars["vote_like"].",".(int)@$pub_vars["vote_dislike"].")";
	$db->execute($sql);

	return $id;
}
//End. The function adds a new photo to the database




//The function adds photo sizes to the database
function publication_photo_sizes_add($id,$file,$without_post,$price_license="royalty_free",$price_license_id=0)
{
	global $dr;
	global $rs;
	global $db;
	global $_POST;

	if($price_license=="royalty_free")
	{
		$sql="select * from sizes order by priority";
		$dr->open($sql);
		while(!$dr->eof)
		{
			$sql="select id,id_parent,url,name,price from items where id_parent=".$id." and price_id=".$dr->row["id_parent"];
			$rs->open($sql);
			if($rs->eof)
			{
				$flag=false;
				if($without_post)
				{
					$flag=true;
				}

				if(isset($_POST["photo_chk".$dr->row["id_parent"]]))
				{
					$flag=true;
				}

				$price=$dr->row["price"];
				if(isset($_POST["photo_price".$dr->row["id_parent"]]))
				{
					$price=(float)$_POST["photo_price".$dr->row["id_parent"]];
				}

				if($flag)
				{
					$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$dr->row["title"]."','".result($file)."',".$price.",".$dr->row["priority"].",0,".$dr->row["id_parent"].")";
					$db->execute($sql);
				}	
			}
		$dr->movenext();
		}
	}
	
	if($price_license=="rights_managed")
	{
		$sql="select title,price from rights_managed where id=".(int)$price_license_id;
		$dr->open($sql);
		if(!$dr->eof)
		{
			$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$dr->row["title"]."','".result($file)."',".$dr->row["price"].",0,0,".(int)$price_license_id.")";
			$db->execute($sql);
		}
	}
	
}
//End. The function adds photo sizes to the database



//The function adds photo watermark/color info  to the database
function publication_watermark_add($id,$file)
{
global $global_settings;
global $site_server_activ;
global $site_servers;
global $db;

	if($global_settings["watermark_photo"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$global_settings["watermark_photo"]))
	{
		watermark($file,$_SERVER["DOCUMENT_ROOT"].$global_settings["watermark_photo"]);
	}

	$size = getimagesize ($file);
	$orientation=0;
	if($size[1]>$size[0]){$orientation=1;}

	$sql="update photos set watermark=".$global_settings["watermark_position"].",color='".define_color($file)."',orientation=".$orientation." where id_parent=".$id;
	$db->execute($sql);

}
//End. The function adds photo watermark/color info  to the database


//The function defines google gps coordinates

function getGps($exifCoord, $hemi) 
{
    $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

}

function gps2Num($coordPart) 
{
    $parts = explode('/', $coordPart);

    if (count($parts) <= 0)
        return 0;

    if (count($parts) == 1)
        return $parts[0];

    return floatval($parts[0]) / floatval($parts[1]);
}
//End. The function defines google gps coordinates


//The function adds IPTC info to the database
function publication_iptc_add($id,$photo)
{
global $rs;
global $db;

$size = getimagesize ($photo,$info);
if(isset ($info["APP13"]))
{
	$iptc = iptcparse ($info["APP13"]);

	//Title
	if(isset($iptc["2#005"][0]) and $iptc["2#005"][0]!="")
	{
		$sql="update photos set title='".result($iptc["2#005"][0])."' where id_parent=".$id;
		$db->execute($sql);
		
		$sql="update vector set title='".result($iptc["2#005"][0])."' where id_parent=".$id;
		$db->execute($sql);

		$sql="update structure set name='".result($iptc["2#005"][0])."' where id=".$id;
		$db->execute($sql);
	}
	else
	{
		if(isset($iptc["2#105"][0]) and $iptc["2#105"][0]!="")
		{
			$sql="update photos set title='".result($iptc["2#105"][0])."' where id_parent=".$id;
			$db->execute($sql);
		
			$sql="update vector set title='".result($iptc["2#105"][0])."' where id_parent=".$id;
			$db->execute($sql);

			$sql="update structure set name='".result($iptc["2#105"][0])."' where id=".$id;
			$db->execute($sql);
		}
	}

	//Description
	if(isset($iptc["2#120"][0]) and $iptc["2#120"][0]!="")
	{
		$iptc_description=result($iptc["2#120"][0]);
		
		/*
		if(isset($iptc["2#090"][0]) and $iptc["2#090"][0]!="")
		{
			$iptc_description.="\nCity: ".result($iptc["2#090"][0]);
		}
		
		if(isset($iptc["2#095"][0]) and $iptc["2#095"][0]!="")
		{
			$iptc_description.="\nState: ".result($iptc["2#095"][0]);
		}
		
		if(isset($iptc["2#101"][0]) and $iptc["2#101"][0]!="")
		{
			$iptc_description.="\nCountry: ".result($iptc["2#101"][0]);
		}
		
		if(isset($iptc["2#055"][0]) and $iptc["2#055"][0]!="")
		{			
			$date_created=result($iptc["2#055"][0]);
			if(strlen($date_created)==8)
			{
				$date_created=substr($date_created,4,2)."/".substr($date_created,6,2)."/".substr($date_created,0,4);
			}
						
			$iptc_description.="\nDate created: ".$date_created;
		}
		*/
		
		
		$sql="update photos set description='".$iptc_description."' where id_parent=".$id;
		$db->execute($sql);
		
		$sql="update vector set description='".$iptc_description."' where id_parent=".$id;
		$db->execute($sql);
	}

	//Keywords
	if(isset($iptc["2#025"][0]) and $iptc["2#025"][0]!="")
	{
		$iptc_kw="";
		for($t=0;$t<count($iptc["2#025"]);$t++)
		{
			if($iptc_kw!=""){$iptc_kw.=",";}
			$iptc_kw.=$iptc["2#025"][$t];
		}
		if($iptc_kw!="")
		{
			$sql="update photos set keywords='".result($iptc_kw)."' where id_parent=".$id;
			$db->execute($sql);
			
			$sql="update vector set keywords='".result($iptc_kw)."' where id_parent=".$id;
			$db->execute($sql);
		}
	}
}

	//Google coordinates
	$exif_info=@exif_read_data($photo,0,true);
	if(isset($exif_info["GPS"]["GPSLongitude"]) and isset($exif_info["GPS"]['GPSLongitudeRef']) and isset($exif_info["GPS"]["GPSLatitude"]) and isset($exif_info["GPS"]['GPSLatitudeRef']))
	{
		$lon = getGps($exif_info["GPS"]["GPSLongitude"], $exif_info["GPS"]['GPSLongitudeRef']);
		$lat = getGps($exif_info["GPS"]["GPSLatitude"], $exif_info["GPS"]['GPSLatitudeRef']);
		
		$sql="update photos set google_x=".$lat.",google_y=".$lon." where id_parent=".$id;
		$db->execute($sql);
	}
	
	//EXIF
	add_exif_to_database($id,$photo);
}
//End. The function adds IPTC info to the database


//The function adds a new print to the database
function publication_prints_add($id,$without_post)
{
	global $rs;
	global $db;


	$sql="select id_parent,title,price,priority from prints order by priority";
	$rs->open($sql);
	while(!$rs->eof)
	{

		$flag=false;
		if($without_post)
		{
			$flag=true;
		}
		if(isset($_POST["prints_chk".$rs->row["id_parent"]]))
		{
			$flag=true;
		}

		$price=$rs->row["price"];
		if(isset($_POST["prints_price".$rs->row["id_parent"]]))
		{
			$price=(float)$_POST["prints_price".$rs->row["id_parent"]];
		}


		if($flag)
		{
			$sql="insert into prints_items (title,price,itemid,priority,printsid) values ('".$rs->row["title"]."',".$price.",".$id.",".$rs->row["priority"].",".$rs->row["id_parent"].")";
			$db->execute($sql);
		}
		
		$rs->movenext();
	}
}
//End. The function adds a new print to the database













//The function adds a new video to the database
function publication_video_add()
{
	global $site_servers;
	global $site_server_activ;
	global $pub_vars;
	global $dr;
	global $db;
	$id=0;

	//add to structure database
	$sql="insert into structure (id_parent,name,module_table) values (".$pub_vars["category"].",'".$pub_vars["title"]."',31)";
	$db->execute($sql);

	//define id
	$sql="select id from structure where name='".$pub_vars["title"]."' order by id desc";
	$dr->open($sql);
	$id=$dr->row['id'];

	//create new folder
	if(!file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id))
	{
		mkdir($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id);
		file_put_contents($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id."/index.html","");
	}

	if(!isset($pub_vars["featured"]))
	{
		$pub_vars["featured"]=0;
	}
	
	if(!isset($pub_vars["adult"]))
	{
		$pub_vars["adult"]=0;
	}
	
	if(isset($_POST["contacts"]))
	{
		$pub_vars["contacts"]=1;
	}
	else
	{
		$pub_vars["contacts"]=0;
	}
	
	if(isset($_POST["exclusive"]))
	{
		$pub_vars["exclusive"]=1;
	}
	else
	{
		$pub_vars["exclusive"]=0;
	}

	//add to video database
	$sql="insert into videos (id_parent,title,description,keywords,userid,published,viewed,data,author,content_type,downloaded,model,examination,server1,free,category2,category3,duration,format,ratio,rendering,frames,holder,usa,featured,google_x,google_y,server2,adult,rights_managed,contacts,exclusive,vote_like,vote_dislike) values (".$id.",'".$pub_vars["title"]."','".$pub_vars["description"]."','".$pub_vars["keywords"]."',".$pub_vars["userid"].",".$pub_vars["published"].",".$pub_vars["viewed"].",".$pub_vars["data"].",'".$pub_vars["author"]."','".$pub_vars["content_type"]."',".$pub_vars["downloaded"].",".$pub_vars["model"].",'".$pub_vars["examination"]."','".$pub_vars["server1"]."',".$pub_vars["free"].",".$pub_vars["category2"].",".$pub_vars["category3"].",'".$pub_vars["duration"]."','".$pub_vars["format"]."','".$pub_vars["ratio"]."','".$pub_vars["rendering"]."','".$pub_vars["frames"]."','".$pub_vars["holder"]."','".$pub_vars["usa"]."',".$pub_vars["featured"].",".$pub_vars["google_x"].",".$pub_vars["google_y"].",0,".$pub_vars["adult"].",0,".$pub_vars["contacts"].",".$pub_vars["exclusive"].",".(int)@$pub_vars["vote_like"].",".(int)@$pub_vars["vote_dislike"].")";
	$db->execute($sql);

	return $id;
}
//End. The function adds a new video to the database




//The function updates video into the database
function publication_video_update($id,$userid)
{
global $pub_vars;
global $dr;
global $db;


	$sql="select id_parent,userid from videos where id_parent=".$id." and userid=".$pub_vars["userid"];
	$dr->open($sql);
	if(!$dr->eof or $userid==0)
	{
		$sql="update structure set name='".$pub_vars["title"]."',id_parent=".$pub_vars["category"]." where id=".$id;
		$db->execute($sql);
	}

	$com="";
	if($userid!=0)
	{
		$com="  and (userid=".$pub_vars["userid"]." or author='".$pub_vars["author"]."')";
	}
	
	if(!isset($pub_vars["featured"]))
	{
		$pub_vars["featured"]=0;
	}
	
	if(!isset($pub_vars["adult"]))
	{
		$pub_vars["adult"]=0;
	}
	
	if(isset($_POST["contacts"]))
	{
		$pub_vars["contacts"]=1;
	}
	else
	{
		$pub_vars["contacts"]=0;
	}
	
	if(isset($_POST["exclusive"]))
	{
		$pub_vars["exclusive"]=1;
	}
	else
	{
		$pub_vars["exclusive"]=0;
	}

	$sql="update videos set title='".$pub_vars["title"]."',description='".$pub_vars["description"]."',keywords='".$pub_vars["keywords"]."',usa='".$pub_vars["usa"]."',duration='".$pub_vars["duration"]."',format='".$pub_vars["format"]."',ratio='".$pub_vars["ratio"]."',rendering='".$pub_vars["rendering"]."',frames='".$pub_vars["frames"]."',holder='".$pub_vars["holder"]."',model=".$pub_vars["model"].",free=".$pub_vars["free"].",category2=".$pub_vars["category2"].",category3=".$pub_vars["category3"].",downloaded=".$pub_vars["downloaded"].",viewed=".$pub_vars["viewed"].",data=".$pub_vars["data"].",content_type='".$pub_vars["content_type"]."',featured=".$pub_vars["featured"].",published=".$pub_vars["published"].",author='".$pub_vars["author"]."',google_x=".$pub_vars["google_x"].",google_y=".$pub_vars["google_y"].",adult=".$pub_vars["adult"].",contacts=".$pub_vars["contacts"].",exclusive=".$pub_vars["exclusive"].",vote_like=".(int)@$pub_vars["vote_like"].",vote_dislike=".(int)@$pub_vars["vote_dislike"]." where id_parent=".$id.$com;
	$db->execute($sql);



}
//End. The function updates video into the database



//The function uploads a video, audio, vector.
function publication_files_upload($id,$type)
{
	global $_POST;
	global $_FILES;
	global $_SESSION;
	global $ds;
	global $dr;
	global $rs;
	global $db;
	global $lvideo;
	global $lpreview;
	global $laudio;
	global $lpreviewaudio;
	global $lvector;
	global $site_servers;
	global $site_server_activ;
	global $folder;
	global $swait;
	global $global_settings;
	
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	
	if(isset($_SESSION["user_id"]))
	{
   		$tmp_folder="admin_".(int)$_SESSION["user_id"];
	}

	if(isset($_SESSION["people_id"]))
	{
   		$tmp_folder="user_".(int)$_SESSION["people_id"];
	}



	$server_id=$site_server_activ;

	if($type=="video")
	{
		$doc_table="videos";
	}

	if($type=="audio")
	{
		$doc_table="audio";
	}

	if($type=="vector")
	{
		$doc_table="vector";
	}
	
	$sql="select server1 from ".$doc_table." where id_parent=".(int)$id;
	$ds->open($sql);
	if(!$ds->eof)
	{
		$server_id=$ds->row["server1"];
	}


	$price_license="";
	$price_license_id=0;

	if((int)$_POST["license_type"]==0)
	{
		$price_license="royalty_free";

		$sql="update ".$doc_table." set rights_managed=0 where id_parent=".$id;
		$db->execute($sql);
	}
	else
	{
		$price_license="rights_managed";
	}

	if($price_license=="royalty_free")
	{
		$sql="select * from ".$type."_types order by priority";
		$ds->open($sql);
		while(!$ds->eof)
		{
			if($ds->row["shipped"]!=1)
			{
				$flag=false;
			
				if(($type=="video" and $global_settings["video_uploader"]=="usual uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="usual uploader") or ($type=="vector" and $global_settings["vector_uploader"]=="usual uploader"))
				{
					$uphoto=explode(",",str_replace(" ","",$ds->row["types"]));
					
					$file_fullname=result_file($_FILES["video_sale".$ds->row["id_parent"]]['name']);
					$file_name=get_file_info($file_fullname,"filename");
					$file_extention=get_file_info($file_fullname,"extention");
		
					for($i=0;$i<count($uphoto);$i++)
					{
						if(strtolower($uphoto[$i])==strtolower($file_extention))
						{
							$flag=true;
						}
					}
		
					if(preg_match("/text/i",$_FILES["video_sale".$ds->row["id_parent"]]['type']))
					{
						$flag=false;
					}
			
					if($type=="video")
					{
						if($_FILES["video_sale".$ds->row["id_parent"]]['size']==0 or $_FILES["video_sale".$ds->row["id_parent"]]['size']>1024*1024*$lvideo)
						{
							$flag=false;
						}
					}
					if($type=="audio")
					{
						if($_FILES["video_sale".$ds->row["id_parent"]]['size']==0 or $_FILES["video_sale".$ds->row["id_parent"]]['size']>1024*1024*$laudio)
						{
							$flag=false;
						}
					}
					if($type=="vector")
					{
						if($_FILES["video_sale".$ds->row["id_parent"]]['size']==0 or $_FILES["video_sale".$ds->row["id_parent"]]['size']>1024*1024*$lvector)
						{
							$flag=false;
						}
					}
				}
			
				if(($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader") or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
				{
					if(isset($_POST["file_sale".$ds->row["id_parent"]]) and $_POST["file_sale".$ds->row["id_parent"]]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST["file_sale".$ds->row["id_parent"]])))
					{
						$uphoto=explode(",",str_replace(" ","",$ds->row["types"]));
						$file_fullname=result_file($_POST["file_sale".$ds->row["id_parent"]]);
						$file_name=get_file_info($file_fullname,"filename");
						$file_extention=get_file_info($file_fullname,"extention");
					
						for($i=0;$i<count($uphoto);$i++)
						{
							if(strtolower($uphoto[$i])==strtolower($file_extention))
							{
								$flag=true;
							}
						}
					}
				}
				
				if($flag==true)
				{				 		
					if(($type=="video" and $global_settings["video_uploader"]=="usual uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="usual uploader") or ($type=="vector" and $global_settings["vector_uploader"]=="usual uploader"))
					{
						$videopath=site_root.$site_servers[$server_id]."/".$folder."/".$file_fullname;
						move_uploaded_file($_FILES["video_sale".$ds->row["id_parent"]]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$videopath);
					}
				
					if(($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader") or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
					{
						$videopath=site_root.$site_servers[$server_id]."/".$folder."/".$file_fullname;							
						@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".$file_fullname,$_SERVER["DOCUMENT_ROOT"].$videopath);
					}
				
					$swait=true;

					$sql="select id,id_parent,url,name,price from items where id_parent=".$id." and price_id=".$ds->row["id_parent"];
					$rs->open($sql);
					if($rs->eof)
					{
						$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$ds->row["title"]."','".$file_fullname."',".floatval($_POST[$type."_price".$ds->row["id_parent"]]).",".$ds->row["priority"].",0,".$ds->row["id_parent"].")";
						$db->execute($sql);
					}
					else
					{
						$sql="update items set name='".$ds->row["title"]."',url='".$file_fullname."',price=".floatval($_POST[$type."_price".$ds->row["id_parent"]])." where id_parent=".$id." and price_id=".$ds->row["id_parent"];
						$db->execute($sql);
					}
					
					//Synchronize related prices
					if($ds->row["thesame"]>0)
					{
						$sql="select * from ".$type."_types where id_parent<>".$ds->row["id_parent"]." and thesame=".$ds->row["thesame"]." order by priority";
						$dp->open($sql);
						while(!$dp->eof)
						{
							$sql="select id,id_parent,url,name,price from items where id_parent=".$id." and price_id=".$dp->row["id_parent"];
							$dt->open($sql);
							if($dt->eof)
							{
								$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$dp->row["title"]."','".$file_fullname."',".floatval($_POST[$type."_price".$dp->row["id_parent"]]).",".$dp->row["priority"].",0,".$dp->row["id_parent"].")";
								$db->execute($sql);
							}
							else
							{
								$sql="update items set name='".$dp->row["title"]."',url='".$file_fullname."',price=".floatval($_POST[$type."_price".$dp->row["id_parent"]])." where id_parent=".$id." and price_id=".$dp->row["id_parent"];
								$db->execute($sql);
							}
								
							$dp->movenext();
						}
					}
					//End. Synchronize related prices
				}
			}
			else
			{
				//Shipped
				if(isset($_POST[$type."_chk".$ds->row["id_parent"]]))
				{	
					$sql="select id,id_parent,url,name,price from items where id_parent=".$id." and price_id=".$ds->row["id_parent"];
					$rs->open($sql);
					if($rs->eof)
					{
						$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$ds->row["title"]."','',".floatval($_POST[$type."_price".$ds->row["id_parent"]]).",".$ds->row["priority"].",1,".$ds->row["id_parent"].")";
						$db->execute($sql);
						$swait=true;
					}
					else
					{
						$sql="update items set name='".$ds->row["title"]."',price=".floatval($_POST[$type."_price".$ds->row["id_parent"]])." where id_parent=".$id." and price_id=".$ds->row["id_parent"];
						$db->execute($sql);
					}
				}
				//End. Shipped
			}
			$ds->movenext();
		}
	}

	if($price_license=="rights_managed")
	{
		$sql="select formats,id,price,title from rights_managed where id=".(int)@$_POST["rights_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag=false;
			
			if(($type=="video" and $global_settings["video_uploader"]=="usual uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="usual uploader") or ($type=="vector" and $global_settings["vector_uploader"]=="usual uploader"))
			{
				$uphoto=explode(",",str_replace(" ","",$ds->row["formats"]));
				
				$file_fullname=result_file($_FILES["video_rights"]['name']);
				$file_name=get_file_info($file_fullname,"filename");
				$file_extention=get_file_info($file_fullname,"extention");
		
				for($i=0;$i<count($uphoto);$i++)
				{
					if(strtolower($uphoto[$i])==strtolower($file_extention))
					{
						$flag=true;
					}
				}
		
				if(preg_match("/text/i",$_FILES["video_rights"]['type']))
				{
					$flag=false;
				}
		
				if($_FILES["video_rights"]['size']==0 and $_FILES["video_rights"]['size']>1024*1024*$lvideo)
				{
					$flag=false;
				}
			}
		
			if(($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader") or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
			{
				if(isset($_POST["file_sale100"]) and $_POST["file_sale100"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST["file_sale100"])))
				{
					$uphoto=explode(",",str_replace(" ","",$ds->row["formats"]));
					$file_fullname=result_file($_POST["file_sale100"]);
					$file_name=get_file_info($file_fullname,"filename");
					$file_extention=get_file_info($file_fullname,"extention");
					
					for($i=0;$i<count($uphoto);$i++)
					{
						if(strtolower($uphoto[$i])==strtolower($file_extention))
						{
							$flag=true;
						}
					}
				}
			}
				
			if($flag==true)
			{
				if(($type=="video" and $global_settings["video_uploader"]=="usual uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="usual uploader") or ($type=="vector" and $global_settings["vector_uploader"]=="usual uploader"))
				{
					$videopath=site_root.$site_servers[$server_id]."/".$folder."/".$file_fullname;
					move_uploaded_file($_FILES["video_rights"]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$videopath);
				}
				
				if(($type=="video" and $global_settings["video_uploader"]=="jquery uploader") or ($type=="audio" and $global_settings["audio_uploader"]=="jquery uploader") or ($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader"))
				{
					$videopath=site_root.$site_servers[$server_id]."/".$folder."/".$file_fullname;							
					@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".$file_fullname,$_SERVER["DOCUMENT_ROOT"].$videopath);
				}
				
				$swait=true;
				
				if($type=="video")
				{
					$sql="update videos set rights_managed=".(int)@$_POST["rights_id"]." where id_parent=".$id;
				}
				if($type=="audio")
				{
					$sql="update audio set rights_managed=".(int)@$_POST["rights_id"]." where id_parent=".$id;
				}
				if($type=="vector")
				{
					$sql="update vector set rights_managed=".(int)@$_POST["rights_id"]." where id_parent=".$id;
				}
				$db->execute($sql);

				$sql="select id,id_parent,url,name,price from items where id_parent=".$id." and price_id=".$ds->row["id"];
				$rs->open($sql);
				
				if($rs->eof)
				{
					$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$ds->row["title"]."','".$file_fullname."',".$ds->row["price"].",0,0,".$ds->row["id"].")";
					$db->execute($sql);
				}
				else
				{
					$sql="update items set url='".$file_fullname."' where id_parent=".$id." and price_id=".$ds->row["id"];
					$db->execute($sql);
				}
			}
		}
	}


	//Upload video previews for usual uploader
	if(!$global_settings["ffmpeg"] and $type=="video")
	{
		if($global_settings["video_uploader"]=="usual uploader")
		{
			//upload video preview
			$mass_preview=array("preview","preview_rights");
			foreach ($mass_preview as $key => $value)
			{
				if(isset($_FILES[$value]['name']))
				{
					$file_fullname=result_file($_FILES[$value]['name']);
					$file_name=get_file_info($file_fullname,"filename");
					$file_extention=get_file_info($file_fullname,"extention");

					if((strtolower($file_extention)=="flv" or strtolower($file_extention)=="wmv" or strtolower($file_extention)=="mov" or strtolower($file_extention)=="mp4") and !preg_match("/text/i",$_FILES[$value]['type']))
					{
						if($_FILES[$value]['size']>0 and $_FILES[$value]['size']<1024*1024*$lpreview)
						{
							$vp=site_root.$site_servers[$server_id]."/".$folder."/thumb.".$file_extention;
							move_uploaded_file($_FILES[$value]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$vp);
			
							$swait=true;
						}
					}
				}
			}
		}
	}	
	//End. Upload video previews for usual uploader



	//Upload audio previews for usual uploader
	if(!$global_settings["sox"] and $type=="audio")
	{
		if($global_settings["audio_uploader"]=="usual uploader")
		{
			$mass_preview=array("preview","preview_rights");
			foreach ($mass_preview as $key => $value)
			{
				if(isset($_FILES[$value]['name']))
				{
					$file_fullname=result_file($_FILES[$value]['name']);
					$file_name=get_file_info($file_fullname,"filename");
					$file_extention=get_file_info($file_fullname,"extention");

					if(strtolower($file_extention)=="mp3" and !preg_match("/text/i",$_FILES[$value]['type']))
					{
						if($_FILES[$value]['size']>0 and $_FILES[$value]['size']<1024*1024*$lpreview)
						{
							$vp=site_root.$site_servers[$server_id]."/".$folder."/thumb.".$file_extention;
							move_uploaded_file($_FILES[$value]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$vp);
			
							$swait=true;
						}
					}
				}
			}
		}
	}	
	//End. Upload audio previews for usual uploader



	//Upload video and audio jpg previews for usual uploader
	if((!$global_settings["ffmpeg"] and $type=="video") or $type=="audio")
	{
		if(($global_settings["video_uploader"]=="usual uploader" and $type=="video") or ($global_settings["audio_uploader"]=="usual uploader" and $type=="audio"))
		{
			//upload photo preview
			$mass_preview2=array("preview2","preview2_rights");
			foreach ($mass_preview2 as $key => $value)
			{
				if(isset($_FILES[$value]['name']))
				{
					$file_fullname=result_file($_FILES[$value]['name']);
					$file_name=get_file_info($file_fullname,"filename");
					$file_extention=get_file_info($file_fullname,"extention");

					if((strtolower($file_extention)=="jpg" or strtolower($file_extention)=="jpeg") and !preg_match("/text/i",$_FILES[$value]['type']))
					{
						if($_FILES[$value]['size']>0 and $_FILES[$value]['size']<2048*1024)
						{									
							$vp=site_root.$site_servers[$server_id]."/".$folder."/thumb.".$file_extention;			
							$vp_big=site_root.$site_servers[$server_id]."/".$folder."/thumb100.".$file_extention;
						
							move_uploaded_file($_FILES[$value]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$vp);
							copy($_SERVER["DOCUMENT_ROOT"].$vp,$_SERVER["DOCUMENT_ROOT"].$vp_big);
				
							photo_resize($_SERVER["DOCUMENT_ROOT"].$vp,$_SERVER["DOCUMENT_ROOT"].$vp,1);
							photo_resize($_SERVER["DOCUMENT_ROOT"].$vp_big,$_SERVER["DOCUMENT_ROOT"].$vp_big,2);
						}
					}
				}
			}
		}
	}
	//End. Upload video and audio previews for usual uploader




	//Upload vector jpg and zip previews for usual uploader
	if($global_settings["vector_uploader"]=="usual uploader" and $type=="vector")
	{
			//upload photo preview
			$mass_preview2=array("preview2","preview2_rights");
			foreach ($mass_preview2 as $key => $value)
			{
				if(isset($_FILES[$value]['name']))
				{
					$file_fullname=result_file($_FILES[$value]['name']);
					$file_name=get_file_info($file_fullname,"filename");
					$file_extention=get_file_info($file_fullname,"extention");

					if((strtolower($file_extention)=="jpg" or strtolower($file_extention)=="jpeg") and !preg_match("/text/i",$_FILES[$value]['type']))
					{
						if($_FILES[$value]['size']>0 and $_FILES[$value]['size']<2048*1024)
						{									
							$vp=site_root.$site_servers[$server_id]."/".$folder."/1.".$file_extention;
							move_uploaded_file($_FILES[$value]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$vp);
							$swait=true;

							photo_resize($_SERVER["DOCUMENT_ROOT"].$vp,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb1.jpg",1);

							photo_resize($_SERVER["DOCUMENT_ROOT"].$vp,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb2.jpg",2);

							publication_watermark_add($id,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb2.jpg");

							publication_iptc_add($id,$_SERVER["DOCUMENT_ROOT"].$vp);
			
							copy($_SERVER["DOCUMENT_ROOT"].$vp,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb_original.jpg");
	
							@unlink($_SERVER["DOCUMENT_ROOT"].$vp);	
						}
					}
				}
				
				//Upload zip preview
				if(strtolower($file_extention)=="zip"  and !preg_match("/text/i",$_FILES[$value]['type']))
				{
					if($_FILES[$value]['size']>0 and $_FILES[$value]['size']<2048*1024)
					{
						move_uploaded_file($_FILES[$value]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/temp.".$file_extention);
						publication_zip_preview(site_root.$site_servers[$server_id]."/".$folder."/temp.".$file_extention);
						$swait=true;
					}
				}
				//End Upload zip preview
			}
			
			//upload swf preview
			$mass_preview2=array("preview3","preview3_rights");
			foreach ($mass_preview2 as $key => $value)
			{
				if(isset($_FILES[$value]['name']))
				{
					$file_fullname=result_file($_FILES[$value]['name']);
					$file_name=get_file_info($file_fullname,"filename");
					$file_extention=get_file_info($file_fullname,"extention");
					
					if(strtolower($file_extention)=="swf" and !preg_match("/text/i",$_FILES[$value]['type']))
					{
						if($_FILES[$value]['size']>0 and $_FILES[$value]['size']<2048*1024)
						{									
							$vpf=site_root.$site_servers[$server_id]."/".$folder."/thumb.swf";
							move_uploaded_file($_FILES[$value]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$vpf);
							$swait=true;
						}
					}
				}
			}			
	}
	//End. Upload vector jpg and zip previews for usual uploader




	//Upload video previews for jquery uploader
	if(!$global_settings["ffmpeg"] and $type=="video")
	{	
		if($global_settings["video_uploader"]=="jquery uploader")
		{
			if($price_license=="rights_managed")
			{
				$file_video_name="file_sale2";
				$file_photo_name="file_sale3";
			}
			else
			{
				$file_video_name="file_sale0";
				$file_photo_name="file_sale1";
			}
		
			//upload video preview
			if(isset($_POST[$file_video_name]) and $_POST[$file_video_name]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$file_video_name])))
			{	
				$file_fullname=result_file($_POST[$file_video_name]);
				$file_name=get_file_info($file_fullname,"filename");
				$file_extention=get_file_info($file_fullname,"extention");
			
				if((strtolower($file_extention)=="flv" or strtolower($file_extention)=="wmv" or strtolower($file_extention)=="mov" or strtolower($file_extention)=="mp4"))			
				{
					$vp=site_root.$site_servers[$server_id]."/".$folder."/thumb.".$file_extention;
					@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$file_video_name]),$_SERVER["DOCUMENT_ROOT"].$vp);
				
					$swait=true;
				}
			}
		}
	}	
	//End. Upload video previews for jquery uploader
	
	
	//Upload audio previews for jquery uploader
	if($type=="audio")
	{
		if($global_settings["audio_uploader"]=="jquery uploader")
		{
			if($price_license=="rights_managed")
			{
				$file_video_name="file_sale2";
				$file_photo_name="file_sale3";
			}
			else
			{
				$file_video_name="file_sale0";
				$file_photo_name="file_sale1";
			}
		}
	}
	
	if(!$global_settings["sox"] and $type=="audio")
	{	
		if($global_settings["audio_uploader"]=="jquery uploader")
		{		
			//upload video preview
			if(isset($_POST[$file_video_name]) and $_POST[$file_video_name]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$file_video_name])))
			{	
				$file_fullname=result_file($_POST[$file_video_name]);
				$file_name=get_file_info($file_fullname,"filename");
				$file_extention=get_file_info($file_fullname,"extention");
			
				if(strtolower($file_extention)=="mp3")			
				{
					$vp=site_root.$site_servers[$server_id]."/".$folder."/thumb.".$file_extention;
					@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$file_video_name]),$_SERVER["DOCUMENT_ROOT"].$vp);
				
					$swait=true;
				}
			}
		}
	}	
	//End. Upload video previews for jquery uploader


	//Upload video and audio jpg previews for jquery uploader
	if((!$global_settings["ffmpeg"] and $type=="video") or $type=="audio")
	{		
		if(($global_settings["video_uploader"]=="jquery uploader" and $type=="video") or ($global_settings["audio_uploader"]=="jquery uploader" and $type=="audio"))
		{	
			//upload photo preview
			if(isset($_POST[$file_photo_name]) and $_POST[$file_photo_name]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$file_photo_name])))
			{	
				$file_fullname=result_file($_POST[$file_photo_name]);
				$file_name=get_file_info($file_fullname,"filename");
				$file_extention=get_file_info($file_fullname,"extention");
			
				if((strtolower($file_extention)=="jpg" or strtolower($file_extention)=="jpeg"))			
				{
					$vp=site_root.$site_servers[$server_id]."/".$folder."/thumb.".$file_extention;				
					$vp_big=site_root.$site_servers[$server_id]."/".$folder."/thumb100.".$file_extention;
				
					@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$file_photo_name]),$_SERVER["DOCUMENT_ROOT"].$vp);
					@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$file_photo_name]),$_SERVER["DOCUMENT_ROOT"].$vp_big);
				
					photo_resize($_SERVER["DOCUMENT_ROOT"].$vp,$_SERVER["DOCUMENT_ROOT"].$vp,1);
					photo_resize($_SERVER["DOCUMENT_ROOT"].$vp_big,$_SERVER["DOCUMENT_ROOT"].$vp_big,2);
				
					$swait=true;
				}
			}
		}
	}
	//End. Upload video and audio jpg previews for jquery uploader
	
	
	
	//Upload vector jpg and zip previews for jquery uploader
	if($type=="vector" and $global_settings["vector_uploader"]=="jquery uploader")
	{		
		$mass_preview2=array("file_sale0","file_sale1","file_sale2","file_sale3");
		foreach ($mass_preview2 as $key => $value)
		{
			//upload photo preview
			if(isset($_POST[$value]) and $_POST[$value]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$value])))
			{	
				$file_fullname=result_file($_POST[$value]);
				$file_name=get_file_info($file_fullname,"filename");
				$file_extention=get_file_info($file_fullname,"extention");
			
				if($value=="file_sale0" or $value=="file_sale2")
				{
					if((strtolower($file_extention)=="jpg" or strtolower($file_extention)=="jpeg"))			
					{
						$vp=site_root.$site_servers[$server_id]."/".$folder."/thumb1.".$file_extention;				
						$vp_big=site_root.$site_servers[$server_id]."/".$folder."/thumb2.".$file_extention;
				
						@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$value]),$_SERVER["DOCUMENT_ROOT"].$vp);
						@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$value]),$_SERVER["DOCUMENT_ROOT"].$vp_big);
						@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$value]),$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb_original.jpg");
						
						publication_iptc_add($id,$_SERVER["DOCUMENT_ROOT"].$vp);
						
						photo_resize($_SERVER["DOCUMENT_ROOT"].$vp,$_SERVER["DOCUMENT_ROOT"].$vp,1);
						photo_resize($_SERVER["DOCUMENT_ROOT"].$vp_big,$_SERVER["DOCUMENT_ROOT"].$vp_big,2);
						
						publication_watermark_add($id,$_SERVER["DOCUMENT_ROOT"].$vp_big);
	
						$swait=true;
					}
					
					if(strtolower($file_extention)=="zip")			
					{
						copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$value]),$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".result_file($_POST[$value]));
						publication_zip_preview(site_root.$site_servers[$server_id]."/".$folder."/".result_file($_POST[$value]));
						$swait=true;
					}
				}
			
				if($value=="file_sale1" or $value=="file_sale3")
				{
					if(strtolower($file_extention)=="swf")			
					{
						$vpf=site_root.$site_servers[$server_id]."/".$folder."/thumb.swf";
						@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST[$value]),$_SERVER["DOCUMENT_ROOT"].$vpf);
						$swait=true;
					}
				}
			}
		}
	}
	//End. Upload vector jpg and zip previews for jquery uploader
	
	


	//Generate video previews by ffmpeg
	if($type=="video" and $global_settings["ffmpeg"] and $global_settings["ffmpeg_cron"])
	{
		$sql="select filename1 from filestorage_files where id_parent=".$id;
		$ds->open($sql);
		if($ds->eof)
		{
			if(!file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb.flv") and !file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb.mp4"))
			{
				$sql="insert into ffmpeg_cron (id,data1,data2,generation) values (".$id.",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,".(int)@$_POST["generation"].")";
				$db->execute($sql);
		
				$sql="update videos set published=0 where id_parent=".$id;
				$db->execute($sql);
			}
		}
	}
	
	if($type=="video" and $global_settings["ffmpeg"] and !$global_settings["ffmpeg_cron"])
	{
		//FFMPEG generation
		if($price_license=="royalty_free")
		{
			//Define a source file for generation
			$generation_file="";
			$generation_file2="";
			$sql="select * from video_types order by priority";
			$ds->open($sql);
			while(!$ds->eof)
			{
				if($global_settings["video_uploader"]=="usual uploader")
				{
					if($_FILES["video_sale".$ds->row["id_parent"]]['name']!="")
					{
						if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_sale".$ds->row["id_parent"]]['name']))
						{
							$generation_file2=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_sale".$ds->row["id_parent"]]['name'];

							if(isset($_POST["generation"]) and (int)$_POST["generation"]==$ds->row["id_parent"])
							{
								$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_sale".$ds->row["id_parent"]]['name'];
							}
						}
					}
				}
			
				if($global_settings["video_uploader"]=="jquery uploader")
				{
					if(isset($_POST["file_sale".$ds->row["id_parent"]]) and $_POST["file_sale".$ds->row["id_parent"]]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST["file_sale".$ds->row["id_parent"]])))
					{
						$generation_file2=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".result_file($_POST["file_sale".$ds->row["id_parent"]]);

						if(isset($_POST["generation"]) and (int)$_POST["generation"]==$ds->row["id_parent"])
						{
							$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".result_file($_POST["file_sale".$ds->row["id_parent"]]);
						}
					}
				}

				$ds->movenext();
			}
		
			if($generation_file=="")
			{
				$generation_file=$generation_file2;
			}
	
			if($generation_file!="")
			{
				$fln=generate_flv($generation_file,0,0);
			}
		}
	
		if($price_license=="rights_managed")
		{
			if($global_settings["video_uploader"]=="usual uploader")
			{
				if($_FILES["video_rights"]['name']!="")
				{
					if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_rights"]['name']))
					{
						$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_rights"]['name'];
				
						$fln=generate_flv($generation_file,0,0);
					}
				}
			}
		
			if($global_settings["video_uploader"]=="jquery uploader")
			{
				if(isset($_POST["file_sale100"]) and $_POST["file_sale100"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST["file_sale100"])))
				{
					$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".result_file($_POST["file_sale100"]);

					$fln=generate_flv($generation_file,0,0);
				}
			}	
		}
	}
	//End. Generate video previews by ffmpeg
	
	
	//Generate audio previews by sox
	if($type=="audio" and $global_settings["sox"])
	{
		//Sox generation
		if($price_license=="royalty_free")
		{
			//Define a source file for generation
			$generation_file="";
			$generation_file2="";
			$sql="select * from audio_types order by priority";
			$ds->open($sql);
			while(!$ds->eof)
			{
				if($global_settings["audio_uploader"]=="usual uploader")
				{
					if($_FILES["video_sale".$ds->row["id_parent"]]['name']!="")
					{
						if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_sale".$ds->row["id_parent"]]['name']))
						{
							$generation_file2=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_sale".$ds->row["id_parent"]]['name'];

							if(isset($_POST["generation"]) and (int)$_POST["generation"]==$ds->row["id_parent"])
							{
								$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_sale".$ds->row["id_parent"]]['name'];
							}
						}
					}
				}
			
				if($global_settings["audio_uploader"]=="jquery uploader")
				{
					if(isset($_POST["file_sale".$ds->row["id_parent"]]) and $_POST["file_sale".$ds->row["id_parent"]]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST["file_sale".$ds->row["id_parent"]])))
					{
						$generation_file2=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".result_file($_POST["file_sale".$ds->row["id_parent"]]);

						if(isset($_POST["generation"]) and (int)$_POST["generation"]==$ds->row["id_parent"])
						{
							$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".result_file($_POST["file_sale".$ds->row["id_parent"]]);
						}
					}
				}

				$ds->movenext();
			}
		
			if($generation_file=="")
			{
				$generation_file=$generation_file2;
			}
	
			if($generation_file!="")
			{
				generate_mp3($generation_file,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb.mp3");
			}
		}
	
		if($price_license=="rights_managed")
		{
			if($global_settings["audio_uploader"]=="usual uploader")
			{
				if($_FILES["video_rights"]['name']!="")
				{
					if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_rights"]['name']))
					{
						$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".$_FILES["video_rights"]['name'];
				
						generate_mp3($generation_file,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb.mp3");
					}
				}
			}
		
			if($global_settings["audio_uploader"]=="jquery uploader")
			{
				if(isset($_POST["file_sale100"]) and $_POST["file_sale100"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST["file_sale100"])))
				{
					$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/".result_file($_POST["file_sale100"]);

					generate_mp3($generation_file,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb.mp3");
				}
			}	
		}
	}
	//End. Generate audio previews by sox


}
//End. The function uploads a video, audio, vector.















//The function adds a new audio to the database
function publication_audio_add()
{
	global $site_servers;
	global $site_server_activ;
	global $pub_vars;
	global $dr;
	global $db;
	$id=0;

	//add to structure database
	$sql="insert into structure (id_parent,name,module_table) values (".$pub_vars["category"].",'".$pub_vars["title"]."',52)";
	$db->execute($sql);

	//define id
	$sql="select id from structure where name='".$pub_vars["title"]."' order by id desc";
	$dr->open($sql);
	$id=$dr->row['id'];

	//create new folder
	if(!file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id))
	{
		mkdir($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id);
		file_put_contents($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id."/index.html","");
	}

	if(!isset($pub_vars["featured"]))
	{
		$pub_vars["featured"]=0;
	}
	
	if(!isset($pub_vars["adult"]))
	{
		$pub_vars["adult"]=0;
	}
	
	if(isset($_POST["contacts"]))
	{
		$pub_vars["contacts"]=1;
	}
	else
	{
		$pub_vars["contacts"]=0;
	}
	
	if(isset($_POST["exclusive"]))
	{
		$pub_vars["exclusive"]=1;
	}
	else
	{
		$pub_vars["exclusive"]=0;
	}

	//add to audio database
	$sql="insert into audio (id_parent,title,description,keywords,userid,published,viewed,data,author,content_type,downloaded,model,examination,server1,free,category2,category3,duration,source,format,holder,featured,google_x,google_y,server2,adult,rights_managed,contacts,exclusive,vote_like,vote_dislike) values (".$id.",'".$pub_vars["title"]."','".$pub_vars["description"]."','".$pub_vars["keywords"]."',".$pub_vars["userid"].",".$pub_vars["published"].",".$pub_vars["viewed"].",".$pub_vars["data"].",'".$pub_vars["author"]."','".$pub_vars["content_type"]."',".$pub_vars["downloaded"].",".$pub_vars["model"].",'".$pub_vars["examination"]."','".$pub_vars["server1"]."',".$pub_vars["free"].",".$pub_vars["category2"].",".$pub_vars["category3"].",'".$pub_vars["duration"]."','".$pub_vars["source"]."','".$pub_vars["format"]."','".$pub_vars["holder"]."',".$pub_vars["featured"].",".$pub_vars["google_x"].",".$pub_vars["google_y"].",0,".$pub_vars["adult"].",0,".$pub_vars["contacts"].",".$pub_vars["exclusive"].",".(int)@$pub_vars["vote_like"].",".(int)@$pub_vars["vote_dislike"].")";
	$db->execute($sql);

	return $id;
}
//End. The function adds a new audio to the database










//The function updates audio into the database
function publication_audio_update($id,$userid)
{
global $pub_vars;
global $dr;
global $db;


	$sql="select id_parent,userid from audio where id_parent=".$id." and userid=".$pub_vars["userid"];
	$dr->open($sql);
	if(!$dr->eof or $userid==0)
	{
		$sql="update structure set name='".$pub_vars["title"]."',id_parent=".$pub_vars["category"]." where id=".$id;
		$db->execute($sql);
	}

	$com="";
	if($userid!=0)
	{
		$com="  and (userid=".$pub_vars["userid"]." or author='".$pub_vars["author"]."')";
	}
	
	if(!isset($pub_vars["featured"]))
	{
		$pub_vars["featured"]=0;
	}

	if(!isset($pub_vars["adult"]))
	{
		$pub_vars["adult"]=0;
	}
	
	if(isset($_POST["contacts"]))
	{
		$pub_vars["contacts"]=1;
	}
	else
	{
		$pub_vars["contacts"]=0;
	}
	
	if(isset($_POST["exclusive"]))
	{
		$pub_vars["exclusive"]=1;
	}
	else
	{
		$pub_vars["exclusive"]=0;
	}

	$sql="update audio set title='".$pub_vars["title"]."',description='".$pub_vars["description"]."',keywords='".$pub_vars["keywords"]."',duration='".$pub_vars["duration"]."',format='".$pub_vars["format"]."',source='".$pub_vars["source"]."',holder='".$pub_vars["holder"]."',model=".$pub_vars["model"].",free=".$pub_vars["free"].",category2=".$pub_vars["category2"].",category3=".$pub_vars["category3"].",downloaded=".$pub_vars["downloaded"].",viewed=".$pub_vars["viewed"].",data=".$pub_vars["data"].",content_type='".$pub_vars["content_type"]."',featured=".$pub_vars["featured"].",published=".$pub_vars["published"].",author='".$pub_vars["author"]."',google_x=".$pub_vars["google_x"].",google_y=".$pub_vars["google_y"].",adult=".$pub_vars["adult"].",contacts=".$pub_vars["contacts"].",exclusive=".$pub_vars["exclusive"].",vote_like=".(int)@$pub_vars["vote_like"].",vote_dislike=".(int)@$pub_vars["vote_dislike"]." where id_parent=".$id.$com;
	$db->execute($sql);



}
//End. The function updates audio into the database











//The function adds a new vector to the database
function publication_vector_add()
{
	global $site_servers;
	global $site_server_activ;
	global $pub_vars;
	global $dr;
	global $db;
	$id=0;

	//add to structure database
	$sql="insert into structure (id_parent,name,module_table) values (".$pub_vars["category"].",'".$pub_vars["title"]."',53)";
	$db->execute($sql);

	//define id
	$sql="select id from structure where name='".$pub_vars["title"]."' order by id desc";
	$dr->open($sql);
	$id=$dr->row['id'];

	//create new folder
	if(!file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id))
	{
		mkdir($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id);
		file_put_contents($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$id."/index.html","");
	}

	if(!isset($pub_vars["featured"]))
	{
		$pub_vars["featured"]=0;
	}
	
	if(!isset($pub_vars["adult"]))
	{
		$pub_vars["adult"]=0;
	}
	
	if(isset($_POST["contacts"]))
	{
		$pub_vars["contacts"]=1;
	}
	else
	{
		$pub_vars["contacts"]=0;
	}
	
	if(isset($_POST["exclusive"]))
	{
		$pub_vars["exclusive"]=1;
	}
	else
	{
		$pub_vars["exclusive"]=0;
	}

	//add to vector database
	$sql="insert into vector (id_parent,title,description,keywords,userid,published,viewed,data,author,content_type,downloaded,model,examination,server1,free,category2,category3,flash_version,script_version,flash_width,flash_height,featured,google_x,google_y,server2,adult,rights_managed,contacts,exclusive,vote_like,vote_dislike) values (".$id.",'".$pub_vars["title"]."','".$pub_vars["description"]."','".$pub_vars["keywords"]."',".$pub_vars["userid"].",".$pub_vars["published"].",".$pub_vars["viewed"].",".$pub_vars["data"].",'".$pub_vars["author"]."','".$pub_vars["content_type"]."',".$pub_vars["downloaded"].",".$pub_vars["model"].",'".$pub_vars["examination"]."','".$pub_vars["server1"]."',".$pub_vars["free"].",".$pub_vars["category2"].",".$pub_vars["category3"].",'".$pub_vars["flash_version"]."','".$pub_vars["script_version"]."',".$pub_vars["flash_width"].",".$pub_vars["flash_height"].",".$pub_vars["featured"].",".$pub_vars["google_x"].",".$pub_vars["google_y"].",0,".$pub_vars["adult"].",0,".$pub_vars["contacts"].",".$pub_vars["exclusive"].",".(int)@$pub_vars["vote_like"].",".(int)@$pub_vars["vote_dislike"].")";
	$db->execute($sql);
	
	return $id;
}
//End. The function adds a new vector to the database










//The function updates vector into the database
function publication_vector_update($id,$userid)
{
global $pub_vars;
global $dr;
global $db;


	$sql="select id_parent,userid from vector where id_parent=".$id." and userid=".$pub_vars["userid"];
	$dr->open($sql);
	if(!$dr->eof or $userid==0)
	{
		$sql="update structure set name='".$pub_vars["title"]."',id_parent=".$pub_vars["category"]." where id=".$id;
		$db->execute($sql);
	}

	$com="";
	if($userid!=0)
	{
		$com="  and (userid=".$pub_vars["userid"]." or author='".$pub_vars["author"]."')";
	}
	
	if(!isset($pub_vars["featured"]))
	{
		$pub_vars["featured"]=0;
	}
	
	if(!isset($pub_vars["adult"]))
	{
		$pub_vars["adult"]=0;
	}
	
	if(isset($_POST["contacts"]))
	{
		$pub_vars["contacts"]=1;
	}
	else
	{
		$pub_vars["contacts"]=0;
	}
	
	if(isset($_POST["exclusive"]))
	{
		$pub_vars["exclusive"]=1;
	}
	else
	{
		$pub_vars["exclusive"]=0;
	}

	$sql="update vector set title='".$pub_vars["title"]."',description='".$pub_vars["description"]."',keywords='".$pub_vars["keywords"]."',flash_version='".$pub_vars["flash_version"]."',script_version='".$pub_vars["script_version"]."',flash_width=".$pub_vars["flash_width"].",flash_height=".$pub_vars["flash_height"].",model=".$pub_vars["model"].",free=".$pub_vars["free"].",category2=".$pub_vars["category2"].",category3=".$pub_vars["category3"].",downloaded=".$pub_vars["downloaded"].",viewed=".$pub_vars["viewed"].",data=".$pub_vars["data"].",content_type='".$pub_vars["content_type"]."',featured=".$pub_vars["featured"].",published=".$pub_vars["published"].",author='".$pub_vars["author"]."',google_x=".$pub_vars["google_x"].",google_y=".$pub_vars["google_y"].",adult=".$pub_vars["adult"].",contacts=".$pub_vars["contacts"].",exclusive=".$pub_vars["exclusive"].",vote_like=".(int)@$pub_vars["vote_like"].",vote_dislike=".(int)@$pub_vars["vote_dislike"]." where id_parent=".$id.$com;
	$db->execute($sql);



}
//End. The function updates vector into the database







//The function updates photo into the database
function publication_photo_update($id,$userid)
{
global $pub_vars;
global $dr;
global $db;


	$sql="select id_parent,userid from photos where id_parent=".$id." and userid=".$pub_vars["userid"];
	$dr->open($sql);
	if(!$dr->eof or $userid==0)
	{
		$sql="update structure set name='".$pub_vars["title"]."',id_parent=".$pub_vars["category"]." where id=".$id;
		$db->execute($sql);
	}

	$com="";
	if($userid!=0)
	{
		$com="  and (userid=".$pub_vars["userid"]." or author='".$pub_vars["author"]."')";
	}
	
	if(!isset($pub_vars["featured"]))
	{
		$pub_vars["featured"]=0;
	}
	
	if(!isset($pub_vars["color"]))
	{
		$pub_vars["color"]="";
	}
	
	if(isset($_POST["editorial"]))
	{
		$pub_vars["editorial"]=1;
	}
	else
	{
		$pub_vars["editorial"]=0;
	}
	
	if(isset($_POST["adult"]))
	{
		$pub_vars["adult"]=1;
	}
	else
	{
		$pub_vars["adult"]=0;
	}
	
	if(isset($_POST["contacts"]))
	{
		$pub_vars["contacts"]=1;
	}
	else
	{
		$pub_vars["contacts"]=0;
	}
	
	if(isset($_POST["exclusive"]))
	{
		$pub_vars["exclusive"]=1;
	}
	else
	{
		$pub_vars["exclusive"]=0;
	}
	
	$license_type=0;
	if(isset($_POST["license_type"]) and (int)$_POST["license_type"]>0)
	{
		if(isset($_POST["rights_id"]))
		{
			$license_type=(int)$_POST["rights_id"];
		}
	}
	else
	{
		$license_type=0;
	}

	$sql="update photos set title='".$pub_vars["title"]."',description='".$pub_vars["description"]."',keywords='".$pub_vars["keywords"]."',model=".$pub_vars["model"].",free=".$pub_vars["free"].",category2=".$pub_vars["category2"].",category3=".$pub_vars["category3"].",downloaded=".$pub_vars["downloaded"].",viewed=".$pub_vars["viewed"].",data=".$pub_vars["data"].",content_type='".$pub_vars["content_type"]."',featured=".$pub_vars["featured"].",published=".$pub_vars["published"].",author='".$pub_vars["author"]."',google_x=".$pub_vars["google_x"].",google_y=".$pub_vars["google_y"].",color='".$pub_vars["color"]."',editorial=".$pub_vars["editorial"].",adult=".$pub_vars["adult"].",rights_managed=".$license_type.",contacts=".$pub_vars["contacts"].",exclusive=".$pub_vars["exclusive"].",vote_like=".(int)@$pub_vars["vote_like"].",vote_dislike=".(int)@$pub_vars["vote_dislike"]." where id_parent=".$id.$com;
	$db->execute($sql);



}
//End. The function updates photo into the database








//The function makes previews from zip archive of jpg photos
function publication_zip_preview($zarc)
{
global $_POST;
global $_FILES;
global $site_servers;
global $site_server_activ;
global $folder;


$vp=$zarc;

		$archive = new PclZip($_SERVER["DOCUMENT_ROOT"].$vp);
		if ($archive->extract(PCLZIP_OPT_PATH,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder) == true) 
		{
			$afiles=array();

  			$dir = opendir ($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder);
  			while ($file = readdir ($dir)) 
  			{
				if($file <> "." && $file <> "..")
    			{
					if (preg_match("/.jpg$|.jpeg$/i",$file) and !preg_match("/thumb/i",$file)) 
					{ 
						$file=result_file($file);
						$afiles[count($afiles)]=$file;
					}
					else
					{
						@unlink($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$file);
					}
					if (preg_match("/php/i",$file)) 
					{
						@unlink($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$file);
					}
    			}
 			 }
  			closedir ($dir);
			@unlink($_SERVER["DOCUMENT_ROOT"].$vp);

			sort ($afiles);
			reset ($afiles);	

			for($n=0;$n<count($afiles);$n++)
			{
				$file=$afiles[$n];

				photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$file,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumbs".$n.".jpg",1);
				photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$file,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumbz".$n.".jpg",2);
				
				publication_watermark_add(0,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumbz".$n.".jpg");

				if($n==0)
				{				
					photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$file,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb1.jpg",1);
					photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$file,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb2.jpg",2);
					publication_watermark_add(0,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb2.jpg");
				}

				@unlink($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$file);
			}

		}

}
//End. The function makes previews from zip archive of jpg photos









//The function uploads a photo.
function publication_photo_upload($id)
{
	global $_POST;
	global $_FILES;
	global $site_servers;
	global $site_server_activ;
	global $folder;
	global $swait;
	global $db;
	global $ds;
	global $global_settings;
	
	if(isset($_SESSION["user_id"]))
	{
   		$tmp_folder="admin_".(int)$_SESSION["user_id"];
	}

	
	if(isset($_SESSION["people_id"]))
	{
   		$tmp_folder="user_".(int)$_SESSION["people_id"];
	}
	
	


	$server_id=$site_server_activ;
	$sql="select server1 from photos where id_parent=".(int)$id;
	$ds->open($sql);
	if(!$ds->eof)
	{
		$server_id=$ds->row["server1"];
	}

	$price_license="";
	$price_license_id=0;

	if((int)$_POST["license_type"]==0)
	{
		$price_license="royalty_free";	
		$photo_formats=array();
		$sql="select id,photo_type from photos_formats where enabled=1 order by id";
		$ds->open($sql);
		while(!$ds->eof)
		{
			$photo[$ds->row["id"]]="";
			$photo_formats[$ds->row["id"]]=$ds->row["photo_type"];
			$flag[$ds->row["id"]]=true;
			$ds->movenext();
		}
	}
	else
	{
		$photo[0]="";
		$flag[0]=true;	
		$photo_formats[0]="jpg";
		$price_license="rights_managed";
	}

	if($price_license=="royalty_free")
	{
		foreach ($photo_formats as $key => $value) 
		{
			if($global_settings["photo_uploader"]=="usual uploader")
			{
				$file_fullname[$key]=result_file($_FILES["photo_".$value]['name']);
				$file_name[$key]=get_file_info($file_fullname[$key],"filename");
				$file_extention[$key]=get_file_info($file_fullname[$key],"extention");
				
				$_FILES["photo_".$value]['name']=result_file($_FILES["photo_".$value]['name']);
	
				if(preg_match("/text/i",$_FILES["photo_".$value]["type"]))
				{
					$flag[$key]=false;
				}

				if(!preg_match("/\.jpg$|\.jpeg$|\.gif$|\.png$|\.raw$|\.eps$|\.tif$|\.tiff$/i",$file_fullname[$key]))
				{
					$flag[$key]=false;
				}
			
				if($_FILES["photo_".$value]["size"]<=0)
				{
					$flag[$key]=false;
				}
			
				if($flag[$key]==true)
				{
					$photo[$key]=site_root.$site_servers[$server_id]."/".$folder."/".$file_fullname[$key];
					move_uploaded_file($_FILES["photo_".$value]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$photo[$key]);
					$swait=true;
				}
			}
		
			if($global_settings["photo_uploader"]=="jquery uploader")
			{
				if(isset($_POST["file_sale".$key]) and $_POST["file_sale".$key]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST["file_sale".$key])))
				{
					$file_fullname[$key]=result_file($_POST["file_sale".$key]);
					$file_name[$key]=get_file_info($file_fullname[$key],"filename");
					$file_extention[$key]=get_file_info($file_fullname[$key],"extention");
				
					if(strtolower($file_extention[$key])!="jpg" and strtolower($file_extention[$key])!="jpeg" and strtolower($file_extention[$key])!="png" and strtolower($file_extention[$key])!="gif" and strtolower($file_extention[$key])!="raw" and strtolower($file_extention[$key])!="tif" and strtolower($file_extention[$key])!="tiff" and strtolower($file_extention[$key])!="eps")
					{
						$flag[$key]=false;
					}
					else
					{
						$photo[$key]=site_root.$site_servers[$server_id]."/".$folder."/".$file_fullname[$key];							
						@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".$file_fullname[$key],$_SERVER["DOCUMENT_ROOT"].$photo[$key]);
					}
				}
				else
				{
					$flag[$key]=false;
				}
			}
		
			if(@$flag[$key]==true and @$photo[$key]!="")
			{		
				//If reupload
				if(strtolower($file_extention[$key])!="jpg" or strtolower($file_extention[$key])!="jpeg")
				{
					$sql="update items set url='".$file_fullname[$key]."' where id_parent=".$id;
					$db->execute($sql);
				}
			
				$sql="update photos set rights_managed=0,url_".$value."='".$file_fullname[$key]."' where id_parent=".$id;
				$db->execute($sql);

				$swait=true;
			}
		}
	}

	if($price_license=="rights_managed")
	{
		if($global_settings["photo_uploader"]=="usual uploader")
		{
			$file_fullname[0]=result_file($_FILES["video_rights"]['name']);
	
			if(preg_match("/text/i",$_FILES["video_rights"]["type"]))
			{
				$flag[0]=false;
			}

			if(!preg_match("/\.jpg$|\.jpeg$|\.gif$|\.png$|\.raw$|\.tif$|\.tiff$|\.eps$/i",$file_fullname[0]))
			{
				$flag[0]=false;
			}
			
			if($_FILES["video_rights"]["size"]<=0)
			{
				$flag[0]=false;
			}
			
			if($flag[0]==true)
			{
				$photo[0]=site_root.$site_servers[$server_id]."/".$folder."/".$file_fullname[0];
				move_uploaded_file($_FILES["video_rights"]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$photo[0]);
				$swait=true;
			}
		}
		
		if($global_settings["photo_uploader"]=="jquery uploader")
		{
			if(isset($_POST["file_sale100"]) and $_POST["file_sale100"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".result_file($_POST["file_sale100"])))
			{
				$file_fullname[0]=result_file($_POST["file_sale100"]);
				$file_name[0]=get_file_info($file_fullname[0],"filename");
				$file_extention[0]=get_file_info($file_fullname[0],"extention");
				
				if(strtolower($file_extention[0])!="jpg" and strtolower($file_extention[0])!="jpeg" and strtolower($file_extention[0])!="gif" and strtolower($file_extention[0])!="png" and strtolower($file_extention[0])!="raw" and strtolower($file_extention[0])!="tif" and strtolower($file_extention[0])!="tiff" and strtolower($file_extention[0])!="eps")
				{
					$flag[0]=false;
				}
				else
				{
					$photo[0]=site_root.$site_servers[$server_id]."/".$folder."/".$file_fullname[0];							
					@copy($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".$file_fullname[0],$_SERVER["DOCUMENT_ROOT"].$photo[0]);
				}
			}
			else
			{
				$flag[0]=false;
			}
		}

		if(@$flag[0]==true and @$photo[0]!="")
		{		
			//If reupload
			$sql="update items set url='".$file_fullname[0]."' where id_parent=".$id;
			$db->execute($sql);
			
			$ext=strtolower($file_extention[0]);
			if($ext=="jpeg"){$ext="jpg";}
			if($ext=="tif"){$ext="tiff";}
			
			$sql="update photos set rights_managed=".(int)@$_POST["rights_id"].",url_".$ext."='".$file_fullname[0]."' where id_parent=".$id;
			$db->execute($sql);
			
			$price_license_id=(int)@$_POST["rights_id"];
		
			$swait=true;
		}
	}

	foreach ($photo as $key => $value) 
	{
		if($photo[$key]!="" and $file_fullname[$key]!="")
		{
			//create different dimensions
			if($price_license=="royalty_free")
			{
				publication_photo_sizes_add($id,$file_fullname[$key],false,"royalty_free",0);
		
				//IPTC support
				if(isset($_POST["photo_iptc"]))
				{
					publication_iptc_add($id,$_SERVER["DOCUMENT_ROOT"].$photo[$key]);
				}
			}
	
			if($price_license=="rights_managed")
			{
				publication_photo_sizes_add($id,$file_fullname[$key],false,"rights_managed",$price_license_id);
		
				//IPTC support
				if(isset($_POST["photo_iptc_rights"]))
				{
					publication_iptc_add($id,$_SERVER["DOCUMENT_ROOT"].$photo[$key]);
				}
			}

			//Create thumbs
			if(strtolower($file_extention[$key])=="jpg" or strtolower($file_extention[$key])=="jpeg" or strtolower($file_extention[$key])=="gif" or strtolower($file_extention[$key])=="png")
			{
				photo_resize($_SERVER["DOCUMENT_ROOT"].$photo[$key],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb1.jpg",1);
				photo_resize($_SERVER["DOCUMENT_ROOT"].$photo[$key],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb2.jpg",2);
				publication_watermark_add($id,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb2.jpg");	
			}
			if((strtolower($file_extention[$key])=="png" or strtolower($file_extention[$key])=="gif") and (!file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb1.jpg") or !file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb2.jpg")))
			{
				photo_resize($_SERVER["DOCUMENT_ROOT"].$photo[$key],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb1.jpg",1);
				photo_resize($_SERVER["DOCUMENT_ROOT"].$photo[$key],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb2.jpg",2);
				publication_watermark_add($id,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server_id]."/".$folder."/thumb2.jpg");	
			}
		}
	}
}
//End. The function uploads a photo.







//Search all included for the category
function get_included_publications($t_id)
{
global $db;
$dp = new TMySQLQuery;
$dp->connection = $db;
global $nlimit;
global $res_id;
global $res_module;
global $res_category;
global $res_photo;
global $res_video;
global $res_audio;
global $res_vector;

$sql="select id,module_table from structure where id_parent=".$t_id;
$dp->open($sql);
	while(!$dp->eof)
	{
	
		if($dp->row["module_table"]==34)
		{
			$res_id[]=$dp->row["id"];
			$res_module[]=$dp->row["module_table"];
			$res_category++;
		}
		
		if($dp->row["module_table"]==30)
		{
			$res_id[]=$dp->row["id"];
			$res_module[]=$dp->row["module_table"];
			$res_photo++;
		}
		
		if($dp->row["module_table"]==31)
		{
			$res_id[]=$dp->row["id"];
			$res_module[]=$dp->row["module_table"];
			$res_video++;
		}
		
		if($dp->row["module_table"]==52)
		{
			$res_id[]=$dp->row["id"];
			$res_module[]=$dp->row["module_table"];
			$res_audio++;
		}
		
		if($dp->row["module_table"]==53)
		{
			$res_id[]=$dp->row["id"];
			$res_module[]=$dp->row["module_table"];
			$res_vector++;
		}
	
		if($nlimit<10000)
		{
			get_included_publications($dp->row["id"]);
		}
		
		$nlimit++;
		$dp->movenext();
	}
}
//End. Search all included for the category



//Search all included sybcategories for the category
function get_included_categories($t_id)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	global $nlimit;
	global $res_id;

	$sql="select id,module_table from structure where id_parent=".$t_id;
	$dp->open($sql);
	while(!$dp->eof)
	{
	
		if($dp->row["module_table"]==34)
		{
			$res_id[]=$dp->row["id"];
		}
	
		if($nlimit<1000)
		{
			get_included_categories($dp->row["id"]);
		}
		
		$nlimit++;
		$dp->movenext();
	}
}
//End. Search all included subcategories for the category




//The function returns javascript code for the jquery uploader
function get_jquery_uploader_code($filelimit,$filetypes)
{
	$jquery_code="<script src='".site_root."/admin/plugins/jquery-file-upload-9.5.7/js/vendor/jquery.ui.widget.js'></script>
				<script src='".site_root."/admin/plugins/jquery-file-upload-9.5.7/js/jquery.iframe-transport.js'></script>
				<script src='".site_root."/admin/plugins/jquery-file-upload-9.5.7/js/jquery.fileupload.js'></script>
				<script src='".site_root."/admin/plugins/jquery-file-upload-9.5.7/js/jquery.fileupload.js'></script>
				<script src='".site_root."/admin/plugins/jquery-file-upload-9.5.7/js/jquery.fileupload-process.js'></script>
				<script src='".site_root."/admin/plugins/jquery-file-upload-9.5.7/js/jquery.fileupload-validate.js'></script>
				<link rel='stylesheet' href='".site_root."/admin/plugins/jquery-file-upload-9.5.7/css/jquery.fileupload.css'>
    			<script>
    			
    			bar_number=0;
    			files_massive=new Array();
    			files_massive2=new Array();
    			
    			
    			function change_bar(x)
    			{
    				bar_number=x;
    			}
    			
				/*jslint unparam: true */
				/*global window, $ */
				$(function () {
  				  	'use strict';
   				 	// Change this to the location of your server-side upload handler:
   					var url = '".site_root."/members/upload_files_jquery.php';
   					
   					
   				 	$('#uploadform').fileupload({
     			  		url: url,
     			   		dataType: 'json',
     			   		maxFileSize: ".($filelimit*1000*1000).", 
     			  		acceptFileTypes: /(\.|\/)(".$filetypes.")$/i,
     			  		
     			  		send: function (e, data) {
     			  			$.each(data.files, function (index, file) {
          						var flag_exist=false;
          						for(var i=0;i<files_massive.length;i++)
          						{
          							if(files_massive[i]==file.name)
          							{
          								flag_exist=true;
          							}
          						}
          						if(flag_exist==false)
          						{
          							files_massive[files_massive.length]=file.name;
          							files_massive2[files_massive2.length]=bar_number;
          						}
        					});
     			  		},

    			    	done: function (e, data) {
    	        			$.each(data.result.files, function (index, file) {  
                				for(var i=0;i<files_massive.length;i++)
          						{
                					if(files_massive[i]==file.name)
          							{
                						document.getElementById('file_sale'+files_massive2[i]).value=file.name;
                						document.getElementById('files'+files_massive2[i]).innerHTML=file.name+' [ '+(file.size/(1024*1024)).toFixed(3)+'MB ]';
                					}
                				}
            				});
        				},
        				
        				progress: function (e, data) {
        						var filess = data.files;
								var filenam = filess[0].name;
        				
                				for(var i=0;i<files_massive.length;i++)
          						{
            						if(files_massive[i]==filenam)
          							{
            							var progress = parseInt(data.loaded / data.total * 100, 10);
            							$('#bar'+files_massive2[i]).css('width',progress + '%');
            							document.getElementById('files'+files_massive2[i]).innerHTML=filenam +' '+(data.loaded/(1024*1024)).toFixed(2)+' from ' +(data.total/(1024*1024)).toFixed(2) +'MB ('+progress+ '%)';
            						}
            					}
        				}
        				
    			}).prop('disabled', !$.support.fileInput)
        			.parent().addClass($.support.fileInput ? undefined : 'disabled');
			});
	</script>";
	
	return $jquery_code;
}



//The function returns preview form for video, audio and vector files
function get_preview_form($type,$flag_rights_managed)
{
	global $lvideo;
	global $lpreviewvideo;
	global $laudio;
	global $lpreviewaudio;
	global $lvector;
	global $global_settings;
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	
	$res="";
	
	if($flag_rights_managed)
	{
		$number_field0=2;
		$number_field1=3;
		$rights_field="_rights";
	}
	else
	{
		$number_field0=0;
		$number_field1=1;
		$rights_field="";
	}
		
	if($type=="video")
	{

		$res.="<tr class='snd'><td colspan='4'>(".word_lang("size")." < ".$lvideo."Mb.)</td></tr>";

		if(!$global_settings["ffmpeg"])
		{
			$res.="<tr><th colspan=4><b>".word_lang("preview")." ".word_lang("video").":</b></th></tr><tr><td colspan='4'>";
		
			if($global_settings["video_uploader"]=="usual uploader")
			{
				$res.="<input class='ibox form-control' name='preview".$rights_field."' type='file' style='width:400px'>";
			}		
		
			if($global_settings["video_uploader"]=="jquery uploader")
			{	
				$res.="<span class=\"btn btn-success fileinput-button\">
        					<i class=\"icon-plus icon-white\" style=\"float:left\"></i> ".word_lang("select files")."...
        					<!-- The file input field used as target for the file upload widget -->
        					<input type=\"file\" name=\"files[]\" onClick='change_bar(".$number_field0.")'>
        					<input type=\"hidden\" id='file_sale".$number_field0."' name='file_sale".$number_field0."' value=''>
    					</span>
    					<div id=\"progress\" class=\"progress progress-striped active\" style=\"margin-top:15px\">
        					<div id=\"bar".$number_field0."\" class=\"bar\" style=\"width:0%\"></div>
    					</div>
    					<div id=\"files".$number_field0."\" class=\"files\"></div>
    					";
				
			}	
		
			$res.="<span class='smalltext'>(*.flv,*.wmv. ,*.mp4,*.mov. ".word_lang("size")." < ".$lpreviewvideo."Mb.)</span></td></tr>";
		

			$res.="<tr><th colspan=4><b>".word_lang("preview")." ".word_lang("photo").":</th></tr><tr><td colspan='4'>";
		
			if($global_settings["video_uploader"]=="usual uploader")
			{		
				$res.="<input class='ibox form-control' name='preview2".$rights_field."' type='file' style='width:400px'>";
			}
		
			if($global_settings["video_uploader"]=="jquery uploader")
			{	
				$res.="<span class=\"btn btn-success fileinput-button\">
        					<i class=\"icon-plus icon-white\" style=\"float:left\"></i> ".word_lang("select files")."...
        					<!-- The file input field used as target for the file upload widget -->
        					<input type=\"file\" name=\"files[]\" onClick='change_bar(".$number_field1.")'>
        					<input type=\"hidden\" id='file_sale".$number_field1."' name='file_sale".$number_field1."' value=''>
    					</span>
    					<div id=\"progress\" class=\"progress progress-striped active\" style=\"margin-top:15px\">
        					<div id=\"bar".$number_field1."\" class=\"bar\" style=\"width:0%\"></div>
    					</div>
    					<div id=\"files".$number_field1."\" class=\"files\"></div>
    					";
				
			}	
		
			$res.="<br><span class='smalltext'>(*.jpg,*.jpeg)</span></td></tr>";
		}
		else
		{
			if(!$flag_rights_managed)
			{			
				$res.="<tr><th colspan=4><b>".word_lang("Generate video preview from").":</b></th></tr><tr><td colspan='4'>
				<select name='generation' style='width:400px' class='ibox form-control'>";
			
				$sql="select id_parent,name from licenses order by priority";
				$dp->open($sql);
				while(!$dp->eof)
				{
					$sql="select id_parent,title from video_types where license = " .$dp->row["id_parent"]." and shipped<>1 order by priority";
					$dt->open($sql);
					while(!$dt->eof)
					{
						$res.="<option value='".$dt->row["id_parent"]."'>".$dp->row["name"]." - ".$dt->row["title"]."</option>";

						$dt->movenext();
					}
				
					$dp->movenext();
				}

				$res.="</select>
				</td></tr>";
			}
		}
	}
		
		if($type=="audio")
		{	
			if($global_settings["sox"]==0)
			{			
				$res.="<tr class='snd'><td colspan=4><span class='smalltext'>(".word_lang("size")." < ".$laudio."Mb.)</span></td></tr>";

				$res.="<tr><th colspan=4><b>".word_lang("preview")." ".word_lang("audio").":</b></th></tr><tr><td colspan='4'>";
		
				if($global_settings["audio_uploader"]=="usual uploader")
				{
					$res.="<input class='ibox form-control' name='preview".$rights_field."' type='file' style='width:400px'>";
				}		
		
				if($global_settings["audio_uploader"]=="jquery uploader")
				{	
					$res.="<span class=\"btn btn-success fileinput-button\">
        					<i class=\"icon-plus icon-white\" style=\"float:left\"></i> ".word_lang("select files")."...
        					<!-- The file input field used as target for the file upload widget -->
        					<input type=\"file\" name=\"files[]\" onClick='change_bar(".$number_field0.")'>
        					<input type=\"hidden\" id='file_sale".$number_field0."' name='file_sale".$number_field0."' value=''>
    					</span>
    					<div id=\"progress\" class=\"progress progress-striped active\" style=\"margin-top:15px\">
        					<div id=\"bar".$number_field0."\" class=\"bar\" style=\"width:0%\"></div>
    					</div>
    					<div id=\"files".$number_field0."\" class=\"files\"></div>
    					";				
				}	
		
				$res.="<span class='smalltext'>(*.mp3 ".word_lang("size")." < ".$lpreviewaudio."Mb.)</span></td></tr>";
			}
			else
			{
				if(!$flag_rights_managed)
				{			
					$res.="<tr><th colspan=4><b>".word_lang("Generate *.mp3 audio preview from").":</b></th></tr><tr><td colspan='4'>
				<select name='generation' style='width:400px' class='ibox form-control'>";
			
					$sql="select id_parent,name from licenses order by priority";
					$dp->open($sql);
					while(!$dp->eof)
					{
						$sql="select id_parent,title from audio_types where license = " .$dp->row["id_parent"]." and shipped<>1 order by priority";
						$dt->open($sql);
						while(!$dt->eof)
						{
							$res.="<option value='".$dt->row["id_parent"]."'>".$dp->row["name"]." - ".$dt->row["title"]."</option>";

							$dt->movenext();
						}			
						$dp->movenext();
					}

					$res.="</select>
					</td></tr>";
				}
			}
		
			$res.="<tr><th colspan=4><b>".word_lang("preview")." ".word_lang("photo").":</th></tr><tr><td colspan='4'>";
		
			if($global_settings["audio_uploader"]=="usual uploader")
			{		
				$res.="<input class='ibox form-control' name='preview2".$rights_field."' type='file' style='width:400px'>";
			}
		
			if($global_settings["audio_uploader"]=="jquery uploader")
			{	
				$res.="<span class=\"btn btn-success fileinput-button\">
        					<i class=\"icon-plus icon-white\" style=\"float:left\"></i> ".word_lang("select files")."...
        					<!-- The file input field used as target for the file upload widget -->
        					<input type=\"file\" name=\"files[]\" onClick='change_bar(".$number_field1.")'>
        					<input type=\"hidden\" id='file_sale".$number_field1."' name='file_sale".$number_field1."' value=''>
    					</span>
    					<div id=\"progress\" class=\"progress progress-striped active\" style=\"margin-top:15px\">
        					<div id=\"bar".$number_field1."\" class=\"bar\" style=\"width:0%\"></div>
    					</div>
    					<div id=\"files".$number_field1."\" class=\"files\"></div>
    					";				
			}	
		
			$res.="<br><span class='smalltext'>(*.jpg,*.jpeg)</span></td></tr>";
		}
		
		
		if($type=="vector")
		{	
			$res.="<tr class='snd'><td colspan=4><span class='smalltext'>(".word_lang("size")." < ".$lvector."Mb.)</span></td></tr>";
		
			$res.="<tr><th colspan=4><b>".word_lang("preview")." ".word_lang("photo").":</th></tr><tr><td colspan='4'>";
		
			if($global_settings["vector_uploader"]=="usual uploader")
			{		
				$res.="<input class='ibox form-control' name='preview2".$rights_field."' type='file' style='width:400px'>";
			}
		
			if($global_settings["vector_uploader"]=="jquery uploader")
			{	
				$res.="<span class=\"btn btn-success fileinput-button\">
        					<i class=\"icon-plus icon-white\" style=\"float:left\"></i> ".word_lang("select files")."...
        					<!-- The file input field used as target for the file upload widget -->
        					<input type=\"file\" name=\"files[]\" onClick='change_bar(".$number_field0.")'>
        					<input type=\"hidden\" id='file_sale".$number_field0."' name='file_sale".$number_field0."' value=''>
    					</span>
    					<div id=\"progress\" class=\"progress progress-striped active\" style=\"margin-top:15px\">
        					<div id=\"bar".$number_field0."\" class=\"bar\" style=\"width:0%\"></div>
    					</div>
    					<div id=\"files".$number_field0."\" class=\"files\"></div>
    					";				
			}	
		
			$res.="<br><span class='smalltext'>(*.jpg,*.jpeg or *.zip archive of jpgs)</span></td></tr>";
					
			if($global_settings["flash"])
			{
				$res.="<tr><th colspan=4><b>".word_lang("flash").":</th></tr><tr><td colspan='4'>";
				
				if($global_settings["vector_uploader"]=="usual uploader")
				{		
					$res.="<input class='ibox form-control' name='preview3".$rights_field."' type='file' style='width:400px'>";
				}
		
				if($global_settings["vector_uploader"]=="jquery uploader")
				{	
					$res.="<span class=\"btn btn-success fileinput-button\">
        					<i class=\"icon-plus icon-white\" style=\"float:left\"></i> ".word_lang("select files")."...
        					<!-- The file input field used as target for the file upload widget -->
        					<input type=\"file\" name=\"files[]\" onClick='change_bar(".$number_field1.")'>
        					<input type=\"hidden\" id='file_sale".$number_field1."' name='file_sale".$number_field1."' value=''>
    					</span>
    					<div id=\"progress\" class=\"progress progress-striped active\" style=\"margin-top:15px\">
        					<div id=\"bar".$number_field1."\" class=\"bar\" style=\"width:0%\"></div>
    					</div>
    					<div id=\"files".$number_field1."\" class=\"files\"></div>
    					";				
				}	
				
				$res.="<br><span class='smalltext'>(*.swf)</span></td></tr>";
			}
		}
		

		
		return $res;
}
//End. The function returns preview form for video, audio and vector files



//The function removes all files and subfolders from the temp folder
function remove_files_from_folder($tmp_folder)
{
	global $DOCUMENT_ROOT;
	
	if(file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder) and $tmp_folder!="")
	{
		$dir = opendir ($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder);
		while ($file = readdir ($dir)) 
		{
			if($file <> "." && $file <> "..")
			{
				if(is_file($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file)) 
				{ 
					@unlink($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file);
				}
				if(is_dir($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file))
				{
					$dir2 = opendir ($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file);
					while ($file2 = readdir ($dir2)) 
					{
						if($file2 <> "." && $file2 <> "..")
						{
							if(is_file($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file."/".$file2)) 
							{ 
								@unlink($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file."/".$file2);
							}
						}
					}
					rmdir($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$file); 
				}
			}
		}
	}
}
//End. The function removes all files and subfolders from the temp folder
?>