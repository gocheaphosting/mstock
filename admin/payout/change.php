<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_payout");


$sql="select * from payout";
$rs->open($sql);
while(!$rs->eof)
{
$activ=0;
if(isset($_POST["activ_".$rs->row["svalue"]])){$activ=1;}

$sql="update payout set activ=".$activ." where svalue='".$rs->row["svalue"]."'";
$db->execute($sql);


$rs->movenext();
}

$db->close();

header("location:index.php?d=1");
?>