<?
if(!defined("site_root")){exit();}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?=$global_settings["site_name"]?> - Admin panel</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    
    <link href="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/css/style.css" rel="stylesheet" type="text/css" />
    <link href="../images/favicon.gif" type="image/gif" rel="icon">
	<link href="../images/favicon.gif" type="image/gif" rel="shortcut icon">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue sidebar-mini  
  <?if(isset($_COOKIE["ch_fixed_layout"]) and $_COOKIE["ch_fixed_layout"]==1){echo(" fixed ");}?>
  <?if(isset($_COOKIE["ch_boxed_layout"]) and $_COOKIE["ch_boxed_layout"]==1){echo(" layout-boxed ");}?>
  <?if(isset($_COOKIE["ch_sidebar_toggle"]) and $_COOKIE["ch_sidebar_toggle"]==1){echo(" sidebar-collapse ");}?>
  ">
    <div class="wrapper">

      <header class="main-header">
        <a href="../content/" class="logo">
          <span class="logo-mini"><i class="glyphicon glyphicon-home"></i></span>
          <span class="logo-lg"><b>Admin</b> Panel</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            
            <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src='<?=site_root?>/admin/images/languages/<?=strtolower($lng2)?>.gif'>&nbsp;&nbsp;<?=$lang_name[$lng]?>
                </a>
                <ul class="dropdown-menu">
                	<li class="header"><?=word_lang("languages")?></li>
                	<li>
						<ul class="menu">
						<?
							$sql="select * from languages where display=1 order by name";
							$rs->open($sql);
							while(!$rs->eof)
							{
								$lt="";
								$sel="selected";
								if($lng!=$rs->row["name"]){$lt="2";$sel="";}
				
								$lng3=strtolower($rs->row["name"]);
								if($lng3=="chinese traditional"){$lng3="chinese";}
								if($lng3=="chinese simplified"){$lng3="chinese";}
								if($lng3=="afrikaans formal"){$lng3="afrikaans";}
								if($lng3=="afrikaans informal"){$lng3="afrikaans";}
				
								echo("<li><a href='".site_root."/members/language.php?lang=".$rs->row["name"]."' class='lang_link'><img src='".site_root."/admin/images/languages/".$lng3.$lt.".gif'>&nbsp;&nbsp;".$rs->row["name"]."</a></li>");
								$rs->movenext();
							}
						?>
						</ul>
					</li>
                </ul>
             </li>
            
            
			
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <?
					if(@$_SESSION["user_support"]>0)
					{
				  ?>
                  <span class="label label-success"><?=@$_SESSION["user_support"]?></span>
                  <?
					}
				  ?>
                </a>
                <ul class="dropdown-menu">
                  <li class="header"><?=word_lang("support")?></li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                    	<?
                    	if(@$_SESSION["user_support"]>0)
						{
							$sql="select id_parent,message,user_id,data from  support_tickets where user_id<>0 order by id desc limit 0,".(int)@$_SESSION["user_support"]; 
							$dr->open($sql);
							while(!$dr->eof)
							{
								 $sql="select login,photo from users where id_parent=".$dr->row["user_id"];
								$ds->open($sql);
								if(!$ds->eof)
								{
									$photo=site_root."/images/user.gif";
									if($ds->row["photo"]!="")
									{
										$photo=$ds->row["photo"];
									}
								?>
								  <li><!-- start message -->
									<a href="../support/content.php?id=<?=$dr->row["id_parent"]?>">
									  <div class="pull-left">
										<img src="<?=$photo?>" class="img-circle"/>
									  </div>
									  <h4><?=$ds->row["login"]?>
										<small><i class="fa fa-clock-o"></i> <?=show_time_ago($dr->row["data"])?></small>
									  </h4>
									  <p><?=substr($dr->row["message"],0,100)?></p>
									</a>
								  </li><!-- end message -->
								  <?
								}
								$dr->movenext();
							}	
                      	}
                      	else
                      	{
                      		?>
                      		<li style="padding-top:15px;text-align:center"><i class="fa fa-coffee"></i>&nbsp;

 							<?=word_lang("not found")?></li>
                      		<?
                      	}
                      ?>
    
                    </ul>
                  </li>
                  <li class="footer"><a href="../support/"><?=word_lang("All support tickets")?></a></li>
                </ul>
              </li>










			 <?
				$users_properties=array("orders","credits","subscription","commission","downloads","uploads","exams","comments","lightboxes","users","support","messages","contacts","testimonials","blog","documents","payments","invoices");
				$count_notifications=0;
				
				for($i=0;$i<count($users_properties);$i++)
				{
					$count_notifications += @$_SESSION["user_".$users_properties[$i]];
				}		
			   ?>
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>   
                  <?
				  if($count_notifications >0)
				  {
                  ?>
                  		<span class="label label-warning"><?=$count_notifications?></span>
                  <?
                  }
                  ?>
                </a>
                <ul class="dropdown-menu">
                  <li class="header"><?=word_lang("notifications")?></li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
						<?
						if($count_notifications >0)
						{
							for($i=0;$i<count($users_properties);$i++)
							{
								if(@$_SESSION["user_".$users_properties[$i]]!=0)
								{
									if($users_properties[$i] == "orders")
									{
									?>
									<li>
										<a href="../orders/"><span class="label label-success pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("new orders")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "credits")
									{
									?>
									<li>
										<a href="../credits_list/"><span class="label label-danger pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("new credits")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "subscription")
									{
									?>
									<li>
										<a href="../subscription_list/"><span class="label label-warning pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("new subscriptions")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "commission")
									{
									?>
									<li>
										<a href="../commission/"><span class="label label-info pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("commission")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "downloads")
									{
									?>
									<li>
										<a href="../downloads/"><span class="label bg-purple pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("Last downloads")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "uploads")
									{
									?>
									<li>
										<a href="../upload/"><span class="label bg-olive pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("Last uploads")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "exams")
									{
									?>
									<li>
										<a href="../exam/"><span class="label label-warning pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("seller examination")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "comments")
									{
									?>
									<li>
										<a href="../comments/"><span class="label label-danger pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("Recent comments")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "lightboxes")
									{
									?>
									<li>
										<a href="../lightboxes/"><span class="label label-success pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("lightboxes")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "users")
									{
									?>
									<li>
										<a href="../customers/"><span class="label label-info pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("Latest Members")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "support")
									{
									?>
									<li>
										<a href="../support/"><span class="label bg-purple pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("support")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "messages")
									{
									?>
									<li>
										<a href="../messages/"><span class="label bg-maroon pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("messages")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "contacts")
									{
									?>
									<li>
										<a href="../contacts/"><span class="label bg-orange pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("contacts")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "testimonials")
									{
									?>
									<li>
										<a href="../testimonials/"><span class="label bg-navy pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("testimonials")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "blog")
									{
									?>
									<li>
										<a href="../blog/"><span class="label label-danger pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("blog")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "documents")
									{
									?>
									<li>
										<a href="../documents/"><span class="label label-danger pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("Documents")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "invoices")
									{
									?>
									<li>
										<a href="../invoices/"><span class="label label-danger pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("Invoices")?> </a>
									</li>
									<?
									}
									if($users_properties[$i] == "payments")
									{
									?>
									<li>
										<a href="../payments/"><span class="label label-danger pull-right"><?=@$_SESSION["user_".$users_properties[$i]]?></span><i class="fa fa-caret-right"></i> <?=word_lang("payments")?> </a>
									</li>
									<?
									}
								}
							}
						}
						else
						{
							?>
							<li style="padding-top:15px;text-align:center"><i class="fa fa-frown-o"></i>&nbsp;
 								<?=word_lang("not found")?></li>
							<?
						}
						?>
                    </ul>
                  </li>
                </ul>
              </li>












              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-question-circle"></i>
                </a>
                <ul class="dropdown-menu">
                  <li class="header"><?=word_lang("help")?></li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- Task item -->
                        <a href="https://www.cmsaccount.com/photostore/faq/" target="blank"><i class="fa fa-coffee text-yellow"></i>   <?=word_lang("faq")?></a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                        <a href="https://www.cmsaccount.com/photostore/documentation/" target="blank"><i class="fa fa-book text-red"></i>    <?=word_lang("Documentation")?></a>
                      </li><!-- end task item -->
                       <li><!-- Task item -->
                        <a href="https://www.cmsaccount.com/forum/" target="blank"><i class="fa fa-users text-green"></i>  <?=word_lang("Forum")?></a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                        <a href="https://www.cmsaccount.com/members/upgrades.php" target="blank"><i class="fa fa-refresh text-blue"></i>  <?=word_lang("Updates")?></a>
                      </li><!-- end task item -->
                       <li><!-- Task item -->
                        <a href="https://www.cmsaccount.com/contacts/" target="blank"><i class="fa fa-envelope text-aqua"></i>  <?=word_lang("Support")?></a>
                      </li><!-- end task item -->

                    </ul>
                  </li>
                </ul>
              </li>
              
              
              
              
              
              
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?=$_SESSION["user_photo"]?>" class="user-image" />
                  <span class="hidden-xs"><?=$_SESSION["user_login"]?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?=$_SESSION["user_photo"]?>" class="img-circle" />
                    <p>
                      <?=$_SESSION["user_name"]?>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-5 text-center">
                      <a href="../administrators/stats.php?id=<?=$_SESSION["user_id"]?>" style="white-space:nowrap"><i class="fa fa-bar-chart text-yellow"></i>   <?=word_lang("stats")?></a>
                    </div>
                    <div class="col-xs-7 text-center">
                      <a href="../settings/password.php" style="white-space:nowrap"><i class="fa fa-refresh text-red"></i> <?=word_lang("changepassword")?></a>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="../administrators/content.php?id=<?=$_SESSION["user_id"]?>" class="btn btn-default btn-flat"><?=word_lang("my profile")?></a>
                    </div>
                    <div class="pull-right">
                      <a href="../auth/exit.php" class="btn btn-default btn-flat"><?=word_lang("logout")?></a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?=$_SESSION["user_photo"]?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?=$_SESSION["user_name"]?></p>
              <a href="../administrators/content.php?id=<?=$_SESSION["user_id"]?>"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form action="../catalog/index.php" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search..." value="" />
              <input type="hidden" name="search_type" value="title" />
              <span class="input-group-btn">
                <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>


		<script language="javascript">
		function fmenu(pname)
		{
			sbm=new Array(
			<?
			for($i=0;$i<count($menu_admin);$i++)
			{
				if($i!=0){echo(",");}
				echo("'".$menu_admin[$i]."'");
			}
			?>
			);
		
			for(i=0;i<sbm.length;i++)
			{
				document.cookie = "z_" + sbm[i] + "=" + escape (0) + ";path=/";
			}
		
			document.cookie = "z_" + pname + "=" + escape (1) + ";path=/";
		}		
		
		<?
		function box_status()
		{
			global $menu_admin;
		
			$res="orders";
			for($i=0;$i<count($menu_admin);$i++)
			{
				if(isset($_COOKIE["z_".$menu_admin[$i]]) and (int)$_COOKIE["z_".$menu_admin[$i]]==1)
				{
					$res=$menu_admin[$i];
				}
			}
			return $res;
		}
		
		?>
		
		function fmenu_default()
		{
		//document.getElementById("sub_<?=box_status()?>").style.display ='inline';
		$("#menu_<?=box_status()?>").addClass('active');
		}
		</script>





          <ul class="sidebar-menu">
            <li class="header"><?=strtoupper(word_lang("menu"))?></li>

            
            <?
				for($i=0;$i<count($menu_admin);$i++)
				{
					$icon="";
					if($i==0)
					{
						$icon="<i class='fa fa-briefcase text-red'></i> ";
					}
					if($i==1)
					{
						$icon="<i class='fa fa-folder-open text-yellow'></i> ";
					}
					if($i==2)
					{
						$icon="<i class='fa fa-users text-aqua'></i> ";
					}
					if($i==3)
					{
						$icon="<i class='fa fa-gear text-purple'></i> ";
					}
					if($i==4)
					{
						$icon="<i class='fa fa-cubes text-teal'></i> ";
					}
					if($i==5)
					{
						$icon="<i class='fa fa-book text-orange '></i> ";
					}
					if($i==6)
					{
						$icon="<i class='fa fa-pie-chart text-green'></i> ";
					}
					?>
					<li  class="treeview" id="menu_<?=$menu_admin[$i]?>"><a href="#" onClick="javascript:fmenu('<?=$menu_admin[$i]?>')"><?=$icon?><span><?=$menu_admin_name[$i]?></span> <i class="fa fa-angle-left pull-right"></i></a>
					
					<ul id="sub_<?=$menu_admin[$i]?>"  class="treeview-menu">
						<?
						foreach ($submenu_admin as $key => $value) 
						{
							if(preg_match("/^".$menu_admin[$i]."_/",$key) and isset($_SESSION["rights"][$key]))
							{
								$sel="";
								if($key==$admin_submenu)
								{
									$sel="class='active'";
								}
								
								$menu_count="";
								
								if($key=="orders_orders" and @$_SESSION["user_orders"]>0)
								{
									$menu_count='<span class="label label-danger pull-right">'.@$_SESSION["user_orders"].'</span>';
								}
								
								if($key=="orders_credits" and @$_SESSION["user_credits"]>0)
								{
									$menu_count='<span class="label label-warning pull-right">'.@$_SESSION["user_credits"].'</span>';
								}
								
								if($key=="orders_subscription" and @$_SESSION["user_subscription"]>0)
								{
									$menu_count='<span class="label label-info pull-right">'.@$_SESSION["user_subscription"].'</span>';
								}
								
								if($key=="orders_commission" and @$_SESSION["user_commission"]>0)
								{
									$menu_count='<span class="label label-primary pull-right">'.@$_SESSION["user_commission"].'</span>';
								}
								
								if($key=="orders_downloads" and @$_SESSION["user_downloads"]>0)
								{
									$menu_count='<span class="label bg-purple pull-right">'.@$_SESSION["user_downloads"].'</span>';
								}
								
								if($key=="orders_invoices" and @$_SESSION["user_invoices"]>0)
								{
									$menu_count='<span class="label label-info pull-right">'.@$_SESSION["user_invoices"].'</span>';
								}
								
								if($key=="orders_payments" and @$_SESSION["user_payments"]>0)
								{
									$menu_count='<span class="label label-warning pull-right">'.@$_SESSION["user_payments"].'</span>';
								}
								
								if($key=="catalog_exam" and @$_SESSION["user_exams"]>0)
								{
									$menu_count='<span class="label label-info pull-right">'.@$_SESSION["user_exams"].'</span>';
								}
								
								if($key=="catalog_comments" and @$_SESSION["user_comments"]>0)
								{
									$menu_count='<span class="label label-warning pull-right">'.@$_SESSION["user_comments"].'</span>';
								}
								
								if($key=="catalog_lightboxes" and @$_SESSION["user_lightboxes"]>0)
								{
									$menu_count='<span class="label label-success pull-right">'.@$_SESSION["user_lightboxes"].'</span>';
								}
								
								if($key=="users_customers" and @$_SESSION["user_users"]>0)
								{
									$menu_count='<span class="label bg-purple pull-right">'.@$_SESSION["user_users"].'</span>';
								}
								
								if($key=="users_documents" and @$_SESSION["user_documents"]>0)
								{
									$menu_count='<span class="label label-success pull-right">'.@$_SESSION["user_documents"].'</span>';
								}
								
								if($key=="users_messages" and @$_SESSION["user_messages"]>0)
								{
									$menu_count='<span class="label label-warning pull-right">'.@$_SESSION["user_messages"].'</span>';
								}
								
								if($key=="users_contacts" and @$_SESSION["user_contacts"]>0)
								{
									$menu_count='<span class="label label-success pull-right">'.@$_SESSION["user_contacts"].'</span>';
								}
								
								if($key=="users_testimonials" and @$_SESSION["user_testimonials"]>0)
								{
									$menu_count='<span class="label label-info pull-right">'.@$_SESSION["user_testimonials"].'</span>';
								}
								
								if($key=="users_blogs" and @$_SESSION["user_blog"]>0)
								{
									$menu_count='<span class="label label-primary pull-right">'.@$_SESSION["user_blog"].'</span>';
								}
								
								if($key=="users_support" and @$_SESSION["user_support"]>0)
								{
									$menu_count='<span class="label label-danger pull-right">'.@$_SESSION["user_support"].'</span>';
								}
								if($key=="catalog_upload" and @$_SESSION["user_uploads"]>0)
								{
									$menu_count='<span class="label label-danger pull-right">'.@$_SESSION["user_uploads"].'</span>';
								}
								?>
								<li <?=$sel?>><a href="<?=$submenu_admin_url[$key]?>"><i class="fa fa-circle-o"></i>  <?=$submenu_admin[$key]?><?=$menu_count?></a></li>
								<?
							}
						}
						?>
					</ul>

					</li>
					<?
				}
			?>

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
      <section class="content-header">
      <div id='lightbox' style='top:0px;left:0px;position:absolute;z-index:1000;display:none'></div>