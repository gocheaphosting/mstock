<?$site="testimonials";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>



<?
$type="mine";
?>

<?include("profile_top.php");?>


<?


//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];


//Количество страниц на странице
$kolvo2=k_str2;
?>


<script type="text/javascript" language="JavaScript">
function edit(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('c'+value).innerHTML =req.responseText;

        }
    }
    req.open(null, '<?=site_root?>/members/testimonials_edit.php', true);
    req.send( { id: value } );
}



function change(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('c'+value).innerHTML =req.responseText;
        }
    }
    req.open(null, '<?=site_root?>/members/testimonials_change.php', true);
    req.send( {'id': value, 'content': document.getElementById("content"+value).value } );
}


</script>


<h1><?=word_lang("testimonials")?> - <?=word_lang("mine")?></h1>




<script language="javascript">
function publications_select_all(sel_form)
{
    if(sel_form.selector.checked)
   	{
        $("input:checkbox", sel_form).attr("checked",true);
    }
    else
    {
        $("input:checkbox", sel_form).attr("checked",false);
    }
}
</script>






<?
$n=0;
$tr=1;
$sql="select id_parent,touser,fromuser,data,content from testimonials where fromuser='".result($_SESSION["people_login"])."' order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<form method="post" action="testimonials_delete.php" id="testimonialsform" name="testimonialsform">
	<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%">
	<tr>
	<th><input type="checkbox"  id="selector" name="selector" onClick="publications_select_all(document.testimonialsform);"></th>
	<th><?=word_lang("to")?>:</th>
	<th class='hidden-phone hidden-tablet'><?=word_lang("date")?>:</th>
	<th width="60%"><?=word_lang("content")?>:</th>
	<th><?=word_lang("edit")?>:</th>
	</tr>
	<?
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?>>
			<td><input type="checkbox" id="m<?=$rs->row["id_parent"]?>" name="m<?=$rs->row["id_parent"]?>" value="1"></td>
			<td nowrap><?=show_user_avatar($rs->row["touser"],"login")?></td>
			<td nowrap class='hidden-phone hidden-tablet'><div class="link_date"><?=show_time_ago($rs->row["data"])?></div></td>
			<td><div id="c<?=$rs->row["id_parent"]?>" name="c<?=$rs->row["id_parent"]?>"><?=str_replace("\n","<br>",$rs->row["content"])?></div></td>
			<td><div class="link_edit"><a href="javascript:edit(<?=$rs->row["id_parent"]?>);"><?=word_lang("edit")?></a></div></td>
			</tr>
			<?
		}
		$n++;
		$tr++;
		$rs->movenext();
	}
	?>
	</table><input class='isubmit' type="submit" value="<?=word_lang("delete")?>" style="margin-top:4px"></form>
	<?
	echo(paging($n,$str,$kolvo,$kolvo2,"testimonials.php",""));
	}
	else
	{
		echo("<b>".word_lang("not found")."</b>");
	}
?>



<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>