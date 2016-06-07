<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("pages_textpages");
?>
<? include("../inc/begin.php");?>







<a class="btn btn-success toright" href="content.php"><i class="icon-list-alt icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>



<h1><?=word_lang("Text pages")?>:</h1>

<p>The text page's URL depends on a title. If you change the title the URL changes too.</p>

<p>The page's URL can be  2 types:</p>

<ul>
<li><b>/pages/[page-title].html</b></li>
<li><b>/pages/[page ID].html</b></li>
</ul>

<p>They are Apache mod_rewrite <b>virtual URLs</b>. There is no /pages/ folder on the server. To define a correct URL you should click on <b>'Preview'</b> link.</p>

<p>if <b>'Site info'</b> property is enable the page will be in 'Site info' menu. 
</p>

<p><b>'Link'</b> property means some <b>external URL</b> in 'Site info' menu (/contacts/ link for example) or <b>anchor</b> (This is text for other page. For example 'Signup' is text  for /members/signup.php page).</p>

<p>You may have <b>multilingual</b> pages. The syntax: <b>{if english}</b>Text in English<b>{/if} {if french}</b>Text in French<b>{/if}</b>.</p>

<br>

<?
$sql="select id_parent,title,content,priority,link,url,siteinfo from pages order by priority";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change.php">
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b>ID:</b></th>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("priority")?>:</b></th>
	<th><b><?=word_lang("site info")?>:</b></th>
	<th></th>
	<th></th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		$url=site_root.$rs->row["url"];
		if($rs->row["link"]!="")
		{
			if(preg_match("/\//",$rs->row["link"]))
			{
				$url=$rs->row["link"];
			}
			else
			{
				$url="";
			}
		}
		
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td><?=$rs->row["id_parent"]?></td>
		<td class="big"><?=$rs->row["title"]?></td>
		<td><input name="priority<?=$rs->row["id_parent"]?>" type="text" style="width:40px" value="<?=$rs->row["priority"]?>"></td>
		<td><input type="checkbox"  name="siteinfo<?=$rs->row["id_parent"]?>" <?if($rs->row["siteinfo"]==1){echo("checked");}?>></td>
		<td><?if($url!=""){?><div class="link_preview"><a href='<?=$url?>' target="blank"><?=word_lang("preview")?></a><?}?></td>
		<td><div class="link_edit"><a href='content.php?id=<?=$rs->row["id_parent"]?>'><?=word_lang("edit")?></a></td>
		<td>
		<div class="link_delete"><a href='delete.php?id=<?=$rs->row["id_parent"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
		</td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:10px 0px 0px 6px">
	</form><br>
<?
}
?>





<? include("../inc/end.php");?>