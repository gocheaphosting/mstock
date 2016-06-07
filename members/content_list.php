<?
if(!defined("site_root")){exit();}

include("content_list_vars.php");
?>
<style>
/*New styles for the previews. It overwrites style.css file.*/
.item_list 
{ 
	width: <?=($global_settings["thumb_width"]+20)?>px;
}

.item_list_img
{
	width: <?=($global_settings["thumb_width"]+20)?>px;
	height: <?=($global_settings["thumb_width"]+20)?>px;
}

.item_list_text1,.item_list_text2,.item_list_text3,.item_list_text4
{
	width: <?=($global_settings["thumb_width"]+20)?>px;
}

<?if(@$stock!="site"){?>
	<?if(@$stock!="fotolia" and @$stock!="depositphotos"){?>
		.iviewed,.idownloaded
		{
		display:none;
		}
	<?}?>
	
	.action-control,.fa-heart-o,li.hb_lightbox
	{
	display:none;
	}
	
	.preview_listing
	{
	max-width:120px;
	max-height:120px;
	}
	
<?}?>
</style>

<?
$search_page="<table border='0' cellpadding='0' cellspacing='0' width='100%'>
	<tr valign='top'>
		<td class='search_left'>
			{MENU}
		</td>
		<td class='search_right'>
			{RESULTS}
		</td>
	</tr>
</table>";

if(file_exists($DOCUMENT_ROOT."/".$site_template_url."catalog.tpl"))
{
	$search_page=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."catalog.tpl");
}

$search_page=str_replace("{MENU}","{SEPARATOR}",$search_page);
$search_page=str_replace("{RESULTS}","{SEPARATOR}",$search_page);

$search_parts=explode("{SEPARATOR}",$search_page);

$search_header=$search_parts[0];
$search_middle=@$search_parts[1];
$search_footer=@$search_parts[2];

?>


