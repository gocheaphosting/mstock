<?$site="user_testimonials";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>




<?include("user_top.php");?>







<script type="text/javascript" language="JavaScript">



function testimonials_show(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('testimonialscontent').innerHTML =req.responseText;
        }
    }
    req.open(null, '<?=site_root?>/members/user_testimonials_content.php', true);
    req.send( { login: value} );
}


function testimonials_add(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('testimonialscontent').innerHTML =req.responseText;

        }
    }
    req.open(null, '<?=site_root?>/members/user_testimonials_content.php', true);
    req.send( {'form': document.getElementById(value) } );
}

testimonials_show('<?=$name_user?>');
</script>




















<div id="testimonialscontent" name="testimonialscontent"></div>







<?include("user_bottom.php");?>






















<?include("../inc/footer.php");?>