<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("pages_textpages");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<?
//If it is new
$id=0;
if(isset($_GET["id"])){$id=(int)$_GET["id"];}


//Fields list
$admin_fields=array("title","priority","content","link","siteinfo");

$admin_names=array(word_lang("title"),word_lang("priority"),word_lang("content"),word_lang("link"),word_lang("site info"));

//Fields meanings
$admin_meanings=array("","0","","",0);

//Fields types
$admin_types=array("text","int","editor","text","checkbox");




//If it isn't a new category
if($id!=0)
{
	//Get field's meanings
	$sql = "select id_parent,title,content,priority,link,url,siteinfo from pages where id_parent=".(int)$_GET["id"];
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

<div class="back"><a href="index.php" class="btn btn-primary btn-sm btn-mini"><i class="icon-arrow-left fa fa-arrow-left"></i> <?=word_lang("back")?></a></div>


<h1><?
if($id==0)
{
echo(word_lang("add")." &mdash; ".word_lang("text pages"));
}
else
{
echo(word_lang("edit")." &mdash; ".word_lang("text pages"));
}
?>:</h1>

<?=build_admin_form("add.php?id=".$id,"catalog")?>

<? include("../inc/end.php");?>