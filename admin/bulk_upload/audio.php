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
			if(ms[i].nado==true)
			{
				if(dd=="")
				{	
					flag=false;
					mat+="\""+ms[i].name+"\","
				}
			}

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
			alert(mat2)}
			return false
		}
	}
}






function mdefault(t)
{
	with(document.uploadform)
	{
		dd=eval(t+".value");
		for(i=0;i<<?=$global_settings["bulk_upload"]?>;i++)
		{
			rs=eval(t+i);
			rs.value=dd
		}
	}
}
</script>






<form method="post" action="audio_upload.php" name="uploadform" onsubmit="return check();">

<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">


<div class="form_field">
You should upload files here <b><?=surl?><?=site_root?><?=$global_settings["audiopreupload"]?></b> on FTP<br><br>

Photo previews: <b>*.jpg, *.jpeg</b><br>
Audio previews: <b>mp3</b>

</div>

<div class="form_field">
	<span><b><?=word_lang("category")?> 1:</b></span>
	<select class="ft" name="category" style="width:300px;">
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
	<span><?=word_lang("category")?> 2:</span>
	<select class="ft" name="category2" style="width:300px;">
	<option value=""></option>
	<?
		echo($itg);
	?>
	</select>
</div>

<div class="form_field">
	<span><?=word_lang("category")?> 3:</span>
	<select class="ft" name="category3" style="width:300px;">
		<option value=""></option>
		<?
		echo($itg);
		?>
	</select>
</div>



<div class="form_field">
	<span><b>Select author:</b></span>
	<select class="ft" name="author" style="width:150px">
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


<div class="form_field">
	<span><b><?=word_lang("license")?>:</b></span>
	<?
		if($global_settings["royalty_free"] and $global_settings["rights_managed"])
		{
			?>
				<script>
					function set_license(value)
					{
						if(value==1)
						{
							$('.box_license2').css('display','none');
							$('.box_license1').css('display','block');
						}
						else
						{
							$('.box_license2').css('display','block');
							$('.box_license1').css('display','none');
						}
					}
				</script>
				<input type="radio" name="license_type"  id="license_type1" value="0" checked onClick="set_license(1)">&nbsp;<label for='license_type1' style='display:inline;font-size:12px'><?=word_lang("royalty free")?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="license_type"  id="license_type2" value="1" onClick="set_license(2)">&nbsp;<label for='license_type2'  style='display:inline;font-size:12px'><?=word_lang("rights managed")?></label>
			<?
		}
		else
		{
			?>
			<input type="hidden" name="license_type" value="<?if(!$global_settings["royalty_free"]){echo(1);}else{echo(0);}?>">
			<?
		}
	?>
</div>





</div>
<div class="subheader"><?=word_lang("audio")?></div>
<div class="subheader_text">

<div class="table_t2" style="margin-left:-6px"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=7 cellspacing=1 class='table_admin table table-striped' style="width:100%">
<tr>
<th><b><?=word_lang("file")?></b></th>
<th><b><?=word_lang("title")?>/<?=word_lang("description")?>/<?=word_lang("keywords")?><?if($global_settings["model"]){echo("/".word_lang("model property release"));}?></b></th>
<th><b><?=word_lang("settings")?></b></th>
</tr>




<tr valign="top">
<td>Default meanings for the fields:</td>
<td><div><input type="text" name="title" onkeyup="mdefault('title')" value="" style="width:400px"></div>
<div style="margin-top:3px"><textarea name="description" onkeyup="mdefault('description')" style="width:400px;height:100px"></textarea></div>
<div style="margin-top:3px"><input type="text" name="keywords" onkeyup="mdefault('keywords')" value="" style="width:400px"></div>
<?if($global_settings["model"]){?>
<div style="margin-top:3px">
<select name="model" onChange="mdefault('model')" style="width:400px">
<option value="0"></option>
<?
$sql="select id_parent,name from models order by name";
$dn->open($sql);
while(!$dn->eof)
{
?>
<option value="<?=$dn->row["id_parent"]?>"><?=$dn->row["name"]?></option>
<?
$dn->movenext();
}
?>
</select></div>
<?}?>





</td>

<td >
















