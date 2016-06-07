<?
if(!defined("site_root")){exit();}


include($_SERVER["DOCUMENT_ROOT"].site_root."/admin/plugins/printful/PrintfulClient.php");

define('API_KEY', $global_settings["printful_api"]);


$pf = new PrintfulClient(API_KEY);

$confirm=0;
if($global_settings["printful_mode"]=="confirm")
{
	$confirm=1;
}


for($i=0;$i<count($printful_ids);$i++)
{
	$sql="select id,user,shipping_firstname,shipping_lastname,shipping_address,shipping_country,shipping_city,shipping_zip,shipping_state from orders where id=".(int)$printful_ids[$i]." and status=1";
	$rs->open($sql);
	if(!$rs->eof)
	{
		// create the order
		$name=$rs->row["shipping_firstname"]." ".$rs->row["shipping_lastname"];
		$adr1="";
		$adr2="";
		$adr=explode("\n",$rs->row["shipping_address"]);
		$adr1=$adr[0];
		if(isset($adr[1]))
		{
			$adr2=$adr[1];
		}

		$text="Order ID".$printful_ids[$i];

		
		try{
			
			$items=array();
			
			$sql="select item,price,quantity,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,printslab,printslab_id,prints from orders_content where prints=1 and id_parent=".(int)$printful_ids[$i];
			$ds->open($sql);
			while(!$ds->eof)
			{		
				$printful_id=0;
				
				if($ds->row["prints"]==1)
				{
					if($ds->row["printslab"]!=1)
					{
						$sql="select printsid,itemid,title from prints_items where id_parent=".$ds->row["item"];
						$dr->open($sql);
						if(!$dr->eof)
						{
							$sql="select printful_id from printful_prints where print_id=".$dr->row["printsid"]." and option1=".$ds->row["option1_id"]." and option1_value='".$ds->row["option1_value"]."'  and option2=".$ds->row["option2_id"]." and option2_value='".$ds->row["option2_value"]."'  and option3=".$ds->row["option3_id"]." and option3_value='".$ds->row["option3_value"]."'  and option4=".$ds->row["option4_id"]." and option4_value='".$ds->row["option4_value"]."'  and option5=".$ds->row["option5_id"]." and option5_value='".$ds->row["option5_value"]."'  and option6=".$ds->row["option6_id"]." and option6_value='".$ds->row["option6_value"]."'  and option7=".$ds->row["option7_id"]." and option7_value='".$ds->row["option7_value"]."'  and option8=".$ds->row["option8_id"]." and option8_value='".$ds->row["option8_value"]."'  and option9=".$ds->row["option9_id"]." and option9_value='".$ds->row["option9_value"]."'  and option10=".$ds->row["option10_id"]." and option10_value='".$ds->row["option10_value"]."' ";
							$dn->open($sql);
							if(!$dn->eof)
							{
								$printful_id=$dn->row["printful_id"];
							}
						}
					}
					else
					{
						$sql="select printful_id from printful_prints where print_id=".$ds->row["item"]." and option1=".$ds->row["option1_id"]." and option1_value='".$ds->row["option1_value"]."'  and option2=".$ds->row["option2_id"]." and option2_value='".$ds->row["option2_value"]."'  and option3=".$ds->row["option3_id"]." and option3_value='".$ds->row["option3_value"]."'  and option4=".$ds->row["option4_id"]." and option4_value='".$ds->row["option4_value"]."'  and option5=".$ds->row["option5_id"]." and option5_value='".$ds->row["option5_value"]."'  and option6=".$ds->row["option6_id"]." and option6_value='".$ds->row["option6_value"]."'  and option7=".$ds->row["option7_id"]." and option7_value='".$ds->row["option7_value"]."'  and option8=".$ds->row["option8_id"]." and option8_value='".$ds->row["option8_value"]."'  and option9=".$ds->row["option9_id"]." and option9_value='".$ds->row["option9_value"]."'  and option10=".$ds->row["option10_id"]." and option10_value='".$ds->row["option10_value"]."' ";
						$dn->open($sql);
						if(!$dn->eof)
						{
							$printful_id=$dn->row["printful_id"];
						}
					}
				}
				
				if($ds->row["prints"]==1 and $printful_id!=0)
				{
					if($ds->row["printslab"]!=1)
					{
						$sql="select printsid,itemid,title from prints_items where id_parent=".$ds->row["item"];
						$dr->open($sql);
						if(!$dr->eof)
						{
							$url="";
							$flag_storage=false;
				
							$sql="select url,filename2,filename1,width,height,item_id from filestorage_files where id_parent=".$dr->row["itemid"];
							$dn->open($sql);
							while(!$dn->eof)
							{
								if($dn->row["item_id"]!=0)
								{
									$url=$dn->row["url"]."/".$dn->row["filename2"];
								}
					
								$flag_storage=true;
								$dn->movenext();
							}
				
							if(!$flag_storage)
							{
								$sql="select server1 from photos where id_parent=".$dr->row["itemid"];
								$dd->open($sql);
								if(!$dd->eof)
								{
									$sql="select url from items where id_parent=".$dr->row["itemid"];
									$dn->open($sql);
									if(!$dn->eof)
									{
										$afile=$dn->row["url"];
									}
									else
									{
										$dir = opendir ($DOCUMENT_ROOT.server_url($dd->row["server1"])."/".$dr->row["itemid"]);
										while ($file = readdir ($dir)) 
										{
											if($file <> "." && $file <> "..")
											{
												if(preg_match("/.jpg$|.jpeg$/i",$file) and !preg_match("/thumb/",$file) and !preg_match("/photo_[0-9]+/",$file)) 
												{
													$afile=$file;
												}
											}
										}
										closedir ($dir);
									}
						
									if($afile!="")
									{
										$url=$_SERVER["DOCUMENT_ROOT"].site_root.server_url($dd->row["server1"])."/".$dr->row["itemid"]."/".$afile;
									}
								}
							}
								
							if($url!="")
							{
								if(!$flag_storage)
								{
									$new_url=site_root."/content/printful/".$rs->row["id"]."_".md5(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))).".jpg";
									copy($url,$_SERVER["DOCUMENT_ROOT"].$new_url);
									$url=surl.$new_url;
								}
								$items[]=array(
								'variant_id' => $printful_id,
								'name' => $title, 
								'retail_price' => $ds->row["price"], 
								'quantity' => $ds->row["quantity"],
								'files' => array(
									array(
										'url' => $url
									))
								);								
							}			
						}
					}
					else
					{
						$url="";
						$sql="select id,title,id_parent,photo from galleries_photos where id=".$ds->row["printslab_id"];
						$dn->open($sql);
						if(!$dn->eof)
						{
							$url=surl.site_root."/content/galleries/".$dn->row["id_parent"]."/".$dn->row["photo"];
						}

						if($url!="")
						{
							$items[]=array(
							'variant_id' => $printful_id,
							'name' => $title, 
							'retail_price' => $ds->row["price"], 
							'quantity' => $ds->row["quantity"],
							'files' => array(
								array(
									'url' => $url
								))
							);										
						}
					}
				}
			
				$ds->movenext();
			}
		
			if(count($items)>0)
			{
				$order = $pf->post('orders',
					array(
						'recipient' => array(
							'name' => $name,
							'address1' => $adr1,
							'city' => $rs->row["shipping_city"],
							'state_code' => $rs->row["shipping_state"],
							'country_code' => @$mcountry_code[$rs->row["shipping_country"]],
							'zip' => $rs->row["shipping_city"]
						),
						'items' => $items
					),
					array('confirm'=>$confirm)
				);
				//var_export($order);
			
				$sql="insert printful_orders (order_id,printful_id,data) values (".$rs->row["id"].",".$order["id"].",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).")";
				$db->execute($sql);
			}
		
		
		}
		catch(PrintfulApiException $e){ //API response status code was not successful
			echo 'Printful API Exception: '.$e->getCode().' '.$e->getMessage();
		}
		catch(PrintfulException $e){ //API call failed
			echo 'Printful Exception: '.$e->getMessage();
			var_export($pf->getLastResponseRaw());
		}
	
	}
}


?>