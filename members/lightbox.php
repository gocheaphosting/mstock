<?$site="lightbox";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>

<input type="button" value="<?=word_lang("add a new lightbox")?>" class="profile_button" onClick="location.href='lightbox_content.php'">
<h1><?=word_lang("my favorite list")?></h1>

<?
$lightbox_list="";
$tr=1;

	$sql="select id_parent,user_owner from lightboxes_admin where user=".(int)$_SESSION["people_id"];
	$rs->open($sql);
	while(!$rs->eof)
	{
		$sql="select title from lightboxes where id=".$rs->row["id_parent"]." order by title";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$count_files=0;
			$sql="select count(id_parent) as count_files from lightboxes_files where id_parent=".$rs->row["id_parent"];
			$dr->open($sql);
			if(!$dr->eof)
			{
				$count_files=$dr->row["count_files"];
			}
			
			$tr_class="";
			if($tr%2==0)
			{
				$tr_class=" class='snd' ";
			}
			
			$link_edit="&mdash;";
			$link_delete="&mdash;";
			if($rs->row["user_owner"]==1)
			{
				$link_edit="<div class='link_edit'><a href='lightbox_content.php?id=".$rs->row["id_parent"]."'>".word_lang("edit")."</a></div>";
				$link_delete="<div class='link_delete'><a href='lightbox_delete.php?id=".$rs->row["id_parent"]."' onClick=\"return confirm('".word_lang("delete")."?');\">".word_lang("delete")."</a></div>";
			}
			
			$lightbox_admin="";
			$sql="select user,user_owner from lightboxes_admin where id_parent=".$rs->row["id_parent"]." order by user_owner desc";
			$dr->open($sql);
			while(!$dr->eof)
			{
				$user_name="";
				$sql="select login from users where id_parent=".$dr->row["user"];
				$dn->open($sql);
				if(!$dn->eof)
				{
					$user_name=show_user_name($dn->row["login"]);
				}
				
				if($lightbox_admin!=""){$lightbox_admin.=", ";}
				
				if($dr->row["user_owner"]==1)
				{
					$lightbox_admin.="<a href='".site_root."/users/".$dr->row["user"].".html'><b>".$user_name."</b></a>";
				}
				else
				{
					$lightbox_admin.="<a href='".site_root."/users/".$dr->row["user"].".html'>".$user_name."</a>";
				}
				
				$dr->movenext();
			}
			
			$lightbox_list.="<tr ".$tr_class."><td><div class='link_lightbox'><a href='".lightbox_url($rs->row["id_parent"],$ds->row["title"])."'>".$ds->row["title"]."</a></div></td><td><a href='".lightbox_url($rs->row["id_parent"],$ds->row["title"])."'>".$count_files."</a></td><td class='hidden-phone hidden-tablet'>".$lightbox_admin."</td><td>".$link_edit."</td><td>".$link_delete."</td></tr>";
		}
		$tr++;
		$rs->movenext();
	}

if($lightbox_list!="")
{
	?>
	<table border='0' cellpadding='0' cellspacing='0' style="margin-bottom:20px" class="profile_table" width="100%">
	<tr>
	<th><?=word_lang("title")?></th>
	<th><?=word_lang("files")?></th>
	<th class='hidden-phone hidden-tablet'><?=word_lang("administrators")?></th>
	<th></th>
	<th></th>
	</tr>
	<?=$lightbox_list?>
	</table>
	<?
}
?>


<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>