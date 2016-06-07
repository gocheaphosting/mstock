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
			//$('#'+form_fields[i]).removeClass("ibox");
			//$('#'+form_fields[i]).addClass("ibox_error");
			
			if(flag_scrolling)
			{
				$(window).scrollTo($('#'+form_fields[i]).offset().top-100,1000, {axis:'y'} );
				flag_scrolling=false;
			}
	/*		
				$('#'+form_fields[i]).qtip({
   				content: error_message,
				show: { ready: true },
				hide: {fixed:true},
				position: {    
					corner: {
     					target: 'topRight',
         				tooltip: 'bottomLeft'
   					},
   					adjust: { x: 10, y: 30 } 
   				},
   				style: { 
      				width: 150,
      				padding: 3,
     				background: '#febbc9',
      				color: '#6a1414',
     				 textAlign: 'center',
     				 border: {
         				width: 0,
         				radius: 5,
         				color: '#febbc9'
      				},
     				 tip: 'leftMiddle',
     				 name: 'dark'
  				 }
			});
			*/
		}
		else
		{
			
			if($('#'+form_fields[i]).attr('class')=="ibox_error")
			{
				//$('#'+form_fields[i]).removeClass("ibox_error");
				//$('#'+form_fields[i]).addClass("ibox_ok");
				//$('#'+form_fields[i]).qtip("destroy");
			}
			
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
        		$.colorbox({html:req.responseJS.lightbox_message,width:'400px',scrolling:false});
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
		
		if(document.getElementById("hb_cart"+cart_mass[i]))
		{
			$('#hb_cart'+cart_mass[i]).removeClass("hb_cart");
			$('#hb_cart'+cart_mass[i]).addClass("hb_cart2");
		}
		
		if(document.getElementById("ts_cart"+cart_mass[i]))
		{
			document.getElementById("ts_cart_text"+cart_mass[i]).style.display='none';
			document.getElementById("ts_cart_text2"+cart_mass[i]).style.display='inline';
			$('#ts_cart'+cart_mass[i]).removeClass("btn-primary");
			$('#ts_cart'+cart_mass[i]).addClass("btn-danger");
		}
	}
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
					if(document.getElementById('hb_cart'+value.toString()))
					{
						$('#hb_cart'+value.toString()).removeClass("hb_cart");
						$('#hb_cart'+value.toString()).addClass("hb_cart2");
					}
					
					if(typeof reload_cart == 'function') 
					{
   						reload_cart();
   						
   						if(document.getElementById("ts_cart"+value.toString()))
						{
							document.getElementById("ts_cart_text"+value.toString()).style.display='none';
							document.getElementById("ts_cart_text2"+value.toString()).style.display='inline';
							$('#ts_cart'+value.toString()).removeClass("btn-primary");
							$('#ts_cart'+value.toString()).addClass("btn-danger");
						}
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
				if(document.getElementById('hb_cart'+value.toString()))
				{
					$('#hb_cart'+value.toString()).removeClass("hb_cart2");
					$('#hb_cart'+value.toString()).addClass("hb_cart");
				}
				
				if(typeof reload_cart == 'function') 
				{
   					reload_cart();
   					
   					if(document.getElementById("ts_cart"+value.toString()))
					{
						document.getElementById("ts_cart_text"+value.toString()).style.display='inline';
						document.getElementById("ts_cart_text2"+value.toString()).style.display='none';
						$('#ts_cart'+value.toString()).removeClass("btn-danger");
						$('#ts_cart'+value.toString()).addClass("btn-primary");
					}
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
