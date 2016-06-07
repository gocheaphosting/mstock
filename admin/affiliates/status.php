<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("affiliates_payout");

include("../../members/JsHttpRequest.php");

$JsHttpRequest =new JsHttpRequest($mtg);


$sql="select data,aff_referal,status from affiliates_signups where data=".(int)$_REQUEST["data"]." and  aff_referal=".(int)$_REQUEST["user"];
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["status"]==1)
	{
		$sql="update affiliates_signups set status=0 where data=".(int)$_REQUEST["data"]." and  aff_referal=".(int)$_REQUEST["user"];
		$db->execute($sql);
		?>
			<a href="javascript:status(<?=$rs->row["data"]?>,<?=$rs->row["aff_referal"]?>);" class="red"><?=word_lang("pending")?></a>
		<?
	}
	else
	{
		$sql="update affiliates_signups set status=1 where data=".(int)$_REQUEST["data"]." and  aff_referal=".(int)$_REQUEST["user"];
		$db->execute($sql);
		?>
			<a href="javascript:status(<?=$rs->row["data"]?>,<?=$rs->row["aff_referal"]?>);"><?=word_lang("approved")?></a>
		<?
	}
}

$db->close();
?>