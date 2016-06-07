<?
if(!defined("site_root")){exit();}
?>
<?if(isset($_SESSION["rights"]["orders_orders"])  and isset($_SESSION["rights"]["orders_credits"])  and isset($_SESSION["rights"]["orders_subscription"])){?>
              <div class="box" id="box_sales">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=word_lang("sales")?></h3>
                  <div class="box-tools pull-right">
                    <button id="sales_collapse" class="btn btn-box-tool" data-widget="collapse" onClick="collapse_tab('sales')"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" onClick="remove_tab('sales')"><i class="fa fa-times"></i></button>
                  </div>
                </div>  
                <div class="box-body">
                      <div class="chart">
                        <canvas id="salesChart" height="180"></canvas>
                      </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block border-right">
                        <span class="description-percentage text-red">
                        <?if($global_settings["credits"]){?>
                        	<i class="fa fa-diamond"></i> <?=word_lang("credits")?>
                        <?}else{?>
                        	<i class="fa fa-cart-plus"></i> <?=word_lang("orders")?>
                        <?}?>
                        </span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block border-right">
                        <span class="description-percentage text-blue"><i class="fa fa-clock-o"></i> <?=word_lang("subscription")?></span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                  </div>
                </div>
              </div>
<?}?>