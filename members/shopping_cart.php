<?$site="shopping_cart";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<div class="page_internal">
<h1><?=word_lang("shopping cart")?></h1>


<script type="text/javascript" language="JavaScript">

function cart_delete(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			document.getElementById('shopping_cart2').innerHTML =req.responseText;
			
			reload_flow('<?=site_root?>');
			
   			if(typeof set_styles == 'function') 
			{
   				set_styles();
   			}
        }
    }
    req.open(null, '<?=site_root?>/members/shopping_cart_delete.php', true);
    req.send( {id: value} );
}


function cart_change(value,value2) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			document.getElementById('shopping_cart2').innerHTML =req.responseText;
        }
      
      	reload_flow('<?=site_root?>');
      	
   		if(typeof set_styles == 'function') 
		{
   			set_styles();
   		}
    }
    req.open(null, '<?=site_root?>/members/shopping_cart_change.php', true);
    req.send( {id: value,qty: value2} );
}

function cart_add(value,value2) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			document.getElementById('shopping_cart2').innerHTML =req.responseText;
			
			reload_flow('<?=site_root?>');
			
   			if(typeof set_styles == 'function') 
			{
   				set_styles();
   			}
        }
    }
    req.open(null, '<?=site_root?>/members/shopping_cart_change_new.php', true);
    req.send( {id: value,id2: value2} );
}


function cart_clear(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			document.getElementById('shopping_cart2').innerHTML =req.responseText;
			
			reload_flow('<?=site_root?>');
			
   			if(typeof set_styles == 'function') 
			{
   				set_styles();
   			}
        }
    }
    req.open(null, '<?=site_root?>/members/shopping_cart_clear.php', true);
    req.send( {id: value} );
}


function cart_change_option(value2,value3,value4,value5) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			document.getElementById('shopping_cart2').innerHTML =req.responseText;
			
			reload_flow('<?=site_root?>');
			
   			if(typeof set_styles == 'function') 
			{
   				set_styles();
   			}
        }
    }
    req.open(null, '<?=site_root?>/members/shopping_cart_change_option.php', true);
    req.send( {id: value2,i: value3,option_id: value4,option_value: value5} );
}

</script>


<?
if(!isset($_SESSION["people_type"]) or $_SESSION["people_type"]=="buyer" or $_SESSION["people_type"]=="common")
{
?>
	<div id="shopping_cart2" name="shopping_cart2"><?include("shopping_cart_content.php");?></div>
<?
}
else
{
?>
	<p><b><?=word_lang("the seller may not buy items")?></b></p>
<?
}
?>
</div>
<?include("../inc/footer.php");?>