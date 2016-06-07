<?
//Check access
admin_panel_access("users_newsletter");

if(!defined("site_root")){exit();}
?>
<?
$sql="select * from newsletter order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
?>
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover" style='width:100%'>
<tr>


<th><b><?=word_lang("subject")?></b></th>
<th class="hidden-phone hidden-tablet"><b><?=word_lang("to")?></b></th>
<th class="hidden-phone hidden-tablet"><b><?=word_lang("type")?></b></th>
<th class="hidden-phone hidden-tablet"><b><?=word_lang("date")?></b></th>
<th width="50%"><b><?=word_lang("content")?></b></th>
<th><b><?=word_lang("edit")?> / <?=word_lang("send")?></b></th>
<th><b><?=word_lang("delete")?></b></th>

</tr>
<?
$tr=1;
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">

<td class="big"><div class="link_message"><?=$rs->row["subject"]?></div></td>
<td class="hidden-phone hidden-tablet"><?
if($rs->row["touser"]=="admin"){echo("Admin email for testing");}
if($rs->row["touser"]=="newsletter"){echo("Users with approved newsletter");}
if($rs->row["touser"]=="buyer_newsletter"){echo("Buyers with approved newsletter");}
if($rs->row["touser"]=="seller_newsletter"){echo("Sellers with approved newsletter");}
if($rs->row["touser"]=="affiliate_newsletter"){echo("Affiliates with approved newsletter");}
if($rs->row["touser"]=="common_newsletter"){echo("common users with approved newsletter");}
if($rs->row["touser"]=="marketing"){echo("Marketing emails");}
if($rs->row["touser"]=="all"){echo("All users");}

?></td>
<td class="hidden-phone hidden-tablet"><?
if($rs->row["types"]=="message"){echo("Site message");}
if($rs->row["types"]=="email"){echo("Email");}
if($rs->row["types"]=="all"){echo("Site message & Email");}
?></td>
<td class="gray hidden-phone hidden-tablet"><?=date(date_short,$rs->row['data'])?></td>
<td>
<?
if($rs->row["html"]==1)
{
	echo($rs->row["content"]);
}
else
{
	echo(str_replace("\n","<br>",$rs->row["content"]));
}
?>
</td>
<td>

<div class="link_edit"><a href='index.php?d=1&id=<?=$rs->row["id"]?>'><?=word_lang("edit")?></a></div>

</td>
<td>

<div class="link_delete"><a href='delete.php?id=<?=$rs->row["id"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>

</td>
</tr>
<?
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>
<?
}
else
{
echo("<p><b>".word_lang("not found")."</b></p>");
}



?>
