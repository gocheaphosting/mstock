<?if(!defined("site_root")){exit();}?>

<form method="post" action="<?=site_root?>/blog/<?=$nameuser?>.html">
<input type="text" name="blogsearch" style="width:100px;float:left;margin-bottom:20px" value="<?=word_lang("search")?>..." onClick="this.value=''">
</form>



<div style="margin-top:20px;clear:both" class="blogmenu"><?=word_lang("archives")?></div>
<?
for($i=1;$i<13;$i++)
{
	$sql="select user from blog where data>".(mktime(0,0,0,$i,1,date("Y"))-1)." and data<".(mktime(0,0,0,$i+1,1,date("Y"))+1);
	$rs->open($sql);
	if($rs->rc>0)
	{
		?>
		<div style="margin-top:3px">&nbsp;&#187;&nbsp;<a href="<?=site_root?>/blog/<?=date("Y")?>/<?=$i?>/<?=$nameuser?>.html"><?=word_lang($m_month[$i-1])?> <?=date("Y")?></a></div>
		<?
	}
}
?>

<div style="margin-top:20px" class="blogmenu"><?=word_lang("categories")?></div>
<?
$sql="select title,user,id_parent from blog_categories where user='".result3(user_url_back($nameuser))."'";
//echo($sql);
$rs->open($sql);
while(!$rs->eof)
{
	$pcount=0;
	$sql="select count(id_parent) as pcount from blog where  user='".result3(user_url_back($nameuser))."' and categories like '%".$rs->row["title"]."%' group by id_parent";
	$dr->open($sql);
	if(!$dr->eof){$pcount=$dr->row["pcount"];}
	?>
		<div style="margin-top:3px">&nbsp;&#187;&nbsp;<a href="<?=site_root?>/blog/<?=$rs->row["id_parent"]?>/<?=$nameuser?>.html"><?=$rs->row["title"]?></a> (<?=$pcount?>)</div>
	<?
	$rs->movenext();
}
$pcount=0;
$sql="select count(id_parent) as pcount from blog where  user='".result3(user_url_back($nameuser))."' and categories='' group by id_parent";
$dr->open($sql);
if(!$dr->eof){$pcount=$dr->row["pcount"];}
?>
<div style="margin-top:3px">&nbsp;&#187;&nbsp;<a href="<?=site_root?>/blog/0/<?=$nameuser?>.html"><?=word_lang("uncategorized")?></a> (<?=$pcount?>)</div>

<div style="margin-top:20px"><a href="<?=site_root?>/members/rss_blog.php?user=<?=$nameuser?>"><img src="<?=site_root?>/images/rss.gif" border="0" width="41" height="15"></a></div>