<?
$sql="select * from audio_fields order by priority";
$rs->open($sql);
while(!$rs->eof)
{
?>





<?if($rs->row["name"]=="track format" and ($rs->row["activ"]==1 or $rs->row["always"]==1)){?>
<div style="margin-bottom:9px"><b><?=word_lang("track format")?>:</b><br><select name="format" style="width:150px" onChange="mdefault('format')">
<option value="">...</option>
<?
$sql="select * from audio_format";
$dr->open($sql);
while(!$dr->eof)
{
?>
<option value="<?=$dr->row["name"]?>"><?=$dr->row["name"]?></option>
<?
$dr->movenext();
}
?>
</select></div>
<?}?>




<?if($rs->row["name"]=="track source" and ($rs->row["activ"]==1 or $rs->row["always"]==1)){?>
<div style="margin-bottom:9px"><b><?=word_lang("track source")?>:</b><br><select name="source" style="width:150px" onChange="mdefault('source')">
<option value="">...</option>
<?
$sql="select * from audio_source";
$dr->open($sql);
while(!$dr->eof)
{
?>
<option value="<?=$dr->row["name"]?>"><?=$dr->row["name"]?></option>
<?
$dr->movenext();
}
?>
</select></div>
<?}?>











<?if($rs->row["name"]=="copyright holder" and ($rs->row["activ"]==1 or $rs->row["always"]==1)){?>
<div style="margin-bottom:9px"><b><?=word_lang("copyright holder")?>:</b><br><input name="holder" value="" type="text" style="width:150px" onkeyup="mdefault('holder')"></div>
<?}?>






<?
$rs->movenext();
}
?>




















</td>


</tr>



<?
$afiles=array();
$bfiles=array();
$cfiles=array();

if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$global_settings["audiopreupload"]))
{
	$dir = opendir ($_SERVER["DOCUMENT_ROOT"].site_root.$global_settings["audiopreupload"]);
	while ($file = readdir ($dir)) 
	{
		if($file <> "." && $file <> "..")
		{
			if(preg_match("/.jpg$|.gif$|.png$|.jpeg$/i", $file)) 
			{
				$afiles[count($afiles)]=$file;
			}

			if(preg_match("/.mp3$/i", $file)) 
			{
				$bfiles[count($bfiles)]=$file;
			}

			if(!preg_match("/.jpg$|.gif$|.png$|.jpeg$/i", $file)) 
			{
				$cfiles[count($cfiles)]=$file;
			}
		}
	}
}

sort ($afiles);
reset ($afiles);
sort ($bfiles);
reset ($bfiles);
sort ($cfiles);
reset ($cfiles);



$sfiles=array();

if($global_settings["royalty_free"])
{
	$sql="select * from audio_types where shipped<>1 order by priority";
	$ds->open($sql);
	while(!$ds->eof)
	{
		$sfiles[$ds->row["id_parent"]]="";
		$poisk=".".str_replace(",","$|.",str_replace(" ","",$ds->row["types"]))."$";
		
		for($i=0;$i<count($cfiles);$i++)
		{
			if(preg_match("/".$poisk."/i",$cfiles[$i])) 
			{
				if($sfiles[$ds->row["id_parent"]]!=""){$sfiles[$ds->row["id_parent"]].="|";}
				$sfiles[$ds->row["id_parent"]].=$cfiles[$i];
			}
		}
		
		$ds->movenext();
	}
}

$xfiles=array();

if($global_settings["rights_managed"])
{
	$sql="select id,formats from rights_managed where audio=1";
	$ds->open($sql);
	while(!$ds->eof)
	{
		$xfiles[$ds->row["id"]]="";
		$poisk=".".str_replace(",","$|.",str_replace(" ","",$ds->row["formats"]))."$";
		for($i=0;$i<count($cfiles);$i++)
		{
			if(preg_match("/".$poisk."/i",$cfiles[$i])) 
			{
				if($xfiles[$ds->row["id"]]!=""){$xfiles[$ds->row["id"]].="|";}				
				$xfiles[$ds->row["id"]].=$cfiles[$i];				
			}
		}
		$ds->movenext();
	}
}




