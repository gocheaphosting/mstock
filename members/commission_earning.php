<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>

<h1><?=word_lang("my commission")?> &mdash; <?=word_lang("earning")?></h1>

<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;


$sql="select total,user,orderid,item,publication,types,data from commission where user=".(int)$_SESSION["people_id"]." and total>0 order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<table border=0 cellpadding=0 cellspacing=0 class=profile_table width="100%">
	<tr>
	<th class='hidden-phone hidden-tablet'><b><?=word_lang("preview")?></b></th>
	<th class='hidden-phone hidden-tablet'><b><?=word_lang("item")?></b></th>
	<th><b><?=word_lang("date")?></b></th>
	<th><b><?=word_lang("commission")?></b></th>
	</tr>
	<?
	$n=0;
	$total=0;
	$tr=1;
	while(!$rs->eof)
	{
		$idp=$rs->row["publication"];
		$userid=$rs->row["user"];
		$url="";
		$title="";


		if($rs->row["types"]!="prints_items")
		{
			$sql="select id_parent,title from ".$rs->row["types"]." where id_parent=".$idp;
			$dr->open($sql);
			if(!$dr->eof)
			{
				$url=item_url($dr->row["id_parent"]);
				$title=$dr->row["title"];
			}
		}
		else
		{
			$sql="select id_parent,title,itemid from ".$rs->row["types"]." where id_parent=".$idp;
			$dr->open($sql);
			if(!$dr->eof)
			{
				$url=item_url($dr->row["itemid"]);
				$title=$dr->row["title"];
			}
		}

		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?>>
			<td class='hidden-phone hidden-tablet'>
			<?
							$sql="select name,module_table from structure where id=".$idp;
							$dr->open($sql);
							if(!$dr->eof)
							{
								$img_preview="";
								if($dr->row["module_table"]==30)
								{
									$img_preview=show_preview($idp,"photo",1,1,"","");
								}
								if($dr->row["module_table"]==31)
								{
									$img_preview=show_preview($idp,"video",1,1,"","");
								}
								if($dr->row["module_table"]==52)
								{
									$img_preview=show_preview($idp,"audio",1,1,"","");
								}
								if($dr->row["module_table"]==53)
								{
									$img_preview=show_preview($idp,"vector",1,1,"","");
								}
							?>
								<div class="profile_home_preview" style="background:url('<?=$img_preview?>')" onClick="location.href='<?=$url?>'"></div>
							<?
							}
							?>
			</td>
			<td class='hidden-phone hidden-tablet'><?=$idp?> - <a href="<?=$url?>"><?=$title?></a></td>
			<td><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
			<td><span class="price"><b><?=currency(1,false);?><?=float_opt($rs->row["total"]*$global_settings["payout_price"],2)?> <?=currency(2,false);?></b></span></td>
			</tr>
			<?
		}
		$n++;
		$tr++;
		$total+=$rs->row["total"]*$global_settings["payout_price"];
		$rs->movenext();
	}
	?>
	</table>

	<p><b><?=word_lang("total")?>:</b> <span class="price"><b><?=currency(1,false);?><?=float_opt($total,2)?> <?=currency(2,false);?></b></span></p>

	<?
	echo(paging($n,$str,$kolvo,$kolvo2,"commission.php","&d=2"));
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>