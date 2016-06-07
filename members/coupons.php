<?$site="coupons";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>

<h1><?=word_lang("coupons")?></h1>



<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;



$sql="select id_parent,title,user,data2,total,	percentage,url,used,orderid,data,ulimit,tlimit,coupon_id,coupon_code from coupons where user='".result($_SESSION["people_login"])."' and used=0 and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." order by used,data2 desc";
$rs->open($sql);
if(!$rs->eof)
{
?>
<table border="0" cellpadding="0" cellspacing="0"  class="profile_table" style="width:100%">
<tr>
<th><?=word_lang("code")?></th>
<th class='hidden-phone hidden-tablet'><?=word_lang("title")?></th>
<th class='hidden-phone hidden-tablet'><?=word_lang("setup date")?></th>
<th><?=word_lang("expiration date")?></th>
<th><?=word_lang("discount")?></th>
<th class='hidden-phone hidden-tablet'><?=word_lang("limit of usage")?></th>
</tr>
<?
$n=0;
$tr=1;
while(!$rs->eof)
{
if($rs->row["data"]<mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")) or $rs->row["ulimit"]==$rs->row["tlimit"])
{
$sql="update coupons set used=1 where id_parent=".$rs->row["id_parent"];
$db->execute($sql);
}


if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td><div class="link_coupon"><?=$rs->row["coupon_code"]?></div></td>
<td class='hidden-phone hidden-tablet'><?=$rs->row["title"]?></td>
<td class='hidden-phone hidden-tablet'><div class="link_date"><?=date(date_format,$rs->row["data2"])?></div></td>
<td><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
<td>
<?
if($rs->row["total"]>0)
{
	echo(currency(1).$rs->row["total"]." ".currency(2));
}
if($rs->row["percentage"]>0)
{
	echo($rs->row["percentage"]."%");
}
if($rs->row["url"]!="")
{
	echo("<a href='go.php?id=".$rs->row["id_parent"]."'>".word_lang("free download")."</a>");
}
?>
</td>
<td class='hidden-phone hidden-tablet'><?=$rs->row["tlimit"]?>(<?=$rs->row["ulimit"]?>)</td>



</tr>
<?
}
$tr++;
$rs->movenext();
}
?>
</table>
<?
echo(paging($n,$str,$kolvo,$kolvo2,"coupons.php","&d=1"));
}
else
{
?>
<p><?=word_lang("not found")?>.</p>
<?
}
?>














<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>