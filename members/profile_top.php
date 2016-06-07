<?if(!defined("site_root")){exit();}?>

<?
$profile_page="<table border='0' cellpadding='0' cellspacing='0' width='100%'>
	<tr valign='top'>
		<td class='profile_left'>
			<div id='profile_menu_top'></div>
			<div id='profile_menu'>
				{MENU}
			</div>
			<div id='profile_menu_bottom'></div>
		</td>
		<td class='profile_right'>
			{RESULTS}
		</td>
	</tr>
</table>";

if(file_exists($DOCUMENT_ROOT."/".$site_template_url."profile.tpl"))
{
	$profile_page=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."profile.tpl");
}

$profile_page=str_replace("{MENU}","{SEPARATOR}",$profile_page);
$profile_page=str_replace("{RESULTS}","{SEPARATOR}",$profile_page);

$profile_parts=explode("{SEPARATOR}",$profile_page);

$profile_header=$profile_parts[0];
$profile_middle=@$profile_parts[1];
$profile_footer=@$profile_parts[2];

echo($profile_header);


$sql="select photo,name,lastname from users where id_parent=".(int)$_SESSION["people_id"];
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["photo"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"]))
	{
		$photo=$rs->row["photo"];
	}
	else
	{
		$photo=site_root."/images/photo.gif";
	}
	?>
	<div id="profile_photo">
	<a href="profile_home.php"><img src="<?=$photo?>" width="50"></a><a href="profile_home.php"><b><?=$rs->row["name"]?> <?=$rs->row["lastname"]?></b></a>
	<span><a href="profile_about.php"><?=word_lang("edit")?></a></span>
	</div>
	<?
}
?>







<?if($_SESSION["people_type"]=="buyer" or $_SESSION["people_type"]=="common"){?>
	<ul>
		<?if(!$global_settings["printsonly"]){?>
			<li  id="icons_downloads" <?if($site=="profile_downloads"){echo("class='activno'");}?>><a href="profile_downloads.php"><?=word_lang("my downloads")?></a></li>
		<?}?>
	
		<?if(!$global_settings["subscription_only"]){?>
			<li  id="icons_orders" <?if($site=="orders"){echo("class='activno'");}?>><a href="orders.php"><?=word_lang("orders")?></a></li>
		<?}?>
	
		<?if($global_settings["credits"] and !$global_settings["subscription_only"]){?>
			<li  id="icons_credits" <?if($site=="credits"){echo("class='activno'");}?>><a href="credits.php"><?=word_lang("credits")?></a></li>
				<?if($site=="credits"){?>
					<ul>
						<li <?if(isset($_GET["d"]) and $_GET["d"]==2){?>class="activno"<?}?>><a href="credits.php?d=2"><?=word_lang("balance")?></a></li>																																																															
						<li <?if(isset($_GET["d"]) and $_GET["d"]==1){?>class="activno"<?}?>><a href="credits.php?d=1"><?=word_lang("buy credits")?></a></li>
					</ul>
				<?}?>
		<?}?>
	
		<?if($global_settings["subscription"]){?>
			<li  id="icons_subscription" <?if($site=="subscription"){echo("class='activno'");}?>><a href="subscription.php"><?=word_lang("subscription")?></a></li>
		<?}?>
	
		<li id="icons_coupons" <?if($site=="coupons"){echo("class='activno'");}?>><a href="coupons.php"><?=word_lang("coupons")?></a></li>
	
		<?if($global_settings["prints_lab"]){?>
			<li  id="icons_publications" <?if($site=="printslab"){echo("class='activno'");}?>><a href="printslab.php"><?=word_lang("prints lab")?></a></li>
		<?}?>
	</ul>
<?}?>












