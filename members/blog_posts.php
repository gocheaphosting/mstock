<?$site="blog";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>



<?
$type="posts";
?>

<?include("profile_top.php");?>


<h1><?=word_lang("blog")?> - <?=word_lang("posts")?></h1>

<?

//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];


//Количество страниц на странице
$kolvo2=k_str2;
?>




<?
$n=0;
$idmass="";
$sql="select id_parent,title,content,data,user,published,photo,categories,comments from blog where user='".result($_SESSION["people_login"])."' order by data desc";
$rs->open($sql);
while(!$rs->eof)
{
	if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
	{
		if($idmass!=""){$idmass.=",";}
		$idmass.="'m".$rs->row["id_parent"]."'";
	}
	$n++;
	$rs->movenext();
}
?>
<script language="javascript">
function checkedbox()
{
	ids=new Array(<?=$idmass?>);
	for(i=0;i<ids.length;i++)
	{
		if(document.getElementById('allboxes').checked)
		{
			document.getElementById(ids[i]).checked=true
		}
		else
		{
			document.getElementById(ids[i]).checked=false
		}
	}
}
</script>






<?
$n=0;
$tr=1;
$sql="select id_parent,title,content,data,user,published,photo,categories,comments from blog where user='".result($_SESSION["people_login"])."' order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="blog_posts_delete.php">
<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%">
<tr>
<th><input type="checkbox" id="allboxes" name="allboxes" onClick="checkedbox();"></th>
<th><?=word_lang("title")?>:</th>
<th><?=word_lang("date")?>:</th>
<th class='hidden-phone hidden-tablet'><?=word_lang("status")?>:</th>
</tr>
<?
while(!$rs->eof)
{
if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="checkbox" id="m<?=$rs->row["id_parent"]?>" name="m<?=$rs->row["id_parent"]?>" value="1"></td>
<td><div class="link_message"><a href="blog.php?post=<?=$rs->row["id_parent"]?>"><?=$rs->row["title"]?></a></div></td>
<td><div class="link_date"><?=show_time_ago($rs->row["data"])?></div></td>
<td class='hidden-phone hidden-tablet'><?if($rs->row["published"]==1){echo("<div class='link_approved'>".word_lang("published")."</div>");}else{echo("<div class='link_pending'>".word_lang("pending")."</div>");}?></td>
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
echo(paging($n,$str,$kolvo,$kolvo2,"blog_posts.php",""));
}
else
{
echo("<b>".word_lang("not found")."</b>");
}
?>

<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>