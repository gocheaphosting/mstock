<?
if(!defined("site_root")){exit();}	
?>

<script type="text/javascript" language="JavaScript">

//Add to cart on catalog listing
function add_cart(x)
{
	flag_add=true;
	x_number=0;
	value=x;
    var req = new JsHttpRequest();
    for(i=0;i<cart_mass.length;i++)
	{
		if(cart_mass[i]==x)
		{
			flag_add=false;
			x_number=i;
		}
	}
    
    if(flag_add)
    {
    	cart_mass[cart_mass.length]=x;
    	
    	// Code automatically called on load finishing.
    	req.onreadystatechange = function()
    	{
       	 	if (req.readyState == 4)
       	 	{
				if(document.getElementById('shopping_cart'))
				{
					document.getElementById('shopping_cart').innerHTML =req.responseJS.box_shopping_cart;
				}
				if(document.getElementById('shopping_cart_lite'))
				{
					document.getElementById('shopping_cart_lite').innerHTML =req.responseJS.box_shopping_cart_lite;
				}
				if(document.getElementById('cart'+value.toString()))
				{
					document.getElementById('cart'+value.toString()).innerHTML ="<a href='javascript:add_cart("+value+");' class='ac2'><?=word_lang("in your cart")?></a>";
				}
				
				if(typeof reload_cart == 'function') 
				{
   					reload_cart();
				}
        	}
   	 	}
    	req.open(null, '<?=site_root?>/members/shopping_cart_add_light.php', true);
    	req.send( {id: value } );
    }
    else
    {
   	 	cart_mass[x_number]=0;
   	 	
   	 	// Code automatically called on load finishing.
    	req.onreadystatechange = function()
    	{
        	if (req.readyState == 4)
        	{
				if(document.getElementById('shopping_cart'))
				{
					document.getElementById('shopping_cart').innerHTML =req.responseJS.box_shopping_cart;
				}
				if(document.getElementById('shopping_cart_lite'))
				{
					document.getElementById('shopping_cart_lite').innerHTML =req.responseJS.box_shopping_cart_lite;
				}
				if(document.getElementById('cart'+value.toString()))
				{
					document.getElementById('cart'+value.toString()).innerHTML ="<a href='javascript:add_cart("+value+");' class='ac'><?=word_lang("add to cart")?></a>";
				}
				
				if(typeof reload_cart == 'function') 
				{
   					reload_cart();
				}
        	}
   	 	}
   	 	req.open(null, '<?=site_root?>/members/shopping_cart_delete_light.php', true);
    	req.send( {id: value } );
    }
}




		$(function(){
		$('.preview_listing').each(function(){
     		$(this).animate({opacity:'1.0'},1);
   			$(this).mouseover(function(){
     		$(this).stop().animate({opacity:'0.3'},600);
    		});
    		$(this).mouseout(function(){
    		$(this).stop().animate({opacity:'1.0'},300);
    		});
		});

		});
		
//Video mp4/mov preview
function lightboxon_istock(fl,width,height,event,rt)
{
	rcontent="<video   width='"+width+"' height='"+height+"' autoplay controls><source src='"+fl+"' type='video/mp4'></video>";

	preview_moving(rcontent,width,height,event);
}

</script>