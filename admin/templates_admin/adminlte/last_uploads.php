<?if(!defined("site_root")){exit();}?>
<?if(isset($_SESSION["rights"]["catalog_upload"])){?>
              <div class="box box-warning" id="box_last_uploads">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=word_lang("Last uploads")?></h3>
                  <div class="box-tools pull-right">
                  <?
                  	if(@$_SESSION["user_uploads"] > 0)
					{
                  		?>
                  		<span class="label label-warning"><?=$_SESSION["user_uploads"]?></span>
                  		<?
                  	}
                  	?>
                    <button id="last_uploads_collapse"  onClick="collapse_tab('last_uploads')" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('last_uploads')"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <ul class="products-list product-list-in-box">

                    <?					
					$sql="(select id_parent,title,description,server1,userid,data,'photo' as tbl from photos where userid<>0 order by data desc) union (select id_parent,title,description,server1,userid,data,'video' as tbl from videos where userid<>0 order by data desc) union (select id_parent,title,description,server1,userid,data,'audio' as tbl from audio where userid<>0 order by data desc) union (select id_parent,title,description,server1,userid,data,'vector' as tbl from vector where userid<>0 order by data desc)  limit 0,5";
					$rs->open($sql);
					
					if(!$rs->eof)
					{
						while(!$rs->eof)
						{
								?>
								<li class="item">
								  <div class="product-img">
									<a href="../catalog/content.php?id=<?=$rs->row["id_parent"]?>"><img src="<?=show_preview($rs->row["id_parent"],$rs->row["tbl"],1,1,$rs->row["server1"],$rs->row["id_parent"])?>" /></a>
								  </div>
								  <div class="product-info">
									<a href="../catalog/content.php?id=<?=$rs->row["id_parent"]?>" class="product-title"><?=$rs->row["title"]?> <span class="label label-warning pull-right"><?=word_lang($rs->row["tbl"])?></span></a>
									<span class="product-description">
									  <?=$rs->row["description"]?>
									</span>
								  </div>
								</li>
								<?
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
                  <a href="../upload/" class="btn btn-default"><?=word_lang("All uploads")?></a>
                </div>
              </div>
<?}?>