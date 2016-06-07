<?
if(!defined("site_root")){exit();}

function build_rights_managed_admin($t_id)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	global $itg;
	global $nlimit;
	global $_GET;

	$sql="select * from rights_managed_structure where id_parent=".$t_id." and price_id=".(int)$_GET["id"]." order by id";
	$dp->open($sql);
	while(!$dp->eof)
	{
		if($nlimit<2000)
		{
			$collapse="minus";
			$collapse_style="block";
			if($dp->row["collapse"]==1)
			{
				$collapse="plus";
				$collapse_style="none";
			}
			
			if($dp->row["types"]==0)
			{
				$itg.="<div class='subheader'><a class='btn btn-mini btn-default btn-sm' href='collapse.php?id=".$dp->row["id"]."&price=".(int)$_GET["id"]."'><i class='icon-".$collapse." fa fa-".$collapse."'></i></a>&nbsp;&nbsp;".word_lang($dp->row["title"])."<a class=\"btn btn-mini btn-danger btn-sm toright\" href=\"javascript:open_preview('".(int)$_GET["id"]."','step_delete',".$dp->row["id"].")\" style=\"margin-left:5px\">".word_lang("delete")."</a><a class=\"btn btn-mini btn-warning btn-sm toright\" href=\"javascript:open_preview('".(int)$_GET["id"]."','step_edit',".$dp->row["id"].")\" style=\"margin-left:5px\">".word_lang("edit")."</a><a class=\"btn btn-mini btn-primary btn-sm toright\" href=\"javascript:open_preview('".(int)$_GET["id"]."','group_add',".$dp->row["id"].")\" style=\"margin-left:5px\">".word_lang("add group")."</a></div><div class='subheader_text' style='display:".$collapse_style."'>";
			}
			
			if($dp->row["types"]==1)
			{
				$itg.="<div class='group_box'><a class=\"btn btn-mini  btn-default btn-sm toright\" href=\"javascript:open_preview('".(int)$_GET["id"]."','group_delete',".$dp->row["id"].")\" style=\"margin-left:5px\">".word_lang("delete")."</a><a class=\"btn btn-mini btn-default btn-sm toright\" href=\"javascript:open_preview('".(int)$_GET["id"]."','conditions',".$dp->row["id"].")\" style=\"margin-left:5px\">".word_lang("conditions")."</a><h2><a class='btn btn-mini' href='collapse.php?id=".$dp->row["id"]."&price=".(int)$_GET["id"]."'><i class='icon-".$collapse."'></i></a>&nbsp;&nbsp;".word_lang($dp->row["title"])."</h2><div style='display:".$collapse_style."'>";
			}
			
			if($dp->row["types"]==2)
			{
				$itg.="<div class='option_box'><a class=\"btn btn-mini  btn-default btn-sm toright\" href=\"javascript:open_preview('".(int)$_GET["id"]."','option_delete',".$dp->row["id"].")\" style=\"margin-left:5px\">".word_lang("delete")."</a><a class=\"btn btn-mini toright\" href=\"javascript:open_preview('".(int)$_GET["id"]."','option_edit',".$dp->row["id"].")\" style=\"margin-left:5px\">".word_lang("edit")."</a><a class=\"btn btn-mini toright\" href=\"javascript:open_preview('".(int)$_GET["id"]."','group_add',".$dp->row["id"].")\" style=\"margin-left:5px\">".word_lang("add group")."</a><h3>".word_lang($dp->row["title"])."</h3>".word_lang("price").": <span class='option_price'>".$dp->row["adjust"]." ".$dp->row["price"]."</span>";
			}

			build_rights_managed_admin($dp->row["id"]);
		
			if($dp->row["types"]==1)
			{
			 	$itg.="</div>";
			}
			
			$itg.="</div>";
		}
		$nlimit++;
		$dp->movenext();
	}
}


function delete_rights_managed_admin($t_id)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	global $nlimit;
	
	$sql="delete from rights_managed_structure where id=".(int)$t_id;
	$db->execute($sql);

	$sql="select * from rights_managed_structure where id_parent=".(int)$t_id;
	$dp->open($sql);
	while(!$dp->eof)
	{
		if($nlimit<2000)
		{
			delete_rights_managed_admin($dp->row["id"]);
		}
		$nlimit++;
		$dp->movenext();
	}
}





function build_rights_managed($publication_id,$t_id,$price_id)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	
	global $itg;
	global $nlimit;
	global $flag_visible;
	global $first_step_id;
	global $_SESSION;

	$sql="select * from rights_managed_structure where id_parent=".$t_id." and price_id=".(int)$price_id." order by id";
	$dp->open($sql);
	while(!$dp->eof)
	{
		if($nlimit<2000)
		{		
			if($dp->row["types"]==0)
			{
				$itg.="<div class='subheader' style='width:100%'>".word_lang($dp->row["title"])."</div><div class='subheader_text' style='margin:0px 8px 5px 8px;padding:0px'>";
			}
			
			if($dp->row["types"]==1)
			{			
				if($flag_visible or $first_step_id==$dp->row["id_parent"])
				{
				 	$style="style='display:block'";
				 	$_SESSION["rights_managed_value".$publication_id][$dp->row["id"]]=0;
				 	$_SESSION["rights_managed".$publication_id][$dp->row["id"]]=$dp->row["id_parent"];
				}
				else
				{
					$style="style='display:none'";
				}
				
				$itg.="<div class='group_box' id='group_box".$dp->row["id"]."' ".$style."><div style='margin-bottom:3px;'><b>".word_lang($dp->row["title"]).":</b></div><select name='group".$dp->row["id"]."' id='group".$dp->row["id"]."' style='width:350px' class='form-control' onChange='change_rights_managed(".$publication_id.",".$price_id.",".$dp->row["id"].",this.value)'><option value='0'>...</option>";
					
				$sql="select id,title from rights_managed_structure where id_parent=".$dp->row["id"];
				$dt->open($sql);
				while(!$dt->eof)
				{
					$itg.="<option value='".$dt->row["id"]."'>".word_lang($dt->row["title"])."</option>";
					$dt->movenext();
				}
				
				$itg.="</select></div>";
				
				if($flag_visible)
				{
					$first_step_id=$dp->row["id_parent"];
				}
				
				$flag_visible=false;
			}
			
			build_rights_managed($publication_id,$dp->row["id"],$price_id);		

			if($dp->row["types"]==0)
			{
				$itg.="</div>";
			}
		}
		$nlimit++;
		$dp->movenext();
	}
}


