<?
if(!defined("site_root")){exit();}
$home_page_flag=true;


?>

          <h1>
            Photo Video Store Script
            <small><a href="https://www.cmsaccount.com/photostore/script_versions/" target="blank">version <?=$script_version?></a></small>
          </h1>
        </section>
        
        
        
		<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
		<script type="text/javascript" language="JavaScript">
		function doLoad(value) {
			var req = new JsHttpRequest();
			// Code automatically called on load finishing.
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
		document.getElementById('status'+value).innerHTML =req.responseText;
		
				}
			}
			req.open(null, '../orders/status.php', true);
			req.send( {'form': document.getElementById("f"+value) } );
		}
		
		function doLoad2(value) {
			var req = new JsHttpRequest();
			// Code automatically called on load finishing.
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
		document.getElementById('cstatus'+value).innerHTML =req.responseText;
		
				}
			}
			req.open(null, '../orders/credits_status.php', true);
			req.send( {'form': document.getElementById("cf"+value) } );
		}
		
		function doLoad3(value) {
			var req = new JsHttpRequest();
			// Code automatically called on load finishing.
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
		document.getElementById('sstatus'+value).innerHTML =req.responseText;
		
				}
			}
			req.open(null, '../orders/subscription_status.php', true);
			req.send( {'form': document.getElementById("sf"+value) } );
		}
		
		
		
		function doLoad4(value) {
			var req = new JsHttpRequest();
			// Code automatically called on load finishing.
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
		document.getElementById('shipping'+value).innerHTML =req.responseText;
		
				}
			}
			req.open(null, '../orders/shipping.php', true);
			req.send( {'id': value} );
		}
		</script>

        
        
        <?
$current_month=date("n");
$current_year=date("Y");
$month_step=8;

$sales_year_credits=array();
$sales_year_orders=array();
$sales_year_subscription=array();
$sales_month_credits=array();
$sales_month_orders=array();
$sales_month_subscription=array();

$sales_year_credits[$current_year]=0;
$sales_year_orders[$current_year]=0;
$sales_year_subscription[$current_year]=0;

$count_photos=0;
$count_videos=0;
$count_audio=0;
$count_vector=0;
$count_orders=0;
$count_credits=0;
$count_subscription=0;
$count_users=0;





for($i=$month_step;$i>=0;$i--)
{
	$j=$current_month-$i;
	if($j<=0)
	{
		$j=12+$current_month-$i;
	}
	$sales_month_credits[$j]=0;
	$sales_month_orders[$j]=0;
	$sales_month_subscription[$j]=0;
}

$buyers_total=array();
$items_total=array();


if($global_settings["credits"]==1)
{
	//Credits
	$sql="select total,data,quantity from credits_list where quantity>0 and approved=1";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($rs->row["data"]>mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)
		{
			foreach ($sales_month_credits as $key => $value) 
			{
				if($key==date("n",$rs->row["data"]))
				{
					$sales_month_credits[$key]+=$rs->row["total"];
				}
			}
			if($current_year==date("Y",$rs->row["data"]))
			{
				$sales_year_credits[$current_year]+=$rs->row["total"];
			}
		}
		
		$count_credits+=$rs->row["quantity"];
		
		$rs->movenext();
	}
}

if(!$global_settings["credits"] or ($global_settings["credits"] and $global_settings["credits_currency"]))
{
	//Orders
	$sql="select total,data,user from orders where status=1";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($rs->row["data"]>mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)
		{
			foreach ($sales_month_orders as $key => $value) 
			{
				if($key==date("n",$rs->row["data"]))
				{
					$sales_month_orders[$key]+=$rs->row["total"];
				}
			}
			if($current_year==date("Y",$rs->row["data"]))
			{
				$sales_year_orders[$current_year]+=$rs->row["total"];
			}
		}
		
		if(!isset($buyers_total[$rs->row["user"]]))
		{
			$buyers_total[$rs->row["user"]] = $rs->row["total"];
		}
		else
		{
			$buyers_total[$rs->row["user"]] += $rs->row["total"];
		}
		
		$count_orders++;
		
		$rs->movenext();
	}
}