<?if(($_SESSION["people_type"]=="seller" or $_SESSION["people_type"]=="common") and $global_settings["userupload"]==1){?>


<?
//Check photographer's rights/limits
$scategory=false;
$sphoto=false;
$svideo=false;
$saudio=false;
$svector=false;
$lvideo=10;
$lpreview=3;
$laudio=10;
$lpreviewaudio=3;
$lphoto=5;
$lvector=10;
$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["category"]==1){$scategory=true;}
	if($rs->row["upload"]==1){$sphoto=true;}
	if($rs->row["upload2"]==1){$svideo=true;}
	if($rs->row["upload3"]==1){$saudio=true;}
	if($rs->row["upload4"]==1){$svector=true;}

	$lvideo=$rs->row["videolimit"];
	$lpreview=$rs->row["previewvideolimit"];
	$lpreviewvideo=$lpreview;
	$laudio=$rs->row["audiolimit"];
	$lpreviewaudio=$rs->row["previewaudiolimit"];
	$lphoto=$rs->row["photolimit"];
	$lvector=$rs->row["vectorlimit"];
}
?>

<div class="profile_separator"></div>
	<ul>
		<li  id="icons_upload" <?if($site=="upload"){?>class="activno"<?}?>><a href="upload.php"><span><?=word_lang("upload files")?></span></a></li>
			<ul style="<?if($site=="upload" or $site=="upload_java" or $site=="upload_flash" or $site=="upload_jquery" or $site=="upload_plupload" or $site=="upload_video" or $site=="upload_audio" or $site=="upload_vector" or $site=="upload_category"){echo("display:block");}else{echo("display:none");}?>">
			
				<?if($sphoto==true and $global_settings["jquery_uploader"] and $global_settings["allow_photo"]){?>
					<li <?if($site=="upload_jquery"){echo("class='activno'");}?>><a href="filemanager_photo_jquery.php"><?=word_lang("simple photo uploader")?></a></li>
				<?}?>
				
				<?if($sphoto==true and $global_settings["plupload_uploader"] and $global_settings["allow_photo"]){?>
					<li <?if($site=="upload_plupload"){echo("class='activno'");}?>><a href="filemanager_photo_plupload.php"><?=word_lang("plupload photo uploader")?></a></li>
				<?}?>
			
				<?if($sphoto==true and $global_settings["java_uploader"] and $global_settings["allow_photo"]){?>
					<li <?if($site=="upload_java"){echo("class='activno'");}?>><a href="filemanager_photo_java.php"><?=word_lang("java photo uploader")?></a></li>
				<?}?>
					
				<?if($sphoto==true and $global_settings["flash_uploader"] and $global_settings["allow_photo"]){?>
					<li <?if($site=="upload_flash"){echo("class='activno'");}?>><a href="filemanager_photo_flash.php"><?=word_lang("flash photo uploader")?></a></li>
				<?}?>
				

				
				<?if($svideo==true and $global_settings["allow_video"]){?>
					<li <?if($site=="upload_video"){echo("class='activno'");}?>><a href="filemanager_video.php"><?=word_lang("upload video")?></a></li>
				<?}?>

				<?if($saudio==true and $global_settings["allow_audio"]){?>
					<li <?if($site=="upload_audio"){echo("class='activno'");}?>><a href="filemanager_audio.php"><?=word_lang("upload audio")?></a></li>
				<?}?>

				<?if($svector==true and $global_settings["allow_vector"]){?>
					<li <?if($site=="upload_vector"){echo("class='activno'");}?>><a href="filemanager_vector.php"><?=word_lang("upload vector")?></a></li>
				<?}?>

				<?if($scategory==true){?>
					<?if($global_settings["examination"] and $_SESSION["people_exam"]!=1){}else{?>
						<li <?if($site=="upload_category"){echo("class='activno'");}?>><a href="filemanager_category.php?d=1"><?=word_lang("create category")?></a></li>
				<?}}?>
			</ul>
		<li  id="icons_publications" <?if($site=="publications"){?>class="activno"<?}?>><a href="publications.php"><span><?=word_lang("my publications")?></span></a></li>
		
