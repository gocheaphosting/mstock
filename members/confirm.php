<?$site="signup";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<h1><?=word_lang("confirm registration")?></h1>



<?
$sql="select * from users where id_parent=".(int)$_GET["id"]." and login='".result($_GET["login"])."'";
$rs->open($sql);
if(!$rs->eof)
{
$sql="update users set accessdenied=0 where id_parent=".(int)$_GET["id"];
$db->execute($sql);
$s=1;
}
else
{
$s=2;
}

if($s==1){
?>
<p><?=word_lang("confirmed registartion")?></p>

<?}else{?>
<p><?=word_lang("unconfirmed registration")?></p>
<?}?>

<?include("../inc/footer.php");?>
