<?
if(!defined("site_root")){exit();}	

if($global_settings["google_coordinates"])
{
?>
	<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
	<script type="text/javascript" src="<?=site_root?>/inc/jquery.ui.map.js"></script>
	<script type="text/javascript" language="JavaScript">
	function map_show(x,y)
	{
		document.getElementById('reviewscontent').style.display='none';
			document.getElementById('reviewscontent').innerHTML ="<div id='map'></div>";
			$("#reviewscontent").slideDown("slow");
			 pos=x+","+y;
			 $('#map').gmap({'zoom':11, 'center': pos}).bind('init', function(ev, map) {
				$('#map').gmap('addMarker', { 'position': map.getCenter(), 'bounds': false})
			});


	}
	</script>
<?}?>
<script type="text/javascript" src="<?=site_root?>/inc/js/raty/jquery.raty.min.js"></script>

<script type="text/javascript">
    $(function() {
      $.fn.raty.defaults.path = '<?=site_root?>/inc/js/raty/img';

      $('.star').raty({ score: 5 });
      
    });
    
    function vote_rating(id,score)
    {
    	<?
    	if(!isset($_SESSION["people_id"]) and $global_settings["auth_rating"])
		{
		?>	
			location.href='<?=site_root?>/members/login.php';
		<?
		}
		else
		{
    	?>
    		var req = new JsHttpRequest();
        
    		// Code automatically called on load finishing.
   	 		req.onreadystatechange = function()
    		{
        		if (req.readyState == 4)
        		{
				
        		}
    		}
    		req.open(null, '<?=site_root?>/members/vote_add.php', true);
    		req.send( {id: id,vote:score } );
    	<?
    	}
    	?>
   	}
</script>


<script>
	 
	function like_dislike(value)
    {
    	<?
    	if(!isset($_SESSION["people_id"]) and $global_settings["auth_rating"])
		{
		?>	
			location.href='<?=site_root?>/members/login.php';
		<?
		}
		else
		{
    	?>
    		var req = new JsHttpRequest();
        
    		// Code automatically called on load finishing.
   	 		req.onreadystatechange = function()
    		{
        		if (req.readyState == 4)
        		{
					if(req.responseText!="")
					{
						if(value>0)
						{
							document.getElementById('vote_like').innerHTML =req.responseText
						}
						else
						{
							document.getElementById('vote_dislike').innerHTML =req.responseText
						}
					}
        		}
    		}
    		req.open(null, '<?=site_root?>/members/like.php', true);
    		req.send( {id: <?=$id_parent?>,vote:value} );
    	<?
    	}
    	?>
   	}



    $(function(){ 
        $('.like-btn').click(function(){
            $('.dislike-btn').removeClass('dislike-h');    
            $(this).addClass('like-h');
			like_dislike(1);
        });
        $('.dislike-btn').click(function(){
            $('.like-btn').removeClass('like-h');
            $(this).addClass('dislike-h');
			like_dislike(-1)
        });
    });
</script>
	

<script src='<?=site_root?>/admin/plugins/galleria/galleria-1.2.9.js'></script>
<script type="text/javascript" language="JavaScript">



//Rights-managed photos
function rights_managed(id)
{
    var req = new JsHttpRequest();
    
    
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			$.colorbox({html:req.responseJS.cart_content,width:'600px',scrolling:false});
				
			if(typeof set_styles == 'function') 
			{
   				set_styles();
   			}
        }
    }
    req.open(null, '<?=site_root?>/members/rights_managed.php', true);
    req.send( {id: id } );
}

