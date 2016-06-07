<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_customers");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<a class="btn btn-success toright" href="content.php"><i class="icon-user icon-white"></i> <?=word_lang("add")?></a>

<h1><?=word_lang("customers")?>:</h1>


<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function status_user(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('status'+value).innerHTML =req.responseText;

        }
    }
    req.open(null, 'status.php', true);
    req.send( {'id': value} );
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
//Get Search
$search="";
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if(isset($_POST["search"])){$search=result($_POST["search"]);}

//Get Search type
$search_type="";
if(isset($_GET["search_type"])){$search_type=result($_GET["search_type"]);}
if(isset($_POST["search_type"])){$search_type=result($_POST["search_type"]);}





//Get pub_type
$pub_type="all";
if(isset($_GET["pub_type"])){$pub_type=result($_GET["pub_type"]);}
if(isset($_POST["pub_type"])){$pub_type=result($_POST["pub_type"]);}


//Items
$items=30;
if(isset($_GET["items"])){$items=(int)$_GET["items"];}
if(isset($_POST["items"])){$items=(int)$_POST["items"];}


//Search variable
$var_search="search=".$search."&search_type=".$search_type."&items=".$items."&pub_type=".$pub_type;




//Sort by title
$atitle=0;
if(isset($_GET["atitle"])){$atitle=(int)$_GET["atitle"];}

//Sort by date
$adate=0;
if(isset($_GET["adate"])){$adate=(int)$_GET["adate"];}



//Sort by ID
$aid=0;
if(isset($_GET["aid"])){$aid=(int)$_GET["aid"];}

//Sort by default
if($atitle==0 and $adate==0 and $aid==0)
{
$adate=2;
}



//Add sort variable
$com="";

if($atitle!=0)
{
	$var_sort="&atitle=".$atitle;
	if($atitle==1){$com=" order by login ";}
	if($atitle==2){$com=" order by login desc ";}
}

if($adate!=0)
{
	$var_sort="&adate=".$adate;
	if($adate==1){$com=" order by data1 ";}
	if($adate==2){$com=" order by data1 desc ";}
}



if($aid!=0)
{
	$var_sort="&aid=".$aid;
	if($aid==1){$com=" order by id_parent ";}
	if($aid==2){$com=" order by id_parent desc ";}
}








//Items on the page
$items_mass=array(10,20,30,50,75,100);




//Search parameter
$com2="";

if($search!="")
{
	if($search_type=="name")
	{
		$com2.=" and (name like '%".$search."%' or lastname like '%".$search."%') ";
	}
	if($search_type=="id")
	{
		$com2.=" and id_parent=".(int)$search." ";
	}
	if($search_type=="login")
	{
		$com2.=" and login = '".$search."' ";
	}
	if($search_type=="email")
	{
		$com2.=" and email = '".$search."' ";
	}
}


if($pub_type=="buyer")
{
	$com2.=" and utype='buyer' ";
}
if($pub_type=="seller")
{
	$com2.=" and utype='seller' ";
}
if($pub_type=="affiliate")
{
	$com2.=" and utype='affiliate' ";
}
if($pub_type=="common")
{
	$com2.=" and utype='common' ";
}

//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select id_parent,login,name,email,data1,accessdenied,utype,authorization,photo from users where id_parent>0 ";


$sql.=$com2.$com;

$rs->open($sql);
$record_count=$rs->rc;





//limit
$lm=" limit ".($kolvo*($str-1)).",".$kolvo;




$sql.=$lm;

//echo($sql);
$rs->open($sql);

?>
<div id="catalog_menu">


<form method="get" action="index.php" style="margin:0px">
<div class="toleft">
<span><?=word_lang("search")?>:</span>
<input type="text" name="search" style="width:200px;display:inline" class="ft" value="<?=$search?>" onClick="this.value=''">
<select name="search_type" style="width:100px;display:inline" class="ft">
<option value="login" <?if($search_type=="login"){echo("selected");}?>><?=word_lang("login")?></option>
<option value="id" <?if($search_type=="id"){echo("selected");}?>>ID</option>
<option value="name" <?if($search_type=="name"){echo("selected");}?>><?=word_lang("name")?></option>
<option value="email" <?if($search_type=="email"){echo("selected");}?>><?=word_lang("email")?></option>
</select>
</div>










<div class="toleft">
<span><?=word_lang("type")?>:</span>
<select name="pub_type" style="width:100px" class="ft">
<option value="all"><?=word_lang("all")?></option>
<option value="buyer" <?if($pub_type=="buyer"){echo("selected");}?>><?=word_lang("buyer")?></option>
<?if($global_settings["userupload"]==1){?>
<option value="seller" <?if($pub_type=="seller"){echo("selected");}?>><?=word_lang("seller")?></option>
<?}?>
<?if($global_settings["affiliates"]){?>
<option value="affiliate" <?if($pub_type=="affiliate"){echo("selected");}?>><?=word_lang("affiliate")?></option>
<?}?>
<?if($global_settings["common_account"]){?>
<option value="common" <?if($pub_type=="common"){echo("selected");}?>><?=word_lang("common")?></option>
<?}?>
</select>
</div>




<div class="toleft">
<span><?=word_lang("page")?>:</span>
<select name="items" style="width:70px" class="ft">
<?
for($i=0;$i<count($items_mass);$i++)
{
$sel="";
if($items_mass[$i]==$items){$sel="selected";}
?>
<option value="<?=$items_mass[$i]?>" <?=$sel?>><?=$items_mass[$i]?></option>
<?
}
?>

