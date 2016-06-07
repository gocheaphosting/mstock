<?if(!defined("site_root")){exit();}?>
<?
if($id_parent!=5)
{
	$sql="select name from structure where id=".(int)$id_parent." and module_table<>34";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$translate_results=translate_publication((int)$id_parent,$rs->row["name"],"","");
		?>
			<h1 style="margin-bottom:7px;margin-top:0" class="old_header"><?=$translate_results["title"]?></h1>
		<?
	}
}
?>