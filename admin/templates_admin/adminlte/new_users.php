<?if(!defined("site_root")){exit();}?>
<?if(isset($_SESSION["rights"]["users_customers"])){?>
                  <div class="box box-danger" id="box_new_users">
                    <div class="box-header with-border">
                      <h3 class="box-title"><?=word_lang("Latest Members")?></h3>
                      <div class="box-tools pull-right">
                        <?
						if(@$_SESSION["user_users"] > 0)
						{
							?>
							<span class="label label-danger"><?=$_SESSION["user_users"]?></span>
							<?
						}
						?>
                        <button  id="new_users_collapse"  onClick="collapse_tab('new_users')" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('new_users')"><i class="fa fa-times"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                      <ul class="users-list clearfix">
                      <?
                      $sql="select id_parent,name,photo,data1,login from users order by data1 desc limit 0,8";
                      $rs->open($sql);
                      while(!$rs->eof)
                      {
                      		$photo=site_root."/images/user.gif";
                      		if($rs->row["photo"]!="")
                      		{
                      			$photo=$rs->row["photo"];
                      		}
                      ?>
                        <li>
                          <a href="../customers/content.php?id=<?=$rs->row["id_parent"]?>"><img src="<?=$photo?>" alt="<?=$rs->row["login"]?>" /></a>
                          <a class="users-list-name" href="../customers/content.php?id=<?=$rs->row["id_parent"]?>" style="white-space:nowrap"><?=$rs->row["login"]?></a>
                          <span class="users-list-date" style="white-space:nowrap"><?=show_time_ago($rs->row["data1"])?></span>
                        </li>
					<?
						$rs->movenext();
					}
					?>
                      </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->
                    <div class="box-footer text-center">
                      <a href="../customers/" class="btn btn-default"><?=word_lang("View All Users")?></a>
                    </div><!-- /.box-footer -->
                  </div><!--/.box -->
<?}?>