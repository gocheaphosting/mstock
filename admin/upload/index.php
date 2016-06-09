<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_upload");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>





<h1><?=word_lang("upload manager")?>:</h1>


<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">


function reason_open(value)
{
document.getElementById('reason'+value).style.display="inline";
document.getElementById('reason_edit'+value).style.display="none";
}

function reason_close(value)
{
document.getElementById('reason'+value).style.display="none";
document.getElementById('reason_edit'+value).style.display="inline";
}


function refuse_reason(fid,ftable)
{
	 var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			document.getElementById('reason_content'+fid).innerHTML =req.responseText;
			reason_close(fid);
		}
    }
    fcontent=document.getElementById('reason_text'+fid).value;
    req.open(null, 'refuse_reason.php', true);
    req.send( {'fid':fid,'ftable':ftable,'fcontent':fcontent} );
}



function fstatus(fid,fdo,ftable) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('status'+fid).innerHTML =req.responseText;

        }
    }
    req.open(null, 'status.php', true);
    req.send( {'fid':fid,'fdo':fdo,'ftable':ftable} );
}

function cstatus(fid,fdo) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('status'+fid).innerHTML =req.responseText;

        }
    }
    req.open(null, 'status_category.php', true);
    req.send( {'fid':fid,'fdo':fdo} );
}


function deselect_row(value)
{
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			$("#"+value).removeClass("success");
        }
    }
    req.open(null, '<?=site_root?>/admin/inc/deselect.php', true);
    req.send( {'id': value} );
}

</script>



<?
$d=4;
if(isset($_GET["d"])){$d=$_GET["d"];}
if($d==""){$d=4;}

?>



<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 6px 10px 6px">
            <li <?if($d==4){echo("class='active'");}?>><a href="index.php?d=4"><?=word_lang("audio")?></a></li>

			<!--li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2"><?=word_lang("photo")?></a></li>
			<li <?if($d==3){echo("class='active'");}?>><a href="index.php?d=3"><?=word_lang("video")?></a></li>
			<li <?if($d==5){echo("class='active'");}?>><a href="index.php?d=5"><?=word_lang("vector")?></a></li-->
			<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1"><?=word_lang("categories")?></a></li>
			<?
			if($global_settings["prints_lab"])
			{
				?>
				<li <?if($d==6 or $d==7){echo("class='active'");}?>><a href="index.php?d=6"><?=word_lang("prints lab")?></a></li>
				<?
			}
			?>
    	</ul>

<div class="box_padding">




<?
//������� ��������
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//���������� �������� �� ��������
$kolvo=$global_settings["k_str"];


//���������� ������� �� ��������
$kolvo2=k_str2;






if(isset($_GET["status"])){$pstatus=(int)$_GET["status"];}
else{$pstatus=-1;}



if(isset($_GET["pid"])){$pid=(int)$_GET["pid"];}
else{$pid=0;}

if(isset($_GET["ptitle"])){$ptitle=(int)$_GET["ptitle"];}
else{$ptitle=0;}

if(isset($_GET["pviewed"])){$pviewed=(int)$_GET["pviewed"];}
else{$pviewed=0;}

if(isset($_GET["pdownloads"])){$pdownloads=(int)$_GET["pdownloads"];}
else{$pdownloads=0;}

if(isset($_GET["pdata"])){$pdata=(int)$_GET["pdata"];}
else{$pdata=2;}

if(isset($_GET["puser"])){$puser=(int)$_GET["puser"];}
else{$puser=0;}


//Status
$com="";
if($pstatus>=0)
{
	if($pstatus==2)
	{
		$com=" and published=-1";
	}
	else
	{
		$com=" and published=".$pstatus;
	}
}


//Sort
$com2="";
if($pdata==1){$com2=" order by data";}
if($pdata==2){$com2=" order by data desc";}
if($pdownloads==1){$com2=" order by downloaded";}
if($pdownloads==2){$com2=" order by downloaded desc";}
if($pid==1){$com2=" order by id_parent";}
if($pid==2){$com2=" order by id_parent desc";}
if($ptitle==1){$com2=" order by title";}
if($ptitle==2){$com2=" order by title desc";}
if($pviewed==1){$com2=" order by viewed";}
if($pviewed==2){$com2=" order by viewed desc";}
if($puser==1){$com2=" order by author";}
if($puser==2){$com2=" order by author desc";}


//search
$search="";
if(isset($_POST["search"])){$search=result($_POST["search"]);}
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if($search!=""){$com.=" and author='".$search."'";}


$mstatus=array();
$mstatus["all"]=-1;
$mstatus["approved"]=1;
$mstatus["pending"]=0;
$mstatus["declined"]=2;

