<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_subscription");
?>
<? include("../inc/begin.php");?>



<script>
$(document).ready(function(){
	$("#add_new").colorbox({width:"400",height:"", inline:true, href:"#new_box",scrolling:false});
});
</script>

<?
$subscription_limit="Credits";
$sql="select name from subscription_limit where activ=1";
$rs->open($sql);
if(!$rs->eof)
{
	$subscription_limit=$rs->row["name"];
}
?>



<a class="btn btn-success toright" id="add_new" href="#"><i class="icon-time icon-white fa fa-plus"></i>&nbsp <?=word_lang("add")?></a>


<h1><?=word_lang("subscription")?>:</h1>


<p>To set a subscription plan you should define <a href="../content_types/"><b>Content Types</b></a> first. Content type is a method to divide all files into several global categories. For example: Premium files, usual files and etc.</p>

<br>


<h2>Subscription limit:</h2>

<p>The seller's commission depends on the file's price only when the subscription limit is "By Credits".</p>

<form method="post" action="change_limit.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover">
<tr>
<th></th>
<th style="width:90%"><b><?=word_lang("title")?>:</b></th>
</tr>
<?
$tr=1;
$sql="select * from subscription_limit";
$rs->open($sql);
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td align="center"><input name="limit" type="radio" value="<?=$rs->row["name"]?>" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
<td class="big">By <?=$rs->row["name"]?></td>

</tr>
<?
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>"  style="margin:10px 0px 0px 6px">
</form><br>




<br>
<h2><?=word_lang("subscription")?>:</h2>

<?
$tr=1;
$sql="select * from subscription order by priority";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="change.php">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th><b><?=word_lang("priority")?>:</b></th>
<th style="width:60%"><b><?=word_lang("title")?>:</b></th>
<th><b><?=word_lang("price")?>:</b></th>
<th><b><?=word_lang("days till expiration")?>:</b></th>
<th><b>*<?=word_lang("recurring payments")?>:</b></th>
<th><b><?=word_lang("content type")?>:</b></th>
<th><b><?=word_lang("limit")?> (<?=$subscription_limit?><?if($subscription_limit=="Bandwidth"){echo(" Mb.");}?>)</b></th>
<th><b>**<?=word_lang("daily limit")?></b></th>
<th><b><?=word_lang("delete")?></b></th>
</tr>
<?
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td align="center"><input name="priority<?=$rs->row["id_parent"]?>" type="text" style="width:40px" value="<?=$rs->row["priority"]?>"></td>
<td><input name="title<?=$rs->row["id_parent"]?>" type="text" style="width:250px" value="<?=$rs->row["title"]?>"></td>
<td align="center"><input name="price<?=$rs->row["id_parent"]?>" type="text" style="width:100px"  value="<?=float_opt($rs->row["price"],2)?>"></td>
<td align="center"><input name="days<?=$rs->row["id_parent"]?>" type="text" style="width:100px"  value="<?=$rs->row["days"]?>"></td>
<td align="center"><input name="recurring<?=$rs->row["id_parent"]?>" type="checkbox" <?if($rs->row["recurring"]==1){echo("checked");}?>></td>
<td>


<?
$sql="select * from content_type order by priority";
$ds->open($sql);
while(!$ds->eof)
{
?>
<div style="margin-bottom:2px"><input name="type<?=$rs->row["id_parent"]?>_<?=$ds->row["id_parent"]?>" type="checkbox" <?if(preg_match("/".$ds->row["name"]."/i",$rs->row["content_type"])){echo("checked");}?>>&nbsp;<?=$ds->row["name"]?></div>
<?
$ds->movenext();
}
?>



</td>
<td align="center"><input name="bandwidth<?=$rs->row["id_parent"]?>" type="text" style="width:100px"  value="<?=$rs->row["bandwidth"]?>"></td>
<td align="center"><input name="bandwidth_daily<?=$rs->row["id_parent"]?>" type="text" style="width:100px"  value="<?=$rs->row["bandwidth_daily"]?>"></td>
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
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>"  style="margin:10px 0px 0px 6px">
</form><br>
<?
}
?>


<p>* - for Paypal only.</p>
<p>** - if 0 there is no daily limit.</p>









<div style='display:none'>
		<div id='new_box'>
		<div class="modal_header"><?=word_lang("subscription")?></div>

<form method="post" action="add.php">


<div class="admin_field">
<span><b><?=word_lang("title")?>:</b></span>
<input name="title" type="text" style="width:250px">
</div>
<div class="admin_field">
<span><b><?=word_lang("price")?>:</b></span>
<input name="price" type="text" style="width:60px" value="1.00">
</div>
<div class="admin_field">
<span><b><?=word_lang("days till expiration")?>:</b></span>
<input name="days" type="text" style="width:40px" value="10">
</div>
<div class="admin_field">
<span><b><?=word_lang("recurring payments")?>:</b></span>
<input name="recurring" type="checkbox"></td>
</div>
<div class="admin_field">
<span><b><?=word_lang("content type")?>:</b></span>
<?
$sql="select * from content_type order by priority";
$rs->open($sql);
while(!$rs->eof)
{
?>
<div style="margin-bottom:2px"><input name="type<?=$rs->row["id_parent"]?>" type="checkbox" checked>&nbsp;<?=$rs->row["name"]?></div>
<?
$rs->movenext();
}
?>
</div>
<div class="admin_field">
<span><b><?=word_lang("limit")?> (<?=$subscription_limit?><?if($subscription_limit=="Bandwidth"){echo(" Mb.");}?>)</b></span>
<input name="bandwidth" type="text" style="width:40px" value="1">
</div>


<div class="admin_field">
<span><b><?=word_lang("daily limit")?>:</b></span>
<input name="bandwidth_daily" type="text" style="width:40px" value="1">
</div>


<div class="admin_field">
<input type="submit" class="btn btn-primary" value="<?=word_lang("add")?>">
</div>
</form>


</div>
</div>















<? include("../inc/end.php");?>