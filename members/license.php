<?$site="license";$site2="";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<?
$sql="select * from licenses order by id_parent";
$rs->open($sql);
while(!$rs->eof)
{
?>
<h1><?=$rs->row["name"]?></h1>

<?=translate_text($rs->row["description"])?><br><br><br>
<?
$rs->movenext();
}
?>














<?include("../inc/footer.php");?>