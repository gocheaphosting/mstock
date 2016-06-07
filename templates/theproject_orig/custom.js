function make_cart()
{	
	total=0;
	qty=0;
	
	for(i=0;i<cart_mass.length;i++)
	{
		if(cart_remove[i]==0)
		{
			total+=cart_price[i]*cart_qty[i];
			qty+=cart_qty[i]*1;
		}
	}
	
	cart_content="<button type=\"button\" class=\"btn dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"icon-basket-1\"></i><span class=\"cart-count default-bg\">"+qty+"</span></button>";
	
	if(total!=0)
	{
		cart_content+="<ul class=\"dropdown-menu dropdown-menu-right dropdown-animation cart\"><li>";
	}
	
	cart_text="";
	
	if(total!=0)
	{
		cart_text="<table class=\"table table-hover\"><thead><tr><th class=\"product\" colspan='2'>"+cart_word_item+"</th><th class=\"amount\">"+cart_word_subtotal+"</th><th class=\"quantity\">"+cart_word_qty+"</th><th class=\"quantity\">"+cart_word_delete+"</th></tr></thead><tbody>";
	}

	for(i=0;i<cart_mass.length;i++)
	{
		if(cart_remove[i]==0)
		{
			cart_text+="<tr id='cart_tr_top_"+i+"'><td class='cart_preview'><a href=\""+cart_url[i]+"\"><img src=\""+cart_photo[i]+"\" alt=\""+cart_title[i]+"\"></a></td><td class=\"product\"><a href=\""+cart_url[i]+"\"> "+cart_title[i]+" </a><span class=\"small\"> "+cart_description[i]+" </span></td><td class=\"amount\" nowrap>"+cart_currency1+cart_price[i]+" "+cart_currency2+"</td><td class=\"quantity\" style=\"text-align:center\">x&nbsp;"+cart_qty[i]+"</td><td  style=\"width:10%;text-align:center\" class=\"delete\"><a href='javascript:remove_cart_position("+i+","+cart_content_id[i]+")'><i class='glyphicon glyphicon-remove'></i></a></td></tr>";
		}
	}
	
	if(total!=0)
	{
		cart_text+="<tr><td class=\"total-quantity\" colspan=\"5\">"+cart_word_total+": <b>"+cart_currency1+total+" "+cart_currency2+"</b></td></tr></tbody></table><div class=\"panel-body text-right\"><a href='"+site_root+"members/shopping_cart.php' class=\"btn btn-group btn-default btn-sm\">"+cart_word_view+"</a><a href='"+site_root+"members/checkout.php' class=\"btn btn-group btn-primary btn-sm\">"+cart_word_checkout+"</a></div></li></ul>";
	}
	
	cart_content+=cart_text;
		
	$('#cart_desktop').html(cart_content);	
}
  
  
function remove_cart_position(value,content_id)
{
	var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			cart_remove[value]=1;
			make_cart();
        }
    }
    req.open(null, site_root+'members/shopping_cart_delete.php', true);
    req.send( {id: content_id} );
}
    


function search_go(value)
{
	document.getElementById('search').value=value;
	$('#site_search').submit();
}

function show_search()
{
	var req = new JsHttpRequest();
	
	 req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			search_result=req.responseText
			if(search_result!="")
			{
				$('#instant_search').slideDown("fast");
				document.getElementById('instant_search').innerHTML =search_result;
			}
			else
			{
				document.getElementById('instant_search').style.display='none';
			}
        }
    }
    req.open(null, site_root+'members/search_lite.php', true);
    req.send( { search: document.getElementById('search').value } );
}




