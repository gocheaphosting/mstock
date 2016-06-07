<?
if(!defined("site_root")){exit();}
?>
</section>
<br>
</div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> <?=$script_version?>
        </div>
        <strong>Copyright &copy; 2006-2016 <a href="http://www.cmsaccount.com/">www.cmsaccount.com</a> - All rights reserved. &nbsp;&nbsp;&nbsp;Theme: <a href="https://github.com/almasaeed2010/AdminLTE" target="blank">AdminLTE</a>
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">


        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <!-- Home tab content -->
          <div class="tab-pane" id="control-sidebar-home-tab">
            

          </div><!-- /.tab-pane -->

          <!-- Settings tab content -->
          <div class="tab-pane" id="control-sidebar-settings-tab">
            
          </div><!-- /.tab-pane -->
        </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>

    </div><!-- ./wrapper -->

	<script>
		//Translations
		word_select_skin = "<?=word_lang("select skin")?>";
		word_color = "<?=word_lang("color")?>";
		word_settings = "<?=word_lang("settings")?>";
		word_fixed_layout = "<?=word_lang("Fixed layout")?>";
		word_boxed_layout = "<?=word_lang("Boxed Layout")?>";
		word_toggle_left_sidebar = "<?=word_lang("Toggle Left Sidebar")?>";
		word_left_sidebar_expand_on_hover = "<?=word_lang("Left Sidebar Expand on Hover")?>";
		
		<?
		if(isset($_COOKIE["ch_sidebar_hover"]) and $_COOKIE["ch_sidebar_hover"]==1)
		{
		?>
			var AdminLTEOptions = {
			  sidebarExpandOnHover: true
			};
        <?
        }
        else
        {
 		?>
			var AdminLTEOptions = {
			  sidebarExpandOnHover: false
			};
        <?       
        }
        ?>
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/js/app.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/chartjs/Chart.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/js/demo.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/js/scripts.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?=site_root?>/members/swfobject.js"></script>
	<script type="text/javascript" src="<?=site_root?>/inc/jquery.lightbox-0.5.js"></script>
	<script src="<?=site_root?>/inc/jquery.colorbox-min.js" type="text/javascript"></script>
	<script>
	function change_hover_sidebar()
    {
     <?
		if(isset($_COOKIE["ch_sidebar_hover"]) and $_COOKIE["ch_sidebar_hover"]==1)
		{
		?>
				$("#ch_sidebar_hover").attr("checked","checked");
				$('#ch_sidebar_hover').prop('disabled', false);
        <?
        }
        ?>   
    }
        
    change_hover_sidebar()
	
	</script>
	<?
	if(@$home_page_flag)
	{
	?>
	<script>
'use strict';
$(function () {

  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */

  //-----------------------
  //- MONTHLY SALES CHART -
  //-----------------------

  // Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $("#salesChart").get(0).getContext("2d");
  // This will get the first returned node in the jQuery collection.
  var salesChart = new Chart(salesChartCanvas);

  var salesChartData = {
    labels: [<?=@$sales_month_list?>],
    datasets: [
      <?
      if($global_settings["credits"])
      {
      ?>
		  {
			label: "<?=word_lang("credits")?>",
			fillColor: "rgb(218, 69, 65)",
			strokeColor: "rgb(218, 69, 65)",
			pointColor: "rgb(218, 69, 65)",
			pointStrokeColor: "#c1c7d1",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgb(218, 69, 65)",
			data: [<?=@$sales_credits_list?>]
		  }
      <?
      }
      else
	  {
	  ?>
		  {
			label: "<?=word_lang("orders")?>",
			fillColor: "rgb(218, 69, 65)",
			strokeColor: "rgb(218, 69, 65)",
			pointColor: "rgb(218, 69, 65)",
			pointStrokeColor: "#c1c7d1",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgb(218, 69, 65)",
			data: [<?=@$sales_orders_list?>]
		  }
	  <? 
	  }
	  

      
      
      if($global_settings["subscription"])
      {
      	 if(!$global_settings["subscription_only"])
	  	{
	  		echo(",");
	  	}
      ?>
		  {
			label: "<?=word_lang("subscription")?>",
			fillColor: "rgba(60,141,188,0.9)",
			strokeColor: "rgba(60,141,188,0.8)",
			pointColor: "#3b8bba",
			pointStrokeColor: "rgba(60,141,188,1)",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(60,141,188,1)",
			data: [<?=@$sales_subscription_list?>]
		  }
      <?
      }
      ?>
    ]
  };

  var salesChartOptions = {
    //Boolean - If we should show the scale at all
    showScale: true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines: false,
    //String - Colour of the grid lines
    scaleGridLineColor: "rgba(0,0,0,.05)",
    //Number - Width of the grid lines
    scaleGridLineWidth: 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,
    //Boolean - Whether the line is curved between points
    bezierCurve: true,
    //Number - Tension of the bezier curve between points
    bezierCurveTension: 0.3,
    //Boolean - Whether to show a dot for each point
    pointDot: false,
    //Number - Radius of each point dot in pixels
    pointDotRadius: 4,
    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth: 1,
    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius: 20,
    //Boolean - Whether to show a stroke for datasets
    datasetStroke: true,
    //Number - Pixel width of dataset stroke
    datasetStrokeWidth: 2,
    //Boolean - Whether to fill the dataset with a color
    datasetFill: true,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true
  };

  //Create the line chart
  salesChart.Line(salesChartData, salesChartOptions);

  //---------------------------
  //- END MONTHLY SALES CHART -
  //---------------------------

  //-------------
  //- PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
  var pieChart = new Chart(pieChartCanvas);
  var PieData = [
  <?if($global_settings["allow_photo"]){?>
    {
      value: <?=@$count_photos?>,
      color: "#f56954",
      highlight: "#f56954",
      label: "<?=word_lang("photos")?>"
    },
  <?}?>
  <?if($global_settings["allow_video"]){?>
    {
      value: <?=@$count_videos?>,
      color: "#00a65a",
      highlight: "#00a65a",
      label: "<?=word_lang("videos")?>"
    },
  <?}?>
  <?if($global_settings["allow_audio"]){?>
    {
      value: <?=@$count_audio?>,
      color: "#f39c12",
      highlight: "#f39c12",
      label: "<?=word_lang("audio")?>"
    },
  <?}?>
  <?if($global_settings["allow_vector"]){?>
    {
      value: <?=@$count_vector?>,
      color: "#00c0ef",
      highlight: "#00c0ef",
      label: "<?=word_lang("vector")?>"
    },
  <?}?>
  ];
  var pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    //String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    //Number - The width of each segment stroke
    segmentStrokeWidth: 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps: 100,
    //String - Animation easing effect
    animationEasing: "easeOutBounce",
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    //String - A tooltip template
    tooltipTemplate: "<%=value %> <%=label%>"
  };
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.  
  pieChart.Doughnut(PieData, pieOptions);
  //-----------------
  //- END PIE CHART -
  //-----------------



  /* SPARKLINE CHARTS
   * ----------------
   * Create a inline charts with spark line
   */

  //-----------------
  //- SPARKLINE BAR -
  //-----------------
  $('.sparkbar').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type: 'bar',
      height: $this.data('height') ? $this.data('height') : '30',
      barColor: $this.data('color')
    });
  });

  //-----------------
  //- SPARKLINE PIE -
  //-----------------
  $('.sparkpie').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type: 'pie',
      height: $this.data('height') ? $this.data('height') : '90',
      sliceColors: $this.data('color')
    });
  });

  //------------------
  //- SPARKLINE LINE -
  //------------------
  $('.sparkline').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type: 'line',
      height: $this.data('height') ? $this.data('height') : '90',
      width: '100%',
      lineColor: $this.data('linecolor'),
      fillColor: $this.data('fillcolor'),
      spotColor: $this.data('spotcolor')
    });
  });
});
	</script>
	<?
	}
	?>
  </body>
</html>
