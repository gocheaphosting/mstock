<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_downloads");
?>
<? include("../inc/begin.php");?>





<h1><?=word_lang("downloads")?></h1>


<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">



function deselect_row(value)
{
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			$("#"+value).removeClass("success");
        }
    }
    req.open(null, '<?=site_root?>/admin/inc/deselect.php', true);
    req.send( {'id': value} );
}
</script>


<?
//Get Search
$search="";
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if(isset($_POST["search"])){$search=result($_POST["search"]);}

//Get Search type
$search_type="";
if(isset($_GET["search_type"])){$search_type=result($_GET["search_type"]);}
if(isset($_POST["search_type"])){$search_type=result($_POST["search_type"]);}



$type="";
if(isset($_REQUEST["type"])){$type=result($_REQUEST["type"]);}


//Items
$items=30;
if(isset($_GET["items"])){$items=(int)$_GET["items"];}
if(isset($_POST["items"])){$items=(int)$_POST["items"];}


//Search variable
$var_search="search=".$search."&search_type=".$search_type."&items=".$items."&type=".$type;






//Sort by date
$adate=0;
if(isset($_GET["adate"])){$adate=(int)$_GET["adate"];}



//Sort by ID
$aid=0;
if(isset($_GET["aid"])){$aid=(int)$_GET["aid"];}

//Sort by default
if($adate==0 and $aid==0)
{
$adate=2;
}



//Add sort variable
$com="";


if($adate!=0)
{
	$var_sort="&adate=".$adate;
	if($adate==1){$com=" order by data ";}
	if($adate==2){$com=" order by data desc ";}
}



if($aid!=0)
{
	$var_sort="&aid=".$aid;
	if($aid==1){$com=" order by id ";}
	if($aid==2){$com=" order by id desc ";}
}








//Items on the page
$items_mass=array(10,20,30,50,75,100);




//Search parameter
$com2="";

if($search!="")
{
	if($search_type=="user")
	{
		$com2.=" and user_id=".user_url(result($search))." ";
	}
	if($search_type=="order")
	{
		$com2.=" and order_id=".(int)$search." ";
	}
	if($search_type=="subscription")
	{
		$com2.=" and subscription_id=".(int)$search." ";
	}
	if($search_type=="file")
	{
		$com2.=" and publication_id=".(int)$search." ";
	}
}


if($type!="")
{
	if($type=="order")
	{
		$com2.=" and order_id>0 ";
	}
	if($type=="subscription")
	{
		$com2.=" and subscription_id>0 ";
	}
	if($type=="free")
	{
		$com2.=" and order_id=0 and subscription_id=0 ";
	}
}



//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select * from downloads where id>0 ";



$sql.=$com2.$com;

$rs->open($sql);
$record_count=$rs->rc;





//limit
$lm=" limit ".($kolvo*($str-1)).",".$kolvo;




$sql.=$lm;

//echo($sql);
$rs->open($sql);

?>
<div id="catalog_menu">


<form method="get" action="index.php" style="margin:0px">
<div class="toleft">
<span><?=word_lang("search")?>:</span>
<input type="text" name="search" style="width:200px;display:inline" class="ft" value="<?=$search?>" onClick="this.value=''">
<select name="search_type" style="width:150px;display:inline" class="ft">
<option value="user" <?if($search_type=="user"){echo("selected");}?>><?=word_lang("user")?></option>
<option value="order" <?if($search_type=="order"){echo("selected");}?>><?=word_lang("order")?> ID</option>
<option value="subscription" <?if($search_type=="subscription"){echo("selected");}?>><?=word_lang("subscription")?> ID</option>
<option value="file" <?if($search_type=="file"){echo("selected");}?>><?=word_lang("item")?> ID</option>
</select>
</div>




<div class="toleft">
<span><?=word_lang("type")?>:</span>
<select name="type" style="width:200px;display:inline" class="ft">
<option value="all" <?if($type=="all"){echo("selected");}?>><?=word_lang("all")?></option>
<option value="order" <?if($type=="order"){echo("selected");}?>><?=word_lang("order")?></option>
<option value="subscription" <?if($type=="subscription"){echo("selected");}?>><?=word_lang("subscription")?></option>
<option value="free" <?if($type=="free"){echo("selected");}?>><?=word_lang("free download")?></option>
</select>
</div>














<div class="toleft">
<span><?=word_lang("page")?>:</span>
<select name="items" style="width:70px" class="ft">
<?
for($i=0;$i<count($items_mass);$i++)
{
$sel="";
if($items_mass[$i]==$items){$sel="selected";}
?>
<option value="<?=$items_mass[$i]?>" <?=$sel?>><?=$items_mass[$i]?></option>
<?
}
?>

</select>
</div>

<div class="toleft">
<span>&nbsp;</span>
<input type="submit" class="btn btn-danger" value="<?=word_lang("search")?>">
</div>

<div class="toleft_clear"></div>
</form>


</div>



<?





