<?$site="profile_about";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>









<?include("profile_top.php");?>

<h1><?=word_lang("my profile")?></h1>



<div class="subheader"><?=word_lang("settings")?></b></div>

<div class="subheader_text">


<?
$sql="select id_parent,login,name,email,telephone,address,data1,ip, accessdenied,country,category,lastname,city,state,zipcode,avatar,photo,description,website,utype,company,newsletter,paypal,moneybookers,examination,passport ,authorization,aff_commission_buyer,aff_commission_seller,aff_visits ,aff_signups,aff_referal,business,vat from users where id_parent=".(int)$_SESSION["people_id"];
$rs->open($sql);
if(!$rs->eof)
{
	$user_fields["login"]=$rs->row["login"];
	$user_fields["name"]=$rs->row["name"];
	$user_fields["country"]=$rs->row["country"];
	$user_fields["telephone"]=$rs->row["telephone"];
	$user_fields["address"]=$rs->row["address"];
	$user_fields["email"]=$rs->row["email"];
	$user_fields["lastname"]=$rs->row["lastname"];
	$user_fields["city"]=$rs->row["city"];
	$user_fields["state"]=$rs->row["state"];
	$user_fields["zipcode"]=$rs->row["zipcode"];
	$user_fields["description"]=$rs->row["description"];
	$user_fields["website"]=$rs->row["website"];
	$user_fields["utype"]=$rs->row["utype"];
	$user_fields["company"]=$rs->row["company"];
	$user_fields["newsletter"]=$rs->row["newsletter"];
	$user_fields["business"]=$rs->row["business"];
	$user_fields["vat"]=$rs->row["vat"];
}



$ss="modify";



include("signup_content.php");

?>
</div>



<?
$com="";
if($_SESSION["people_type"]=="buyer" or $_SESSION["people_type"]=="common")  
{
	$com=" and buyer=1 ";
}
if($_SESSION["people_type"]=="seller" or $_SESSION["people_type"]=="common")  
{
	$com=" and seller=1 ";
}
if($_SESSION["people_type"]=="affiliate" or $_SESSION["people_type"]=="common")  
{
	$com=" and affiliate=1 ";
}

$sql="select id,title,description,filesize from documents_types where enabled=1 ".$com." order by priority";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div class="subheader"><?=word_lang("Documents")?></b></div>
	<div class="subheader_text">
	
	<a name="documents"></a>
	<form method=post Enctype="multipart/form-data" action="profile_document_upload.php">
	<div  class="form_field">
		<span><b><?=word_lang("type")?></b>:</span>
		<select name="document_type" class="ibox form-control" style="width:400px;">
			<?
			while(!$rs->eof)
			{
				?>
				<option value="<?=$rs->row["id"]?>"><?=$rs->row["title"]?> (< <?=$rs->row["filesize"]?>MB.)</option>
				<?
				$rs->movenext();
			}
			?>
		</select>
	</div>
	
	<div  class="form_field">	 
		<input type="file" name="document_file" style="width:400px">
		<span><small><?=word_lang("file types")?>:</b> *.jpg,*.pdf.</small></span>
	</div>
	
	<div  class="form_field">
		<input class='isubmit' type="submit" value="<?=word_lang("upload")?>">
	</div>

	</form>
	<?
	$sql="select id,title,status,comment,filename,data from documents where user_id=".(int)$_SESSION["people_id"]." order by data desc";
	$dr->open($sql);
	
	if(!$dr->eof)
	{
		?><br>
		<table border='0' cellpadding='0' cellspacing='0' style="margin-bottom:20px" class="profile_table" width="100%">
		<tr>
		<th><?=word_lang("date")?></th>
		<th><?=word_lang("Documents")?></th>
		<th class='hidden-phone hidden-tablet'><?=word_lang("file")?></th>
		<th class='hidden-phone hidden-tablet'><?=word_lang("size")?></th>
		<th><?=word_lang("status")?></th>
		</tr>
		<?
		
		while(!$dr->eof)
		{
			$size=filesize($_SERVER["DOCUMENT_ROOT"].site_root."/content/users/doc_".$dr->row["id"]."_".$dr->row["filename"]);
			?>
			<tr>
				<td><div class="link_date"><?=date(date_format,$dr->row["data"])?></div></td>
				<td><b><?=$dr->row["title"]?></b></td>
				<td><?=$dr->row["filename"]?></td>
				<td><?=float_opt($size/(1024*1024),3)." Mb."?></td>
				<td>
				<?
				if($dr->row["status"]==1)
				{
					?>
					<span class="label label-success"><?=word_lang("approved")?></span>
					<?
				}
				if($dr->row["status"]==0)
				{
					?>
					<span class="label label-warning"><?=word_lang("pending")?></span>
					<?
				}
				if($dr->row["status"]==-1)
				{
					?>
					<span class="label label-danger"><?=word_lang("declined")?></span>
					<?
					if($dr->row["comment"]!="")
					{
						?>
						<br><small><?=$dr->row["comment"]?></small>
						<?
					}
				}
				?>
				</td>
			</tr>
			<?
			$dr->movenext();
		}
		?>
		</table>
		<?
	}
	?>
	</div>
	<?
}
?>



<div class="subheader"><?=word_lang("photo")?></b></div>
	<div class="subheader_text">

<?
$sql="select login,avatar,photo from users where login='".result($_SESSION["people_login"])."'";
$rs->open($sql);
if(!$rs->eof)
{
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr valign="top">
<td width="50%"><p><b><?=word_lang("avatar")?>:</b></p>



<table border="0" cellpadding="0" cellspacing="0">
<tr valign="top">

<?if($rs->row["avatar"]!=""){?>
<td style="padding-right:10px">
<?if(file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["avatar"])){?>
<div style="margin-bottom:5px"><img src="<?=$rs->row["avatar"]?>" width="<?=$global_settings["avatarwidth"]?>"></div>
<?}?>
<div><a href="profile_photo_delete.php?type=avatar"><?=word_lang("delete")?></a></div>
</td>
<?}?>


<td>
<form method=post Enctype="multipart/form-data" action="profile_photo_upload.php?type=avatar">
<div style="margin-top:5px"><input type=file name="avatar" style="width:200px"></div>
<div class="smalltext"><small>*.jpg,*.jpeg,*.gif,*.png < 50Kb.</small></div>
<div style="margin-top:5px"><input class='isubmit' type="submit" value="<?=word_lang("upload")?>"></div>
</form>
</td>
</tr>
</table>












</td>

<td width="50%" style="padding-left:10px"><p><b><?=word_lang("photo")?>:</b></p>









<table border="0" cellpadding="0" cellspacing="0">
<tr valign="top">

<?if($rs->row["photo"]!=""){?>
<td style="padding-right:10px">
<?if(file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"])){?>
<div style="margin-bottom:5px"><img src="<?=$rs->row["photo"]?>" width="<?=$global_settings["userphotowidth"]?>"></div>
<?}?>
<div><a href="profile_photo_delete.php?type=photo"><?=word_lang("delete")?></a></div>
</td>
<?}?>


<td>
<form method=post Enctype="multipart/form-data" action="profile_photo_upload.php?type=photo">

<div style="margin-top:5px"><input type=file name="photo" style="width:200px"></div>
<div class="smalltext"><small>*.jpg,*.jpeg,*.gif,*.png < 200Kb.</small></div>
<div style="margin-top:5px"><input class='isubmit' type="submit" value="<?=word_lang("upload")?>"></div>
</form>
</td>
</tr>
</table>











</td>
</tr>
</table>
<?
}





?>
</div>



<?include("profile_bottom.php");?>























<?include("../inc/footer.php");?>