for($i=0;$i<$global_settings["bulk_upload"];$i++)
{
	?>
	<tr valign="top"  <?if($i%2==0){echo("class='snd'");}?>>
		<td>
			<div><?=word_lang("preview")?> <?=word_lang("photo")?>:</div>

			<div><select name="previewphoto<?=$i?>" style="width:130px"><option value="">...</option>
			<?
			for($j=0;$j<count($afiles);$j++)
			{
				?><option value="<?=$afiles[$j]?>"><?=$afiles[$j]?></option><?
			}
			?>
			</select></div>

			<div style="margin-top:10px"><?=word_lang("preview")?> <?=word_lang("audio")?>:</div>
			
			<div><select name="previewaudio<?=$i?>" style="width:130px"><option value="">...</option>
			<?
			for($j=0;$j<count($bfiles);$j++)
			{
				?><option value="<?=$bfiles[$j]?>"><?=$bfiles[$j]?></option><?
			}
			?>
			</select></div>

			<?if($global_settings["royalty_free"]){?>
				<div class="box_license1" style="display:block">			
					<?
					$sql="select id_parent,name from licenses order by priority";
					$dr->open($sql);
					while(!$dr->eof)
					{
						?>
						<div style="margin-top:10px"><b><?=$dr->row["name"]?></b></div>
						<?
							$sql="select * from audio_types  where license=".$dr->row["id_parent"]." order by priority";
							$ds->open($sql);
							while(!$ds->eof)
							{
								?>
								<div style="margin-top:10px"><?=$ds->row["title"]?>:</div>
								
								<?if($ds->row["shipped"]==1){?>
									<div><input type="checkbox" name="file<?=$ds->row["id_parent"]?>_<?=$i?>"></div>
								<?}else{?>
									<div><select name="file<?=$ds->row["id_parent"]?>_<?=$i?>" style="width:130px"><option value="">...</option>
									<?
										$zfiles=explode("|",$sfiles[$ds->row["id_parent"]]);
										for($j=0;$j<count($zfiles);$j++)
										{
											?><option value="<?=$zfiles[$j]?>"><?=$zfiles[$j]?></option><?
										}
									?>
									</select></div>
								<?
							}
							$ds->movenext();
						}
						$dr->movenext();
					}
				?>
		</div>
		<?}?>
		
		
		<?if($global_settings["rights_managed"]){?>
		<div class="box_license2" style="display:<?if(!$global_settings["royalty_free"]){echo("block");}else{echo("none");}?>">
			<?
			$sql="select id,title from rights_managed where audio=1";
			$dr->open($sql);
			while(!$dr->eof)
			{
				?>
					<div style="margin-top:10px"><?=$dr->row["title"]?>:</div>

					<div>
						<select name="file<?=$dr->row["id"]?>_<?=$i?>" style="width:130px"><option value="">...</option>
							<?
								$zfiles=explode("|",$xfiles[$dr->row["id"]]);
								for($j=0;$j<count($zfiles);$j++)
								{
									?><option value="<?=$zfiles[$j]?>"><?=$zfiles[$j]?></option><?
								}
							?>
						</select>
					</div>
				<?
				$dr->movenext();
			}
		?>
		</div>
		<?}?>
</td>




<td class=tab<?if($i%2==1){echo(4);}else{echo(6);}?>>
<div><input type="text" name="title<?=$i?>" value="" style="width:400px"></div>
<div style="margin-top:3px"><textarea name="description<?=$i?>" style="width:400px;height:100px"></textarea></div>
<div style="margin-top:3px"><input type="text" name="keywords<?=$i?>" value="" style="width:400px"></div>


<?if($global_settings["model"]){?>
<div style="margin-top:3px">
<select name="model<?=$i?>" style="width:400px">
<option value="0"></option>
<?
$sql="select id_parent,name from models order by name";
$dn->open($sql);
while(!$dn->eof)
{
?>
<option value="<?=$dn->row["id_parent"]?>"><?=$dn->row["name"]?></option>
<?
$dn->movenext();
}
?>
</select></div>
<?}else{?>
<input type="hidden" name="model<?=$i?>" value="">
<?}?>



</td>
<td  class=tab<?if($i%2==1){echo(4);}else{echo(6);}?>>
















<?
$sql="select * from audio_fields order by priority";
$rs->open($sql);
while(!$rs->eof)
{
?>


<?if($rs->row["name"]=="duration" and ($rs->row["activ"]==1 or $rs->row["always"]==1)){?>
<div style="margin-bottom:9px"><b><?=word_lang("duration")?>:</b><br><?=duration_form(0,"duration".$i)?></div>
<?}?>


<?if($rs->row["name"]=="track format" and ($rs->row["activ"]==1 or $rs->row["always"]==1)){?>
<div style="margin-bottom:9px"><b><?=word_lang("track format")?>:</b><br><select name="format<?=$i?>" style="width:150px">
<option value="">...</option>
<?
$sql="select * from audio_format";
$dr->open($sql);
while(!$dr->eof)
{
?>
<option value="<?=$dr->row["name"]?>"><?=$dr->row["name"]?></option>
<?
$dr->movenext();
}
?>
</select></div>
<?}?>




<?if($rs->row["name"]=="track source" and ($rs->row["activ"]==1 or $rs->row["always"]==1)){?>
<div style="margin-bottom:9px"><b><?=word_lang("track source")?>:</b><br><select name="source<?=$i?>" style="width:150px">
<option value="">...</option>
<?
$sql="select * from audio_source";
$dr->open($sql);
while(!$dr->eof)
{
?>
<option value="<?=$dr->row["name"]?>"><?=$dr->row["name"]?></option>
<?
$dr->movenext();
}
?>
</select></div>
<?}?>











<?if($rs->row["name"]=="copyright holder" and ($rs->row["activ"]==1 or $rs->row["always"]==1)){?>
<div style="margin-bottom:9px"><b><?=word_lang("copyright holder")?>:</b><br><input name="holder<?=$i?>" value="" type="text" style="width:150px"></div>
<?}?>






<?
$rs->movenext();
}
?>



















</td>

</tr>

<?
}
?>
</table>
</div></div></div></div></div></div></div></div>
</div>


	<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">
			<input type="submit" value="<?=word_lang("upload")?>" class="btn btn-primary" style="margin-top:20px">
		</div>
	</div>


</form>