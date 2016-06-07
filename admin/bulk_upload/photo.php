<?
//Check access
admin_panel_access("catalog_bulkupload");
?>
<?if(!defined("site_root")){exit();}?>




<script language="javascript">
	function anketa(name,pole,nado)
	{
		this.name=name;
		this.pole=pole;
		this.nado=nado;
	}


	ms=new Array(new anketa('<?=word_lang("category")?>','category',true),new anketa('<?=word_lang("author")?>','author',true))


	function check()
	{
		with(document.uploadform)
		{
			flag=true
			mat="<?=word_lang("please insert")?>: "
			mat2=""
			for(i=0;i<ms.length;i++)
			{
				dd=eval(ms[i].pole+".value");
				if(ms[i].nado==true){if(dd==""){flag=false;mat+="\""+ms[i].name+"\","}}

				if(ms[i].pole=="email")
				{
					mm=dd
					mr=mm.split("@")
					if(mr.length==1){mat2+=" <?=word_lang("incorrect")?> "+ms[i].name+".";flag=false}
				}
			}

			if(flag==false)
			{
				mat=mat.substring(0,mat.length-1)+"."
				if(mat!="<?=word_lang("please insert")?>:.")
				{
					alert(mat+mat2)
				}
				else
				{
					alert(mat2)
				}
				return false
			}
		}
	}
</script>






<form method="post" action="photo_upload.php" name="uploadform" onsubmit="return check();">


<div class="subheader"><?=word_lang("photos")?></div>
<div class="subheader_text">

<p><b>You should preupload</b> *.jpg files here <b><?=site_root?><?=$global_settings["photopreupload"]?></b> on FTP</p>

<p>If you want to upload <b>additional formats</b> (*.gif,*.png,*.raw,*.tiff,*.eps) the files must have the same names as *.jpg.</p>


<?
$photo_formats=array();
$sql="select id,photo_type from photos_formats where enabled=1 and photo_type<>'jpg' order by id";
$dr->open($sql);
while(!$dr->eof)
{
	$photo_formats[$dr->row["id"]]=$dr->row["photo_type"];
	$dr->movenext();
}



$n=0;
$afiles=array();

  $dir = opendir ($DOCUMENT_ROOT.$global_settings["photopreupload"]);
  while ($file = readdir ($dir)) 
  {

    if($file <> "." && $file <> "..")
    {
	if (preg_match("/.jpg$|.jpeg$/i",$file)) 
	{ 
	$afiles[count($afiles)]=$file;
	$n++;
	}
    }
  }
  closedir ($dir);
sort ($afiles);
reset ($afiles);
?>
<script language="javascript">

function selectall()
{
	if(document.getElementById("allphotos").checked)
	{
		for(i=0;i<<?=count($afiles)?>;i++)
		{
			$(".photocheck").attr("checked",true);
		}
	}
	else
	{
		for(i=0;i<<?=count($afiles)?>;i++)
		{
			$(".photocheck").attr("checked",false);
		}
	}
}

</script>

<?

$page_mass=array(10,20,30,40,50,100,0);


?>




<table border="0" cellpadding="0" cellspacing="0" class="bulk_header">
<tr>
<td style="padding-left:6px" class="gray">
There are <b><?=$n?></b> files
</td>
<td style="padding-left:6px;text-align:right" class="gray">
<select onChange="location.href=this.value" style="padding:1px;margin:5px">
<?
for($i=0;$i<count($page_mass);$i++)
{
	$sel="";
	if($page_mass[$i]==(int)$_SESSION["bulk_page"])
	{
		$sel="selected";
	}
	?>
	<option value="index.php?d=1&quantity=<?=$page_mass[$i]?>" <?=$sel?>>
	<?
	if($page_mass[$i]==0)
	{
		echo(word_lang("all files"));
	}
	else
	{
		echo($page_mass[$i]);
	}
	?>
	</option>
	<?
}
?>
</select>
</td>
<td align="right">
<?
if((int)$_SESSION["bulk_page"]!=0)
{
	echo(paging($n,$str,(int)$_SESSION["bulk_page"],7,"index.php","&d=1"));
	$kolvo=(int)$_SESSION["bulk_page"];
}
else
{
	$kolvo=100000000000;
}
?>
</td>
</tr>
</table>


