<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_home");
?>
<? include("../inc/begin.php");?>

<a class="btn btn-success toright" href="index.php?m=content&d=2"><i class="icon-plus icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>

<h1><?=word_lang("home page")?>:</h1>





<?if(!isset($_GET["m"])){?>


<p>
If you want to modify a home page you should edit the file on FTP:<br>
<b><?=site_root."/".$site_template_url."home.tpl"?></b>
</p><p>
You are able to place different file's sets by different criterias into the HTML code.
</p><p>
To add a new file set component you should insert the next code: <b>{COMPONENT_ID}</b>.
</p><p>
<b>Important!</b> The components are cached once per hour. So you should <a href="../caching/">clear cache</a> after you change a component.
</p><p>
You can specify the components below:
</p>





<br>

<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover" style="width:100%">
<tr>

<th><b><?=word_lang("id")?></b></th>
<th><b><?=word_lang("title")?></b></th>
<th><b><?=word_lang("edit")?></b></th>
<th><b><?=word_lang("delete")?></b></th>

</tr>
<?
$sql="select * from components";
$rs->open($sql);
$tr=1;
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
<td>{COMPONENT_<?=$rs->row["id"]?>}</td>
<td class="big"><?=$rs->row["title"]?></td>
<td><div class="link_edit"><a href="index.php?m=content&id=<?=$rs->row["id"]?>&d=2"><?=word_lang("edit")?></div></td>
<td>
<div class="link_delete">
<a href='components_delete.php?id=<?=$rs->row["id"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a>
</div>
</td>
</tr>
<?
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>






<?}else{?>




<div class="box box_padding">








<?if(isset($_GET["id"]))
{
$sql="select * from components where id=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
$title=$rs->row["title"];
$content=$rs->row["content"];
$types=$rs->row["types"];
$category=$rs->row["category"];
$user=$rs->row["user"];
$quantity=$rs->row["quantity"];
$columns=$rs->row["acells"];
$rows=$rs->row["arows"];
$slideshow=$rs->row["slideshow"];
$slideshowtime=$rs->row["slideshowtime"];
}
?>
<form method=post Enctype="multipart/form-data" name="componentform" action="components_change.php?id=<?=$_GET["id"]?>">
<?
}
else
{
$title="";
$content="";
$types="";
$category=0;
$user="";
$quantity=1;
$columns=1;
$rows=1;
$slideshow=0;
$slideshowtime=1;
?>
<form method=post Enctype="multipart/form-data" name="componentform" action="components_add.php">
<?}?>



<script language="javascript">

function fslideshow()
{
with(document.componentform)
{
if(slideshow.checked==true)
{
slideshowtime.disabled=false
}
else
{
slideshowtime.disabled=true
}
}
}

</script>




<div class="admin_field">
<span><b><?=word_lang("title")?>:</b></span>
<input name="title" type="text" value="<?=$title?>" style="width:300px">
</div>



<div class="admin_field">
<span><b><?=word_lang("content")?>:</b></span>
<select name="content" style="width:300px">
<option value="">...</option>

<option value="photo1" <?if($content=="photo1"){echo("selected");}?>><?=word_lang("photos")?> - <?=word_lang("small")?> <?=word_lang("preview")?></option>

<option value="photo2" <?if($content=="photo2"){echo("selected");}?>><?=word_lang("photos")?> - <?=word_lang("big")?> <?=word_lang("thumb")?></option>

<option value="video" <?if($content=="video"){echo("selected");}?>><?=word_lang("videos")?> - <?=word_lang("small")?> <?=word_lang("thumb")?></option>

<option value="video2" <?if($content=="video2"){echo("selected");}?>><?=word_lang("videos")?> - <?=word_lang("big")?> <?=word_lang("thumb")?></option>

<option value="audio" <?if($content=="audio"){echo("selected");}?>><?=word_lang("audio")?> - <?=word_lang("small")?> <?=word_lang("thumb")?></option>

<option value="audio2" <?if($content=="audio2"){echo("selected");}?>><?=word_lang("audio")?> - <?=word_lang("big")?> <?=word_lang("thumb")?></option>

<option value="vector1" <?if($content=="vector1"){echo("selected");}?>><?=word_lang("vector")?> - <?=word_lang("small")?> <?=word_lang("thumb")?></option>

<option value="vector2" <?if($content=="vector2"){echo("selected");}?>><?=word_lang("vector")?> - <?=word_lang("big")?> <?=word_lang("thumb")?></option>
</select>
</div>


<div class="admin_field">
<span><b><?=word_lang("type")?>:</b></span>
<select name="types" style="width:300px">
<option value="">...</option>
<option value="featured" <?if($types=="featured"){echo("selected");}?>><?=word_lang("featured")?></option>
<option value="new" <?if($types=="new"){echo("selected");}?>><?=word_lang("new")?></option>
<option value="popular" <?if($types=="popular"){echo("selected");}?>><?=word_lang("most popular")?></option>
<option value="downloaded" <?if($types=="downloaded"){echo("selected");}?>><?=word_lang("most downloaded")?></option>
<option value="free" <?if($types=="free"){echo("selected");}?>><?=word_lang("free download")?></option>
<option value="random" <?if($types=="random"){echo("selected");}?>><?=word_lang("random")?></option>
</select>
</div>



<div class="admin_field">
<span><b><?=word_lang("category")?>:</b></span>
<select name="category" style="width:300px">
<option value="0">...</option>
<?
$itg="";
$nlimit=0;
$iid=0;
if(isset($id)){$iid=$id;}
buildmenu2(5,$category,2,$iid);
echo($itg);
?>
</select>
</div>






<div class="admin_field">
<span><b><?=word_lang("user")?>:</b></span>
<select name="user" style="width:300px">
<option value="">...</option>
<?
$sql="select login from users order by login";
$rs->open($sql);
while(!$rs->eof)
{
?>
<option value="<?=$rs->row["login"]?>" <?if($user==$rs->row["login"]){echo("selected");}?>><?=$rs->row["login"]?></option>
<?
$rs->movenext();
}
?>
</select>
</div>




<div class="admin_field">
<span><b><?=word_lang("quantity")?>:</b></span>
<input name="quantity" type="text" value="<?=$quantity?>" style="width:80px">
</div>


<div class="admin_field">
<span><b><?=word_lang("columns")?>:</b></span>
<input name="columns" type="text" value="<?=$columns?>" style="width:80px">
</div>



<div class="admin_field">
<span><b><?=word_lang("rows")?>:</b></span>
<input name="rows" type="text" value="<?=$rows?>" style="width:80px">
</div>




<div class="admin_field">
<input type=submit value="<?if(isset($_GET["id"])){echo(word_lang("change"));}else{echo(word_lang("add"));}?>" style="margin-top:7px" class="btn btn-primary">
</div>


</form>


<script language="javascript">
fslideshow()
</script>





</div>


<?}?>
<? include("../inc/end.php");?>