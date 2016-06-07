<? 
include("../function/db.php");
if(!isset($_SESSION['entry_admin'])){redirect("../auth/");exit();}
include("../../members/JsHttpRequest.php");

$JsHttpRequest =new JsHttpRequest($mtg);

$_SESSION["admin_rows_".result($_REQUEST["id"])]=1;

$db->close();
?>
