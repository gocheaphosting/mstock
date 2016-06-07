<? 
include("../function/db.php");
if(!isset($_SESSION['entry_admin'])){redirect("../auth/");}
include("../inc/begin.php");
include("../templates_admin/".$admin_template."/home.php");
include("../inc/end.php");
?>