
ar_menu=new Array('downloaded','featured','popular','new','free','random');

str=1;
id_component=0;
ctype_component="";
flag_auto=true;
res="";

function zcomponent(id,ctype,page) 
{
	if(id!=id_component)
	{
		document.getElementById('home_boxes').innerHTML = "";
	}

	str=page;
	
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
        
        
        for(i=0;i<ar_menu.length;i++)
        {
        	if(ar_menu[i]==ctype)
        	{
        		document.getElementById('menu_'+ctype).className="tact";
        	}
        	else
        	{
        		document.getElementById('menu_'+ar_menu[i]).className="tact2";
        	}
        }
        

 		if(page==1)
 		{
			document.getElementById('home_boxes').innerHTML =req.responseText;
			res=req.responseText;
			check_carts('');
		}
		else
		{
			document.getElementById('home_boxes').innerHTML = document.getElementById('home_boxes').innerHTML + req.responseText;
			res=req.responseText;
			check_carts('');
		}

		$('#home_boxes').masonry({
  		itemSelector: '.home_box'
		});
		
		$('#home_boxes').masonry('reload') ;
		
		
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


        }
    }
    req.open(null, 'members/component_light2.php', true);
    req.send( {id: id, ctype: ctype,str: str} );
    str++;
   	id_component=id;
    ctype_component=ctype;
}







$(document).ready(function(){

	$(window).scroll(function(){
		if($(document).height() - $(window).height() - $(window).scrollTop() <150) 
    	{
    		if(str!=1 && flag_auto)
    		{
    			flag_auto=false;
    			if(res!="")
    			{
    				//zcomponent(id_component,ctype_component,str);
    			}
    		}
    	}
    	else
    	{
    		flag_auto=true;
    	}
	});

	
	$('#slides').superslides({
    slide_easing: 'easeInOutCubic',
    slide_speed: 800,
    pagination: false,
    hashchange: true,
    scrollable: false,
    inherit_width_from: '#wrapper_slideshow',
    inherit_height_from: '#wrapper_slideshow',
    animation:'fade',
    play:5000
  });
  
  $(".prev1").css({ opacity: 0.5});
   $(".next1").css({ opacity: 0.5});
  
  	$(".next1").mouseover(function(){
     	$(this).stop().animate({ opacity: 1}, 600);
   });

    $(".next1").mouseout(function(){
    	$(this).stop().animate({ opacity: 0.5}, 600);
    });
    
    $(".prev1").mouseover(function(){
     	$(this).stop().animate({ opacity: 1}, 600);
   });

    $(".prev1").mouseout(function(){
    	$(this).stop().animate({ opacity: 0.5}, 600);
    });
	
});