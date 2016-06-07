<?if(!defined("site_root")){exit();}?>
<?if(isset($_SESSION["rights"]["orders_downloads"])){?>
              <div class="box box-danger" id="box_last_downloads">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=word_lang("Last downloads")?></h3>
                  <div class="box-tools pull-right">
                  	<?
                  	if(@$_SESSION["user_downloads"] > 0)
					{
                  		?>
                  		<span class="label label-danger"><?=$_SESSION["user_downloads"]?></span>
                  		<?
                  	}
                  	?>
                    <button id="last_downloads_collapse"  onClick="collapse_tab('last_downloads')" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('last_downloads')"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <ul class="products-list product-list-in-box">

                    <?					
					$sql="select id_parent,link,tlimit,ulimit,publication_id,id_parent,user_id from downloads order by data desc limit 0,5";
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
								$item_name=$ds->row["name"];
							}
							
							if($preview!="")
							{
								?>
								<li class="item">
								  <div class="product-img">
									<a href="../catalog/content.php?id=<?=$rs->row["publication_id"]?>"><img src="<?=$preview?>" /></a>
								  </div>
								  <div class="product-info">
									<a href="../catalog/content.php?id=<?=$rs->row["publication_id"]?>" class="product-title"><?=$preview_title?> <span class="label label-warning pull-right"><?=user_url_back($rs->row["user_id"])?></span></a>
									<span class="product-description">
									  <?=$item_name?>
									</span>
								  </div>
								</li>
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



                  </ul>
                </div>
                <div class="box-footer text-center">
                  <a href="../downloads/" class="btn btn-default"><?=word_lang("All downloads")?></a>
                </div>
              </div>
<?}?>