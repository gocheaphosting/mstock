<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);



$publication_id=(int)$_REQUEST["publication_id"];
$price_id=(int)$_REQUEST["price_id"];
$option_id=(int)$_REQUEST["option_id"];
$option_value=(int)$_REQUEST["option_value"];

$_SESSION["rights_managed_value".$publication_id][$option_id]=$option_value;

include("../admin/rights_managed/rights_managed_functions.php");


$test="";

//Define step id
$step_id=define_step_id($price_id,$option_value);

$remove_steps=array();
$flag=false;
$sql="select id from rights_managed_structure where price_id=".(int)$price_id." and types=0 order by id";
$ds->open($sql);
while(!$ds->eof)
{
	if($flag)
	{	
		$remove_steps[]=$ds->row["id"];		
	}

	if($ds->row["id"]==$step_id)
	{
		$flag=true;
	}
	$ds->movenext();
}

//Remove other branches
$nlimit=0;
remove_other_branches($publication_id,$price_id,$option_id);


//Remove next steps

for($i=0;$i<count($remove_steps);$i++)
{
	foreach ($_SESSION["rights_managed".$publication_id] as $key => $value) 
	{
		if($value==$remove_steps[$i])
		{
			unset($_SESSION["rights_managed".$publication_id][$key]);
		}
	}
}




$flag_finish=true;
$next_options=array();
$sql="select id,conditions from rights_managed_structure where id_parent=".$option_value." and types=1 and price_id=".$price_id;
$rs->open($sql);
if(!$rs->eof)
{
	while(!$rs->eof)
	{
		if(define_conditions($rs->row["id"],$price_id,$rs->row["conditions"],$publication_id))
		{
			$_SESSION["rights_managed".$publication_id][$rs->row["id"]]=$step_id;
			$_SESSION["rights_managed".$publication_id][$option_id]=$step_id;
			$flag_finish=false;
		}
		$rs->movenext();
	}
	
	
}
else
{
	//Move to other step
	
	//Define next step
	$flag=false;
	$next_step_id=0;
	
	$sql="select id from rights_managed_structure where price_id=".(int)$price_id." and types=0 order by id";
	$ds->open($sql);
	while(!$ds->eof)
	{
		if($flag)
		{
			$next_step_id=$ds->row["id"];
			$flag=false;
		}
		
		if($ds->row["id"]==$step_id)
		{
			$flag=true;
		}
		
		$ds->movenext();
	}
	
	if($next_step_id>0)
	{
		$sql="select id,conditions from rights_managed_structure where id_parent=".$next_step_id." and  types=1 and price_id=".$price_id;
		$ds->open($sql);
		while(!$ds->eof)
		{		
			if(define_conditions($ds->row["id"],$price_id,$ds->row["conditions"],$publication_id))
			{
				$_SESSION["rights_managed".$publication_id][$ds->row["id"]]=$next_step_id;
				$flag_finish=false;
			}
			$ds->movenext();
		}
	}
}




$new_options_list="";
foreach ($_SESSION["rights_managed".$publication_id] as $key => $value) 
{
	if($new_options_list!=""){$new_options_list.="-";}
	$new_options_list.=$key;
}


$price=0;

$sql="select price from rights_managed where id=".$price_id;
$rs->open($sql);
if(!$rs->eof)
{
	$price=$rs->row["price"];
}

$nlimit=0;
calculate_price($publication_id,$price_id,0);

$price_content=currency(1).float_opt($price,2)." ".currency(2);

$_SESSION["rights_managed_price".$publication_id]=$price;

$GLOBALS['_RESULT'] = array(
  "next_options"     => $new_options_list,
  "price" => $price_content,
    "finish" => (int)$flag_finish
); 

$db->close();
?>