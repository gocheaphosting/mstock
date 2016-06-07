<?if($_SESSION['entry_admin']!=1){redirect("../auth/");}?>
<?if(!defined("site_root")){exit();}?>


<?
$upload_limit=50;
?>




<script language="javascript">
function anketa(name,pole,nado)
{
this.name=name;
this.pole=pole;
this.nado=nado;
}


ms=new Array(new anketa('<?=word_lang("author")?>','author',true))


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






<form method="post" action="vector_upload2.php" name="uploadform" onsubmit="return check();">


<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

<p><b>Preupload</b> files here <b><?=surl?><?=site_root?><?=$global_settings["vectorpreupload"]?></b> via FTP</p>

<p>The *.jpg previews and files for sale must have the same names.</p>






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


<?

//Category
$itg="";
$nlimit=0;
buildmenu2(5,0,2,0);



$n=0;
$afiles=array();
$bfiles=array();

  $dir = opendir ($DOCUMENT_ROOT.$global_settings["vectorpreupload"]);
  while ($file = readdir ($dir)) 
  {

    if($file <> "." && $file <> ".." && $file!="index.html")
    {
			if (preg_match("/.jpg$|.jpeg$/i",$file)) 
			{ 
				//if(count($afiles)<$upload_limit)
				//{
					$afiles[count($afiles)]=$file;
				//}
				$n++;
			}
			else
			{
				$bfiles[count($bfiles)]=$file;
			}
    }
  }
  closedir ($dir);
sort ($afiles);
reset ($afiles);
sort ($bfiles);
reset ($bfiles);



$sfiles=array();

if($global_settings["royalty_free"])
{
	$sql="select * from vector_types where shipped<>1 order by priority";
	$ds->open($sql);
	while(!$ds->eof)
	{
		$sfiles[$ds->row["id_parent"]]="";
		$poisk=".".str_replace(",","$|.",str_replace(" ","",$ds->row["types"]))."$";
		for($i=0;$i<count($bfiles);$i++)
		{
			if(preg_match("/".$poisk."/i",$bfiles[$i])) 
			{
				if($sfiles[$ds->row["id_parent"]]!=""){$sfiles[$ds->row["id_parent"]].="|";}
				$sfiles[$ds->row["id_parent"]].=$bfiles[$i];
			}
		}
		$ds->movenext();
	}
}

$xfiles=array();

if($global_settings["rights_managed"])
{
	$sql="select id,formats from rights_managed where vector=1";
	$ds->open($sql);
	while(!$ds->eof)
	{
		$xfiles[$ds->row["id"]]="";
		$poisk=".".str_replace(",","$|.",str_replace(" ","",$ds->row["formats"]))."$";
		for($i=0;$i<count($bfiles);$i++)
		{
			if(preg_match("/".$poisk."/i",$bfiles[$i])) 
			{
				if($xfiles[$ds->row["id"]]!=""){$xfiles[$ds->row["id"]].="|";}				
				$xfiles[$ds->row["id"]].=$bfiles[$i];				
			}
		}
		$ds->movenext();
	}
}



if(count($afiles)<$upload_limit)
{
	$upload_limit=count($afiles);
}
?>

</div>
<div class="subheader"><?=word_lang("vector")?></div>
<div class="subheader_text">


There are <b><?=$upload_limit?></b> of <b><?=$n?></b> files:<br><br>

<div class="table_t2" style="margin-left:-6px"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped">
<tr>
<th><?=word_lang("preview")?></th>
<th><?=word_lang("file")?></th>
<th><?=word_lang("category")?>1/<?=word_lang("category")?>2/<?=word_lang("category")?>3/<?=word_lang("title")?>/<?=word_lang("description")?>/<?=word_lang("keywords")?>/<?=word_lang("model property release")?></th>
</tr>
<?


for($i=0;$i<$upload_limit;$i++)
{
$title="";
$description="";
$keywords="";

$size = getimagesize ($_SERVER["DOCUMENT_ROOT"].site_root.$global_settings["vectorpreupload"].$afiles[$i],$info);
if(isset ($info["APP13"]))
{
	$iptc = iptcparse ($info["APP13"]);

	//Title
	if(isset($iptc["2#005"][0]) and $iptc["2#005"][0]!="")
	{
		$title=$iptc["2#005"][0];
	}
	
	//Description
	if(isset($iptc["2#120"][0]) and $iptc["2#120"][0]!="")
	{
		$description=$iptc["2#120"][0];
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
<tr valign="top">
<td align="center"><img src="image2.php?file=<?=$afiles[$i]?>"><input name="file<?=$i?>" type="hidden" value="<?=$afiles[$i]?>"><br><?=$afiles[$i]?></td>
<td>

	<input type="hidden"  name="previewphoto<?=$i?>" value="<?=$afiles[$i]?>">
	<input type="hidden"  name="previewflash<?=$i?>" value="">

	<?
	$preview_mass=explode(".",$afiles[$i]);
	$preview_name="";
	for($k=0;$k<count($preview_mass)-1;$k++)
	{
		$preview_name.=$preview_mass[$k];
	}
	?>
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
			$sql="select * from vector_types  where license=".$dr->row["id_parent"]." order by priority";
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
						$sel="";
						if(preg_match("/".$preview_name."/i",$zfiles[$j]))
						{
							$sel="selected";
							?><option value="<?=$zfiles[$j]?>" <?=$sel?>><?=$zfiles[$j]?></option><?
						}
					}
					?>
					</select></div>
				<?}?>
				<?
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
			$sql="select id,title from rights_managed where vector=1";
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
									$sel="";
									if(preg_match("/".$preview_name."/i",$zfiles[$j]))
									{
										$sel="selected";
										?><option value="<?=$zfiles[$j]?>" <?=$sel?>><?=$zfiles[$j]?></option><?
									}
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
<td>
<div><select class="ft" name="category_<?=$i?>" style="width:300;margin-top:2px">
<option value=""></option>
<?=$itg?>
</select></div>

<div style="margin-top:3px"><select class="ft" name="category2_<?=$i?>" style="width:300;margin-top:2px">
<option value=""></option>
<?=$itg?>
</select></div>

<div style="margin-top:3px"><select class="ft" name="category3_<?=$i?>" style="width:300;margin-top:2px">
<option value=""></option>
<?=$itg?>
</select></div>


<div style="margin-top:3px"><input class='ft' type="text" name="title<?=$i?>" value="<?=$title?>" style="width:400px"></div>
<div style="margin-top:3px"><textarea class='textarea' name="description<?=$i?>" style="width:400px;height:70px"><?=$description?></textarea></div>
<div style="margin-top:3px"><textarea class='textarea' name="keywords<?=$i?>"  style="width:400px;height:70px"><?=$keywords?></textarea></div>
<?if($global_settings["model"]){?>
<div style="margin-top:3px">
<select name="model<?=$i?>" style="width:200px">
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