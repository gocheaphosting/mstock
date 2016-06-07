<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_creditstypes");


$sql="insert into credits (title,quantity,price,priority,days) values ('".result($_POST["title"])."',".(int)$_POST["quantity"].",".(float)$_POST["price"].",".(int)$_POST["priority"].",".(int)$_POST["days"].")";
$db->execute($sql);

$db->close();

header("location:index.php");
?>
