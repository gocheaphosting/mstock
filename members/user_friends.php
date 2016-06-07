<?$site="user_friends";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>




<?include("user_top.php");?>



<?
//user
$com4="";
$sql="select friend1,friend2 from friends where friend1='".result3(user_url_back($nameuser))."' and friend2<>'".result3(user_url_back($nameuser))."'";
$dr->open($sql);
if(!$dr->eof)
{
	$com4.=" ";
	$k=0;
	while(!$dr->eof)
	{
		if($k!=0){$com4.=" or ";}
		$com4.=" login='".$dr->row["friend2"]."'";
		$k++;
		$dr->movenext();
	}
}



$n=0;
$sql="select id_parent,avatar,login from users where  ".$com4." order by login";
$rs->open($sql);
if(!$rs->eof)
{
	while(!$rs->eof)
	{
		?>
		<div style="margin-right:50px;padding-bottom:20px;width:150px;float:left" class="seller_list"><?=show_user_avatar($rs->row["login"],"login")?></div>
		<?
		$n++;
		$rs->movenext();
	}
}
else
{
	echo("<p><b>".word_lang("not found")."</b></p>");
}
?>







<?include("user_bottom.php");?>
<?include("../inc/footer.php");?>