<? include("../function/db.php");?>
<? include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");

?>
<? include("../inc/begin.php");?>






<?
$d=0;
if(isset($_GET["d"])){$d=$_GET["d"];}
if($d==""){$d=0;}
?>


<h1><?=word_lang("payments")?></h1>




<div class="tabbable nav-tabs-custom">

    	<ul class="nav nav-tabs" style="margin:10px 6px 10px 6px">
<li <?if($d==0){echo("class='active'");}?>><a href="payments.php?d=0"><?=word_lang("payments")?></a></li>



<?
$i=1;
foreach ($payments as $key => $value) 
{
	$t="site_".strtolower($key)."_account";
	$tt=$$t;
	if($tt!="" or $d==$i)
	{
	?>
		<li <?if($d==$i){echo("class='active'");}?>>
		<a href="payments.php?d=<?=$i?>"><?=$value?></a>
		</li>
	<?
	}
	$i++;
}
?>
    	</ul>










<?if($d==0){

?>
<br>
</div>
<div>

<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function payment(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('status'+value).innerHTML =req.responseText;

        }
    }
    req.open(null, 'payment_status.php', true);
    req.send( {'id': value } );
}
</script>

<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover">
<?
$i=1;
foreach ($payments as $key => $value) 
{
$t="site_".$key."_account";
$tt=$$t;

if($tt!=""){$cl="green";}
else{$cl="red";}
?>

<tr <?if($i%2==0){echo("class='snd'");}?>>
<td><a href="payments.php?d=<?=$i?>"><b><?=$value?></b></td>
<td><div id="status<?=$i?>" name="status<?=$i?>"><a href="javascript:payment(<?=$i?>);" class="<?=$cl?>"><b><?if($tt!=""){echo(word_lang("enabled"));}else{echo(word_lang("disabled"));}?></b></a></div></td>
</tr>

<?
$i++;
}
?>
</table>
</div></div></div></div></div></div></div></div>











<?
}
else
{
	$i=1;
	foreach ($payments as $key => $value) 
	{
		if($i==$d)
		{		
			echo("<div class='box_padding'>");
			include($key.".php");
			echo("</div>");
		}
		$i++;
	}
}
?>













</div>










<? include("../inc/end.php");?>