function reload_cart()
{
	cart_mass.splice(0,cart_mass.length);
	cart_title.splice(0,cart_title.length);
	cart_price.splice(0,cart_price.length);
	cart_qty.splice(0,cart_qty.length);
	cart_url.splice(0,cart_url.length);
	cart_photo.splice(0,cart_photo.length);
	cart_description.splice(0,cart_description.length);
	cart_remove.splice(0,cart_remove.length);	
	cart_content_id.splice(0,cart_content_id.length);


	if($('#list_cart_mass').val()!="")
	{
		cart_mass = $('#list_cart_mass').val().split('||');
	}
	
	if($('#list_cart_title').val()!="")
	{
		cart_title = $('#list_cart_title').val().split('||');
	}
	
	if($('#list_cart_price').val()!="")
	{
		cart_price = $('#list_cart_price').val().split('||');
	}
	
	if($('#list_cart_qty').val()!="")
	{
		cart_qty = $('#list_cart_qty').val().split('||');
	}
	
	if($('#list_cart_url').val()!="")
	{
		cart_url = $('#list_cart_url').val().split('||');
	}
	
	if($('#list_cart_photo').val()!="")
	{
		cart_photo = $('#list_cart_photo').val().split('||');
	}
	
	if($('#list_cart_description').val()!="")
	{
		cart_description = $('#list_cart_description').val().split('||');
	}
	
	if($('#list_cart_remove').val()!="")
	{
		cart_remove = $('#list_cart_remove').val().split('||');
	}
	
	if($('#list_cart_content_id').val()!="")
	{
		cart_content_id = $('#list_cart_content_id').val().split('||');
	}


	make_cart();
}



function set_styles()
{
	main_color=$(".text-default").css("color");
	main_color2="#dddddd";
	$(".search_title").css("border-top-color", main_color2);
	$("#profile_menu").css("border-top-color", main_color2);
	$(".portfolio_left").css("border-top-color", main_color2);
	$(".checkoutbox2_title").css("border-top-color", main_color2);
	$("#lightbox_header").css("background-color", main_color);
	$(".portfolio_right li.activno a").css("background-color", main_color);
	
	$(".paging").css("border-color", main_color);
	$(".paging").css("color", main_color);
	$(".paging2").css("border-color", main_color);
	$(".paging2").css("background-color", main_color);
	
	$("a.paging").hover(
  	function () {
    	$(this).css("background-color", main_color);
    	$(this).css("color", "#FFFFFF");
  	}, 
 	 function () {
    	$(this).css("background-color", "#FFFFFF");
    	$(this).css("color", main_color);
  	}
	);
	
	$('#icons_downloads a').before('<i class="glyphicon glyphicon-floppy-save"> </i>&nbsp;&nbsp;');
	$('#icons_orders a').before('<i class="glyphicon glyphicon-shopping-cart"> </i>&nbsp;&nbsp;');
	$('#icons_credits a').before('<i class="glyphicon glyphicon-cd"> </i>&nbsp;&nbsp;');
	$('#icons_subscription a').before('<i class="glyphicon glyphicon-time"> </i>&nbsp;&nbsp;');
	$('#icons_coupons a').before('<i class="glyphicon glyphicon-barcode"> </i>&nbsp;&nbsp;');
	$('#icons_publications a').before('<i class="glyphicon glyphicon-picture"> </i>&nbsp;&nbsp;');
	$('#icons_upload a').before('<i class="glyphicon glyphicon-upload"> </i>&nbsp;&nbsp;');
	$('#icons_models a').before('<i class="glyphicon glyphicon-user"> </i>&nbsp;&nbsp;');
	$('#icons_commission a').before('<i class="glyphicon glyphicon-piggy-bank"> </i>&nbsp;&nbsp;');
	$('#icons_partner a').before('<i class="glyphicon glyphicon-thumbs-up"> </i>&nbsp;&nbsp;');
	$('#icons_lightbox a').before('<i class="glyphicon glyphicon-heart"> </i>&nbsp;&nbsp;');
	$('#icons_comments a').before('<i class="glyphicon glyphicon-comment"> </i>&nbsp;&nbsp;');
	$('#icons_friends a').before('<i class="glyphicon glyphicon-user"> </i>&nbsp;&nbsp;');
	$('#icons_messages a').before('<i class="glyphicon glyphicon-envelope"> </i>&nbsp;&nbsp;');
	$('#icons_blog a').before('<i class="glyphicon glyphicon-list"> </i>&nbsp;&nbsp;');
	$('#icons_testimonials a').before('<i class="glyphicon glyphicon-star"> </i>&nbsp;&nbsp;');
	$('#icons_preview a').before('<i class="glyphicon glyphicon-eye-open"> </i>&nbsp;&nbsp;');
	$('.link_total').prepend('<i class="glyphicon glyphicon-cd"> </i>&nbsp;&nbsp;');
	$('.link_subscription').prepend('<i class="glyphicon glyphicon-calendar"> </i>&nbsp;&nbsp;');
	$('.link_date').prepend('<i class="glyphicon glyphicon-calendar"> </i>&nbsp;&nbsp;');
	$('.link_download').prepend('<i class="glyphicon glyphicon-download"> </i>&nbsp;&nbsp;');
	$('.link_lightbox').prepend('<i class="glyphicon glyphicon-heart"> </i>&nbsp;&nbsp;');
	$('.link_files').prepend('<i class="glyphicon glyphicon-folder-open"> </i>&nbsp;&nbsp;');
	$('.link_commission').prepend('<i class="glyphicon glyphicon-briefcase"> </i>&nbsp;&nbsp;');
	$('.link_credits').prepend('<i class="glyphicon glyphicon-piggy-bank"> </i>&nbsp;&nbsp;');
	$('.link_payout').prepend('<i class="glyphicon glyphicon-share-alt"> </i>&nbsp;&nbsp;');
	$('.link_approved').prepend('<i class="glyphicon glyphicon-check"> </i>&nbsp;&nbsp;');
	$('.link_pending').prepend('<i class="glyphicon glyphicon-edit"> </i>&nbsp;&nbsp;');
	$('.link_status').prepend('<i class="glyphicon glyphicon-education"> </i>&nbsp;&nbsp;');
	$('.link_order').prepend('<i class="glyphicon glyphicon-shopping-cart"> </i>&nbsp;&nbsp;');
	$('.link_payment').prepend('<i class="glyphicon glyphicon-credit-card"> </i>&nbsp;&nbsp;');
	$('.link_message').prepend('<i class="glyphicon glyphicon-envelope"> </i>&nbsp;&nbsp;');
	$('.link_edit').prepend('<i class="glyphicon glyphicon-edit"> </i>&nbsp;&nbsp;');
	$('.link_delete').prepend('<i class="glyphicon glyphicon-trash"> </i>&nbsp;&nbsp;');
	$('.link_comments').prepend('<i class="glyphicon glyphicon-comment"> </i>&nbsp;&nbsp;');
	$('.link_coupons').prepend('<i class="glyphicon glyphicon-barcode"> </i>&nbsp;&nbsp;');
	$('.link_subscription').prepend('<i class="glyphicon glyphicon-time"> </i>&nbsp;&nbsp;');

	
	$(".add_to_cart").addClass("btn").addClass("btn-primary").addClass("btn-lg");
	$("input.isubmit").addClass("btn").addClass("btn-primary");
	$("input.isubmit_orange").addClass("btn").addClass("btn-danger");
	$("input.profile_button").addClass("btn").addClass("btn-danger");
	$(".lightbox_button").addClass("btn").addClass("btn-primary");
	$(".lightbox_button2").addClass("btn").addClass("btn-danger");
	$(".ibox").addClass("form-control");
	
	$(".bar").addClass("progress-bar").addClass("progress-bar-success").addClass("progress-bar-striped");
	
		


}