$varsort="&pid=".$pid."&ptitle=".$ptitle."&pviewed=".$pviewed."&pdownloads=".$pdownloads."&pdata=".$pdata."&puser=".$puser."&search=".$search;

if($pid==1){$varsort_id="&pid=2&ptitle=0&pviewed=0&pdownloads=0&pdata=0&puser=0&search=".$search;}
elseif($pid==2){$varsort_id="&pid=1&ptitle=0&pviewed=0&pdownloads=0&pdata=0&puser=0&search=".$search;}
else{$varsort_id="&pid=1&ptitle=0&pviewed=0&pdownloads=0&pdata=0&puser=0&search=".$search;}


if($ptitle==1){$varsort_title="&pid=0&ptitle=2&pviewed=0&pdownloads=0&pdata=0&puser=0&search=".$search;}
elseif($ptitle==2){$varsort_title="&pid=0&ptitle=1&pviewed=0&pdownloads=0&pdata=0&puser=0&search=".$search;}
else{$varsort_title="&pid=0&ptitle=1&pviewed=0&pdownloads=0&pdata=0&puser=0&search=".$search;}


if($pviewed==1){$varsort_viewed="&pid=0&ptitle=0&pviewed=2&pdownloads=0&pdata=0&puser=0&search=".$search;}
elseif($pviewed==2){$varsort_viewed="&pid=0&ptitle=0&pviewed=1&pdownloads=0&pdata=0&puser=0&search=".$search;}
else{$varsort_viewed="&pid=0&ptitle=0&pviewed=2&pdownloads=0&pdata=0&puser=0&search=".$search;}


if($pdownloads==1){$varsort_downloads="&pid=0&ptitle=0&pviewed=0&pdownloads=2&pdata=0&puser=0&search=".$search;}
elseif($pdownloads==2){$varsort_downloads="&pid=0&ptitle=0&pviewed=0&pdownloads=1&pdata=0&puser=0&search=".$search;}
else{$varsort_downloads="&pid=0&ptitle=0&pviewed=0&pdownloads=2&pdata=0&puser=0&search=".$search;}

if($pdata==1){$varsort_data="&pid=0&ptitle=0&pviewed=0&pdownloads=0&pdata=2&puser=0&search=".$search;}
elseif($pdata==2){$varsort_data="&pid=0&ptitle=0&pviewed=0&pdownloads=0&pdata=1&puser=0&search=".$search;}
else{$varsort_data="&pid=0&ptitle=0&pviewed=0&pdownloads=0&pdata=2&puser=0&search=".$search;}


if($puser==1){$varsort_user="&pid=0&ptitle=0&pviewed=0&pdownloads=0&pdata=0&puser=2&search=".$search;}
elseif($puser==2){$varsort_user="&pid=0&ptitle=0&pviewed=0&pdownloads=0&pdata=0&puser=1&search=".$search;}
else{$varsort_user="&pid=0&ptitle=0&pviewed=0&pdownloads=0&pdata=0&puser=1&search=".$search;}

if($d==2){$table="photos";}
if($d==3){$table="videos";}
if($d==4){$table="audio";}
if($d==5){$table="vector";}

?>

<?if($d!=1 and $d!=6 and $d!=7){?>





<div id="catalog_menu">
<form method="get" action="index.php" style="margin:0px">
<div class="toleft">
<span><?=word_lang("author")?>:</span>
<input type="hidden" name="d" value="<?=$d?>">
<input type="text" name="search" style="width:200px" class="ft" value="<?=$search?>" onClick="this.value=''">
</div>
<div class="toleft">
<span><?=word_lang("type")?>:</span>
<select name="status" style="width:120px" class="ft">
<option value="-1"><?=word_lang("all")?></option>
<option value="1" <?if($pstatus==1){echo("selected");}?>><?=word_lang("approved")?></option>
<option value="0" <?if($pstatus==0){echo("selected");}?>><?=word_lang("pending")?></option>
<option value="2" <?if($pstatus==2){echo("selected");}?>><?=word_lang("declined")?></option>
</select>
</div>

<div class="toleft">
<span>&nbsp;</span>
<input type="submit" class="btn btn-danger" value="<?=word_lang("search")?>">
</div>

<div class="toleft_clear"></div>
</form>
</div>
<?}?>



<?if($d==1){include("publications_category.php");}?>
<?if($d==2){include("publications_content.php");}?>
<?if($d==3){include("publications_content.php");}?>
<?if($d==4){include("publications_content.php");}?>
<?if($d==5){include("publications_content.php");}?>
<?if($d==6){include("publications_galleries.php");}?>
<?if($d==7){include("gallery.php");}?>







</div>
</div>










<? include("../inc/end.php");?>