<?
if($showmenu==1 and $global_settings["left_search"])
{
	echo($search_header);
	
	if($stock == 'site')
	{
		include("content_list_menu.php");
	}
	
	if($stock == 'istockphoto')
	{
		include("content_list_menu_istockphoto.php");
	}	
	
	if($stock == 'shutterstock')
	{
		include("content_list_menu_shutterstock.php");
	}	
	
	if($stock == 'fotolia')
	{
		include("content_list_menu_fotolia.php");
	}	
	
	if($stock == 'depositphotos')
	{
		include("content_list_menu_depositphotos.php");
	}	
	
	if($stock == 'rf123')
	{
		include("content_list_menu_123rf.php");
	}
	
	if($stock == 'bigstockphoto')
	{
		include("content_list_menu_bigstockphoto.php");
	}

	echo($search_middle);
}
//Show menu
?>
<div class="search_header_mobile visible-phone"></div>
<div id="search_header">
	<?
	$search_title=word_lang("results");
	if($id_parent!=5)
	{
		$sql2="select id_parent,title from category where id_parent=".(int)$id_parent;
		$dr->open($sql2);
		if(!$dr->eof)
		{
			$translate_results=translate_category($dr->row["id_parent"],$dr->row["title"],"","");
			$search_title=$translate_results["title"];
		}
	}
	?>
	<h1><?=$search_title?> <span id="result_count">(<?
	if(!$global_settings["no_calculation"])
	{
		echo($record_count);
	}
	else
	{
		echo(" > ".$global_settings["no_calculation_result"]);
	}
	
	?>)</span>
	<?
	/*
	if($id_parent!=5)
	{
		$sql2="select a.id,a.id_parent,b.id_parent,b.title,b.url from structure a,category b where a.id=b.id_parent and a.id_parent=".(int)$id_parent."  order by b.priority limit 0,7";
		$dr->open($sql2);
		while(!$dr->eof)
		{
			$translate_results=translate_category($dr->row["id"],$dr->row["title"],"","");
			$search_title=$translate_results["title"];
			?>&nbsp;&nbsp;&nbsp;	<a href="<?=$dr->row["url"]?>"><?=$search_title?></a> <?
			$dr->movenext();
		}
	}
	*/
	?></h1>
	<div id="search_header2">
	<div id="search_sort">
		<?
		if($stock_remote)
		{
			$stockmenu="<select onChange='location.href=this.value' style='width:130px' class='ibox2'>";
			foreach ($mstocks as $key => $value) 
			{
				if($global_settings[$key."_api"])
				{
					$sel="";
					if($key==$stock)
					{
						$sel="selected";
					}
					$stockmenu.="<option value='?stock=".$key."&search=".urlencode(@$_REQUEST["search"])."' ".$sel.">".$value."</option>";
				}
			}
			$stockmenu.="</select>";
			echo($stockmenu);
		}
		else
		{
			echo($sortmenu);
		}
		?>
	</div>
	
	<div id="search_contentmenu">
		<?
		$stock_sort=array();
		
		if($stock != "site")
		{
			if($stock == 'shutterstock')
			{
				$stock_sort=array( "popular","newest", "relevance", "random");
						
				$vars_sort=build_variables("sort","");
				$sortmenu="<select onChange='location.href=this.value' style='width:160px' class='ibox2'>";
				
				foreach ($stock_sort as $key => $value) 
				{
					$sel="";
					if($value==@$_REQUEST["sort"])
					{
						$sel="selected";
					}
					$sortmenu.="<option value='".$vars_sort."&sort=".$value."' ".$sel.">".word_lang($value)."</option>";
				}
				
				$sortmenu.="</select>";
				echo($sortmenu);
			}
			
			if($stock == 'fotolia')
			{
				$stock_sort=array();
				$stock_sort[ 'relevance' ] = word_lang("relevance");
				$stock_sort[ 'price_1' ] = word_lang("price");
				$stock_sort[ 'creation' ] = word_lang("date");
				$stock_sort[ 'nb_views' ] = word_lang("most popular");
				$stock_sort[ 'nb_downloads' ] = word_lang("most downloaded");
						
				$vars_sort=build_variables("sort","");
				$sortmenu="<select onChange='location.href=this.value' style='width:160px' class='ibox2'>";
				
				foreach ($stock_sort as $key => $value) 
				{
					$sel="";
					if($key == @$_REQUEST["sort"])
					{
						$sel="selected";
					}
					$sortmenu.="<option value='".$vars_sort."&sort=".$key."' ".$sel.">".word_lang($value)."</option>";
				}
				
				$sortmenu.="</select>";
				echo($sortmenu);
			}
			
			
			if($stock == 'istockphoto')
			{
				$stock_sort=array();
				$stock_sort[ 'best_match' ] = word_lang("relevance");
				$stock_sort[ 'most_popular' ] = word_lang("most popular");
				$stock_sort[ 'newest' ] = word_lang("date");
						
				$vars_sort=build_variables("sort","");
				$sortmenu="<select onChange='location.href=this.value' style='width:160px' class='ibox2'>";
				
				foreach ($stock_sort as $key => $value) 
				{
					$sel="";
					if($key == @$_REQUEST["sort"])
					{
						$sel="selected";
					}
					$sortmenu.="<option value='".$vars_sort."&sort=".$key."' ".$sel.">".word_lang($value)."</option>";
				}
				
				$sortmenu.="</select>";
				echo($sortmenu);
			}
			
			if($stock == 'depositphotos')
			{
				$stock_sort=array();
				$stock_sort[ '1' ] = word_lang("relevance");
				$stock_sort[ '4' ] = word_lang("most downloaded");
				$stock_sort[ '5' ] = word_lang("new");
						
				$vars_sort=build_variables("sort","");
				$sortmenu="<select onChange='location.href=this.value' style='width:160px' class='ibox2'>";
				
				foreach ($stock_sort as $key => $value) 
				{
					$sel="";
					if($key == @$_REQUEST["sort"])
					{
						$sel="selected";
					}
					$sortmenu.="<option value='".$vars_sort."&sort=".$key."' ".$sel.">".word_lang($value)."</option>";
				}
				
				$sortmenu.="</select>";
				echo($sortmenu);
			}
			
			if($stock == 'bigstockphoto')
			{
				$stock_sort=array();
				$stock_sort[ 'popular' ] = word_lang("most popular");
				$stock_sort[ 'relevant' ] = word_lang("relevance");			
				$stock_sort[ 'new' ] = word_lang("date");
						
				$vars_sort=build_variables("sort","");
				$sortmenu="<select onChange='location.href=this.value' style='width:160px' class='ibox2'>";
				
				foreach ($stock_sort as $key => $value) 
				{
					$sel="";
					if($key == @$_REQUEST["sort"])
					{
						$sel="selected";
					}
					$sortmenu.="<option value='".$vars_sort."&sort=".$key."' ".$sel.">".word_lang($value)."</option>";
				}
				
				$sortmenu.="</select>";
				echo($sortmenu);
			}
			
			if($stock == 'rf123')
			{
				$stock_sort=array();
				$stock_sort[ 'random' ] = word_lang("random");
				$stock_sort[ 'latest' ] = word_lang("new");			
				$stock_sort[ 'most_downloaded' ] = word_lang("most downloaded");
						
				$vars_sort=build_variables("sort","");
				$sortmenu="<select onChange='location.href=this.value' style='width:160px' class='ibox2'>";
				
				foreach ($stock_sort as $key => $value) 
				{
					$sel="";
					if($key == @$_REQUEST["sort"])
					{
						$sel="selected";
					}
					$sortmenu.="<option value='".$vars_sort."&sort=".$key."' ".$sel.">".word_lang($value)."</option>";
				}
				
				$sortmenu.="</select>";
				echo($sortmenu);
			}
		}
		else
		{
			if($stock_remote)
			{
				echo($sortmenu);
			}
			else
			{
				echo($contentmenu);
			}
		}
		?>
	</div>

	<div id="search_items"><?=$itemsmenu?></div>
	<?
	$flow_count=0;
	if($global_settings["grid"]){$flow_count++;}
	if($global_settings["fixed_width"]){$flow_count++;}
	if($global_settings["fixed_height"]){$flow_count++;}
	
	
	
	if($flow_count>1)
	{
	?>
		<div id="search_flow_menu">
			<?
			if($global_settings["grid"])
			{
				?>
				<a href="<?=build_variables("flow","")?>&flow=0"><img src="<?=site_root?>/<?=$site_template_url?>images/view0.gif" class='<?if($flow==0){echo("active");}else{echo("disabled");}?>'></a>
				<?
			}
			if($global_settings["fixed_width"])
			{
				?>
				<a href="<?=build_variables("flow","")?>&flow=1"><img src="<?=site_root?>/<?=$site_template_url?>images/view1.gif" class='<?if($flow==1){echo("active");}else{echo("disabled");}?>'></a>
				<?
			}
			if($global_settings["fixed_height"])
			{
				?>
				<a href="<?=build_variables("flow","")?>&flow=2"><img src="<?=site_root?>/<?=$site_template_url?>images/view2.gif" class='<?if($flow==2){echo("active");}else{echo("disabled");}?>'></a>
				<?
			}
			?>
		</div>
	<?
	}
	?>
	
	<?
	if($global_settings["auto_paging"])
	{
	?>
		<div id="search_autopaging_menu"><input type="checkbox" name="autopaging" <?if($autopaging==1){echo("checked");}?> onClick="location.href='<?=build_variables("autopaging","")?>&autopaging=<?if($autopaging==1){echo(0);}else{echo(1);}?>'">&nbsp;<?=word_lang("auto")?></div>
	<?
	}
	if($global_settings["left_search"])
	{
	?>
		<div id="search_show_menu" style="margin-top:5px"><input type="checkbox" name="showmenu" <?if($showmenu==1){echo("checked");}?> onClick="location.href='<?=build_variables("showmenu","")?>&showmenu=<?if($showmenu==1){echo(0);}else{echo(1);}?>'">&nbsp;<?=word_lang("menu")?></div>
	<?
	}
	?>
	</div>
	<?
	if($record_count>$kolvo and $autopaging==0)
	{
		?>
		<!--<div id="search_paging"><?=$paging_text?></div>-->
		<?
	}
	?>
	