function definesize(param) 
{
	if(param==1)
	{
		return $(window).width();
	}
	else
	{
		return $(window).height();
	}
}



//Move a hover
function lightboxmove(width,height,event)
{
	dd=document.getElementById("lightbox")

	x_coord=event.clientX;
	y_coord=event.clientY;

	scroll_top=$(document).scrollTop();

	if(definesize(1)-x_coord-10-width>0)
	{
		param_left=x_coord+10;
	}
	else
	{
		param_left=x_coord-10-width;
	}

	if(definesize(2)-y_coord-10-height>0)
	{
		param_top=y_coord+scroll_top+10;
	}
	else
	{
		param_top=y_coord+scroll_top-10-height;
		if(param_top-scroll_top<0)
		{
			param_top=scroll_top;
		}
	}

	p_top=param_top.toString()+"px";
	p_left=param_left.toString()+"px";

	dd.style.top=p_top
	dd.style.left=p_left
	dd.style.zIndex=10000000000000000000
}


function lightboxoff()
{
	dd=document.getElementById("lightbox")
	dd.innerHTML="";
	dd.style.display="none";
}


//Make a hover visible and insert an appropriate content
function preview_moving(rcontent,width,height,event)
{
	dd=document.getElementById("lightbox");
	dd.style.width=width+2;
	dd.style.width=height+2;
	dd.innerHTML=rcontent;
	$('#lightbox').fadeIn(500);

	lightboxmove(width,height,event);
}


