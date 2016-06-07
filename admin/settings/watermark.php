<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_watermark");
?>
<? include("../inc/begin.php");?>


<h1><?=word_lang("watermark")?>:</h1>

<div class="box box_padding">
<ul>
<li>You should upload *.png file with the transparent background</li>
<li>The watermark's width must be small than portrdit's thumb's width</li>
<li>The watermarked thumbs are generated one time when you upload a photo. If you upload a new watermark you can bulk recreate old previews here: Admin panel -> Catalog -> Select action -> Regenerate thumbs.</li>
</ul>
</div>

<form Enctype="multipart/form-data" method="post" action="watermark_add.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped">
<tr>
<th><b><?=word_lang("position")?>:</b></th>
<th><b><?=word_lang("image")?>:</b></th>
</tr>
<tr>
<td>

<script language="javascript">
img_orange=new Image()
img_blue=new Image()
img_orange.src="<?=site_root?>/images/o.gif"
img_blue.src="<?=site_root?>/images/b.gif"

function wposition(j)
{
document.getElementById('position').value=j
for(i=1;i<10;i++)
{
if(i==j){document.getElementById('ris'+i).src=img_orange.src}
else{document.getElementById('ris'+i).src=img_blue.src}
}
}

</script>


<table border="0" cellpadding="0" cellspacing="0">
<tr>
<?
for($i=1;$i<10;$i++)
{

?>
<td style="border:0px;padding:2px;"><a href="javascript:wposition(<?=$i?>)"><img name="ris<?=$i?>" id="ris<?=$i?>" src="<?=site_root?>/images/<?if($global_settings["watermark_position"]==$i){echo("o");}else{echo("b");}?>.gif" width="54" height="54" border="0"></a></td>
<?
if($i%3==0){echo("</tr><tr>");}
}
?>
</tr>
</table>
<input type="hidden" name="position" id="position" value="<?=$global_settings["watermark_position"]?>">

</td>
<td><input type="file" name="watermark"><?
if($global_settings["watermark_photo"]!="")
{
?>
	<div style="margin-top:10px;margin-bottom:5px;"><img src="watermark_image.php"></div><div><a href="watermark_delete.php" class="btn btn-mini"><?=word_lang("delete")?></a></div>
<?
}
?></td>
</tr>

</table>
</div></div></div></div></div></div></div></div>

<input type="submit" value="<?=word_lang("change")?>" class="btn btn-primary" style="margin:10px 0px 0px 6px">
</form>














<? include("../inc/end.php");?>