if($global_settings["subscription"]==1)
{
	//Subscription
	$sql="select total,data1,payments,user from subscription_list where approved=1";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($rs->row["data1"]>mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-365*24*3600)
		{
			foreach ($sales_month_subscription as $key => $value) 
			{
				if($key==date("n",$rs->row["data1"]))
				{
					$sales_month_subscription[$key]+=$rs->row["total"]*$rs->row["payments"];
				}
			}
			if($current_year==date("Y",$rs->row["data1"]))
			{
				$sales_year_subscription[$current_year]+=$rs->row["total"]*$rs->row["payments"];
			}
		}
		
		if(!isset($buyers_total[user_url($rs->row["user"])]))
		{
			$buyers_total[user_url($rs->row["user"])] = $rs->row["total"];
		}
		else
		{
			$buyers_total[user_url($rs->row["user"])] += $rs->row["total"];
		}
		
		$count_subscription++;
		
		$rs->movenext();
	}
}



$sales_month_list="";
$sales_credits_list="";
$sales_orders_list="";
$sales_subscription_list="";

foreach ($sales_month_credits as $key => $value) 
{
	if($sales_month_list!="")
	{
		$sales_month_list.=",";
	}
	if($sales_credits_list!="")
	{
		$sales_credits_list.=",";
	}
	$sales_month_list.='"'.$m_month[$key-1].'"';
	$sales_credits_list.=$value;
}

foreach ($sales_month_orders as $key => $value) 
{
	if($sales_orders_list!="")
	{
		$sales_orders_list.=",";
	}
	$sales_orders_list.=$value;
}

foreach ($sales_month_subscription as $key => $value) 
{
	if($sales_subscription_list!="")
	{
		$sales_subscription_list.=",";
	}
	$sales_subscription_list.=$value;
}





if($global_settings["allow_photo"])
{
	$sql="select count(id_parent) as count_param from photos where published=1 group by published";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$count_photos=$rs->row["count_param"];
	}
}

if($global_settings["allow_video"])
{
	$sql="select count(id_parent) as count_param from videos where published=1 group by published";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$count_videos=$rs->row["count_param"];
	}
}

if($global_settings["allow_audio"])
{
	$sql="select count(id_parent) as count_param from audio where published=1 group by published";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$count_audio=$rs->row["count_param"];
	}
}

if($global_settings["allow_vector"])
{
	$sql="select count(id_parent) as count_param from vector where published=1 group by published";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$count_vector=$rs->row["count_param"];
	}
}

