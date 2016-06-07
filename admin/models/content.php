<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_models");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<?
//If it is new
$id=0;
if(isset($_GET["id"])){$id=(int)$_GET["id"];}


//Fields list
$admin_fields=array("name","description","user","model","modelphoto");

$admin_names=array(word_lang("title"),word_lang("description"),word_lang("user"),word_lang("file"),word_lang("photo"));

//Fields meanings
$admin_meanings=array("","","","","");

//Fields types
$admin_types=array("text","textarea","author","filepdf","file");




//If it isn't a new category
if($id!=0)
{
	//Get field's meanings
	$sql = "select name,description,user,model,modelphoto from models where id_parent=".(int)$_GET["id"];
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

<div class="back"><a href="index.php?d=1" class="btn btn-primary btn-sm btn-mini"><b><i class="fa fa-arrow-left"></i>&nbsp;<?=word_lang("back")?></b></a></div>


<h1><?
if($id==0)
{
echo(word_lang("add")." &mdash; ".word_lang("model property release"));
}
else
{
echo(word_lang("edit")." &mdash; ".word_lang("model property release"));
}
?>:</h1>

<?=build_admin_form("add.php?id=".$id,"category")?>

<? include("../inc/end.php");?>