//Rights-managed photos
function change_rights_managed(publication_id,price_id,option_id,option_value)
{
    var req = new JsHttpRequest();
    
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
				next_options=req.responseJS.next_options;
				list_options=next_options.split("-");
				$(".group_box").css("display","none");
				
				flag_finish=true;
				
				for(i=0;i<list_options.length;i++)
				{
					if(document.getElementById("group_box"+list_options[i]))
					{
						document.getElementById("group_box"+list_options[i]).style.display='block';
						if(document.getElementById("group"+list_options[i]).value==0)
						{
						 	flag_finish=false;
						}
					}
				}				
				
				document.getElementById('rights_managed_price').innerHTML =req.responseJS.price;
				
				if(flag_finish)
				{
					document.getElementById('lightbox_footer').style.display='block';
					document.getElementById('price_box').style.display='block';
				}
				else
				{
					document.getElementById('lightbox_footer').style.display='none';
					document.getElementById('price_box').style.display='none';
				}
				
				$.fn.colorbox.resize({});
        }
    }
    req.open(null, '<?=site_root?>/members/rights_managed_change.php', true);
    req.send( {publication_id: publication_id, price_id:price_id,option_id:option_id,option_value:option_value} );
}

	cartitems=new Array();
	cartprices=new Array();
	<?
	$sql="select id,price from items where id_parent=".$id_parent." order by priority";
	$rs->open($sql);
	$nn=0;
	while(!$rs->eof)
	{
		?>
		cartitems[<?=$nn?>]=<?=$rs->row["id"]?>;
		cartprices[<?=$rs->row["id"]?>]=<?=$rs->row["price"]?>;
		<?
	$nn++;
	$rs->movenext();
	}
	?>

//The function adds an item into the shopping cart
function add_cart(x)
{
	if(x==0)
	{
		value=document.getElementById("cart").value;
	}
	if(x==1)
	{
		value=document.getElementById("cartprint").value;
	}
    var req = new JsHttpRequest();
    
    var IE='\v'=='v';
    
    
    // Code automatically called on load finishing.
    if(cartprices[value]==0 && x==0)
    {
    	location.href="<?=site_root?>/members/count.php?type=<?=@$atype?>&id="+document.getElementById("cart").value+"&id_parent=<?=@$id_parent?>";
    }
    else
    {
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
				
				if(!IE) 
				{
					$.colorbox({html:req.responseJS.cart_content,width:'600px',scrolling:false});
				}
				
				if(typeof set_styles == 'function') 
				{
   					set_styles();
   				}
   				
   				if(typeof reload_cart == 'function') 
				{
   					reload_cart();
   				}
        	}
    	}
   	 	req.open(null, '<?=site_root?>/members/shopping_cart_add.php', true);
    	req.send( {id: value } );
    }
}



//The function shows prints previews

function show_prints_preview(id)
{
    var req = new JsHttpRequest();
        
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
        	$.colorbox({html:req.responseJS.prints_content,width:'600px',scrolling:false});
        	
        	if(typeof set_styles == 'function') 
			{
   				set_styles();
   			}
        }
    }
    req.open(null, '<?=site_root?>/members/prints_preview.php', true);
    req.send( {id: id } );
}




//The function shows a download link
function add_download(a_type,a_parent,a_server)
{
	if(document.getElementById("cart"))
	{
		location.href="<?=site_root?>/members/count.php?type="+a_type+"&id="+document.getElementById("cart").value+"&id_parent="+a_parent+"&server="+a_server;
	}
}





//Voting function
function doVote(value)
{
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			document.getElementById('votebox').innerHTML =req.responseText;
        }
    }
    req.open(null, '<?=site_root?>/members/vote_add.php', true);
    req.send( { id:<?=$id_parent?>,vote: value } );
}


//Show reviews
function reviews_show(value)
{
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			if(document.getElementById('reviewscontent'))
			{
				document.getElementById('reviewscontent').innerHTML =req.responseText;
				document.getElementById('reviewscontent').style.display='none';
				$("#reviewscontent").slideDown("slow");
			}
			if(document.getElementById('comments_content'))
			{
				document.getElementById('comments_content').innerHTML =req.responseText;
			}
        }
    }
    req.open(null, '<?=site_root?>/members/reviews_content.php', true);
    req.send( { id: value } );
}


//Show EXIF
function exif_show(value)
{
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			if(document.getElementById('reviewscontent'))
			{
				document.getElementById('reviewscontent').innerHTML =req.responseText;
				document.getElementById('reviewscontent').style.display='none';
				$("#reviewscontent").slideDown("slow");
			}
			if(document.getElementById('exif_content'))
			{
				document.getElementById('exif_content').innerHTML =req.responseText;
			}
        }
    }
    req.open(null, '<?=site_root?>/members/exif.php', true);
    req.send( { id: value } );
}