//Photo preview
function lightboxon(fl,width,height,event,rt,title,author)
{
	
	rcontent="<div style=\"position:relative;width:"+width+"px;height:"+height+"px;background: url('"+fl+"');background-size:cover;background-position:center center;border: 1px #1f1f1f solid;\"><div class='hover_string' style='position:absolute;left:0;bottom:0;right:0'><p>"+title+"</p><span>"+author+"</span></div></div>";

	preview_moving(rcontent,width,height,event)
}







//Video wmv preview
function lightboxon2(fl,width,height,event,rt)
{
	rcontent="<OBJECT ID='MediaPlayer' WIDTH='"+width+"' HEIGHT='"+height+"' CLASSID='CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95' STANDBY='Loading Windows Media Player components...' TYPE='application/x-oleobject'><PARAM NAME='FileName' VALUE='"+fl+"'><PARAM name='ShowControls' VALUE='false'><param name='ShowStatusBar' value='false'><PARAM name='ShowDisplay' VALUE='false'><PARAM name='autostart' VALUE='true'><EMBED TYPE='application/x-mplayer2' SRC='"+fl+"' NAME='MediaPlayer' WIDTH='"+width+"' HEIGHT='"+height+"' ShowControls='0' ShowStatusBar='0' ShowDisplay='0' autostart='1'></EMBED></OBJECT>";

	preview_moving(rcontent,width,height,event);
}




//Video flv preview
function lightboxon3(fl,width,height,event,rt)
{
	rcontent="<object classid='CLSID:D27CDB6E-AE6D-11cf-96B8-444553540000'  style='width:"+width+"px;height:"+height+"px;' codebase='http://active.macromedia.com/flash2/cabs/swflash.cab#version=8,0,0,0'><param name='movie' value='"+rt+"/images/movie.swf?url="+fl+"&autoplay=true&loop=true&controlbar=false&sound=true&swfborder=true' /><param name='quality' value='high' /><param name='scale' value='exactfit' /><param name='menu' value='true' /><param name='bgcolor' value='#FFFFFF' /><param name='video_url' value=' ' /><embed src='"+rt+"/images/movie.swf?url="+fl+"&autoplay=true&loop=true&controlbar=false&sound=true&swfborder=true' quality='high' scale='exactfit' menu='false' bgcolor='#FFFFFF' style='width:"+width+"px;height:"+height+"px;' swLiveConnect='false' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash'></embed></object>";

	preview_moving(rcontent,width,height,event);
}







//audio preview
function lightboxon4(fl,width,height,event,rt)
{
	var isiPad = navigator.userAgent.match(/iPad/i) != null;

	if(isiPad)
	{
		rcontent="<audio src="+fl+" type='audio/mp3' autoplay controls></audio>";
	}
	else
	{
		rcontent="<object type=\"application/x-shockwave-flash\" data=\""+rt+"/images/player_mp3_mini.swf\" width=\"200\" height=\"20\"><param name=\"movie\" value=\""+rt+"/images/player_mp3_mini.swf\" /><param name=\"bgcolor\" value=\"000000\" /><param name=\"FlashVars\" value=\"mp3="+fl+"&amp;autoplay=1\" /></object>";
	}

	preview_moving(rcontent,width,height,event);
}



