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
if(mat!="<?=word_lang("please insert")?>:."){
alert(mat+mat2)
}
else
{alert(mat2)}
return false
}




}
}












</script>






<form method="post" action="index.php?d=6" name="uploadform" onsubmit="return check();">

<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

<p><b>Select category 1:</b><br><select class="ft" name="category" style="width:300px;margin-top:2px">
<option value=""></option>
<?
$itg="";
$nlimit=0;
buildmenu2(5,0,2,0);
echo($itg);
?>
</select></p>


<p>Select category 2:<br><select class="ft" name="category2" style="width:300px;margin-top:2px">
<option value=""></option>
<?
echo($itg);
?>
</select></p>

<p>Select category 3:<br><select class="ft" name="category3" style="width:300px;margin-top:2px">
<option value=""></option>
<?
echo($itg);
?>
</select></p>




<p><b>Select author:</b><br><select class="ft" name="author" style="width:150px;margin-top:2px">
<option value="">...</option>
<?
$sql="select login from users where utype='seller' or utype='common'  order by login";
$rs->open($sql);
while(!$rs->eof)
{
?>
<option value="<?=$rs->row["login"]?>"><?=$rs->row["login"]?></option>
<?
$rs->movenext();
}
?>
</select></p>

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


<div class="table_t" style="margin-left:-7px"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<?=prints_upload_form()?>
</div></div></div></div></div></div></div></div>
</div>
<?}?>


<div id="java_bulk"></div>


	<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">
			<input type="submit" value="<?=word_lang("next step")?>" class="btn btn-primary" style="margin-top:20px">
		</div>
	</div>


</form>