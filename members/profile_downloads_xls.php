<? 
include("../admin/function/db.php");
include("../admin/function/upload.php");

if(!isset($_SESSION["people_id"]))
{
	header("location:login.php");
	exit();
}
	
	

$xls_content="";
$html_content="<html><body><table border='1'>";

function xlsBOF() {
global $xls_content;
$xls_content.= pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}

function xlsEOF() {
global $xls_content;
$xls_content.= pack("ss", 0x0A, 0x00);
return;
}

function xlsWriteNumber($Row, $Col, $Value) {
global $xls_content;
$xls_content.= pack("sssss", 0x203, 14, $Row, $Col, 0x0);
$xls_content.= pack("d", $Value);
return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
global $xls_content;
$L = strlen($Value);
$xls_content.= pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
$xls_content.= $Value;
return;
}



$export_file = "downloads_".$_SESSION["people_login"].".xls";
ob_clean();
header('Pragma: public');
header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header ("Pragma: no-cache");
header("Expires: 0");
header('Content-Transfer-Encoding: utf8');
header('Content-Type: application/vnd.ms-excel;'); 
header('Content-Disposition: attachment; filename="'.$export_file.'"'); 


/*
xlsBOF(); 

xlsWriteLabel(0,0,"File");
xlsWriteLabel(0,1,"Date");
xlsWriteLabel(0,2,"Order");
xlsWriteLabel(0,3,"Price");
xlsWriteLabel(0,4,"Download");
*/

$html_content.="<tr><th>File</th><th>Date</th><th>Order</th><th>Price</th><th>Download</th></tr>";


$n=1;

$sql="select * from downloads where user_id=".(int)$_SESSION["people_id"]." order by data desc";
$rs->open($sql);


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
		
		$html_content.="<tr>";
		$html_content.="<td>".strip_tags("#".$rs->row["publication_id"]." ".$item_name." (".$preview_size.")")."</td>";
		$html_content.="<td>".date(date_format,($rs->row["data"]-$global_settings["download_expiration"]*3600*24))."</td>";
		
		//xlsWriteLabel($n,0,strip_tags("#".$rs->row["publication_id"]." ".$item_name." (".$preview_size.")"));
		//xlsWriteLabel($n,1,date(date_format,($rs->row["data"]-$global_settings["download_expiration"]*3600*24)));


					$price=0;
					
					if($rs->row["order_id"]!=0)
					{						
						//xlsWriteLabel($n,2,"Order #".$rs->row["order_id"]);
						$html_content.="<td>Order #".$rs->row["order_id"]."</td>";
						
						$sql="select price from orders_content where id_parent=".$rs->row["order_id"]." and item=".$rs->row["id_parent"];
						$ds->open($sql);
						if(!$ds->eof)
						{
							$price=$ds->row["price"];
						}
					}
					elseif($rs->row["subscription_id"]!=0)
					{	
						//xlsWriteLabel($n,2,"Subscription #".$rs->row["subscription_id"]);
						$html_content.="<td>Subscription #".$rs->row["subscription_id"]."</td>";
						
						$sql="select price from items where id=".$rs->row["id_parent"];
						$ds->open($sql);
						if(!$ds->eof)
						{
							$price=$ds->row["price"];
						}
					}
					else
					{
						//xlsWriteLabel($n,2,"Free");
						$html_content.="<td>Free</td>";
					}
					
					//xlsWriteLabel($n,3,currency(1,false).float_opt($price,2)." ".currency(2,false));
					$html_content.="<td>".currency(1,false).float_opt($price,2)." ".currency(2,false)."</td>";


					if($rs->row["tlimit"]>$rs->row["ulimit"] or $rs->row["data"]<mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")))
					{
						xlsWriteLabel($n,4,"Expired");
						$html_content.="<td>Expired</td>";
					}
					else
					{
						xlsWriteLabel($n,4,site_root."/members/download.php?f=".$rs->row["link"]);
						$html_content.="<td>".site_root."/members/download.php?f=".$rs->row["link"]."</td>";
					}
		$n++;
		$rs->movenext();
	}






//xlsEOF();


$html_content.="</table></body></html>";

//echo($xls_content);
echo($html_content);

$db->close();
?>
