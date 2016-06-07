<?
//Check access
admin_panel_access("orders_commission");
?>

<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function status(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('status'+value).innerHTML =req.responseText;

        }
    }
    req.open(null, 'status.php', true);
    req.send( {'id': value} );
}

</script>


<?
if(isset($_GET["t"]))
{
	if($_GET["t"]==1)
	{
		echo("<p><b>The payment has been sent successfully. You should approve the transaction ID=".(int)$_GET["id"]."</b></p>");
	}
	else
	{
		echo("<p><b>Error. The transaction (ID=".(int)$_GET["id"].") has been declined.</b></p>");
	}
}


//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;


$sql="select id,total,user,gateway,description,data,status from commission where total<0 order by data desc";
$rs->open($sql);
if(!$rs->eof)
	{
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b>ID</b></th>
	<th><b><?=word_lang("user")?></b></th>
	<th><b><?=word_lang("payment gateways")?></b></th>
	<th class="hidden-phone hidden-tablet"><b><?=word_lang("description")?></b></th>
	<th class="hidden-phone hidden-tablet"><b><?=word_lang("date")?></b></th>
	<th><b><?=word_lang("refund")?></b></th>
	<th><b><?=word_lang("status")?></b></th>
	<th><b><?=word_lang("delete")?></b></th>
	</tr>
	<?
	$n=0;
	$total=0;
	$tr=1;
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
			<td><?=$rs->row["id"]?></td>
			<td>
			<?
			$sql="select id_parent,login from users where id_parent=".$rs->row["user"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				?>
				<div class="link_user"><a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a></div>
				<?
			}
			?>
			</td>
			<td>
			<?
			if($rs->row["gateway"]=="")
			{
				$rs->row["gateway"]="other";
			}
			echo("<div style='display:inline;' class='text_".$rs->row["gateway"]."'>".ucfirst($rs->row["gateway"])."</div>");
			?>
			</td>
			<td class="hidden-phone hidden-tablet"><?=$rs->row["description"]?></td>
			<td class="gray hidden-phone hidden-tablet"><?=date(date_format,$rs->row["data"])?></td>
			<td><span class="price"><b><?=currency(1,false);?><?=float_opt((-1*$rs->row["total"]),2)?> <?=currency(2,false);?></b></span></td>
			<td>
			<?
				$cl="";
				if($rs->row["status"]!=1)
				{
					$cl="class='red'";
				}
			?>
				<div id="status<?=$rs->row["id"]?>" name="status<?=$rs->row["id"]?>" class="link_status"><a href="javascript:status(<?=$rs->row["id"]?>);" <?=$cl?>><?if($rs->row["status"]==1){echo(word_lang("approved"));}else{echo(word_lang("pending"));}?></a></div>
			</td>
			<td><div class="link_delete"><a href='refund_delete.php?id=<?=$rs->row["id"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
			</td>
			</tr>
			<?
		}
		$tr++;
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
	<p><b><?=word_lang("total")?>:</b> <span class="price"><b><?=currency(1);?><?=float_opt((-1*$total),2)?> <?=currency(2);?></b></span></p>
	<?
	echo(paging($n,$str,$kolvo,$kolvo2,"index.php","&d=2"));
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>