</div>
<div class="search_header_mobile visible-phone"></div>




<?
if($flow==1)
{
	?>
	<script src="<?=site_root?>/inc/jquery.masonry.min.js"></script>
	<script>
	$(document).ready(function(){
		$('#flow_body').masonry({
  		itemSelector: '.home_box'
		});
		
		$('.home_preview').each(function(){


     		$(this).animate({opacity:'1.0'},1);
   			$(this).mouseover(function(){
     		$(this).stop().animate({opacity:'0.6'},600);
    		});
    		$(this).mouseout(function(){
    		$(this).stop().animate({opacity:'1.0'},300);
    		});

    		
    		$(".hb_cart").mouseover(function(){
     			$(this).stop().animate({ opacity: 1}, 600);

    		});

    		$(".hb_cart").mouseout(function(){
    			$(this).stop().animate({ opacity: 0.5}, 600);
    		});
 		
    		<?if(@$stock=="site"){?>
    		 $(".hb_lightbox").mouseover(function(){
     			$(this).stop().animate({ opacity: 1}, 600);
    		});

    		$(".hb_lightbox").mouseout(function(){
    			$(this).stop().animate({ opacity: 0.5}, 600);
    		});
    		<?}?>
    		
    		 $(".hb_free").mouseover(function(){
     			$(this).stop().animate({ opacity: 1}, 600);
    		});

    		$(".hb_free").mouseout(function(){
    			$(this).stop().animate({ opacity: 0.5}, 600);
    		});
        

		});
	});
	</script>
	<?
}



