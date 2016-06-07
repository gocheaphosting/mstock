<?$site="reviews";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>





<?
$type="for me";
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




<h1><?=word_lang("reviews")?> - <?=word_lang("for me")?></h1>









<?
$n=0;
$tr=1;

$sql="(select photos.id_parent,photos.title,photos.author,photos.url,photos.server1,reviews.id_parent as bid,reviews.fromuser,reviews.data as rdata,reviews.content,reviews.itemid from photos,reviews where photos.author='".result($_SESSION["people_login"])."' and photos.id_parent=reviews.itemid) 

union 

(select videos.id_parent,videos.title,videos.author,videos.url,videos.server1,reviews.id_parent as bid,reviews.fromuser,reviews.data as rdata,reviews.content,reviews.itemid from videos,reviews where videos.author='".result($_SESSION["people_login"])."' and videos.id_parent=reviews.itemid) 

union

(select audio.id_parent,audio.title,audio.author,audio.url,audio.server1,reviews.id_parent as bid,reviews.fromuser,reviews.data as rdata,reviews.content,reviews.itemid from audio,reviews where audio.author='".result($_SESSION["people_login"])."' and audio.id_parent=reviews.itemid) 

union

(select vector.id_parent,vector.title,vector.author,vector.url,vector.server1,reviews.id_parent as bid,reviews.fromuser,reviews.data as rdata,reviews.content,reviews.itemid from vector,reviews where vector.author='".result($_SESSION["people_login"])."' and vector.id_parent=reviews.itemid) 

 order by rdata desc


";



$rs->open($sql);
if(!$rs->eof)
{
	?>
	<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%">
	<tr>
	<th colspan="2"><?=word_lang("item")?>:</th>
	
	<th class='hidden-phone hidden-tablet'><?=word_lang("user")?>:</th>
	<th width="70%"><?=word_lang("content")?>:</th>
	<th class='hidden-phone hidden-tablet'><?=word_lang("date")?></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?>>
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
						$item_img=show_preview($rs->row["itemid"],"photo",1,1,$rs->row["server1"],$rs->row["itemid"]);
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
						$item_img=show_preview($rs->row["itemid"],"video",1,1,$rs->row["server1"],$rs->row["itemid"]);
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
						$item_img=show_preview($rs->row["itemid"],"audio",1,1,$rs->row["server1"],$rs->row["itemid"]);
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
						$item_img=show_preview($rs->row["itemid"],"vector",1,1,$rs->row["server1"],$rs->row["itemid"]);
					}
				}
				echo("<td><a href='".$item_url."'><img src='".$item_img."' width='70' border='0'></a></td>");
				echo("<td class='hidden-phone hidden-tablet'><a href='".$item_url."'>".$item_title."</a></td>");
			}
			?>
			

			<td nowrap class='hidden-phone hidden-tablet'><?=show_user_avatar($rs->row["fromuser"],"login")?>
			</td>
			<td nowrap><div id="c<?=$rs->row["bid"]?>" name="c<?=$rs->row["bid"]?>"><?=str_replace("\n","<br>",$rs->row["content"])?></div></td>
			<td nowrap class='hidden-phone hidden-tablet'><div class="link_date"><?=show_time_ago($rs->row["rdata"])?></div></td>
			</tr>
			<?
		}
		$n++;
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	<?
	echo(paging($n,$str,$kolvo,$kolvo2,"reviews_for_me.php",""));
	}
	else
	{
		echo("<b>".word_lang("not found")."</b>");
	}
	?>


<?include("profile_bottom.php");?>























<?include("../inc/footer.php");?>