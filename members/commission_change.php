<?$site="commission";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
$sql="update users set ";
$sql_input="";

$zapros="select * from payout where activ=1";
$ds->open($zapros);
while(!$ds->eof)
{
	if($sql_input!=""){$sql_input.=",";}
	
	if($ds->row["svalue"]=="bank")
	{
		$sql_input.="bank_name='".result($_POST["bank_name"])."',bank_account='".result($_POST["bank_account"])."'";
	}
	else
	{
		$sql_input.=$ds->row["svalue"]."='".result($_POST[$ds->row["svalue"]])."'";
	}

	$ds->movenext();
}

$sql.=$sql_input." where id_parent=".(int)$_SESSION["people_id"];
$db->execute($sql);


if($global_settings["payout_set"])
{
	$sql="update users set payout_limit=".(float)@$_POST["payout_limit"]." where id_parent=".(int)$_SESSION["people_id"];
	$db->execute($sql);
}

header("location:commission.php?d=4");
?>