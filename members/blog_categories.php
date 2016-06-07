<?$site="blog";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>



<?
$type="categories";


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
    req.open(null, '<?=site_root?>/members/blog_categories_edit.php', true);
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
    req.open(null, '<?=site_root?>/members/blog_categories_change.php', true);
    req.send( {'id': value, 'category': document.getElementById("category"+value).value } );
}


</script>


<?
$n=0;
$idmass="";
$sql="select id_parent,user from blog_categories where user='".result($_SESSION["people_login"])."'";
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






<?include("profile_top.php");?>



<h1><?=word_lang("blog")?> - <?=word_lang("categories")?></h1>


<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:25px">
<tr>
<form method="post" action="blog_categories_add.php">
<td><input class='ibox form-control' type="text" name="category" value="" style="width:150px">&nbsp;</td>
<td><input class='isubmit_orange' type="submit" value="<?=word_lang("add")?>"></td>
</form>
</tr>
</table>

















<?
$n=0;
$tr=1;
$sql="select id_parent,title,user from blog_categories where user='".result($_SESSION["people_login"])."' order by title";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="blog_categories_delete.php">
<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%">
<tr>
<th><input type="checkbox" id="allboxes" name="allboxes" onClick="checkedbox();"></th>
<th width="90%"><?=word_lang("category")?>:</th>
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
<td nowrap><div id="c<?=$rs->row["id_parent"]?>" name="c<?=$rs->row["id_parent"]?>"><?=str_replace("\n","<br>",$rs->row["title"])?></div></td>
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
echo(paging($n,$str,$kolvo,$kolvo2,"blog_categories.php",""));
}
else
{
echo("<b>".word_lang("not found")."</b>");
}
?>


































<?include("profile_bottom.php");?>























<?include("../inc/footer.php");?>