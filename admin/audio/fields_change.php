<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_audio");

$sql="select * from audio_fields order by priority";
$rs->open($sql);
while(!$rs->eof)
{

$enbl=0;
$rqd=0;
if(isset($_POST["enable".$rs->row["id"]])){$enbl=1;}
if(isset($_POST["required".$rs->row["id"]])){$rqd=1;}

$sql="update audio_fields set priority=".(int)$_POST["priority".$rs->row["id"]].",activ=".$enbl.",required=".$rqd."  where id=".$rs->row["id"];
$db->execute($sql);

$rs->movenext();
}


$db->close();

header("location:index.php?d=0");
?>