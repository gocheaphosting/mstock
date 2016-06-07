<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_exam");
?>
<? include("../inc/begin.php");?>


<h1><?=word_lang("seller examination")?></h1>

<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">



function deselect_row(value)
{
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			$("#"+value).removeClass("success");
        }
    }
    req.open(null, '<?=site_root?>/admin/inc/deselect.php', true);
    req.send( {'id': value} );
}
</script>


<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;



$sql="select * from examinations order by data desc,id desc";
$rs->open($sql);
if(!$rs->eof)
{
$tr=1;
?>
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th><b><?=word_lang("examination")?></b></th>
<th class="hidden-phone hidden-tablet"><b><?=word_lang("date")?></b></th>
<th><b><?=word_lang("status")?></b></th>
<th><b><?=word_lang("user")?></b></th>
<th class="hidden-phone hidden-tablet"><b><?=word_lang("delete")?></b></th>
</tr>
<?
$n=0;
while(!$rs->eof)
{
	$cl3="";
	$cl_script="";
	if(isset($_SESSION["user_exams_id"]) and !isset($_SESSION["admin_rows_exams".$rs->row["id"]]) and $rs->row["id"]>$_SESSION["user_exams_id"])
	{
		$cl3="success";	
		$cl_script="onMouseover=\"deselect_row('exams".$rs->row["id"]."')\"";
	}


if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
{

?>
<tr id="exams<?=$rs->row["id"]?>" class='<?if($tr%2==0){echo("snd");}?> <?=$cl3?>' valign="top" <?=$cl_script?>>

<td>

<div class="link_exam"><a href="exam_content.php?id=<?=$rs->row["id"]?>"><b># <?=$rs->row["id"]?></b></a></div>


</td>

<td class="gray hidden-phone hidden-tablet"><?=date(date_format,$rs->row["data"])?></td>
<td><div class="link_status"><?if($rs->row["status"]==0){echo(word_lang("pending"));}?><?if($rs->row["status"]==1){echo(word_lang("approved"));}?><?if($rs->row["status"]==2){echo(word_lang("declined"));}?></div></td>
<td><?
$sql="select id_parent,login from users where id_parent=".$rs->row["user"];
$ds->open($sql);
if(!$ds->eof)
{
?>
<div class="link_user"><a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a></div>
<?
}
?></td>



<td class="hidden-phone hidden-tablet"><div class="link_delete"><a href='exam_delete.php?id=<?=$rs->row["id"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div></td>

</tr>
<?
}
$tr++;
$n++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>
<?
echo(paging($n,$str,$kolvo,$kolvo2,"index.php",""));
}
else
{
?>
<p>Not found.</p>
<?
}
?>





























<? include("../inc/end.php");?>