<div class="table_t" style="margin-left:-6px;"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="5" cellspacing="1" class='table_admin table table-striped'>
<tr>
<th><input type="checkbox" id="allphotos" checked onClick="selectall()"></th>
<th><?=word_lang("preview")?></th>
<th><?=word_lang("IPTC info")?>: <?=word_lang("title")?>/<?=word_lang("description")?>/<?=word_lang("keywords")?></th>
</tr>
<?
for($i=0;$i<count($afiles);$i++)
{
	if($i>$kolvo*($str-1)-1 and $i<$kolvo*$str)
	{
		$title="";
		$description="";
		$keywords="";

		$size = getimagesize ($_SERVER["DOCUMENT_ROOT"].site_root.$global_settings["photopreupload"].$afiles[$i],$info);
		if(isset ($info["APP13"]))
		{
			$iptc = iptcparse ($info["APP13"]);

			//Title
			if(isset($iptc["2#005"][0]) and $iptc["2#005"][0]!="")
			{
				$title=$iptc["2#005"][0];
			}
			else
			{
				if(isset($iptc["2#105"][0]) and $iptc["2#105"][0]!="")
				{
					$title=$iptc["2#105"][0];
				}
			}
	
			//Description
			if(isset($iptc["2#120"][0]) and $iptc["2#120"][0]!="")
			{
				$description=$iptc["2#120"][0];
				
				/*
				if(isset($iptc["2#090"][0]) and $iptc["2#090"][0]!="")
				{
					$description.="\nCity: ".result($iptc["2#090"][0]);
				}
		
				if(isset($iptc["2#095"][0]) and $iptc["2#095"][0]!="")
				{
					$description.="\nState: ".result($iptc["2#095"][0]);
				}
		
				if(isset($iptc["2#101"][0]) and $iptc["2#101"][0]!="")
				{
					$description.="\nCountry: ".result($iptc["2#101"][0]);
				}
		
				if(isset($iptc["2#055"][0]) and $iptc["2#055"][0]!="")
				{		
					$date_created=result($iptc["2#055"][0]);
					if(strlen($date_created)==8)
					{
						$date_created=substr($date_created,4,2)."/".substr($date_created,6,2)."/".substr($date_created,0,4);
					}
			
					$description.="\nDate created: ".$date_created;
				}
				*/
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
					$keywords=$iptc_kw;
				}
			}
		}
		?>
		<tr valign="top" <?if(($i+1)%2==0){echo("class='snd'");}?>>
			<td><input name="f<?=$i?>" id="f<?=$i?>" type="checkbox" checked style="margin-top:3px" class="photocheck"></td>
			<td><img src="image.php?file=<?=$afiles[$i]?>"><input name="file<?=$i?>" type="hidden" value="<?=$afiles[$i]?>">
			<div style='margin-bottom:3px'><small><?=$afiles[$i]?></small></div>
			<?
			$filename=get_file_info($afiles[$i],"filename");
			foreach ($photo_formats as $key => $value) 
			{
				if($value=="tiff")
				{
					if(file_exists($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filename.".tif"))
					{
						?>
						<div style='margin-bottom:3px'><small><?=$filename.".tif"?></small></div>
						<?
					}
					if(file_exists($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filename.".tiff"))
					{
						?>
						<div style='margin-bottom:3px'><small><?=$filename.".tiff"?></small></div>
						<?
					}
				}
				else
				{
					if(file_exists($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filename.".".$value))
					{
						?>
						<div style='margin-bottom:3px'><small><?=$filename.".".$value?></small></div>
						<?
					}
				}
			}
			?>
			</td>
			<td>
			<div><input class='ft' type="text" name="title<?=$i?>" value="<?=$title?>" style="width:400px"></div>
			<div style="margin-top:3px"><textarea class='textarea' name="description<?=$i?>" style="width:400px;height:150px"><?=$description?></textarea></div>
			<div style="margin-top:3px"><textarea class='textarea' name="keywords<?=$i?>" style="width:400px;height:150px"><?=$keywords?></textarea></div>
			</td>
		</tr>
		<?
	}
}
?>
</table>
</div></div></div></div></div></div></div></div>

