<?$site="profile_downloads";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>









<?include("profile_top.php");?>


<div class="btn-group" role="group" style="float:right">
  <button type="button" class="btn btn-default" onClick="location.href='profile_downloads.php'"><i class="glyphicon glyphicon-th"></i> <?=word_lang("grid")?></button>
  <button type="button" class="btn btn-primary" onClick="location.href='profile_downloads_table.php'"><i class="glyphicon glyphicon-list"></i> <?=word_lang("Table")?></button>
</div>



<h1><?=word_lang("my downloads")?></h1>

<?

//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];


//Количество страниц на странице
$kolvo2=k_str2;

$tr=1;
$n=0;

$sql="select * from downloads where user_id=".(int)$_SESSION["people_id"]." order by data desc";
$rs->open($sql);

if(!$rs->eof)
{
?>
<table border='0' cellpadding='0' cellspacing='0' style="margin-bottom:20px" class="profile_table" width="100%">
<tr>
<th></th>
<th><?=word_lang("file")?></th>
<th><?=word_lang("date")?></th>
<th><?=word_lang("order")?></th>
<th><?=word_lang("price")?></th>
<th><?=word_lang("download")?></th>
</tr>
<?
	while(!$rs->eof)
	{
	
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
		
		if($preview!="")
		{	
			if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
			{
			?>
			<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
				<td><a href="<?=item_url($rs->row["publication_id"])?>"><img src="<?=$preview?>"></a></td>
				<td style="width:30%"><a href="<?=item_url($rs->row["publication_id"])?>"><b>#<?=$rs->row["publication_id"]?></b></a><?=$item_name?><?=$preview_size?></td>
				
				<td><?=show_time_ago($rs->row["data"]-$global_settings["download_expiration"]*3600*24)?></td>
				<td>
					<?
					$price=0;
					
					if($rs->row["order_id"]!=0)
					{
						echo("<a href='orders_content.php?id=".$rs->row["order_id"]."'>".word_lang("order")."&nbsp;#".$rs->row["order_id"]."</a>");
						
						$sql="select price from items where id=".$rs->row["id_parent"];
						$ds->open($sql);
						if(!$ds->eof)
						{
							$price=$ds->row["price"];
						}
					}
					elseif($rs->row["subscription_id"]!=0)
					{
						echo("<a href='subscription.php'>".word_lang("subscription")."&nbsp;#".$rs->row["subscription_id"]."</a>");
						
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
				<td><span class="price"><?=currency(1,true);?><?=float_opt($price,2)?> <?=currency(2,true);?></span></td>
				<td>
					<?
					if($rs->row["tlimit"]>$rs->row["ulimit"] or $rs->row["data"]<mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")))
					{
						echo(word_lang("expired"));
					}
					else
					{
						?>
						<a href="<?=site_root?>/members/download.php?f=<?=$rs->row["link"]?>"><b><?=word_lang("download")?></b></a><br>
						<?=word_lang("downloads")?>: <?=$rs->row["tlimit"]?>(<?=$rs->row["ulimit"]?>)
						<?
					}
					?>
				</td>
			</tr>
			<?
		}
		$n++;
		$tr++;
		}
		
		$rs->movenext();
	}
?>
</table>
<?
echo(paging($n,$str,$kolvo,$kolvo2,"profile_downloads_table.php",""));
}
else
{
	echo(word_lang("not found"));
}
?>

<br><br>
<a href="profile_downloads_xls.php" class="btn btn-primary" style='color:#FFFFFF;text-decoration:none'><i class="icon-th-list icon-white"></i> <?=word_lang("export as xls file")?></a>


<?include("profile_bottom.php");?>























<?include("../inc/footer.php");?>