if(!$rs->eof)
{
?>


<div style="padding:0px 9px 15px 6px"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>

<script language="javascript">
function publications_select_all(sel_form)
{
    if(sel_form.selector.checked)
   	{
        $("input:checkbox", sel_form).attr("checked",true);
    }
    else
    {
        $("input:checkbox", sel_form).attr("checked",false);
    }
}
</script>



<form method="post" action="delete.php" style="margin:0px"  id="adminform" name="adminform">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"></th>


<th colspan="2"><?=word_lang("file")?></th>

<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>

<th><?=word_lang("order")?></th>

<th><?=word_lang("user")?></th>

<th><?=word_lang("price")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("download")?></th>



</tr>
<?
$tr=1;
	while(!$rs->eof)
	{
		$cl3="";
		$cl_script="";
		if(isset($_SESSION["user_downloads_id"]) and !isset($_SESSION["admin_rows_downloads".$rs->row["id"]]) and $rs->row["id"]>$_SESSION["user_downloads_id"])
		{
			$cl3="success";	
			$cl_script="onMouseover=\"deselect_row('downloads".$rs->row["id"]."')\"";
		}
	
		$preview="";
		$preview_size="";
		
		$sql="select server1,title from photos where id_parent=".(int)$rs->row["publication_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$preview=show_preview($rs->row["publication_id"],"photo",1,1,$ds->row["server1"],$rs->row["publication_id"]);
			$preview_title=$ds->row["title"];
			$preview_class=1;
			
			$image_width=0;
			$image_height=0;
			$image_filesize=0;
			$flag_storage=false;

			if($global_settings["amazon"] or $global_settings["rackspace"])
			{
				$sql="select url,filename1,filename2,width,height,item_id,filesize from filestorage_files where id_parent=".$rs->row["publication_id"];
				$ds->open($sql);
				while(!$ds->eof)
				{
					if($ds->row["item_id"]!=0)
					{
						$image_width=$ds->row["width"];
						$image_height=$ds->row["height"];
						$image_filesize=$ds->row["filesize"];
					}

					$flag_storage=true;
					$ds->movenext();
				}
			}
			
			
			$sql="select url,price_id from items where id=".$rs->row["id_parent"];
			$dr->open($sql);
			if(!$dr->eof)
			{
				if(!$flag_storage)
				{
					if(file_exists($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$rs->row["publication_id"]."/".$dr->row["url"]))
					{
						$size = @getimagesize($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$rs->row["publication_id"]."/".$dr->row["url"]);
						$image_width=$size[0];
						$image_height=$size[1];
						$image_filesize=filesize($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$rs->row["publication_id"]."/".$dr->row["url"]);
					}
				}
						
				$sql="select size from sizes where id_parent=".$dr->row["price_id"];
				$dn->open($sql);
				if(!$dn->eof)
				{
					if($dn->row["size"]!=0 and $image_width!=0 and $image_height!=0)
					{
						if($image_width>$image_height)
						{
							$image_height=round($image_height*$dn->row["size"]/$image_width);
							$image_width=$dn->row["size"];
						}
						else
						{							
							$image_width=round($image_width*$dn->row["size"]/$image_height);
							$image_height=$dn->row["size"];
						}
						$image_filesize=0;
					}
				}
			}
			
			$preview_size="<br>".$image_width."x".$image_height;
			if($image_filesize!=0)
			{
				$preview_size.=" ".strval(float_opt($image_filesize/(1024*1024),3))." Mb.";
			}
		}
		
		$sql="select server1,title from videos where id_parent=".(int)$rs->row["publication_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$preview=show_preview($rs->row["publication_id"],"video",1,1,$ds->row["server1"],$rs->row["publication_id"]);
			$preview_title=$ds->row["title"];
			$preview_class=2;
			
			$flag_storage=false;
			$file_filesize=0;

			if($global_settings["amazon"] or $global_settings["rackspace"])
			{
				$sql="select filename1,filename2,url,item_id,filesize from filestorage_files where id_parent=".$rs->row["publication_id"]." and item_id=".$rs->row["id_parent"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					$file_filesize=$dr->row["filesize"];		
					$flag_storage=true;
				}
			}
			
			if(!$flag_storage)
			{
				$sql="select url,price_id from items where id=".$rs->row["id_parent"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					if(file_exists($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$rs->row["publication_id"]."/".$dr->row["url"]))
					{
						$file_filesize=filesize($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$rs->row["publication_id"]."/".$dr->row["url"]);
					}
				}
			}
			
			$preview_size.="<br>".strval(float_opt($file_filesize/(1024*1024),3))." Mb.";
		}
		
		$sql="select server1,title from audio where id_parent=".(int)$rs->row["publication_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$preview=show_preview($rs->row["publication_id"],"audio",1,1,$ds->row["server1"],$rs->row["publication_id"]);
			$preview_title=$ds->row["title"];
			$preview_class=3;
			
			$flag_storage=false;
			$file_filesize=0;

			if($global_settings["amazon"] or $global_settings["rackspace"])
			{
				$sql="select filename1,filename2,url,item_id,filesize from filestorage_files where id_parent=".$rs->row["publication_id"]." and item_id=".$rs->row["id_parent"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					$file_filesize=$dr->row["filesize"];		
					$flag_storage=true;
				}
			}
			
			if(!$flag_storage)
			{
				$sql="select url,price_id from items where id=".$rs->row["id_parent"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					if(file_exists($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$rs->row["publication_id"]."/".$dr->row["url"]))
					{
						$file_filesize=filesize($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$rs->row["publication_id"]."/".$dr->row["url"]);
					}
				}
			}
			
			$preview_size.="<br>".strval(float_opt($file_filesize/(1024*1024),3))." Mb.";
		}
		
		$sql="select server1,title from vector where id_parent=".(int)$rs->row["publication_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$preview=show_preview($rs->row["publication_id"],"vector",1,1,$ds->row["server1"],$rs->row["publication_id"]);
			$preview_title=$ds->row["title"];
			$preview_class=4;
			
			$flag_storage=false;
			$file_filesize=0;

			if($global_settings["amazon"] or $global_settings["rackspace"])
			{
				$sql="select filename1,filename2,url,item_id,filesize from filestorage_files where id_parent=".$rs->row["publication_id"]." and item_id=".$rs->row["id_parent"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					$file_filesize=$dr->row["filesize"];		
					$flag_storage=true;
				}
			}
			
			if(!$flag_storage)
			{
				$sql="select url,price_id from items where id=".$rs->row["id_parent"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					if(file_exists($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$rs->row["publication_id"]."/".$dr->row["url"]))
					{
						$file_filesize=filesize($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$rs->row["publication_id"]."/".$dr->row["url"]);
					}
				}
			}
			
			$preview_size.="<br>".strval(float_opt($file_filesize/(1024*1024),3))." Mb.";
		}
		
		
		$item_name="";
		$sql="select name from items where id=".(int)$rs->row["id_parent"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$item_name="<br><b>".$ds->row["name"]."</b>";
		}


			?>
			<tr id="downloads<?=$rs->row["id"]?>" class='<?if($tr%2==0){echo("snd");}?> <?=$cl3?>' valign="top" <?=$cl_script?>>
				<td><input type="checkbox" name="sel<?=$rs->row["id"]?>" id="sel<?=$rs->row["id"]?>"></td>
				<td><a href="../catalog/content.php?id=<?=$rs->row["publication_id"]?>"><img src="<?=$preview?>"></a></td>
				<td><a href="../catalog/content.php?id=<?=$rs->row["publication_id"]?>"><b>#<?=$rs->row["publication_id"]?></b></a><?=$item_name?><?=$preview_size?></td>
				
				<td><?=date(date_format,($rs->row["data"]-$global_settings["download_expiration"]*3600*24))?></td>
				<td>
					<?
					$price=0;
					
					if($rs->row["order_id"]!=0)
					{
						echo("<a href='../orders/order_content.php?id=".$rs->row["order_id"]."'>".word_lang("order")."&nbsp;#".$rs->row["order_id"]."</a>");
						
						$sql="select price from items where id=".$rs->row["id_parent"];
						$ds->open($sql);
						if(!$ds->eof)
						{
							$price=$ds->row["price"];
						}
					}
					elseif($rs->row["subscription_id"]!=0)
					{
						echo("<a href='../orders/payments.php?product_id=".$rs->row["subscription_id"]."&product_type=subscription&print=1'>".word_lang("subscription")."&nbsp;#".$rs->row["subscription_id"]."</a>");
						
						$sql="select price from items where id=".$rs->row["id_parent"];
						$ds->open($sql);
						if(!$ds->eof)
						{
							$price=$ds->row["price"];
						}
					}
					else
					{
						echo(word_lang("free"));
					}
					?>
				</td>
				<td><div class="link_user"><a href="../customers/content.php?id=<?=$rs->row["user_id"]?>"><?=user_url_back($rs->row["user_id"])?></a></div></td>
				<td><span class="price"><?=currency(1,true);?><?=float_opt($price,2)?> <?=currency(2,true);?></span></td>
				<td>
					<?
					if($rs->row["tlimit"]>$rs->row["ulimit"] or $rs->row["data"]<mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")))
					{
						echo(word_lang("expired")." - <a href='restore.php?id=".$rs->row["id"]."'>".word_lang("restore link")."</a>");
					}
					else
					{
						?>
						<a href="<?=site_root?>/members/download.php?f=<?=$rs->row["link"]?>"><b><?=word_lang("link")?></b></a><br>
						<?=word_lang("downloads")?>: <?=$rs->row["tlimit"]?>(<?=$rs->row["ulimit"]?>)
						<?
					}
					?>
				</td>
			</tr>
			<?
		$tr++;

		
		$rs->movenext();
	}
?>
</table>
</div></div></div></div></div></div></div></div>


<input type="submit" class="btn btn-danger" value="<?=word_lang("delete")?>"  style="margin:15px 0px 0px 6px;">






</form>
<div style="padding:25px 0px 0px 6px;"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>
<?
}
else
{
echo("<p><b>".word_lang("not found")."</b></p>");
}
?>

<? include("../inc/end.php");?>