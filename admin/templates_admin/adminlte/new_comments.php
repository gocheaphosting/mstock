<?
if(!defined("site_root")){exit();}
?>
<?if(isset($_SESSION["rights"]["catalog_comments"])){?>
                  <div class="box box-warning direct-chat direct-chat-warning" id="box_new_comments">
                    <div class="box-header with-border">
                      <h3 class="box-title"><?=word_lang("Recent comments")?></h3>
                      <div class="box-tools pull-right">
                        <?
						if(@$_SESSION["user_comments"] > 0)
						{
							?>
							<span class="label label-warning"><?=$_SESSION["user_comments"]?></span>
							<?
						}
						?>
                        <button id="new_comments_collapse"  onClick="collapse_tab('new_comments')" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('new_comments')"><i class="fa fa-times"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <!-- Conversations are loaded here -->
                      <div class="direct-chat-messages">
                      
                      
						 <?
						 $sql="select data,id_parent,fromuser,content from reviews order by data desc limit 0,6";
						 $rs->open($sql);
						 while(!$rs->eof)
						 {
							$photo=site_root."/images/user.gif";
							  $sql="select photo from users where login='".$rs->row["fromuser"]."'";
							  $ds->open($sql);
							  if(!$ds->eof)
							  {
									if($ds->row["photo"]!="")
									{
										$photo=$ds->row["photo"];
									}
						      }
							
							?>
							<div class="direct-chat-msg">
							  <div class="direct-chat-info clearfix">
								<span class="direct-chat-name pull-left"><?=$rs->row["fromuser"]?></span>
								<span class="direct-chat-timestamp pull-right"><?=show_time_ago($rs->row["data"])?></span>
							  </div>
							  <img class="direct-chat-img" src="<?=$photo?>" />
							  <div class="direct-chat-text">
								<?=$rs->row["content"]?>
							  </div>
							</div>
							<?
							$rs->movenext();
						 }
						 ?>                     



					</div>
                    </div>
                    <div class="box-footer  text-center">
                    	<a href="../comments/" class="btn btn-default"><?=word_lang("All comments")?></a>
                    </div>
                  </div>
<?}?>