//Video mp4/mov preview
function lightboxon5(fl,width,height,event,rt)
{
	var isiPad = navigator.userAgent.match(/iPad/i) != null

	if(isiPad)
	{
		rcontent="<video   width='"+width+"' height='"+height+"' autoplay controls><source src='"+fl+"' type='video/mp4'></video>";
	}
	else
	{
		
		//JW player
		rcontent="<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'  id='mediaplayer1' name='mediaplayer1' width='"+width+"' height='"+height+"'><param name='movie' value='"+rt+"/images/player_new.swf'><param name='bgcolor' value='#000000'><param name='flashvars' value='file="+fl+"&autostart=true&repeat=always&controlbar.position=none'><embed id='mediaplayer1' name='mediaplayer2' src='"+rt+"/images/player_new.swf' width='"+width+"' height='"+height+"' bgcolor='#000000'    flashvars='file="+fl+"&autostart=true&repeat=always&controlbar.position=none'/></object>";
		
		//Video.js player
		//rcontent='<object type="application/x-shockwave-flash" data="'+rt+'/inc/js/videojs/video-js.swf" width="'+width+'" height="'+height+'" id="video_publication_preview_flash_api" name="video_publication_preview_flash_api" class="vjs-tech" style="display: block; "><param name="movie" value="'+rt+'/inc/js/videojs/video-js.swf"><param name="flashvars" value="readyFunction=videojs.Flash.onReady&amp;eventProxyFunction=videojs.Flash.onEvent&amp;errorEventProxyFunction=videojs.Flash.onError&amp;autoplay=true&amp;preload=undefined&amp;loop=undefined&amp;muted=undefined&amp;src='+fl+'&amp;"><param name="allowScriptAccess" value="always"><param name="allowNetworking" value="all"><param name="wmode" value="opaque"><param name="bgcolor" value="#000000"></object>';
	}

	preview_moving(rcontent,width,height,event);
}









function change_color(value)
{

	color_mass=new Array("black","white","red","green","blue","magenta","cian","yellow","orange");
	for(i=0;i<color_mass.length;i++)
	{
		if(color_mass[i]==value)
		{
			document.getElementById("color_"+color_mass[i]).className='box_color2';
		}
		else
		{
			document.getElementById("color_"+color_mass[i]).className='box_color';
		}
	}
	document.getElementById("color").value=value;
}


function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
    }



function my_form_validate()
{
	flag_validate=true;
	flag_scrolling=true;
	
	for(i=0;i<form_fields.length;i++)
	{
		flag_current=true;
		
		if($('#'+form_fields[i]).val()=="")
		{	
			flag_validate=false;
			flag_current=false;			
		}

		if(fields_emails[i]==1)
		{
			if(!isValidEmailAddress($('#'+form_fields[i]).val()))
   			{
				flag_validate=false;
				flag_current=false;
			}
		}
		
		if(!flag_current)
		{			
			$('#'+form_fields[i]).removeClass("ibox_ok");
			$('#'+form_fields[i]).addClass("ibox_error");			
		}
		else
		{
				$('#'+form_fields[i]).removeClass("ibox_error");
				$('#'+form_fields[i]).addClass("ibox_ok");	
		}
	}	
	return flag_validate;
}




function show_lightbox(id,site_root)
{
	var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() 
    {
        if(req.readyState == 4) 
        {
        	if(req.responseJS.authorization==1)
        	{
        		$.colorbox({html:req.responseJS.lightbox_message,width:'600px',scrolling:false});
        		set_styles();
        	}
        	else
        	{
        		document.getElementById('lightbox_menu_error').innerHTML=req.responseJS.lightbox_message;
        		$('#lightbox_menu_error').css({'top':20,'left':'40%','right':'40%'});
        		$('#lightbox_menu_error').fadeIn(3000);
        		setTimeout(function(){
      				$('#lightbox_menu_error').fadeOut(3000);
   				 }, 2000);
        	}
        }
    }
    req.open(null, site_root+'/members/lightbox_show.php', true);
    req.send( {id: id} );
}



function lightbox_add(site_root)
{
	var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() 
    {
        if(req.readyState == 4) 
        {
        	parent.jQuery.colorbox.close();
        	document.getElementById('lightbox_menu_ok').innerHTML=req.responseJS.result_code;
        	$('#lightbox_menu_ok').css({'top':20,'left':'40%','right':'40%'});
        	$('#lightbox_menu_ok').fadeIn(3000);
        	setTimeout(function(){
      			$('#lightbox_menu_ok').fadeOut(3000);
   			}, 2000);
        }
    }
    if($("#new_lightbox").attr("checked") == 'checked' && document.getElementById("new").value=="")
	{
		document.getElementById("new").className='ibox_error';
	}
	else
	{
    	req.open(null, site_root+'/members/lightbox_add.php', true);
    	req.send( {'form': document.getElementById("lightbox_form") } );
    }
}



