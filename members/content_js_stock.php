<?
if(!defined("site_root")){exit();}	
?>
<script src='<?=site_root?>/admin/plugins/galleria/galleria-1.2.9.js'></script>
<script>
//Add stock print in the cart
function prints_stock(stock_type,stock_id,stock_url,stock_preview,stock_site_url)
{
	var req = new JsHttpRequest();
	
	var IE='\v'=='v';
	
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
	req.open(null, '<?=site_root?>/members/shopping_cart_add_prints_stock.php', true);
	req.send( {stock_type:stock_type,stock_id:stock_id,stock_url:stock_url,stock_preview:stock_preview,stock_site_url:stock_site_url,print_id:document.getElementById('cartprint').value} );
}


function apanel(x)
{
	if(x == 0)
	{
		document.getElementById('prices_files').style.display = 'block';
		document.getElementById('prices_prints').style.display = 'none';
	}
	else
	{
		document.getElementById('prices_files').style.display = 'none';
		document.getElementById('prices_prints').style.display = 'block';
	}
}


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

//Show added prints
function xprint(x)
{

	printitems=new Array();
	<?
	$sql="select id_parent,title,price from prints order by priority";
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
				document.getElementById('cartprint').value =x;
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
	        if (aRadio[i].value == x) 
	        {
	        	aRadio[i].checked = true; 
	        }
	    } 

}


</script>