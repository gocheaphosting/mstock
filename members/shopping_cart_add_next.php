<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

for($i=1;$i<11;$i++)
{
	$params["option".$i."_id"]=0;
	$params["option".$i."_value"]="";
}

$content_id=(int)@$_REQUEST['content_id'];


if(@$_REQUEST['item_id']!=0)
{
	//Files
	$params["item_id"]=(int)@$_REQUEST['item_id'];
	$params["prints_id"]=0;

	
	$sql="select id_parent from items where id=".$params["item_id"];
	$dr->open($sql);
	if(!$dr->eof)
	{
		$params["publication_id"]=$dr->row["id_parent"];
	}
}


if(@$_REQUEST['prints_id']!=0)
{
	//Prints
	$params["prints_id"]=(int)@$_REQUEST['prints_id'];
	$params["item_id"]=0;
	
	$sql="select stock,stock_id from carts_content where stock=1 and id=".$content_id;
	$ds->open($sql);
	if(!$ds->eof)
	{
		$params["publication_id"]=$ds->row["stock_id"];
		
		$sql="select option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where id_parent=".$params["prints_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			for($i=1;$i<11;$i++)
			{
				$params["option".$i."_id"]=(int)$ds->row["option".$i];
			
				if($params["option".$i."_id"]!=0 and isset($_REQUEST["option".$params["option".$i."_id"]]))
				{
					$params["option".$i."_value"]=result($_REQUEST["option".$params["option".$i."_id"]]);
				}
			}
		}
	}
	else
	{	
		$sql="select itemid,title,price,printsid from prints_items where id_parent=".$params["prints_id"];
		$dr->open($sql);
		if(!$dr->eof)
		{
			$params["publication_id"]=$dr->row["itemid"];
			
			$sql="select option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where id_parent=".$dr->row["printsid"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				for($i=1;$i<11;$i++)
				{
					$params["option".$i."_id"]=(int)$ds->row["option".$i];
				
					if($params["option".$i."_id"]!=0 and isset($_REQUEST["option".$params["option".$i."_id"]]))
					{
						$params["option".$i."_value"]=result($_REQUEST["option".$params["option".$i."_id"]]);
					}
				}
			}
		}
	}
}




$cart_id=shopping_cart_id();

$sql="update carts_content set option1_id=".$params["option1_id"].",option1_value='".$params["option1_value"]."',option2_id=".$params["option2_id"].",option2_value='".$params["option2_value"]."',option3_id=".$params["option3_id"].",option3_value='".$params["option3_value"]."',option4_id=".$params["option4_id"].",option4_value='".$params["option4_value"]."',option5_id=".$params["option5_id"].",option5_value='".$params["option5_value"]."',option6_id=".$params["option6_id"].",option6_value='".$params["option6_value"]."',option7_id=".$params["option7_id"].",option7_value='".$params["option7_value"]."',option8_id=".$params["option8_id"].",option8_value='".$params["option8_value"]."',option9_id=".$params["option9_id"].",option9_value='".$params["option9_value"]."',option10_id=".$params["option10_id"].",option10_value='".$params["option10_value"]."' where id_parent=".$cart_id." and item_id=".$params["item_id"]." and prints_id=".$params["prints_id"]." and id=".$content_id;
$db->execute($sql);

unset($_SESSION["box_shopping_cart"]);
unset($_SESSION["box_shopping_cart_lite"]);


echo("ok");

$db->close();
?>