function shopping_cart_add(site_root,next_action)
{
	var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() 
    {
        if(req.readyState == 4) 
        {
        	parent.jQuery.colorbox.close();

        	if(next_action==1)
        	{
        		location.href=site_root+"/members/checkout.php";
        	}
        }
    }
    req.open(null, site_root+'/members/shopping_cart_add_next.php', true);
    req.send( {'form': document.getElementById("cart_form") } );
}

function check_carts(word_text)
{
	for(i=0;i<cart_mass.length;i++)
	{
		if(document.getElementById("cart"+cart_mass[i]))
		{
			$("#cart"+cart_mass[i]+" a").removeClass("ac");
			$("#cart"+cart_mass[i]+" a").addClass("ac2");
			$("#cart"+cart_mass[i]+" a").html(word_text);
		}
		
		$(".ts_cart_text"+cart_mass[i]).hide();
		$(".ts_cart_text2"+cart_mass[i]).show();
		$('#ts_cart'+cart_mass[i]).removeClass("btn-primary");
		//$('#ts_cart'+cart_mass[i]).addClass("color_black");
	}
	
	$('.btn-free').removeClass("btn-primary").removeClass("btn");
}


function add_cart_flow(x,site_root)
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
	
	if(site_root=="/")
	{
		site_root="";
	}
    
    if(flag_add)
    {
    	cart_mass[cart_mass.length]=x;
    	
    	// Code automatically called on load finishing.
    	req.onreadystatechange = function()
    	{
       	 	if (req.readyState == 4)
       	 	{
				if(req.responseJS.rights_managed==1)
				{
					location.href=req.responseJS.url;
				}
				else
				{
					if(document.getElementById('shopping_cart'))
					{
						document.getElementById('shopping_cart').innerHTML =req.responseJS.box_shopping_cart;
					}
					if(document.getElementById('shopping_cart_lite'))
					{
						document.getElementById('shopping_cart_lite').innerHTML =req.responseJS.box_shopping_cart_lite;
					}
					
					if(typeof reload_cart == 'function') 
					{
   						reload_cart();
   						
						$(".ts_cart_text"+value.toString()).hide();
						$(".ts_cart_text2"+value.toString()).show();
						//$('#ts_cart'+value.toString()).removeClass("color_black");
						//$('#ts_cart'+value.toString()).addClass("btn-danger");
					}
				}
				
				
        	}
   	 	}
    	req.open(null, site_root+'/members/shopping_cart_add_light.php', true);
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
				
				if(typeof reload_cart == 'function') 
				{
   					reload_cart();
   					
					$(".ts_cart_text"+value.toString()).show();
					$(".ts_cart_text2"+value.toString()).hide();
					//$('#ts_cart'+value.toString()).removeClass("btn-danger");
					//$('#ts_cart'+value.toString()).addClass("btn-primary");
				}
        	}
   	 	}
   	 	req.open(null, site_root+'/members/shopping_cart_delete_light.php', true);
    	req.send( {id: value } );
    }
}






function reload_flow(site_root)
{
    var req = new JsHttpRequest();
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
					
			if(typeof reload_cart == 'function') 
			{
   				reload_cart();				
			}
		}
	}
    req.open(null, site_root+'/members/shopping_cart_reload.php', true);
    req.send( {id: 0 } );
}


function search_submit()
{
	//$("#main_search").submit();
	alert(1);
}


$(document).ready(function() {


		make_cart();
		set_styles();

        
        $('#search').keyup(function() 
		{
 		 	show_search();
		});
	
	
		$("#instant_search").hover
		(
			function () 
			{
				
			},
			function () 
			{
				$('#instant_search').slideUp("fast");
				document.getElementById('instant_search').innerHTML ="";
			}
		);
		
		
		$('.home_img').each(function(){
     		$(this).animate({opacity:'1.0'},1);
   			$(this).mouseover(function(){
     		$(this).stop().animate({opacity:'0.6'},600);
    		});
    		$(this).mouseout(function(){
    		$(this).stop().animate({opacity:'1.0'},300);
    		});
		});

});



