<?if(!defined("site_root")){exit();}?>
<?if(isset($_SESSION["rights"]["catalog_catalog"])){?>
            
              <?if($global_settings["allow_photo"]){?>
              <div class="info-box bg-red">
                <span class="info-box-icon"><i class="fa fa-image"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?=word_lang("photos")?></span>
                  <span class="info-box-number"><?=$count_photos?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 50%"></div>
                  </div>
                  <span class="progress-description">
                    <i class="fa fa-download"></i> <?=word_lang("downloads")?>: 
                    <?
                    $sql="select sum(downloaded) as sum_downloads from photos";
                    $rs->open($sql);
                    if(!$rs->eof)
                    {
                    	echo($rs->row["sum_downloads"]);
                    }
                    ?>
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
              <?}?>
              <?if($global_settings["allow_video"]){?>
              <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-film"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?=word_lang("videos")?></span>
                  <span class="info-box-number"><?=$count_videos?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 50%"></div>
                  </div>
                  <span class="progress-description">
                    <i class="fa fa-download"></i> <?=word_lang("downloads")?>: 
                    <?
                    $sql="select sum(downloaded) as sum_downloads from videos";
                    $rs->open($sql);
                    if(!$rs->eof)
                    {
                    	echo($rs->row["sum_downloads"]);
                    }
                    ?>
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
              <?}?>
              <?if($global_settings["allow_audio"]){?>
              <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="fa fa-music"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?=word_lang("audio")?></span>
                  <span class="info-box-number"><?=$count_audio?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 50%"></div>
                  </div>
                  <span class="progress-description">
                    <i class="fa fa-download"></i> <?=word_lang("downloads")?>: 
                    <?
                    $sql="select sum(downloaded) as sum_downloads from audio";
                    $rs->open($sql);
                    if(!$rs->eof)
                    {
                    	echo($rs->row["sum_downloads"]);
                    }
                    ?>
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
              <?}?>
              <?if($global_settings["allow_vector"]){?>
              <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-leaf"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"><?=word_lang("vector")?></span>
                  <span class="info-box-number"><?=$count_vector?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 50%"></div>
                  </div>
                  <span class="progress-description">
                    <i class="fa fa-download"></i> <?=word_lang("downloads")?>: 
                    <?
                    $sql="select sum(downloaded) as sum_downloads from vector";
                    $rs->open($sql);
                    if(!$rs->eof)
                    {
                    	echo($rs->row["sum_downloads"]);
                    }
                    ?>
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
				<?}?>



              <div class="box box-primary" id="box_files">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=word_lang("files")?></h3>
                  <div class="box-tools pull-right">
                    <button  id="files_collapse"  onClick="collapse_tab('files')" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"  onClick="remove_tab('files')"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="chart-responsive">
                        <canvas id="pieChart" height="150"></canvas>
                      </div><!-- ./chart-responsive -->
                    </div><!-- /.col -->
                    <div class="col-md-4">
                      <ul class="chart-legend clearfix">
                      <?if($global_settings["allow_photo"]){?>
                        <li class="text-red"><i class="fa fa fa-circle-o"></i>&nbsp; <?=word_lang("photos")?>: 
                        <?
                        if($count_photos+$count_videos+$count_audio+$count_vector!=0)
                        {
                        	echo(round(100*$count_photos/($count_photos+$count_videos+$count_audio+$count_vector)));
                        }
                        else
                        {
                        	echo("0");
                        }
                        ?>%</li>
                      <?}?>
                 	  <?if($global_settings["allow_video"]){?>
                        <li class="text-green"><i class="fa fa fa-circle-o"></i>&nbsp; <?=word_lang("videos")?>: 
                        <?
                        if($count_photos+$count_videos+$count_audio+$count_vector!=0)
                        {
                        	echo(round(100*$count_videos/($count_photos+$count_videos+$count_audio+$count_vector)));
                        }
                        else
                        {
                        	echo("0");
                        }
                        ?>%</li>
                      <?}?>
                      <?if($global_settings["allow_audio"]){?>
                        <li class="text-yellow"><i class="fa fa fa-circle-o"></i>&nbsp; <?=word_lang("audio")?>: 
                        <?
                        if($count_photos+$count_videos+$count_audio+$count_vector!=0)
                        {
                        	echo(round(100*$count_audio/($count_photos+$count_videos+$count_audio+$count_vector)));
                        }
                        else
                        {
                        	echo("0");
                        }
                        ?>%</li>
                      <?}?>
                      <?if($global_settings["allow_vector"]){?>
                        <li class="text-aqua"><i class="fa fa fa-circle-o"></i>&nbsp; <?=word_lang("vector")?>: 
                        <?
                        if($count_photos+$count_videos+$count_audio+$count_vector!=0)
                        {
                        	echo(round(100*$count_vector/($count_photos+$count_videos+$count_audio+$count_vector)));
                        }
                        else
                        {
                        	echo("0");
                        }
                        ?>%</li>
                      <?}?>
                      </ul>
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
<?}?>