<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$title="";
$price="";
$publication_title="";
$prints_options=array();

$params["stock"]=1;
$params["stock_type"]=result($_REQUEST["stock_type"]);
$params["stock_id"]=(int)$_REQUEST["stock_id"];
$params["stock_url"]=result($_REQUEST["stock_url"]);
$params["stock_preview"]=result($_REQUEST["stock_preview"]);
$params["stock_site_url"]=result($_REQUEST["stock_site_url"]);

//Prints
$sql="select id_parent,title,price,option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where id_parent=".(int)$_REQUEST["print_id"];
$dr->open($sql);
if(!$dr->eof)
{
	$params["prints_id"]=$dr->row["id_parent"];
	$title=$dr->row["title"];
	$price=$dr->row["price"];
	
	for($i=1;$i<11;$i++)
	{
		$prints_options[]=(int)$dr->row["option".$i];
	}
}

$params["item_id"]=0;
$params["publication_id"]=$params["stock_id"];
$publication_title= @$mstocks[$params["stock_type"]];	


$params["quantity"]=1;

for($i=1;$i<11;$i++)
{
	$params["option".$i."_id"]=0;
	$params["option".$i."_value"]="";
}

$cart_id=shopping_cart_add($params);



$cart_content=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."shopping_cart.tpl");

//Show thumb
$cart_content=str_replace("{IMAGE}",$params["stock_preview"],$cart_content);
$cart_content=str_replace("{WIDTH}",100,$cart_content);
$cart_content=str_replace("{HEIGHT}","",$cart_content);

$cart_content=str_replace("{PUBLICATION_TITLE}",$publication_title,$cart_content);
$cart_content=str_replace("{TITLE}",$title,$cart_content);
$cart_content=str_replace("{ID}",$params["publication_id"],$cart_content);
$cart_content=str_replace("{ITEM_ID}",$params["item_id"],$cart_content);
$cart_content=str_replace("{PRINTS_ID}",$params["prints_id"],$cart_content);
$cart_content=str_replace("{PRICE}",currency(1).float_opt($price,2)." ".currency(2),$cart_content);



$prints_content="";
for($i=0;$i<count($prints_options);$i++)
{
	if($prints_options[$i]!=0)
	{
		//Default meaning
		$option_default="";
		$sql="select option".($i+1)."_value from carts_content where id_parent=".$cart_id." and prints_id=".$params["prints_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$option_default=$ds->row["option".($i+1)."_value"];
		}
		
		$sql="select title,type,activ,required from products_options where activ=1 and id=".$prints_options[$i];
		$dr->open($sql);
		if(!$dr->eof)
		{
			$prints_content.="<div class='param'><b>".$dr->row["title"].":</b><br>";
			
			if($dr->row["type"]=="selectform")
			{
				$prints_content.="<select name='option".$prints_options[$i]."' style='width:150px'>";
			}
			
			$sql="select id,title,price,adjust from products_options_items where id_parent=".$prints_options[$i]." order by id";
			$ds->open($sql);
			while(!$ds->eof)
			{
				$sel="";
				$sel2="";
				
				if($option_default==$ds->row["title"])
				{
					$sel="selected";	
				}
				$sel2="checked";
				
				if($dr->row["type"]=="selectform")
				{
					$prints_content.="<option value='".$ds->row["title"]."' ".$sel.">".$ds->row["title"]."</option>";
				}
				if($dr->row["type"]=="radio")
				{
					$prints_content.="<input name='option".$prints_options[$i]."' type='radio' value='".$ds->row["title"]."' ".$sel2.">&nbsp;".$ds->row["title"]."&nbsp;&nbsp;";
				}
				
				$ds->movenext();
			}
			
			if($dr->row["type"]=="selectform")
			{
				$prints_content.="</select>";
			}
			
			$prints_content.="</div>";
		}
	}
}


$sql="select id from carts_content where id_parent=".$cart_id." and item_id=".$params["item_id"]." and prints_id=".$params["prints_id"];
$ds->open($sql);$sql2=$sql;
if(!$ds->eof)
{
	$cart_content=str_replace("{CONTENT_ID}",$ds->row["id"],$cart_content);
}

$cart_content=str_replace("{PRINTS_OPTIONS}",$prints_content,$cart_content);
$cart_content=str_replace("{SITE_ROOT}",site_root,$cart_content);
$cart_content=translate_text($cart_content);

unset($_SESSION["box_shopping_cart"]);
unset($_SESSION["box_shopping_cart_lite"]);

include("shopping_cart_add_content.php");
$GLOBALS['_RESULT'] = array(
  "box_shopping_cart"     => $box_shopping_cart,
  "box_shopping_cart_lite"     => $box_shopping_cart_lite,
  "cart_content"     => $cart_content
); 

$db->close();
?>