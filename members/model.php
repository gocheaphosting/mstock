<?$site="models";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>

<?
$flag=false;

if(isset($_GET["item_id"]) and isset($_GET["order_id"]))
{
	$sql="select id from orders where id=".(int)$_GET["order_id"]." and user=".(int)$_SESSION["people_id"]." and status=1";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$sql="select id from orders_content where id_parent=".(int)$_GET["order_id"]." and item=".(int)$_GET["item_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag=true;
		}
	}
}

$sql="select id_parent from models where id_parent=".(int)$_GET["model"]." and user='".(int)$_SESSION["people_login"]."'";
$rs->open($sql);
if(!$rs->eof)
{
	$flag=true;
}


if($flag==true)
{
	$sql="select * from models where id_parent=".(int)$_GET["model"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>
		<h1><?=$rs->row["name"]?></h1>



		<?=str_replace("\r","<br>",$rs->row["description"])?>

		<?if($rs->row["model"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["model"])){?>
			<br><br>
			<p><input type="submit" class="isubmit" onClick="location.href='<?=$rs->row["model"]?>'" value="<?=word_lang("model property release")?>"></p>
		<?}?>
		
		<?if($rs->row["modelphoto"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["modelphoto"])){?>
			<img src="<?=$rs->row["modelphoto"]?>">
		<?}?>
		<?
	}
	else
	{
		echo("<b>".word_lang("not found")."</b>");
	}
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>



<?include("../inc/footer.php");?>