function define_step_id($price_id,$t_id)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$nlimit=0;
	$types=2;
	$step_id=$t_id;
	
	while($types!=0 and $nlimit<20)
	{
		$sql="select id,id_parent,types from rights_managed_structure where id=".(int)$t_id." and price_id=".(int)$price_id;
		$dp->open($sql);
		if(!$dp->eof)
		{		
			$types=$dp->row["types"];
			$t_id=$dp->row["id_parent"];
			$step_id=$dp->row["id"];
		}
		$nlimit++;
	}
	
	return $step_id;
}




function remove_other_branches($publication_id,$price_id,$option_id)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;

	global $nlimit;
	global $_SESSION;

	$sql="select id from rights_managed_structure where id_parent=".$option_id." and price_id=".(int)$price_id." order by id";
	$dp->open($sql);
	while(!$dp->eof)
	{
		if($nlimit<2000)
		{				
			if(isset($_SESSION["rights_managed".$publication_id][$dp->row["id"]]))
			{
				unset($_SESSION["rights_managed".$publication_id][$dp->row["id"]]);
				unset($_SESSION["rights_managed_value".$publication_id][$dp->row["id"]]);
			}
			
			remove_other_branches($publication_id,$price_id,$dp->row["id"]);		
		}
		$nlimit++;
		$dp->movenext();
	}
}


function define_conditions($t_id,$price_id,$conditions,$publication_id)
{
	global $_SESSION;
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$flag_conditions=true;
	
	if($conditions!="")
	{
		$mass_conditions=explode("-",$conditions);

		for($i=0;$i<count($mass_conditions);$i++)
		{
			if($mass_conditions[$i]!=0)
			{
				$sql="select id from rights_managed_structure where (option_id=".$mass_conditions[$i]." and types=2) or (group_id=".$mass_conditions[$i]." and types=1) and price_id=".$price_id;
				$dp->open($sql);
				if(!$dp->eof)
				{
					$mass_conditions[$i]=$dp->row["id"];			
				}
				else
				{
					unset($mass_conditions[$i]);
				}
			}
		}	

		$flag2=false;
		
		foreach ($_SESSION["rights_managed".$publication_id] as $key => $value) 
		{
			$sql="select id,id_parent from rights_managed_structure where id=".(int)$key." and price_id=".$price_id;
			$dp->open($sql);
			if(!$dp->eof)
			{
				for($i=0;$i<count($mass_conditions);$i++)
				{
					if($mass_conditions[$i]==$dp->row["id"] or $mass_conditions[$i]==$dp->row["id_parent"])
					{
						$flag2=true;
					}
				}			
			}
		}
		
		$flag_conditions=$flag2;
		
		//If all conditions = 0
		$flag3=false;
		for($i=0;$i<count($mass_conditions);$i++)
		{
			if($mass_conditions[$i]!=0)
			{
				$flag3=true;
			}
		}
		
		if(!$flag3)
		{
			$flag_conditions=true;
		}
		
	}
	
	return $flag_conditions;
}






function calculate_price($publication_id,$price_id,$t_id)
{
	global $db;
	$dp = new TMySQLQuery;
	$dp->connection = $db;
	
	$dt = new TMySQLQuery;
	$dt->connection = $db;
	
	global $test;
	global $price;
	global $nlimit;
	global $_SESSION;

	$sql="select * from rights_managed_structure where id_parent=".$t_id." and price_id=".(int)$price_id." order by id";
	$dp->open($sql);
	while(!$dp->eof)
	{
		if($nlimit<2000)
		{		
			if(isset($_SESSION["rights_managed".$publication_id][$dp->row["id"]]) and isset($_SESSION["rights_managed_value".$publication_id][$dp->row["id"]]))
			{
				$sql="select adjust,price from rights_managed_structure where id=".(int)$_SESSION["rights_managed_value".$publication_id][$dp->row["id"]]." and price_id=".(int)$price_id;
				$dt->open($sql);
				if(!$dt->eof)
				{
					$test.="(".$dt->row["adjust"]." ".$dt->row["price"].")";
					if($dt->row["adjust"]=="+")
					{
						$price+=$dt->row["price"];
					}
					if($dt->row["adjust"]=="-")
					{
						$price-=$dt->row["price"];
					}
					if($dt->row["adjust"]=="x")
					{
						$price*=$dt->row["price"];
					}
				}
			}
			
			calculate_price($publication_id,$price_id,$dp->row["id"]);
		}
		$nlimit++;
		$dp->movenext();
	}
}






?>