//Add a new review
function reviews_add(value)
{
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			if(document.getElementById('reviewscontent'))
			{
				document.getElementById('reviewscontent').innerHTML =req.responseText;
				document.getElementById('reviewscontent').style.display='none';
				$("#reviewscontent").slideDown("slow");
			}
			if(document.getElementById('comments_content'))
			{
				document.getElementById('comments_content').innerHTML =req.responseText;
			}
        }
    }
    req.open(null, '<?=site_root?>/members/reviews_content.php', true);
    req.send( {'form': document.getElementById(value) } );
}

//Hide reviews
function reviews_hide()
{
	document.getElementById('reviewscontent').innerHTML ="";
	$("#reviewscontent").slideUp("slow");
}


//Show share tools
share_flag=true;
function share_show(value) 
{
	if(share_flag)
	{
    	var req = new JsHttpRequest();
   		 // Code automatically called on load finishing.
   		 req.onreadystatechange = function()
    	{
        	if (req.readyState == 4)
        	{
				document.getElementById('share').innerHTML =req.responseText;
				document.getElementById('share').style.display='none';
				$("#share").slideDown("slow");
        	}
		}
    	req.open(null, '<?=site_root?>/members/share.php', true);
    	req.send( { id: value } ); 
    	share_flag=false; 
	}
	else
	{
		$("#share").slideUp("slow");
		share_flag=true;
	}
}



//Show pixels/inches
function show_size(value)
{
	if($('#link_size1_'+value).hasClass('link_pixels'))
	{
		$('#p'+value+' div.item_pixels').css({'display':'none'});
		$('#p'+value+' div.item_inches').css({'display':'block'});
		$('#link_size1_'+value).removeClass("link_pixels");
		$('#link_size1_'+value).addClass("link_inches");
		$('#link_size2_'+value).removeClass("link_inches");
		$('#link_size2_'+value).addClass("link_pixels");
	}
	else
	{
		$('#p'+value+' div.item_pixels').css({'display':'block'});
		$('#p'+value+' div.item_inches').css({'display':'none'});
		$('#link_size1_'+value).removeClass("link_inches");
		$('#link_size1_'+value).addClass("link_pixels");
		$('#link_size2_'+value).removeClass("link_pixels");
		$('#link_size2_'+value).addClass("link_inches");
	}
}





//Show tell a friend
function tell_show(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			if(document.getElementById('reviewscontent'))
			{
				document.getElementById('reviewscontent').innerHTML =req.responseText;
				document.getElementById('reviewscontent').style.display='none';
				$("#reviewscontent").slideDown("slow");
			}
			if(document.getElementById('tell_content'))
			{
				document.getElementById('tell_content').innerHTML =req.responseText;
			}
        }
    }
    req.open(null, '<?=site_root?>/members/tell_a_friend.php', true);
    req.send( { id: value } );
}


//Show tell a friend form
function tell_add(value)
{
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			if(document.getElementById('reviewscontent'))
			{
				document.getElementById('reviewscontent').innerHTML =req.responseText;
			}
			if(document.getElementById('tell_content'))
			{
				document.getElementById('tell_content').innerHTML =req.responseText;
			}
        }
    }
    req.open(null, '<?=site_root?>/members/tell_a_friend.php', true);
    req.send( {'form': document.getElementById(value) } );
}










//Related items scrolling
$(function()
{
  //Get our elements for faster access and set overlay width
  var div = $('div.sc_menu'),
  ul = $('ul.sc_menu'),
  // unordered list's left margin
  ulPadding = 15;

  //Get menu width
  var divWidth = div.width();

  //Remove scrollbars
  div.css({overflow: 'hidden'});

  //Find last image container
  var lastLi = ul.find('li:last-child');

  //When user move mouse over menu
  div.mousemove(function(e){

    //As images are loaded ul width increases,
    //so we recalculate it each time
    var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;

    var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
    div.scrollLeft(left);
  });
});



rimg=new Image()
rimg.src="<?=site_root."/".$site_template_url?>images/rating1.gif"