<br>
</div>


<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">


<div class="form_field">
	<span><b>Select category 1:</b></span>
	<select class="ft" name="category" style="width:300px">
		<option value=""></option>
		<?
		$itg="";
		$nlimit=0;
		buildmenu2(5,0,2,0);
		echo($itg);
		?>
	</select>
</div>


<div class="form_field">
	<span>Select category 2:</span>
	<select class="ft" name="category2" style="width:300px">
		<option value=""></option>
		<?
		echo($itg);
		?>
		</select>
</div>


<div class="form_field">
	<span>Select category 3:</span>
	<select class="ft" name="category3" style="width:300px;">
		<option value=""></option>
		<?
		echo($itg);
		?>
	</select>
</div>




<div class="form_field">
	<span><b>Select author:</b></span>
	<select class="ft" name="author" style="width:150px;margin-top:2px">
		<option value="">...</option>
		<?
		$sql="select login from users where utype='seller' or utype='common' order by login";
		$rs->open($sql);
		while(!$rs->eof)
		{
			?>
			<option value="<?=$rs->row["login"]?>"><?=$rs->row["login"]?></option>
			<?
			$rs->movenext();
		}
		?>
	</select>
</div>



<p><b>Remove</b> files from <?=$global_settings["photopreupload"]?> after the upload<br>
<input type="checkbox" name="remove" checked>



</div>







<?
if(!$global_settings["printsonly"])
{
?>
<div class="subheader"><?=word_lang("price")?></div>
<div class="subheader_text">
	<?
		if($global_settings["royalty_free"] and $global_settings["rights_managed"])
		{
			?>
				<script>
					function set_license(value)
					{
						if(value==1)
						{
							document.getElementById('box_license2').style.display='none';
							document.getElementById('box_license1').style.display='block';
						}
						else
						{
							document.getElementById('box_license2').style.display='block';
							document.getElementById('box_license1').style.display='none';
						}
					}
				</script>
				<input type="radio" name="license_type"  id="license_type1" value="0" checked onClick="set_license(1)">&nbsp;&nbsp;<label for='license_type1' style='display:inline;font-size:12px'><?=word_lang("royalty free")?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="license_type"  id="license_type2" value="1" onClick="set_license(2)">&nbsp;&nbsp;<label for='license_type2'  style='display:inline;font-size:12px'><?=word_lang("rights managed")?></label>
			<?
		}
		else
		{
			?>
			<input type="hidden" name="license_type" value="<?if(!$global_settings["royalty_free"]){echo(1);}else{echo(0);}?>">
			<?
		}
	?>
	<?$file_form=false;?>
	<?if($global_settings["royalty_free"]){?>
	<div id="box_license1" style="display:block;margin-top:20px">
	<div class="table_t" style="margin-left:-6px;"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
		<?=photo_upload_form(0,false)?>
	</div></div></div></div></div></div></div></div>
	</div>
	<?}?>
	
	<?if($global_settings["rights_managed"]){?>
	<div id="box_license2" style="display:<?if(!$global_settings["royalty_free"]){echo("block");}else{echo("none");}?>;margin-top:20px">
	<div class="table_t" style="margin-left:-6px;"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
		<?=rights_managed_upload_form("photo",1,0,false)?>
	</div></div></div></div></div></div></div></div>
	</div>
	<?}?>
</div>
<?
}
?>















<?if($global_settings["prints"]){?>
<div class="subheader"><?=word_lang("prints and products")?></div>
<div class="subheader_text">
	<div class="table_t" style="margin-left:-6px;"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<?=prints_upload_form()?>
	</div></div></div></div></div></div></div></div>
</div>
<?}?>




</p>


	<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">
			<input type="submit" class="btn btn-primary" value="<?=word_lang("upload")?>" style="margin-top:20px">
		</div>
	</div>
</form>