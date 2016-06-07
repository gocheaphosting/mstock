<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("affiliates_payout");
?>
<? include("../inc/begin.php");?>

<h1><?=word_lang("refund")?></h1>


<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function status(value1,value2) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('status'+value1+"_"+value2).innerHTML =req.responseText;

        }
    }
    req.open(null, 'status.php', true);
    req.send( {'data': value1,'user': value2} );
}

</script>


<?

if(isset($_GET["t"]))
{
	if($_GET["t"]==1)
	{
		echo("<p><b>The payment has been sent successfully. You should approve the transaction.</b></p>");
	}
	else
	{
		echo("<p><b>Error. The transaction has been declined.</b></p>");
	}
}


//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;


$sql="select aff_referal,total,data,status,description,gateway from affiliates_signups where total<0 order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("user")?></b></th>
	<th><b><?=word_lang("date")?></b></th>
	<th><b><?=word_lang("refund")?></b></th>
	<th><b><?=word_lang("payment gateways")?></b></th>
	<th><b><?=word_lang("description")?></b></th>
	<th><b><?=word_lang("status")?></b></th>
	<th><b><?=word_lang("delete")?></b></th>
	</tr>
	<?
	$n=0;
	$total=0;
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			?>
			<tr valign="top">
			<td>
			<?
			$sql="select id_parent,login from users where id_parent=".$rs->row["aff_referal"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				?>
				<div class="link_user"><a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a></div>
				<?
			}
			?>
			</td>
			<td class="gray"><?=date(date_format,$rs->row["data"])?></td>
			<td><span class="price"><b><?=currency(1,false);?><?=float_opt((-1*$rs->row["total"]),2)?> <?=currency(2,false);?></b></span></td>
			<td>
			<?
			if($rs->row["gateway"]=="")
			{
				$rs->row["gateway"]="other";
			}
			echo("<div style='display:inline;' class='text_".$rs->row["gateway"]."'>".ucfirst($rs->row["gateway"])."</div>");
			?>
			</td>
			<td><?=$rs->row["description"]?></td>
			<td>
			<?
				$cl="";
				if($rs->row["status"]!=1)
				{
					$cl="class='red'";
				}
			?>
				<div id="status<?=$rs->row["data"]?>_<?=$rs->row["aff_referal"]?>" name="status<?=$rs->row["data"]?>_<?=$rs->row["aff_referal"]?>" class="link_status"><a href="javascript:status(<?=$rs->row["data"]?>,<?=$rs->row["aff_referal"]?>);" <?=$cl?>><?if($rs->row["status"]==1){echo(word_lang("approved"));}else{echo(word_lang("pending"));}?></a></div>
			</td>
			<td>
			<div class="link_delete"><a href='payout_delete.php?data=<?=$rs->row["data"]?>&aff_referal=<?=$rs->row["aff_referal"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
			</td>
			</tr>
			<?
		}
		$n++;
		if($rs->row["status"]==1)
		{
			$total+=$rs->row["total"];
		}
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>

	<p><b><?=word_lang("total")?>:</b> <span class="price"><b><?=currency(1,false);?><?=float_opt((-1*$total),2)?> <?=currency(2,false);?></b></span></p>
	<?
	echo("<p>".paging($n,$str,$kolvo,$kolvo2,"payout.php","")."</p>");
}
else
{
	echo("<p><b>".word_lang("not found")."</b></p>");
}
?>

<? include("../inc/end.php");?>