<ul style="<?if($site=="publications"){echo("display:block");}else{echo("display:none");}?>">
				<?if($sphoto==true and $global_settings["allow_photo"]){?>
					<li <?if(isset($_GET["d"]) and $_GET["d"]==2){echo("class='activno'");}?>><a href="publications.php?d=2"><?=word_lang("photos")?></a></li>
				<?}?>
			
				
				<?if($svideo==true and $global_settings["allow_video"]){?>
					<li <?if(isset($_GET["d"]) and $_GET["d"]==3){echo("class='activno'");}?>><a href="publications.php?d=3"><?=word_lang("videos")?></a></li>
				<?}?>

				<?if($saudio==true and $global_settings["allow_audio"]){?>
					<li <?if(isset($_GET["d"]) and $_GET["d"]==4){echo("class='activno'");}?>><a href="publications.php?d=4"><?=word_lang("audio")?></a></li>
				<?}?>

				<?if($svector==true and $global_settings["allow_vector"]){?>
					<li <?if(isset($_GET["d"]) and $_GET["d"]==5){echo("class='activno'");}?>><a href="publications.php?d=5"><?=word_lang("vector")?></a></li>
				<?}?>

				<?if($scategory==true){?>
					<?if($global_settings["examination"] and $_SESSION["people_exam"]!=1){}else{?>
						<li <?if(isset($_GET["d"]) and $_GET["d"]==1){echo("class='activno'");}?>><a href="publications.php?d=1"><?=word_lang("categories")?></a></li>
				<?}}?>
			</ul>
		
		<?if($global_settings["model"]){?>
			<li id="icons_models" <?if($site=="models"){?>class="activno"<?}?>><a href="models.php"><span><?=word_lang("models")?></span></a></li>
		<?}?>
		<li id="icons_commission" <?if($site=="commission"){echo("class='activno'");}?>><a href="commission.php"><?=word_lang("my commission")?></a></li>
		<ul style="<?if($site=="commission"){echo("display:block");}else{echo("display:none");}?>">
			<li <?if(isset($d) and $d==1){echo("class='activno'");}?>><a href="commission.php?d=1"><?=word_lang("balance")?></a></li>
			<li <?if(isset($d) and $d==2){echo("class='activno'");}?>><a href="commission.php?d=2"><?=word_lang("earning")?></a></li>
			<li <?if(isset($d) and $d==3){echo("class='activno'");}?>><a href="commission.php?d=3"><?=word_lang("refund")?></a></li>
			<li <?if(isset($d) and $d==4){echo("class='activno'");}?>><a href="commission.php?d=4"><?=word_lang("settings")?></a></li>
		</ul>
	</ul>
<?}?>




<?if(($_SESSION["people_type"]=="affiliate" or $_SESSION["people_type"]=="common") and $global_settings["affiliates"]==1){?>
<div class="profile_separator"></div>
	<ul>
		<li  id="icons_partner" <?if($site=="affiliate"){echo("class='activno'");}?>><a href="affiliate.php?d=1"><?=word_lang("affiliate")?></a></li>
			<ul style="<?if($site=="affiliate"){echo("display:block");}else{echo("display:none");}?>">
				<li <?if(isset($d) and $d==1){echo("class='activno'");}?>><a href="affiliate.php?d=1"><?=word_lang("balance")?></a></li>
				<li <?if(isset($d) and $d==2){echo("class='activno'");}?>><a href="affiliate.php?d=2"><?=word_lang("earning")?></a></li>
				<li <?if(isset($d) and $d==3){echo("class='activno'");}?>><a href="affiliate.php?d=3"><?=word_lang("refund")?></a></li>
				<li <?if(isset($d) and $d==4){echo("class='activno'");}?>><a href="affiliate.php?d=4"><?=word_lang("settings")?></a></li>
			</ul>
	</ul>
<?}?>



<div class="profile_separator"></div>

<ul>
<li id="icons_lightbox"><a href="lightbox.php"><?=word_lang("my favorite list")?></a></li>

<?if($global_settings["support"]){

$new_support="";
$support_qty=0;
$sql="select id from support_tickets where user_id='".(int)$_SESSION["people_id"]."' and id_parent=0";
$rs->open($sql);
if(!$rs->eof)
{
	while(!$rs->eof)
	{
		$sql="select count(id) as count_id from support_tickets where id_parent=".$rs->row["id"]." and viewed_user=0";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$support_qty+=$ds->row["count_id"];
		}
		
		$rs->movenext();
	}
	
	if($support_qty!=0)
	{
		$new_support="&nbsp;&nbsp;<span class='badge badge-important'>".$support_qty."</span>";
	}
}
?>
	<li id="icons_comments" <?if($site=="support"){echo("class='activno'");}?>><a href="support.php"><?=word_lang("support")?></a>  <?if($site!="support"){echo($new_support);}?></li>
