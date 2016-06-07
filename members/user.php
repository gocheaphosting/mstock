<?$site="user";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>





<?include("user_top.php");?>





<?
if(isset($user_id))
{
	$flag=false;
	
	$sql="select id_parent,description,utype,login from users where id_parent=".(int)$user_id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		echo(str_replace("\n","<br>",strip_tags($rs->row["description"])));
	
		if($rs->row["utype"]=="seller" or $rs->row["utype"]=="common" )
		{
			$flag=true;
		}
	}
	
	if($flag==true)
	{
			$id_parent=5;
			$_REQUEST["flow"]=1;
			$_REQUEST["portfolio"]=1;
			$_REQUEST["user"]=(int)$user_id;
			
			include("content_list_vars.php");
			include("content_list_items.php");
			
			echo("<div id='flow_body' style='clear:both;margin-top:20px'>".$search_content."</div>");
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
	});
	</script>
			<?
	}
}




?>














<?include("user_bottom.php");?>






















<?include("../inc/footer.php");?>