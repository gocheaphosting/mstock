<?include("../admin/function/db.php");?>
<?





$id_parent=(int)@$_REQUEST['id'];

$id=0;
$sql="select id from items where id_parent=".$id_parent." order by price";
$dr->open($sql);
if(!$dr->eof)
{
	$id=$dr->row["id"];
}

$params["item_id"]=$id;
$params["prints_id"]=0;

$params["publication_id"]=$id_parent;
$params["quantity"]=1;

for($i=1;$i<11;$i++)
{
	$params["option".$i."_id"]=0;
	$params["option".$i."_value"]="";
}

$params["rights_managed"]=1;

shopping_cart_add($params);







unset($_SESSION["box_shopping_cart"]);
unset($_SESSION["box_shopping_cart_lite"]);

$db->close();

header("location:shopping_cart.php");
?>