$sql="select count(id_parent) as count_param from users where accessdenied=0 group by accessdenied";
$rs->open($sql);
if(!$rs->eof)
{
	$count_users=$rs->row["count_param"];
}
?>
        

        
        
        

        <!-- Main content -->
        <section class="content">
        
        
        
                <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=$count_photos+$count_videos+$count_audio+$count_vector?></h3>
                  <p><?=word_lang("items")?></p>
                </div>
                <div class="icon">
                  <i class="ion-images"></i>
                </div>
                <a href="../catalog/" class="small-box-footer">
                  <?=word_lang("View All")?> <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div><!-- ./col -->
            <?if(!$global_settings["subscription_only"]){?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?=$count_orders?></h3>
                  <p><?=word_lang("orders")?></p>
                </div>
                <div class="icon">
                  <i class="ion-bag"></i>
                </div>
                <a href="../orders/" class="small-box-footer">
                  <?=word_lang("View All")?> <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div><!-- ./col -->
            <?}?>
            <?if($global_settings["credits"]){?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?=$count_credits?></h3>
                  <p><?=word_lang("credits")?></p>
                </div>
                <div class="icon">
                  <i class="ion-ios-keypad-outline"></i>
                </div>
                <a href="../credits/" class="small-box-footer">
                  <?=word_lang("View All")?> <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div><!-- ./col -->
            <?
            }
			else
			{
				if($global_settings["subscription"])
				{
					?>
					<div class="col-lg-3 col-xs-6">
					  <!-- small box -->
					  <div class="small-box bg-yellow">
						<div class="inner">
						  <h3><?=$count_subscription?></h3>
						  <p><?=word_lang("subscription")?></p>
						</div>
						<div class="icon">
						  <i class="ion-clock"></i>
						</div>
						<a href="../subscription_list/" class="small-box-footer">
						  <?=word_lang("View All")?> <i class="fa fa-arrow-circle-right"></i>
						</a>
					  </div>
					</div><!-- ./col -->
					<?
				}
			}
            ?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?=$count_users?></h3>
                  <p><?=word_lang("users")?></p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-people-outline"></i>
                </div>
                <a href="../customers/" class="small-box-footer">
                  <?=word_lang("View All")?> <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
        





          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
			<?
			$sql="select title from templates_admin_home where activ=1 and left_right=0 order by priority";
          	$dd->open($sql);
          	while(!$dd->eof)
          	{
          		$tab_name=preg_replace("/[^a-z_]/i","",strtolower(str_replace(" ","_",$dd->row["title"])));
          		if(!isset($_COOKIE["delete_".$tab_name]) or $_COOKIE["delete_".$tab_name]==0)
          		{
          			include($_SERVER["DOCUMENT_ROOT"].site_root."/admin/templates_admin/".$admin_template."/".$tab_name.".php");
          		}
          		
          		$dd->movenext();
          	}
			?>
            

            </div><!-- /.col -->
			<div class="col-md-4">
			<?
			$sql="select title from templates_admin_home where activ=1 and left_right=1 order by priority";
          	$dd->open($sql);
          	while(!$dd->eof)
          	{
          		$tab_name=preg_replace("/[^a-z_]/i","",strtolower(str_replace(" ","_",$dd->row["title"])));
          		
          		if(!isset($_COOKIE["delete_".$tab_name]) or $_COOKIE["delete_".$tab_name]==0)
          		{
          			include($_SERVER["DOCUMENT_ROOT"].site_root."/admin/templates_admin/".$admin_template."/".$tab_name.".php");
          		}
          		
          		$dd->movenext();
          	}
			?>


            </div><!-- /.col -->
          </div><!-- /.row -->
          

         
          <script>
          home_tabs = new Array();
          home_tabs_active = new Array();
          
          <?
          $i=0;
          $sql="select title from templates_admin_home where activ=1";
          $rs->open($sql);
          while(!$rs->eof)
          {
          		$active=1;
          		$tab_name=strtolower(str_replace(" ","_",$rs->row["title"]));
          		
          		if(isset($_COOKIE["pp_".$tab_name]) and $_COOKIE["pp_".$tab_name]==0)
          		{
          			$active=0;
          		}
          		?>
          			home_tabs[<?=$i?>]="<?=$tab_name?>";
          			home_tabs_active[<?=$i?>]=<?=$active?>;
          		<?
          		$i++;
          		$rs->movenext();
          }
          ?>
          
          function collapse_tab(value)
          {
          		for(i=0;i<home_tabs.length;i++)
          		{
          			if(value==home_tabs[i])
          			{
						if(home_tabs_active[i]==0)
						{
							document.cookie = "pp_" + home_tabs[i] + "=" + escape (1) + ";path=/";
							home_tabs_active[i]=1;
						}
						else
						{
							document.cookie = "pp_" + home_tabs[i] + "=" + escape (0) + ";path=/";
							home_tabs_active[i]=0;
						}
          			}
          		}
          }
          
          function collapse_default()
          {
          	for(i=0;i<home_tabs.length;i++)
          	{
          		if(home_tabs_active[i]==0 && $("#box_" + home_tabs[i]) && $("#" + home_tabs[i] + "_collapse"))
				{         			
          			$("#box_" + home_tabs[i]).addClass("collapsed-box");
          			
          			$("#" + home_tabs[i] + "_collapse i").addClass("fa-plus").removeClass("fa-minus");
          		}
          	}
          }
          
          function remove_tab(value)
          {
			document.cookie = "delete_" + value + "=" + escape (1) + ";path=/";
          }

          setTimeout(collapse_default, 1000);
          </script>