<?$site="profile_downloads";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>









<?include("profile_top.php");?>

<div class="btn-group" role="group" style="float:right">
  <button type="button" class="btn btn-primary" onClick="location.href='profile_downloads.php'"><i class="glyphicon glyphicon-th"></i> <?=word_lang("grid")?></button>
  <button type="button" class="btn btn-default" onClick="location.href='profile_downloads_table.php'"><i class="glyphicon glyphicon-list"></i> <?=word_lang("Table")?></button>
</div>

<h1><?=word_lang("my downloads")?></h1>

<?

$sql="select id_parent,link,tlimit,ulimit,publication_id from downloads where user_id=".(int)$_SESSION["people_id"]." and tlimit<ulimit and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." order by data desc";
$rs->open($sql);

if(!$rs->eof)
{
	while(!$rs->eof)
	{
		$preview="";
		$preview_size="";
		
		$sql="select server1,title from photos where id_parent=".(int)$rs->row["publication_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$preview=show_preview($rs->row["publication_id"],"photo",1,1,$ds->row["server1"],$rs->row["publication_id"]);
			$translate_results=translate_publication($rs->row["publication_id"],$ds->row["title"],"","");
			$preview_title=$translate_results["title"];
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
			$translate_results=translate_publication($rs->row["publication_id"],$ds->row["title"],"","");
			$preview_title=$translate_results["title"];
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
			$translate_results=translate_publication($rs->row["publication_id"],$ds->row["title"],"","");
			$preview_title=$translate_results["title"];
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
			$translate_results=translate_publication($rs->row["publication_id"],$ds->row["title"],"","");
			$preview_title=$translate_results["title"];
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
			$item_name="<br>[".$ds->row["name"]."]";
		}
		
		if($preview!="")
		{
			?>
			<div class="item_list">
				<div  class="item_list_img">
					<div  class="item_list_img2">
					<a href="<?=site_root?>/members/download.php?f=<?=$rs->row["link"]?>"><img src="<?=$preview?>"></a>
					</div>
				</div>
				<div  class="item_list_text<?=$preview_class?>">
					<div><a href="<?=site_root?>/members/download.php?f=<?=$rs->row["link"]?>"><?=$preview_title?></a><small><?=$item_name?><?=$preview_size?></small></div>
					<div class="idownloaded"><?=word_lang("downloads")?>: <?=$rs->row["tlimit"]?>(<?=$rs->row["ulimit"]?>)</div>
				</div>
			</div>
			<?
		}
		$rs->movenext();
	}
}
else
{
	echo(word_lang("not found"));
}
?>





<?include("profile_bottom.php");?>























<?include("../inc/footer.php");?>