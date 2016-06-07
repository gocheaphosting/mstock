<?$site="user_blog";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>




<?include("user_top.php");?>

<?
$blog_page="<table border='0' cellpadding='0' cellspacing='0' width='100%'>
<tr valign='top'>
<td width='90%'>{RESULTS}</td>
<td width='145' style='padding-left:30px'>{MENU}</td> 
</tr>
</table>";

if(file_exists($DOCUMENT_ROOT."/".$site_template_url."blog.tpl"))
{
	$blog_page=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."blog.tpl");
}

$blog_page=str_replace("{MENU}","{SEPARATOR}",$blog_page);
$blog_page=str_replace("{RESULTS}","{SEPARATOR}",$blog_page);

$blog_parts=explode("{SEPARATOR}",$blog_page);

$blog_header=$blog_parts[0];
$blog_middle=@$blog_parts[1];
$blog_footer=@$blog_parts[2];

echo($blog_header);

if(isset($_GET["postid"]))
{
	$sql="select id_parent,title,content,data,user,published,photo,categories,comments from blog where user='".result3(user_url_back($nameuser))."' and published=1 and id_parent=".(int)$_GET["postid"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>
		<div><h2 style="margin:0px"><?=$rs->row["title"]?></h2></div>
		<div class="datenews" style="margin-top:1px"><?=show_time_ago($rs->row['data'])?></div>
		<?
		if($rs->row["photo"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"]))
		{
			?>
			<img src="<?=$rs->row["photo"]?>" align="left" style="margin-top:10px;margin-bottom:5px;margin-left:0px;margin-right:10px">
			<?
		}
		?>
		<div style="margin-top:8px"><p><?

		$content=str_replace("\n","<br>",$rs->row["content"]);
		$content=str_replace("\[b\]","<b>",$content);
		$content=str_replace("\[\/b\]","</b>",$content);
		echo($content);
		?></p></div>
		<div style="margin-top:5px" class="grayfont"><?=word_lang("posted in")?>&nbsp;
		<?
		if($rs->row["categories"]=="")
		{
			?><a href="<?=site_root?>/blog/0/<?=user_url($rs->row["user"])?>.html"><?=word_lang("uncategorized")?></a><?
		}
		else
		{
			$cat=explode(",",$rs->row["categories"]);
			for($i=0;$i<count($cat);$i++)
			{
				if($cat[$i]!="")
				{
					$sql="select id_parent from blog_categories where user='".result3(user_url_back($nameuser))."' and title='".result($cat[$i])."'";
					$dn->open($sql);
					if(!$dn->eof)
					{
					
						if($i!=0){echo(" ");}
						?><a href="<?=site_root?>/blog/<?=$dn->row["id_parent"]?>/<?=user_url($rs->row["user"])?>.html"><?=$cat[$i]?></a><?
					}
				}
			}
		}
		?>
		</div>
		<?
	}
}
if($rs->row["comments"]==1)
{
?>
<h2 style="margin-top:35px"><?=word_lang("comments")?></h2>




<script type="text/javascript" language="JavaScript">



function comments_show(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('commentscontent').innerHTML =req.responseText;
        }
    }
    req.open(null, '<?=site_root?>/members/user_comments_content.php', true);
    req.send( { postid: value} );
}


function comments_add(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('commentscontent').innerHTML =req.responseText;

        }
    }
    req.open(null, '<?=site_root?>/members/user_comments_content.php', true);
    req.send( {'form': document.getElementById(value) } );
}

comments_show(<?=$_GET["postid"]?>);
</script>



<div id="commentscontent" name="commentscontent"></div>





<?
}
echo($blog_middle);

include("user_blog_menu.php");


echo($blog_footer);
?>




<?include("user_bottom.php");?>
<?include("../inc/footer.php");?>