</select>
</div>

<div class="toleft">
<span>&nbsp;</span>
<input type="submit" class="btn btn-danger" value="<?=word_lang("search")?>">
</div>


<div class="toleft_clear"></div>
</form>


</div>



<?





if(!$rs->eof)
{
?>


<div style="padding:0px 0px 15px 6px"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>

<script language="javascript">
function publications_select_all(sel_form)
{
    if(sel_form.selector.checked)
   	{
        $("input:checkbox", sel_form).attr("checked",true);
    }
    else
    {
        $("input:checkbox", sel_form).attr("checked",false);
    }
}
</script>



<form method="post" action="delete.php" style="margin:0px"  id="adminform" name="adminform">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"></th>
<th class="hidden_original"></th>
<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&aid=<?if($aid==2){echo(1);}else{echo(2);}?>">ID</a> <?if($aid==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($aid==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>

<th>


<a href="index.php?<?=$var_search?>&atitle=<?if($atitle==1){echo(2);}else{echo(1);}?>"><?=word_lang("login")?></a> <?if($atitle==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($atitle==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>


<th class="hidden-phone hidden-tablet"><?=word_lang("E-mail")?></ht>

<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>


<th class="hidden-phone hidden-tablet"><?=word_lang("type")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("authorization")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("status")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("preview")?></th>

<th><?=word_lang("edit")?></th>
</tr>
<?
$tr=1;
while(!$rs->eof)
{
	$cl3="";
	$cl_script="";
	if(isset($_SESSION["user_users_id"]) and !isset($_SESSION["admin_rows_users".$rs->row["id_parent"]]) and $rs->row["id_parent"]>$_SESSION["user_users_id"])
	{
		$cl3="success";	
		$cl_script="onMouseover=\"deselect_row('users".$rs->row["id_parent"]."')\"";
	}
	
	$photo=site_root."/images/user.gif";
	if($rs->row["photo"]!="")
	{
		$photo=$rs->row["photo"];
	}
?>
<tr id="users<?=$rs->row["id_parent"]?>" class='<?if($tr%2==0){echo("snd");}?> <?=$cl3?>' <?=$cl_script?>>
<td><input type="checkbox" name="sel<?=$rs->row["id_parent"]?>" id="sel<?=$rs->row["id_parent"]?>"></td>
<td class="hidden_original"><img class="direct-chat-img" src="<?=$photo?>" /></td>
<td class="hidden-phone hidden-tablet"><?=$rs->row["id_parent"]?></td>


<td><div><a href="content.php?id=<?=$rs->row["id_parent"]?>"><b><?=$rs->row["login"]?></b></a></div></td>
<td class="hidden-phone hidden-tablet">
	<?if($rs->row["email"]!=""){?>
		<div class="link_email"><a href="mailto:<?=$rs->row["email"]?>"><?=$rs->row["email"]?></a></div>
	<?}else{echo("&mdash;");}?>
</td>
<td class="gray hidden-phone hidden-tablet"><?=show_time_ago($rs->row["data1"])?></td>

<td class="hidden-phone hidden-tablet"><?=word_lang($rs->row["utype"])?></td>
<td class="hidden-phone hidden-tablet">
<?
if($rs->row["authorization"]=="site")
{
	echo(word_lang("website"));
}
else
{
	if($rs->row["authorization"]=="twitter")
	{
		echo("<a href='http://www.twitter.com/".$rs->row["login"]."'>Twitter</a>");
	}
	if($rs->row["authorization"]=="facebook")
	{
		echo("<a href='http://www.facebook.com/profile.php?id=".str_replace("fb","",$rs->row["login"])."'>Facebook</a>");
	}
	if($rs->row["authorization"]=="vk")
	{
		echo("<a href='http://vk.com/id".str_replace("vk","",$rs->row["login"])."'>Vkontakte</a>");
	}
	if($rs->row["authorization"]=="instagram")
	{
		echo("<a href='http://instagram.com/".str_replace("instagram_","",$rs->row["login"])."'>Instagram</a>");
	}
}
?>
</td>
<td nowrap class="hidden-phone hidden-tablet">

<?
$cl="success";
if($rs->row["accessdenied"]==1)
{
	$cl="danger";
}
?>
<div id="status<?=$rs->row["id_parent"]?>" name="status<?=$rs->row["id_parent"]?>" class="link_status"><a href="javascript:status_user(<?=$rs->row["id_parent"]?>);" <?=$cl?>><span class='label label-<?=$cl?>'><?if($rs->row["accessdenied"]!=1){echo(word_lang("active"));}else{echo(word_lang("access denied"));}?></span></a></div>


</td>
<td class="hidden-phone hidden-tablet"><div class="link_preview"><a href="<?=site_root?>/users/<?=$rs->row["id_parent"]?>.html"><?=word_lang("preview")?></a></div></td>

<td><div class="link_edit"><a href="content.php?id=<?=$rs->row["id_parent"]?>"><?=word_lang("edit")?></a></div></td>
</tr>
<?
$n++;
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>


<input type="submit" class="btn btn-danger" value="<?=word_lang("delete")?>"  style="margin:15px 0px 0px 6px;">






</form>
<div style="padding:25px 0px 0px 6px"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>
<?
}
else
{
echo("<p><b>".word_lang("not found")."</b></p>");
}
?>

<? include("../inc/end.php");?>