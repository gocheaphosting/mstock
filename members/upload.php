<?$site="upload";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?include("../inc/header.php");?>
<? include("../admin/function/show.php");?>
<?include("profile_top.php");?>
<?
//Check if the examination's status has been changed
$sql="select examination from users where id_parent=".(int)$_SESSION["people_id"];
$rs->open($sql);
if(!$rs->eof)
{
	$_SESSION["people_exam"]=(int)$rs->row["examination"];
}
?>








<?
$flag_exam=false;
//Examination status
$sql="select data,status,comments from examinations where user=".(int)$_SESSION["people_id"];
$rs->open($sql);
if(!$rs->eof)
{
	$flag_exam=true;
	if(!isset($_GET["t"]))
	{
	?>
		<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2">
		<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home">
		<th><b><?=word_lang("examination")?></b></th>
		<tr>
		<td><?=word_lang("date")?>:</td>
		<td><div class="link_date"><?=date(date_short,$rs->row["data"])?></div></td>
		</tr>
		<tr>
		<td><?=word_lang("status")?>:</td>
		<td><?if($rs->row["status"]==0){echo("<div class='link_pending'>".word_lang("pending")."</div>");}?><?if($rs->row["status"]==1){echo("<div class='link_approved'>".word_lang("approved")."</div>");}?><?if($rs->row["status"]==2){echo("<div class='link_pending'>".word_lang("declined")."</div>");}?></td>
		</tr>
		<?if($rs->row["comments"]!=""){?>
		<tr>
		<td><?=word_lang("Comments")?>:</td> <td><?=str_replace("\n","<br>",$rs->row["comments"])?></td>
		</tr>
		<?}?>
		</table>
</div></div></div></div></div></div></div></div><br><br>
	<?
	}
}
?>










<?




//Settings
$scategory=false;
$sphoto=false;
$svideo=false;
$saudio=false;
$svector=false;
$percentage=0;
$percentage_subscription=0;
$percentage_prints=0;
$percentage_type=0;
$percentage_subscription_type=0;
$percentage_prints_type=0;
$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["category"]==1){$scategory=true;}
	if($rs->row["upload"]==1){$sphoto=true;}
	if($rs->row["upload2"]==1){$svideo=true;}
	if($rs->row["upload3"]==1){$saudio=true;}
	if($rs->row["upload4"]==1){$svector=true;}
	$percentage=$rs->row["percentage"];
	$percentage_prints=$rs->row["percentage_prints"];
	$percentage_subscription=$rs->row["percentage_subscription"];
	$percentage_type=$rs->row["percentage_type"];
	$percentage_prints_type=$rs->row["percentage_prints_type"];
	$percentage_subscription_type=$rs->row["percentage_subscription_type"];
}

?>



<?if($global_settings["examination"] and $_SESSION["people_exam"]!=1){?>



<?
if(!isset($_GET["t"]))
{
	if(!$flag_exam)
	{
		?>
		<h1><?=word_lang("examination")?></h1>
		<?
	}
	//Examination description
	$sql="select content from pages where link='examination'";
	$rs->open($sql);
	if(!$rs->eof)
	{
		echo(translate_text($rs->row["content"]));
	}
}
else
{
	?>
	<h1><?=word_lang("examination")?></h1>
	<?
	//Thank you after the examination
	$sql="select content from pages where link='take_exam'";
	$rs->open($sql);
	if(!$rs->eof)
	{
		echo(translate_text($rs->row["content"]));
	}
}
?>







<?
$mtables=array("photos","videos","audio","vector");
$murls=array("photo","video","audio","vector");
$exam_flag=false;
for($i=0;$i<count($mtables);$i++)
{
	$sql="select id_parent,server1 from ".$mtables[$i]." where examination=1 and userid=".(int)$_SESSION["people_id"]." order by id_parent";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$n=0;
		?>
		<h2><?=word_lang($mtables[$i])?>:</h2>
		<table border='0' cellpadding='0' cellspacing='0' style="margin-bottom:25px">
		<tr valign='top'>
		<?
		while(!$rs->eof)
		{
			$exam_flag=true;
		
			if($i==0)
			{
				$thumb=show_preview($rs->row["id_parent"],"photo",1,1);
			}
			if($i==1)
			{
				$thumb=show_preview($rs->row["id_parent"],"video",1,1);
			}
			if($i==2)
			{
				$thumb=show_preview($rs->row["id_parent"],"audio",1,1);
			}
			if($i==3)
			{
				$thumb=show_preview($rs->row["id_parent"],"vector",1,1);
			}

			if($n%4==0){echo("</tr><tr valign='top'>");}
			?>
			<td style="padding:0px 10px 10px 0px">
			<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2"><a href="filemanager_<?=$murls[$i]?>.php?d=<?=($i+2)?>&id=<?=$rs->row["id_parent"]?>"><img src="<?=$thumb?>" border="0"></a></div></div></div></div></div></div></div></div>
			<div style="margin:3px 0px 0px 10px" class="smalltext"><b>ID:</b> <?=$rs->row["id_parent"]?></div>
			<div style="margin:3px 0px 0px 10px"><a href="delete_publication.php?id=<?=$rs->row["id_parent"]?>"><?=word_lang("delete")?></a></div>
			</td>
			<?
			$n++;
			$rs->movenext();
		}
		?>
		</tr>
		</table>
		<?
	}
}
?>








