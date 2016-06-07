<?
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}

$sql="select id from galleries where id=".(int)$_POST["gallery_id"]." and user_id=".(int)$_SESSION["people_id"];
$rs->open($sql);
if($rs->eof)
{
	exit();
}



$sql="select * from galleries_photos where id_parent=".(int)$_POST["gallery_id"];
$dn->open($sql);
while(!$dn->eof)
{
	if(isset($_POST["sel".$dn->row["id"]]))
	{
		$rights_managed=0;

		$params["prints_id"]=(int)$_POST["prints".$dn->row["id"]];
		$params["item_id"]=0;
		
		$params["publication_id"]=$dn->row["id"];
		$params["quantity"]=1;
		
		for($i=1;$i<11;$i++)
		{
			$params["option".$i."_id"]=0;
			$params["option".$i."_value"]="";
		}
		
		for($i=1;$i<11;$i++)
		{
			foreach ($_POST as $key => $value) 	
			{
				if(preg_match("/option".$dn->row["id"]."_".(int)$_POST["prints".$dn->row["id"]]."_".$i."_/i",$key))
				{
					$params["option".$i."_id"]=strval(str_replace("option".$dn->row["id"]."_".(int)$_POST["prints".$dn->row["id"]]."_".$i."_","",$key));
					$params["option".$i."_value"]=result($value);
				}
			}
		}
		
		$params["printslab"]=1;
		
		shopping_cart_add($params);	
		
		unset($_SESSION["box_shopping_cart"]);
		unset($_SESSION["box_shopping_cart_lite"]);
	}
	$dn->movenext();
}

header("location:shopping_cart.php");
?>

