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
		if(t=='title' || t=='description' || t=='keywords' || t=='price' || t=='model')
		{
			dd=eval(t+".value");
			for(i=0;i<<?=$global_settings["bulk_upload"]?>;i++)
			{
				rs=eval(t+i);
				rs.value=dd
			}
		}

		if(t=='iptc' || t=='prints')
		{
			dd=eval(t);
			for(i=0;i<<?=$global_settings["bulk_upload"]?>;i++)
			{
				rs=eval(t+i);
				if(dd.checked==true)
				{
					rs.checked=true
				}
				else
				{
					rs.checked=false
				}
			}
		}
	}
}
</script>






<form method="post" action="vector_upload.php" name="uploadform" onsubmit="return check();">


<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

<div class="form_field">
You should upload files here <b><?=surl?><?=site_root?><?=$global_settings["vectorpreupload"]?></b> on FTP<br><br>

Vector previews: <b>*.jpg, *.jpeg</b> or <b>*.zip</b> archive of jpg photos<br>

<?if($global_settings["flash"]){?>
Flash previews: <b>*.swf</b>
</div>

<?}?>

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


</div>

<div class="subheader"><?=word_lang("vector")?></div>
<div class="subheader_text">

<div class="table_t" style="margin-left:-6px"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=7 cellspacing=1 class='table_admin table table-striped'>
<tr>
<th><b><?=word_lang("file")?></b></th>
<th><b><?=word_lang("title")?>/<?=word_lang("description")?>/<?=word_lang("keywords")?><?if($global_settings["model"]){echo("/".word_lang("model property release"));}?></b></th>
<th><b><?=word_lang("use iptc info")?></b></th>
<th><b><?=word_lang("prints")?></b></th>
</tr>




<tr valign="top">
<td>Default meanings for the fields:</td>
<td><div><input type="text" name="title" onkeyup="mdefault('title')" value="" style="width:200px"></div>
<div style="margin-top:3px"><textarea name="description" onkeyup="mdefault('description')" style="width:200px;height:30px"></textarea></div>
<div style="margin-top:3px"><input type="text" name="keywords" onkeyup="mdefault('keywords')" value="" style="width:200px"></div>
<?if($global_settings["model"]){?>
<div style="margin-top:3px">
<select name="model" onChange="mdefault('model')" style="width:200px">
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

<td class=tab6 align="center"><input type="checkbox" name="iptc" onclick="mdefault('iptc')" checked></td>
<td class=tab6 align="center"><input type="checkbox" name="prints" onclick="mdefault('prints')" <?if($global_settings["prints"]){?>checked<?}?>></td>
</tr>



<?
$afiles=array();
$bfiles=array();
$cfiles=array();
if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$global_settings["vectorpreupload"]))
{
$dir = opendir ($_SERVER["DOCUMENT_ROOT"].site_root.$global_settings["vectorpreupload"]);
while ($file = readdir ($dir)) 
{
if($file <> "." && $file <> "..")
{
if(preg_match("/.jpg$|.jpeg$|.zip$/i", $file)) 
{
$afiles[count($afiles)]=$file;
}

if(preg_match("/.swf$/i", $file)) 
{
$cfiles[count($cfiles)]=$file;
}

if(!preg_match("/.jpg$|.jpeg|$.swf$/i", $file)) 
{
$bfiles[count($bfiles)]=$file;
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




<?if($global_settings["flash"]){?>
<div style="margin-top:10px"><?=word_lang("preview")?> Flash:</div>

<div><select name="previewflash<?=$i?>" style="width:130px"><option value="">...</option>
<?
for($j=0;$j<count($cfiles);$j++)
{
?><option value="<?=$cfiles[$j]?>"><?=$cfiles[$j]?></option><?
}
?>
</select></div>
<?}?>




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
?><option value="<?=$zfiles[$j]?>"><?=$zfiles[$j]?></option><?
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

</td>




<td class=tab<?if($i%2==1){echo(4);}else{echo(6);}?>>
<div><input type="text" name="title<?=$i?>" value="" style="width:200px"></div>
<div style="margin-top:3px"><textarea name="description<?=$i?>" style="width:200px;height:30px"></textarea></div>
<div style="margin-top:3px"><input type="text" name="keywords<?=$i?>" value="" style="width:200px"></div>





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
<td class=tab<?if($i%2==0){echo(4);}else{echo(6);}?> align="center"><input type="checkbox" name="iptc<?=$i?>" checked></td>
<td class=tab<?if($i%2==0){echo(4);}else{echo(6);}?> align="center"><input type="checkbox" name="prints<?=$i?>" <?if($global_settings["prints"]){?>checked<?}?>></td>
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