<?}else{?>
<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2">
		<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home">
<tr>
<th colspan="2"><?=word_lang("upload files")?></th>
</tr>

<tr>
<td><?=word_lang("status")?></td>
<td><div class="link_status"><?=$_SESSION["people_category"]?></div></td>
</tr>

<?if($percentage!=0){?>
	<tr>
	<td><?=word_lang("Your earnings")?>  &mdash; <?=word_lang("files")?></td>
	<td>
		<div class="link_commission">
		<?
		if ($percentage_type == 0) {
			echo(round($percentage)."%");
		} else {
			echo(currency(1,false) . float_opt($percentage,2)." ".currency(2,false) .word_lang("for download"));
		}
		?>
		</div>
	</td>
	</tr>
<?}?>

<?if($percentage_prints!=0 and $global_settings["prints"] and $global_settings["prints_users"]){?>
	<tr>
	<td><?=word_lang("Your earnings")?>  &mdash; <?=word_lang("subscription")?></td>
	<td>
		<div class="link_commission">
		<?
		if ($percentage_subscription_type == 0) {
			echo(round($percentage_subscription)."%");
		} else {
			echo(currency(1,false) . float_opt($percentage_subscription,2)." ".currency(2,false) .word_lang("for download"));
		}
		?>
		</div>
	</td>
	</tr>
<?}?>

<?if($percentage_prints!=0 and $global_settings["prints"] and $global_settings["prints_users"]){?>
	<tr>
	<td><?=word_lang("Your earnings")?>  &mdash; <?=word_lang("prints")?></td>
	<td>
		<div class="link_commission">
		<?
		if ($percentage_prints_type == 0) {
			echo(round($percentage_prints)."%");
		} else {
			echo(currency(1,false) . float_opt($percentage_prints,2)." ".currency(2,false) .word_lang("for download"));
		}
		?>
		</div>
	</td>
	</tr>
<?}?>



<?if($global_settings["allow_photo"]){?>
	<tr>
	<td><?=word_lang("upload photo")?></td>
	<td><?if($sphoto){echo(word_lang("yes"));}else{echo(word_lang("no"));}?></td>
	</tr>
<?}?>

<?if($global_settings["allow_video"]){?>
	<tr>
	<td><?=word_lang("upload video")?></td>
	<td><?if($svideo){echo(word_lang("yes"));}else{echo(word_lang("no"));}?></td>
	</tr>
<?}?>

<?if($global_settings["allow_audio"]){?>
	<tr>
	<td><?=word_lang("upload audio")?></td>
	<td><?if($saudio){echo(word_lang("yes"));}else{echo(word_lang("no"));}?></td>
	</tr>
<?}?>


<?if($global_settings["allow_vector"]){?>
	<tr>
	<td><?=word_lang("upload vector")?></td>
	<td><?if($svector){echo(word_lang("yes"));}else{echo(word_lang("no"));}?></td>
	</tr>
<?}?>

<tr>
<td><?=word_lang("create category")?></td>
<td><?if($scategory){echo(word_lang("yes"));}else{echo(word_lang("no"));}?></td>
</tr>



</table>
</div></div></div></div></div></div></div></div>
<br>
<?}?>



<?
if($global_settings["examination"] and $_SESSION["people_exam"]!=1)
{
	if($exam_flag)
	{
		$sql="select status from examinations where user=".(int)$_SESSION["people_id"];
		$rs->open($sql);
		if($rs->eof or $rs->row["status"]==2)
		{
			?>
			<input class='isubmit_orange' type="button" value="<?=word_lang("take an examination")?>" onClick="location.href='examination_take.php'">&nbsp;
			<?
		}
	}
}
?>


<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>