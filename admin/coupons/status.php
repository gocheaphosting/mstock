<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_coupons");
?>

<?include("../../members/JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)@$_REQUEST['id'];
$sql="select id_parent,used,coupon_id from coupons where id_parent=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["used"]==1)
	{
		$sql="select days from coupons_types where id_parent=".$rs->row["coupon_id"];
		$ds->open($sql);
		{
			$sql="update coupons set used=0,tlimit=0,data2=".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",data=".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+$ds->row["days"]*3600*24)." where id_parent=".$id;
			$db->execute($sql);
		}
		?>
		<a href="javascript:doLoad(<?=$rs->row["id_parent"]?>);"><?=word_lang("active")?></a>
		<?
	}
	else
	{
		$sql="update coupons set used=1 where id_parent=".$id;
		$db->execute($sql);
		?>
		<a href="javascript:doLoad(<?=$rs->row["id_parent"]?>);" class="red"><?=word_lang("expired")?></a>
		<?
	}
}
$db->close();
?>
