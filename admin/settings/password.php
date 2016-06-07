<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_password");
?>
<? include("../inc/begin.php");?>


<h1><?=word_lang("admin password")?>:</h1>




<p><b>
<?
if(isset($_GET["d"]))
{
if($_GET["d"]==1){echo(word_lang("changepasswordsuccess"));}
if($_GET["d"]==2){echo(word_lang("errorpasswordequal"));}
if($_GET["d"]==3){echo(word_lang("errorpasswordincorrect"));}
}
?>
</b></p>

<form method="post" action="password_change.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class='table_admin table table-striped'>
<tr>
<td><?=word_lang("currentpassword")?></td>
<td style="width:80%"><input type="password" name="p0" class="ft" style="width:100px"></td>
</tr>
<tr class='snd'>
<td><?=word_lang("newpassword")?></td>
<td><input type="password" name="p1" class="ft" style="width:100px"></td>
</tr>
<tr>
<td><?=word_lang("confirmpassword")?></td>
<td><input type="password" name="p2" class="ft" style="width:100px"></td>
</tr>
</table>
</div></div></div></div></div></div></div></div>

<input type="submit" value="<?=word_lang("change")?>" class="btn btn-primary" style="margin:10px 0px 0px 6px">
</form>













<? include("../inc/end.php");?>