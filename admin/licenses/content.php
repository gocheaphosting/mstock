<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_licenses");

?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<?
//If it is new
$id=0;
if(isset($_GET["id"])){$id=(int)$_GET["id"];}


//Fields list
$admin_fields=array("name","priority","description");

$admin_names=array(word_lang("title"),word_lang("priority"),word_lang("description"));

//Fields meanings
$admin_meanings=array("","0","");

//Fields types
$admin_types=array("text","int","editor");




//If it isn't a new category
if($id!=0)
{
	//Get field's meanings
	$sql = "select name,priority,description from licenses where id_parent=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		for($i=0;$i<count($admin_fields);$i++)
		{
			$admin_meanings[$i]=$rs->row[$admin_fields[$i]];
		}
	}
}


?>

<div class="back"><a href="index.php"><b>&#171; <?=word_lang("back")?></b></a></div>


<h1><?
if($id==0)
{
echo(word_lang("add")." &mdash; ".word_lang("license"));
}
else
{
echo(word_lang("edit")." &mdash; ".word_lang("license"));
}
?>:</h1>

<?=build_admin_form("add.php?id=".$id,"catalog")?>

<? include("../inc/end.php");?>