<?$site="reviews";?>
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
    req.open(null, '<?=site_root?>/members/reviews_edit.php', true);
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
    req.open(null, '<?=site_root?>/members/reviews_change.php', true);
    req.send( {'id': value, 'content': document.getElementById("content"+value).value } );
}


</script>


<h1><?=word_lang("reviews")?> - <?=word_lang("mine")?></h1>






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
$sql="select id_parent,fromuser,data,content,itemid from reviews where fromuser='".result($_SESSION["people_login"])."' order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<form method="post" action="reviews_delete.php" id="reviewsform" name="reviewsform">
	<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%">
	<tr>
	<th><input type="checkbox" id="selector" name="selector" onClick="publications_select_all(document.reviewsform);"></th>
	<th colspan="2"><?=word_lang("item")?>:</th>
	<th class='hidden-phone hidden-tablet'><?=word_lang("date")?>:</th>
	<th width="70%"><?=word_lang("content")?>:</th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?>>
			<td><input type="checkbox" id="m<?=$rs->row["id_parent"]?>" name="m<?=$rs->row["id_parent"]?>" value="1"></td>
			<?
			$sql="select module_table from structure where id=".$rs->row["itemid"];
			$dr-> open($sql);
			if(!$dr->eof)
			{
				$item_title="";
				$item_url="";
				$item_img="";
				if($dr->row["module_table"]==30)
				{
					$sql="select title,url,server1 from photos where id_parent=".$rs->row["itemid"];
					$ds->open($sql);
					if(!$ds->eof)
					{
						$item_title=$ds->row["title"];
						$item_url=$ds->row["url"];
						$item_img=show_preview($rs->row["itemid"],"photo",1,1,$ds->row["server1"],$rs->row["itemid"]);
					}
				}
				if($dr->row["module_table"]==31)
				{
					$sql="select title,url,server1 from videos where id_parent=".$rs->row["itemid"];
					$ds->open($sql);
					if(!$ds->eof)
					{
						$item_title=$ds->row["title"];
						$item_url=$ds->row["url"];
						$item_img=show_preview($rs->row["itemid"],"video",1,1,$ds->row["server1"],$rs->row["itemid"]);
					}
				}
				if($dr->row["module_table"]==52)
				{
					$sql="select title,url,server1 from audio where id_parent=".$rs->row["itemid"];
					$ds->open($sql);
					if(!$ds->eof)
					{
						$item_title=$ds->row["title"];
						$item_url=$ds->row["url"];
						$item_img=show_preview($rs->row["itemid"],"audio",1,1,$ds->row["server1"],$rs->row["itemid"]);
					}
				}
				if($dr->row["module_table"]==53)
				{
					$sql="select title,url,server1 from vector where id_parent=".$rs->row["itemid"];
					$ds->open($sql);
					if(!$ds->eof)
					{
						$item_title=$ds->row["title"];
						$item_url=$ds->row["url"];
						$item_img=show_preview($rs->row["itemid"],"vector",1,1,$ds->row["server1"],$rs->row["itemid"]);
					}
				}
				echo("<td><a href='".$item_url."'><img src='".$item_img."' width='70' border='0'></a></td>");
				echo("<td class='hidden-phone hidden-tablet'><a href='".$item_url."'>".$item_title."</a></td>");
			}
			?>
			<td nowrap class='hidden-phone hidden-tablet'><div class="link_date"><?=show_time_ago($rs->row["data"])?></div></td>
			<td nowrap><div id="c<?=$rs->row["id_parent"]?>" name="c<?=$rs->row["id_parent"]?>"><?=str_replace("\n","<br>",$rs->row["content"])?></div></td>
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
		echo(paging($n,$str,$kolvo,$kolvo2,"reviews.php",""));
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>








<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>