if($flow==2)
{
	?>
	<script src="<?=site_root?>/inc/js/collageplus/jquery.collagePlus.min.js"></script>
	<script src="<?=site_root?>/inc/js/collageplus/extras/jquery.removeWhitespace.js"></script>
    <script src="<?=site_root?>/inc/js/collageplus/extras/jquery.collageCaption.js"></script>
	<script>
	$(document).ready(function(){
	
	

	
		refreshCollagePlus();
		
		$('.home_preview').each(function(){
     		$(this).animate({opacity:'1.0'},1);
   			$(this).mouseover(function(){
     		$(this).stop().animate({opacity:'0.6'},600);
    		});
    		$(this).mouseout(function(){
    		$(this).stop().animate({opacity:'1.0'},300);
    		});
    	});
	});
	

	
	function refreshCollagePlus() {
    	$('.item_list_page').removeWhitespace().collagePlus({
        	'targetHeight'    : <?=$global_settings["height_flow"]?>,
            'fadeSpeed'       : "slow",
            'effect'          : 'default',
            'direction'       : 'vertical',
            'allowPartialLastRow'       : true
    	});
	}
	
	// This is just for the case that the browser window is resized
    var resizeTimer = null;
    $(window).bind('resize', function() {
        if (resizeTimer) clearTimeout(resizeTimer);
        resizeTimer = setTimeout(refreshCollagePlus, 200);
    });

	</script>
	<style>

	.item_list_page img{
    	margin:10px;
    	padding:0px;
    	display:inline-block;
    	vertical-align:bottom;
    	opacity:1;
	}
	</style>
	<link rel="stylesheet" type="text/css" href="<?=site_root?>/inc/js/collageplus/css/transitions.css" media="all" />
	<?
}


