<?if(!defined("site_root")){exit();}?>

<p>Please login into LinkPoint Connect Admin and go to <b>Customization -> Settings</b>.</p> 


<p>Set the following URL in the <b>Order Submission Form</b>: <a href="<?=surl?><?=site_root?>/"><?=surl?><?=site_root?>/</a></p>

<p>
Also please uncheck the checkboxes:<br>
- in the 'Confirmation Page ("Thank You" Page URL)' section "URL is a CGI script" checkbox<br>
- in the 'Failure Page ("Sorry" Page URL)' section "URL is a CGI script" checkbox
</p>


<form method="post" action="linkpoint_change.php">
<?
$sql="select * from gateway_linkpoint";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span><?=word_lang("store number")?>:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span><?=word_lang("allow ipn")?>:</span>
<input type='checkbox' name='ipn' value="1" <?if($rs->row["ipn"]==1){echo("checked");}?>>
</div>


<?
}
?>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>
