<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_customers");

$sql="select id_parent,name,login,email,examination,utype,category from users where id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	$_SESSION["people_id"]=$rs->row["id_parent"];
	$_SESSION["people_name"]=$rs->row["name"];
	$_SESSION["people_login"]=$rs->row["login"];
	$_SESSION["people_email"]=$rs->row["email"];
	$_SESSION["people_category"]=$rs->row["category"];
	$_SESSION["people_active"]=$rs->row["id_parent"];
	$_SESSION["people_type"]=$rs->row["utype"];
	$_SESSION["people_exam"]=$rs->row["examination"];
	
	header("location:../../index.php");
}
else
{
	header("location:index.php");
}

$db->close();
?>
