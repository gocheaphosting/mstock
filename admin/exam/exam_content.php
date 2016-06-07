<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_exam");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>

<div class="back"><a href="index.php" class="btn btn-primary btn-sm btn-mini"><i class="icon-arrow-left fa fa-arrow-left"></i> <?=word_lang("back")?></a></div>

<h1><?=word_lang("seller examination")?> # <?=$_GET["id"]?></h1>

<?
$userid=0;
$sql="select * from examinations where id=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
$userid=$rs->row["user"];
?>

<div class="box box_padding">

<form method="post" action="change.php?id=<?=$_GET["id"]?>">






<div class="form_field">
<span><b><?=word_lang("user")?>:</b></span>
<div class="link_user">
<?
$sql="select id_parent,login from users where id_parent=".$rs->row["user"];
$ds->open($sql);
if(!$ds->eof)
{
?>
<a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a>
<?
}
?>
</div>
</div>

<div class="form_field">
<span><b><?=word_lang("date")?>:</b></span>
<?=date(date_format,$rs->row["data"])?>
</div>





<div class="form_field">
<span><b><?=word_lang("status")?>:</b></span>
<select name="status" style="width:150px">
<option value="0" <?if($rs->row["status"]==0){echo("selected");}?>><?=word_lang("pending")?></option>
<option value="1" <?if($rs->row["status"]==1){echo("selected");}?>><?=word_lang("approved")?></option>
<option value="2" <?if($rs->row["status"]==2){echo("selected");}?>><?=word_lang("declined")?></option>
</select>
</div>



<div class="form_field">
<span><b><?=word_lang("comments")?>:</b></span>
<textarea name="comments" style="width:400px;height:150px">
<?=$rs->row["comments"]?>
</textarea>
</div>

<div class="form_field">
<input type="submit" value="<?=word_lang("change")?>" class="btn btn-primary">
</div>
</form>



<?
}
?>








<br><br>





<?
$mtables=array("photos","videos","audio","vector");
$murls=array("photo","video","audio","vector");
$exam_flag=false;
for($i=0;$i<count($mtables);$i++)
{


$sql="select id_parent,server1 from ".$mtables[$i]." where examination=1 and userid=".(int)$userid." order by id_parent";
$rs->open($sql);
if(!$rs->eof)
{
$n=0;


?>
<div class="box3"><?=word_lang($mtables[$i])?>:</div>
<table border='0' cellpadding='0' cellspacing='0' style="margin-bottom:25px">
<tr valign='top'>
<?
while(!$rs->eof)
{
$exam_flag=true;

if($i==0)
{
	$thumb=show_preview($rs->row["id_parent"],"photo",1,1);
}
if($i==1)
{
	$thumb=show_preview($rs->row["id_parent"],"video",1,1);
}
if($i==2)
{
	$thumb=show_preview($rs->row["id_parent"],"audio",1,1);
}
if($i==3)
{
	$thumb=show_preview($rs->row["id_parent"],"vector",1,1);
}




if($mtables[$i]=="photos")
{
$sql="select url from items where id_parent=".$rs->row["id_parent"];
$ds->open($sql);
if(!$ds->eof)
{
$preview_url=site_root.server_url($rs->row["server1"])."/".$rs->row["id_parent"]."/".$ds->row["url"];
}
else
{
  $dir = opendir ($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$rs->row["id_parent"]);
  while ($file = readdir ($dir)) 
  {

    if($file <> "." && $file <> "..")
    {
	if (preg_match("/.jpg$|.jpeg$/i",$file) and !preg_match("/thumb/i",$file)) 
	{
	$preview_url=site_root.server_url($rs->row["server1"])."/".$rs->row["id_parent"]."/".$file;
	}
    }
  }


}
}

if($mtables[$i]=="videos")
{
$preview_url="../content/video.php?id=".$rs->row["id_parent"]."&id_parent=".$id_parent."&module_table=31&module_column=239";
}

if($mtables[$i]=="audio")
{
$preview_url="../content/audio.php?id=".$rs->row["id_parent"]."&id_parent=".$id_parent."&module_table=52&module_column=239";
}

if($mtables[$i]=="vector")
{
$preview_url="../content/vector.php?id=".$rs->row["id_parent"]."&id_parent=".$id_parent."&module_table=53&module_column=239";
}

















if($n%4==0){echo("</tr><tr valign='top'>");}
?>
<td style="padding:0px 10px 10px 0px">
<div style="margin-bottom:3px"><a href="<?=$preview_url?>" target="blank"><img src="<?=$thumb?>" border="0"></a></div>
<div style="margin-bottom:3px" class="smalltext"><b>ID:</b> <?=$rs->row["id_parent"]?></div>
</td>
<?
$n++;
$rs->movenext();
}
?>
</tr>
</table>
<?
}
}
?>











</div>












<? include("../inc/end.php");?>