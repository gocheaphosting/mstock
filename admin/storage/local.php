<?
//Check access
admin_panel_access("settings_storage");

if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>

By default all files are stored into the <b>/content/</b> folder.

</p><p>

Every publication has the next directory's structure: <b>/content/[publication ID]/</b>

</p><p>

In Linux system a directory may have only <b>31998</b> subdirectories. So when you will have <b>31998 publications</b> you should add a new folder for the file's storage.

</p><p>

To <b>add a new file storage folder</b> you should create a new directory for example <b>/content2/</b> and copy <b>/content/.htaccess</b> file there.

</p><p>

Please <b>not to forget to copy /content/.htaccess</b> file to the new file storage directory. It is very important for <b>security reasons</b>.

</p>

</div>
<div class="subheader"><?=word_lang("Folders")?></div>
<div class="subheader_text">

<p>
<a class="btn btn-success" href="add.php"><i class="icon-folder-open icon-white"></i> <?=word_lang("Add  New  Folder")?></a>
</p>

<p>
Here you can select where you would like to store files on the local server:
</p>

<?
$tr=0;
$sql="select * from filestorage where types=0";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="change.php" style="margin-top:25px">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover">
<tr>
<th><b>Enabled</b></th>
<th><b>URL</b></th>
<th><b><?=word_lang("quantity")?></b></th>
<th><b><?=word_lang("delete")?></b></th>
</tr>
<?
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td align="center"><input name="activ" type="radio" value="<?=$rs->row["id"]?>" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
<td><?=site_root?><?=$rs->row["url"]?>/</td>
<td>

<?
$dir_amount = -2;
$dir = opendir ($DOCUMENT_ROOT.$rs->row["url"]);
while ($file = readdir ($dir)) 
{
if(is_dir($DOCUMENT_ROOT.$rs->row["url"]."/".$file))
{
    $dir_amount++;
}
}
closedir ($dir);

echo($dir_amount);
?>




</td>
<td><?if($rs->row["id"]!=1 and $dir_amount==0){?><a href="delete.php?id=<?=$rs->row["id"]?>"><b><?=word_lang("delete")?></b></a><?}else{echo("You may not delete the directory because it is not empty.");}?></td>
</tr>
<?
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>
<input type="submit" value="<?=word_lang("change")?>" class="btn btn-primary" style="margin:3px 0px 0px 6px">
</form><br>
<?
}
?>

</div>
