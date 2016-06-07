<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>

<?

$sql="select id_parent,touser,fromuser,subject,data,viewed,trash,del from messages where touser='".result($_SESSION["people_login"])."' and trash=1 and del=0";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["m".$rs->row["id_parent"]]))
	{
		$sql="update messages set del=1 where id_parent=".$rs->row["id_parent"]." and  touser='".$_SESSION["people_login"]."'";
		$db->execute($sql);
	}
	$rs->movenext();
}



$db->close();


header("location:messages_trash.php");
?>