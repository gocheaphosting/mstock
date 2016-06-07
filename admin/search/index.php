<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_search");
?>
<? include("../inc/begin.php");?>



<a class="btn btn-danger toright" href="delete.php"><i class="icon-trash icon-white"></i> <?=word_lang("remove all")?></a>


<h1><?=word_lang("search history")?>:</h1>



<div id="catalog_menu">
<form method="post" action="index.php" style="margin:0">





<?

//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];


//Количество страниц на странице
$kolvo2=k_str2;

$query="";
if(isset($_POST["query"])){$query=result($_POST["query"]);}
if(isset($_GET["query"])){$query=result($_GET["query"]);}




if(isset($_POST["d1"]) and isset($_POST["m1"]) and isset($_POST["y1"]))
{
	$data1=mktime(0,0,0,$_POST["m1"],$_POST["d1"],$_POST["y1"]);
}
elseif(isset($_GET["d1"]) and isset($_GET["m1"]) and isset($_GET["y1"]))
{
	$data1=mktime(0,0,0,$_GET["m1"],$_GET["d1"],$_GET["y1"]);
}
else
{
	$data1=mktime(0,0,0,date("m"),date("d"),date("Y"))-3600*24-7;
}

if(isset($_POST["d2"]) and isset($_POST["m2"]) and isset($_POST["y2"]))
{
	$data2=mktime(23,59,59,$_POST["m2"],$_POST["d2"],$_POST["y2"]);
}
elseif(isset($_GET["d2"]) and isset($_GET["m2"]) and isset($_GET["y2"]))
{
	$data2=mktime(23,59,59,$_GET["m2"],$_GET["d2"],$_GET["y2"]);
}
else
{
	$data2=mktime(23,59,59,date("m"),date("d"),date("Y"));
}


$d1=date("d",$data1);
$m1=date("m",$data1);
$y1=date("Y",$data1);

$d2=date("d",$data2);
$m2=date("m",$data2);
$y2=date("Y",$data2);

?>


<div class="toleft">


<span><?=word_lang("query")?>:</span>
<input type="text" name="query" value="<?=$query?>" style="width:185px"><br>
</div>
<div class="toleft">

<span>From:</span>

<select name="d1" style="width:70px;display:inline">
<?
for($i=1;$i<32;$i++){
$sel="";
if($d1 ==$i){$sel="selected";}
?>
<option value="<?=$i?>" <?=$sel?>><?=$i?>
<?}?>
</select>&nbsp;<select name="m1" style="width:150px;display:inline">
<?
for($i=0;$i<count($m_month);$i++)
{
$sel="";
if($m1 ==$i+1){$sel="selected";}
?>
<option value='<?=$i+1?>' <?=$sel?>><?=word_lang(strtolower($m_month[$i]))?>
<?}?>
</select>&nbsp;<select name=y1 style="width:80px;display:inline">
<?
for($i=2005;$i<date("Y")+1;$i++)
{
$sel="";
if($y1 ==$i){$sel="selected";}
?>
<option value='<?=$i?>' <?=$sel?>><?=$i?>
<?}?>
</select>

</div>
<div class="toleft">

<span>To:</span>
<select name="d2" style="width:70px;display:inline">
<?
for($i=1;$i<32;$i++){
$sel="";
if($d2 ==$i){$sel="selected";}
?>
<option value="<?=$i?>" <?=$sel?>><?=$i?>
<?}?>
</select>&nbsp;<select name="m2" style="width:150px;display:inline">
<?
for($i=0;$i<count($m_month);$i++)
{
$sel="";
if($m2 ==$i+1){$sel="selected";}
?>
<option value='<?=$i+1?>' <?=$sel?>><?=word_lang(strtolower($m_month[$i]))?>
<?}?>
</select>&nbsp;<select name=y2 style="width:80px;display:inline">
<?
for($i=2005;$i<date("Y")+1;$i++)
{
$sel="";
if($y2 ==$i){$sel="selected";}
?>
<option value='<?=$i?>' <?=$sel?>><?=$i?>
<?}?>
</select>

</div>

<div class="toleft">
<span>&nbsp;</span>
<input type="submit" class="btn btn-danger" value="<?=word_lang("search")?>">
</div>


<div class="toleft_clear"></div>


</form>

</div>




    <div class="alert  alert-warning" style="margin:0px 5px 15px 5px">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    We advice you to clear Search history from time to time. It can strongly increase the database's size. <br>You can disable it in <b>Admin panel -> Settings - >Site settings -> Search history</b>
    </div>













<?
$com="";
if($query!=""){$com=" and zapros like '%".$query."%'";}




$sql="select zapros, count(zapros) as quantity from search_history where data>".($data1-1)." and data<".($data2+1).$com." group by zapros order by quantity desc,zapros";
$rs->open($sql);
if(!$rs->eof)
{
?>
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover">
<tr>
<th><b><?=word_lang("query")?></b></th>
<th><b><?=word_lang("quantity")?></b></th>
</tr>
<?
$n=0;
$tr=1;
while(!$rs->eof)
{
if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
{

?>
<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">

<td><div class="link_preview"><?=$rs->row["zapros"]?></div></td>
<td><?=$rs->row["quantity"]?></td>

</tr>
<?
}
$n++;
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>
<?
echo("<p>".paging($n,$str,$kolvo,$kolvo2,"index.php","&d1=".$d1."&m1=".$m1."&y1=".$y1."&d2=".$d2."&m2=".$m2."&y2=".$y2."&query=".$query)."</p>");
}
else
{
?>
<p>Not found.</p>
<?
}
?>















<? include("../inc/end.php");?>