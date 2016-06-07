<?$site="blog";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>



<?
$type="new post";
?>





<?include("profile_top.php");?>


<h1><?=word_lang("blog")?> - <?if(!isset($_GET["post"])){echo(word_lang("new post"));}else{echo("#".$_GET["post"]);}?></h1>






<?
$title="";
$content="";
$published=1;
$comments=1;
$categories="";
if(isset($_GET["post"]))
{
	$sql="select id_parent,title,content,data,user,published,photo,categories,comments from blog where user='".result($_SESSION["people_login"])."' and id_parent=".(int)$_GET["post"];
	$dr->open($sql);
	if(!$dr->eof)
	{
		$title=$dr->row["title"];
		$content=result_html_back($dr->row["content"]);
		$published=$dr->row["published"];
		$comments=$dr->row["comments"];
		$categories=$dr->row["categories"];
	}
}
?>





<form method="post" action="blog_<?if(!isset($_GET["post"])){?>add<?}else{?>change<?}?>.php"  Enctype="multipart/form-data" name="blogform" style="margin:0px">
<?if(isset($_GET["post"])){?><input type="hidden" name="id" value="<?=$_GET["post"]?>"><?}?>


<div class="form_field">
	<span><b><?=word_lang("title")?>:</b></span>
	<input class='ibox form-control' type="text" style="width:400px" name="title" value="<?=$title?>">
</div>



<div class="form_field">
	<span><b><?=word_lang("content")?>:</b></span>
	<textarea class='ibox form-control' name="content" style="width:700px;height:400px"><?=$content?></textarea>
	<div class="smalltext">Please use the next tag to make text bold: [b]<b>Bold text</b>[/b]</div>
</div>


<div class="form_field">
	<span><b><?=word_lang("photo")?>:</b></span>
	<input class='ibox form-control' type="file" style="width:320px" name="photo">
	<div class="smalltext">(*.jpg,*.jpeg,*.gif,*.png)</div>
</div>


<div class="form_field">
	<span><b><?=word_lang("categories")?>:</b></span>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<?
$sql="select id_parent,title,user from blog_categories where user='".result($_SESSION["people_login"])."' order by title";
$dr->open($sql);
$n=1;
while(!$dr->eof)
{
	if($n%4==0){echo("</tr><tr>");}
	?>
	<td style="padding-right:20px;padding-bottom:5px"><input type="checkbox" name="category<?=$dr->row["id_parent"]?>" value="1" <?if(preg_match("/".$dr->row["title"]."/",$categories)){?>checked<?}?>>&nbsp;<?=$dr->row["title"]?></td>
	<?
	$n++;
	$dr->movenext();
}
?>
</tr>
</table>
</div>





<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="padding-right:60px">
<div class="form_field">
	<span><b><?=word_lang("status")?>:</b></span>
	<select class='ibox form-control' name="published" style="width:150px"><option value="1" <?if($published==1){echo("selected");}?>><?=word_lang("published")?></option><option value="0" <?if($published==0){echo("selected");}?>><?=word_lang("pending")?></option></select>
</div>
</td>


<td nowrap>
<div class="form_field">
	<span><b><?=word_lang("allow comments")?>:</b></span>
	<input type="checkbox" name="comments" value="1" <?if($comments==1){echo("checked");}?>>
</div>
</td>


</tr>
</table>


<div class="form_field">
	<input class='isubmit' type="submit" value="<?if(isset($_GET["post"])){echo(word_lang("change"));}else{echo(word_lang("add"));}?>">
</div>

</form>








































<?include("profile_bottom.php");?>























<?include("../inc/footer.php");?>