<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_content_types");

$flag=false;

//Count files.
$count_types=0;
$content_name="";
$content_select="";

$sql="select id_parent,priority,name from content_type order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	if((int)$_GET["id"]==$rs->row["id_parent"])
	{
		$content_name=$rs->row["name"];
	
		$sql="select count(id_parent) as count_types from photos where content_type='".$rs->row["name"]."' group by content_type";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$count_types+=$ds->row["count_types"];
		}
		
		$sql="select count(id_parent) as count_types from videos where content_type='".$rs->row["name"]."' group by content_type";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$count_types+=$ds->row["count_types"];
		}
		
		$sql="select count(id_parent) as count_types from audio where content_type='".$rs->row["name"]."' group by content_type";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$count_types+=$ds->row["count_types"];
		}
		
		$sql="select count(id_parent) as count_types from vector where content_type='".$rs->row["name"]."' group by content_type";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$count_types+=$ds->row["count_types"];
		}
	}
	else
	{
		$content_select.="<option value='".$rs->row["name"]."'>".$rs->row["name"]."</option>";
	}
	$rs->movenext();
}


if($count_types==0)
{
	$sql="delete from content_type where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
	header("location:index.php");
	exit();
}

?>
<? include("../inc/begin.php");?>









<h1><?=word_lang("content type")?>:</h1>



<p><b><?=$count_types?></b> files have <b>"<?=$content_name?>"</b> content type which you want to remove.</p>

<p>You should select other content type for the files:</p>

<form method="post" action="delete2.php?id=<?=$_GET["id"]?>">
<select name="new_type" style="width:200px">
<?=$content_select?>
</select><br>
<input type="submit" value="<?=word_lang("delete")?>" style="margin-top:10px">
</form>




<? include("../inc/end.php");?>