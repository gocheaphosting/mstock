
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

function collapse_panel(value)
{	
	if($("#marker_"+value).hasClass('icon-minus'))
	{
		$("#"+value).slideUp();
		$("#marker_"+value).removeClass('icon-minus').removeClass('fa-minus').addClass('icon-plus').addClass('fa-plus');
		document.cookie = "p_" + value + "=" + escape (1) + ";path=/";
	}
	else
	{
		$("#"+value).slideDown();
		$("#marker_"+value).addClass('icon-minus').addClass('fa-minus').removeClass('icon-plus').removeClass('fa-plus');
		document.cookie = "p_" + value + "=" + escape (0) + ";path=/";
	}
}

function change_width()
{
	$('#button_bottom').css({'display':'none'});
	$('#button_bottom_layout').css({'display':'none'});
}

function change_layout(value)
{
	if($("#"+value).prop("checked"))
	{
		document.cookie = value + "=" + escape (1) + ";path=/";
	}
	else
	{
		document.cookie = value + "=" + escape (0) + ";path=/";
	}
	$("#"+value).prop('disabled', false);
}

$(document).ready(function(){


    $(window).scroll(function(){
    	if ($(document).height() > $(window).height() && $(document).height() - $(window).scrollTop() > $(window).height()) 
    	{

    		correction=$('.content-wrapper').offset().left
    		
    		button_x= 30+correction;
    		button_y= 65;
    		button_layout_x= correction;
    		button_layout_y= button_y - 15;
    		button_layout_width=205;
			
			if(document.getElementById("actions"))
    		{
    			button_layout_width=380;
    		}
    		
    		if(document.getElementById("java_bulk"))
    		{
    			button_layout_width=235;
    		}
    		
    		$('#button_bottom_layout').css({ 'left' : button_layout_x, 'bottom':button_layout_y ,'width':button_layout_width,'height':60,'display':'block'});
    		$("#button_bottom").css({ 'position' : 'fixed', 'left' : button_x, 'bottom' : button_y ,'z-index':10,'display':'block'});
    		

	
   	 	}
    	else 
    	{
    		$('#button_bottom_layout').css({'left':0,'bottom':0 ,'width':0,'height':0,'display':'none'});
    	};
    });



	fmenu_default();  
	
	$(".table_tr").addClass("box").addClass("box-primary").addClass("table-responsive").addClass("no-padding");
	$(".ibox").addClass("form-control");
	$("input[type=text]").addClass("form-control");
	$("input[type=file]").addClass("form-control");
	$("input[type=password]").addClass("form-control");
	$("textarea").addClass("form-control");
	$("select").addClass("form-control");
	$(".ft").addClass("form-control");
	$("input:file").addClass("form-control");
	$(".content_edit").addClass("box").addClass("box-primary");
	$(".link_edit a").prepend('<i class="fa fa-pencil-square-o"> </i>&nbsp;&nbsp;');
	$(".link_delete a").prepend('<i class="fa fa-remove"> </i>&nbsp;&nbsp;');
	$(".link_order a").prepend('<i class="fa fa-briefcase"> </i>&nbsp;&nbsp;');
	$(".link_user a").prepend('<i class="fa fa-user"> </i>&nbsp;&nbsp;');
	$(".link_payment a").prepend('<i class="fa fa-cc-visa"> </i>&nbsp;&nbsp;');
	$(".link_preview a").prepend('<i class="fa fa-eye"> </i>&nbsp;&nbsp;');
	$(".link_stats a").prepend('<i class="fa fa-bar-chart"> </i>&nbsp;&nbsp;');
	$(".link_email a").prepend('<i class="fa fa-envelope"> </i>&nbsp;&nbsp;');
	$(".hidden-phone").addClass("hidden-xs").addClass("hidden-sm");
	$(".hidden-tablet").addClass("hidden-md");
	$("#catalog_menu").addClass("box").addClass("box-success");
	$(".bar").addClass("progress-bar ").addClass("progress-bar-primary").addClass("progress-bar-striped");

	$('.sidebar-toggle').click(function(){
    	change_width()
	});

    });
    
    
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

	/*
	x_coord=event.clientX;
	y_coord=event.clientY;
	*/
	x_coord=event.clientX-$(".main-sidebar").innerWidth() ;
	y_coord=event.clientY-$(".main-header").innerHeight();
	//alert($("#main-sidebar").innerWidth());

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