rimg2=new Image()
rimg2.src="<?=site_root."/".$site_template_url?>images/rating2.gif"

//Show rating
function mrating(j)
{
	for(i=1;i<6;i++)
	{
		if(i<=j)
		{
			document.getElementById("rating"+i.toString()).src =rimg.src
		}
	}
}

//Show rating2
function mrating2(item_rating)
{
	for(i=5;i>0;i--)
	{
		if(i>item_rating)
		{
			document.getElementById("rating"+i.toString()).src =rimg2.src
		}
	}
}


//Show prices by license
function apanel(x)
{

	sizeboxes=new Array();
	<?
	$sql="select id_parent from licenses order by priority";
	$rs->open($sql);
	$nn=0;
	while(!$rs->eof)
	{
		?>
		sizeboxes[<?=$nn?>]=<?=$rs->row["id_parent"]?>;
		<?
	$nn++;
	$rs->movenext();
	}
	
	if(($module_table==30 or  $module_table==53) and $global_settings["prints"])
	{
	?>
		//Prints
		sizeboxes[<?=$nn?>]=0;
	<?
	}
	?>
	
	//Rights managed and Contact Us
	if(document.getElementById("license1"))
	{
		sizeboxes[sizeboxes.length]=1;
	}
	
	//Hide item cart button
	if(document.getElementById("item_button_cart"))
	{
		if(x==0)
		{
			document.getElementById("item_button_cart").style.display='none';
		}
		else
		{
			document.getElementById("item_button_cart").style.display='block';
		}
	}


	for(i=0;i<sizeboxes.length;i++)
	{
		if(document.getElementById('p'+sizeboxes[i].toString()))
		{
			if(sizeboxes[i]==x)
			{
				document.getElementById('p'+sizeboxes[i].toString()).style.display ='inline';
			}
			else
			{
				document.getElementById('p'+sizeboxes[i].toString()).style.display ='none';
			}
		}
	}
}



//Show added items 
function xcart(x)
{




	for(i=0;i<cartitems.length;i++)
	{
		if(document.getElementById('tr_cart'+cartitems[i].toString()))
		{
			if(cartitems[i]==x)
			{
				document.getElementById('tr_cart'+cartitems[i].toString()).className ='tr_cart_active';
				document.getElementById('cart').value =x;
			}
			else
			{
				document.getElementById('tr_cart'+cartitems[i].toString()).className ='tr_cart';
			}
		}
	}


	    var aRadio = document.getElementsByTagName('input'); 
	    for (var i=0; i < aRadio.length; i++)
	    { 
	        if (aRadio[i].type != 'radio') continue; 
	        if (aRadio[i].value == x) aRadio[i].checked = true; 
	    } 

}




//Show added prints
function xprint(x)
{

	printitems=new Array();
	<?
	$sql="select id_parent,title,price from prints_items where itemid=".(int)$id_parent." order by priority";
	$rs->open($sql);
	$nn=0;
	while(!$rs->eof)
	{
		?>
		printitems[<?=$nn?>]=<?=$rs->row["id_parent"]?>;
		<?
	$nn++;
	$rs->movenext();
	}
	?>


	for(i=0;i<printitems.length;i++)
	{
		if(document.getElementById('tr_cart'+printitems[i].toString()))
		{
			if(printitems[i]==x)
			{
				document.getElementById('tr_cart'+printitems[i].toString()).className ='tr_cart_active';
				document.getElementById('cartprint').value =-1*x;
			}
			else
			{
				document.getElementById('tr_cart'+printitems[i].toString()).className ='tr_cart';
			}
		}
	}


	    var aRadio = document.getElementsByTagName('input'); 
	    for (var i=0; i < aRadio.length; i++)
	    { 
	        if (aRadio[i].type != 'radio') continue; 
	        if (aRadio[i].value == -1*x) 
	        {
	        	aRadio[i].checked = true; 
	        }
	    } 

}

		
//Video mp4/mov preview
function lightboxon_istock(fl,width,height,event,rt)
{
	rcontent="<video   width='"+width+"' height='"+height+"' autoplay controls><source src='"+fl+"' type='video/mp4'></video>";

	preview_moving(rcontent,width,height,event);
}

</script>