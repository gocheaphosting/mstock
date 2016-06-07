<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);





$id_parent=(int)@$_REQUEST['id'];

$rights_managed=0;
$url="";

//If rights_managed
$sql="select module_table from structure where id=".$id_parent;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["module_table"]==30)
	{
		$sql="select rights_managed from photos where id_parent=".$id_parent;
	}
	if($rs->row["module_table"]==31)
	{
		$sql="select rights_managed from videos where id_parent=".$id_parent;
	}
	if($rs->row["module_table"]==52)
	{
		$sql="select rights_managed from audio where id_parent=".$id_parent;
	}
	if($rs->row["module_table"]==53)
	{
		$sql="select rights_managed from vector where id_parent=".$id_parent;
	}
	$ds->open($sql);
	if(!$ds->eof)
	{
		if($ds->row["rights_managed"]>0)
		{
			$rights_managed=1;
			$url=item_url($id_parent);
		}
	}
}


//If not rights_managed
if($rights_managed==0)
{
	$id=0;
	$sql="select id from items where id_parent=".$id_parent." and price<>0 order by price";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$id=$dr->row["id"];
	}
	if($global_settings["printsonly"])
	{
		$printsid="";
		$sql="select id_parent from prints where photo=1";
		$dr->open($sql);
		while(!$dr->eof)
		{
			if($printsid!="")
			{
				$printsid.=" or ";
			}
			$printsid.="printsid=".$dr->row["id_parent"];
			$dr->movenext();
		}
			
		if($printsid!="")
		{
			$printsid=" and (".$printsid.")";
		}
		
		$sql="select id_parent from prints_items where itemid=".$id_parent.$printsid." order by price";
		$dr->open($sql);
		if(!$dr->eof)
		{
			$id=$dr->row["id_parent"];
		}
	}

	if(!$global_settings["printsonly"])
	{
		//Files
		$params["item_id"]=$id;
		$params["prints_id"]=0;
	}
	else
	{
		//Prints
		$params["prints_id"]=$id;
		$params["item_id"]=0;
	}

	$params["publication_id"]=$id_parent;
	$params["quantity"]=1;

	for($i=1;$i<11;$i++)
	{
		$params["option".$i."_id"]=0;
		$params["option".$i."_value"]="";
	}

	shopping_cart_add($params);

	unset($_SESSION["box_shopping_cart"]);
	unset($_SESSION["box_shopping_cart_lite"]);
}

include("shopping_cart_add_content.php");
$GLOBALS['_RESULT'] = array(
  "box_shopping_cart"     => $box_shopping_cart,
  "box_shopping_cart_lite"     => $box_shopping_cart_lite,
  "rights_managed" => $rights_managed,
  "url" => $url
); 

$db->close();
?>