if($autopaging==1)
{
	?>
	<script>
	str=2;
	flag_auto=true;
	res=" ";
	
	function auto_paging(page)
	{
		str=page;
	
    	var req = new JsHttpRequest();
   		 // Code automatically called on load finishing.
    	req.onreadystatechange = function() {
        if (req.readyState == 4) {
 		if(page==1)
 		{
			document.getElementById('flow_body').innerHTML =req.responseText;
			res=req.responseText;
		}
		else
		{
			document.getElementById('flow_body').innerHTML = document.getElementById('flow_body').innerHTML + req.responseText;
			res=req.responseText;
			check_carts('<?=word_lang("in your cart")?>');
		}

		<?
		if($flow==1)
		{
		?>
			$('#flow_body').masonry({
  			itemSelector: '.home_box'
			});
		
			$('#flow_body').masonry('reload') ;
		<?
		}
		if($flow==2)
		{
			?>
			refreshCollagePlus();
			<?
		}
		?>
		
		
		$('.home_preview').each(function(){


     		$(this).animate({opacity:'1.0'},1);
   			$(this).mouseover(function(){
     		$(this).stop().animate({opacity:'0.6'},600);
    		});
    		$(this).mouseout(function(){
    		$(this).stop().animate({opacity:'1.0'},300);
    		});

    		
    		$(".hb_cart").mouseover(function(){
     			$(this).stop().animate({ opacity: 1}, 600);

    		});

    		$(".hb_cart").mouseout(function(){
    			$(this).stop().animate({ opacity: 0.5}, 600);
    		});
    		
    		$(".hb_cart2").mouseover(function(){
     			$(this).stop().animate({ opacity: 1}, 600);
    		});

    		$(".hb_cart2").mouseout(function(){
    			$(this).stop().animate({ opacity: 0.5}, 600);
    		});
 		
    		
    		 $(".hb_lightbox").mouseover(function(){
     			$(this).stop().animate({ opacity: 1}, 600);
    		});

    		$(".hb_lightbox").mouseout(function(){
    			$(this).stop().animate({ opacity: 0.5}, 600);
    		});
    		
    		 $(".hb_free").mouseover(function(){
     			$(this).stop().animate({ opacity: 1}, 600);
    		});

    		$(".hb_free").mouseout(function(){
    			$(this).stop().animate({ opacity: 0.5}, 600);
    		});
        

		});


        }
    }
    req.open(null, '<?=site_root?>/members/content_list_paging.php', true);
    req.send( {<?=$flow_vars?>,str: str,id_parent:<?=$id_parent?>} );
    str++;

	}
	
	
	$(document).ready(function(){
		$(window).scroll(function(){
			if($(document).height() - $(window).height() - $(window).scrollTop() <150) 
    		{
    			if(flag_auto)
    			{
    				flag_auto=false;
    				if(res!="")
    				{
    					auto_paging(str);
    				}
    			}
    		}
    		else
    		{
    			flag_auto=true;
    		}
		});
	});
	</script>
	<?
}
?>


<div class='item_list_page' id="flow_body">
	<?
		$search_content =word_lang("not found");
		
		if($stock == 'site')
		{
			include("content_list_items.php");
		}
		
		if($stock == 'istockphoto')
		{
			include("content_list_istockphoto.php");
		}	
		
		if($stock == 'shutterstock')
		{
			include("content_list_shutterstock.php");
		}	
		
		if($stock == 'fotolia')
		{
			include("content_list_fotolia.php");
		}	
		
		if($stock == 'depositphotos')
		{
			include("content_list_depositphotos.php");
		}	
		
		if($stock == 'rf123')
		{
			include("content_list_123rf.php");
		}	
		
		if($stock == 'bigstockphoto')
		{
			include("content_list_bigstockphoto.php");
		}	
					
		//Show result
		echo($search_content);
	?>
</div>

<script>
check_carts('<?=word_lang("in your cart")?>');
</script>


<?
//Stock results count
if($stock != 'site')
{
	$paging_text=paging((int)@$stock_result_count,$str,$kolvo,$kolvo2,site_root."/index.php",build_variables("str","",false),false,true);
	
	if((int)@$stock_result_count>$kolvo)
	{
	?>
	<div id="search_footer">
		<div id="search_paging2"><?=$paging_text?></div>
	</div>
	<?
	}
	?>
	<script>
		function show_results_count()
		{
			$('#result_count').html('(<?=(int)@$stock_result_count?>)')
		}
		show_results_count()
	</script>
	<?
}
else
{
	if($record_count>$kolvo and @$autopaging==0)
	{
	?>
	<div id="search_footer">
		<div id="search_paging2"><?=$paging_text?></div>
	</div>
	<?
	}
}
?>


<?
if($showmenu==1 and $global_settings["left_search"])
{
	echo($search_footer);
}
?>