<?}?>

<?if($global_settings["friends"]){?>
	<li id="icons_friends" <?if($site=="friends"){echo("class='activno'");}?>><a href="friends.php"><?=word_lang("friends")?></a></li>
<?}?>

<?
if($global_settings["messages"]){

$new_message="";
$sql="select touser,trash,viewed,del from messages where touser='".result($_SESSION["people_login"])."' and trash=0 and viewed=0 and del=0";
$rs->open($sql);
if($rs->rc>0){$new_message="&nbsp;&nbsp;<span class='badge badge-important'>".$rs->rc."</span>";}


?>
<li id="icons_messages" <?if($site=="messages"){echo("class='activno'");}?>><a href="messages.php"><?=word_lang("messages")?></a> <?if($site!="messages"){echo($new_message);}?></li>

<ul style="<?if($site=="messages"){echo("display:block");}else{echo("display:none");}?>">





<li <?if(isset($type) and $type=="inbox"){?>class="activno"<?}?>><a href="messages.php"><?=word_lang("inbox")?></a>
</li>

<li <?if(isset($type) and $type=="sent"){?>class="activno"<?}?>><a href="messages_sent.php"><?=word_lang("sentbox")?></a> 
</li>

<li <?if(isset($type) and $type=="trash"){?>class="activno"<?}?>><a href="messages_trash.php"><?=word_lang("trash")?></a> 
</li>







</ul>


<?}?>
<?if($global_settings["blog"]){?>
<li id="icons_blog" <?if($site=="blog"){echo("class='activno'");}?>><a href="blog.php"><?=word_lang("blog")?></a></li>



<ul style="<?if($site=="blog"){echo("display:block");}else{echo("display:none");}?>">
<li <?if(isset($type) and $type=="new post"){?>class="activno"<?}?>><a href="blog.php" ><?=word_lang("new post")?></a></li>
<li <?if(isset($type) and $type=="posts"){?>class="activno"<?}?>><a href="blog_posts.php"><?=word_lang("posts")?></a></li>

<li <?if(isset($type) and $type=="comments"){?>class="activno"<?}?>><a href="blog_comments.php"><?=word_lang("comments")?></a></li>

<li <?if(isset($type) and $type=="categories"){?>class="activno"<?}?>><a href="blog_categories.php"><?=word_lang("categories")?></a></li>
</ul>



<?}?>
<?if($global_settings["reviews"]){?>
<li id="icons_comments" <?if($site=="reviews"){echo("class='activno'");}?>><a href="reviews.php"><?=word_lang("reviews")?></a></li>

<ul style="<?if($site=="reviews"){echo("display:block");}else{echo("display:none");}?>">
<li <?if(isset($type) and $type=="mine"){?>class="activno"<?}?>><a href="reviews.php" ><?=word_lang("mine")?></a></li>
<li <?if(isset($type) and $type=="for me"){?>class="activno"<?}?>><a href="reviews_for_me.php"><?=word_lang("for me")?></a></li>
</ul>
<?}?>
<?if($global_settings["testimonials"]){?>
<li  id="icons_testimonials" <?if($site=="testimonials"){echo("class='activno'");}?>><a href="testimonials.php"><?=word_lang("testimonials")?></a></li>


<ul style="<?if($site=="testimonials"){echo("display:block");}else{echo("display:none");}?>">
<li <?if(isset($type) and $type=="mine"){?>class="activno"<?}?>><a href="testimonials.php" ><?=word_lang("mine")?></a></li>
<li <?if(isset($type) and $type=="for me"){?>class="activno"<?}?>><a href="testimonials_for_me.php"><?=word_lang("for me")?></a></li>
</ul>



<?}?>
<li id="icons_preview"><a href="<?=site_root?>/users/<?=user_url($_SESSION["people_login"])?>.html"><?=word_lang("preview")?></a></li>
</ul>

<?
echo($profile_middle);
?>

