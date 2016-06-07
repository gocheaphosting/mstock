<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");

$sql="select folder from templates_admin where activ=1";
$rs->open($sql);
$admin_template=$rs->row["folder"];
?>
<!DOCTYPE html>
<html>
<head>
<title>PhotoStoreScript.com - Content Management System</title>
<meta HTTP-EQUIV="content-type" CONTENT="text/html;charset=<?=$mtg?>">


<?if($admin_template=="original"){?>
<link rel="stylesheet" type="text/css" href="<?=site_root?>/inc/bootstrap/css/bootstrap.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="<?=site_root?>/inc/bootstrap/css/bootstrap-responsive.css">
<link href="../images/favicon.gif" type="image/gif" rel="icon">
<link href="../images/favicon.gif" type="image/gif" rel="shortcut icon">
<link rel=stylesheet type="text/css" href="<?=site_root?>/admin/inc/style.css">
<script type="text/javascript" src="<?=site_root?>/inc/audio-player.js"></script>
<script type="text/javascript" src="<?=site_root?>/members/swfobject.js"></script>
<script type="text/javascript" src="<?=site_root?>/inc/jquery-1.7.2.min.js"></script>
<script src="<?=site_root?>/inc/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=site_root?>/inc/jquery.lightbox-0.5.js"></script>
<script src="<?=site_root?>/inc/jquery.colorbox.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=site_root?>/admin/inc/scripts.js"></script>
<script language="javascript" src="<?=site_root?>/inc/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
      <script src="<?=site_root?>/inc/bootstrap/js/html5shiv.js"></script>
<![endif]-->

<?
}
else
{
?>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <link href="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    
    <link href="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/css/style.css" rel="stylesheet" type="text/css" />
    <link href="../images/favicon.gif" type="image/gif" rel="icon">
	<link href="../images/favicon.gif" type="image/gif" rel="shortcut icon">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
     <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/js/app.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/plugins/chartjs/Chart.min.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/js/demo.js" type="text/javascript"></script>
    <script src="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/js/scripts.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?=site_root?>/members/swfobject.js"></script>
	<script type="text/javascript" src="<?=site_root?>/inc/jquery.lightbox-0.5.js"></script>
	<script type="text/javascript" src="<?=site_root?>/inc/scripts.js"></script>
	<script src="<?=site_root?>/inc/jquery.colorbox-min.js" type="text/javascript"></script>
<?
}
?>


</head>
<body>




<?
include("rights_managed_functions.php");



if($_GET["events"]=="step_add")
{
	?>
		<div class="modal_header"><?=word_lang("add step")?></div>
		<form action="step_add.php?id=<?=$_GET["id"]?>" method="post">
		<div class='admin_field'>
			<span><?=word_lang("title")?>:</span>
			<input type="text" name="title" value="" style="width:300px" class="form-control">
		</div>
		<div class='admin_field'>
			<input type="submit" class="btn btn-primary" value="<?=word_lang("add")?>">
		</div>
		</form>
	<?
}

if($_GET["events"]=="step_edit")
{
	$sql="select title from rights_managed_structure where types=0 and id=".(int)$_GET["id_element"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>
		<div class="modal_header"><?=word_lang("edit")?></div>
		<form action="step_edit.php?id=<?=$_GET["id"]?>&id_element=<?=$_GET["id_element"]?>" method="post">
		<div class='admin_field'>
			<span><?=word_lang("title")?>:</span>
			<input type="text" name="title" value="<?=$rs->row["title"]?>" style="width:300px" class="form-control">
		</div>
		<div class='admin_field'>
			<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>">
		</div>
		</form>
		<?
	}
}


if($_GET["events"]=="step_delete")
{
	$sql="select title from rights_managed_structure where types=0 and id=".(int)$_GET["id_element"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>
		<div class="modal_header"><?=word_lang("delete")?></div>
		<form action="step_delete.php?id=<?=$_GET["id"]?>&id_element=<?=$_GET["id_element"]?>" method="post">
		<div class='admin_field'>
			Are you sure that you want to remove <b>"<?=$rs->row["title"]?>" step</b> and all nested elements?
		</div>
		<div class='admin_field'>
			<input type="submit" class="btn btn-danger" value="<?=word_lang("delete")?>">
		</div>
		</form>
		<?
	}
}


if($_GET["events"]=="group_add")
{
	?>
		<div class="modal_header"><?=word_lang("add group")?></div>
		<form action="group_add.php?id=<?=$_GET["id"]?>&step=<?=$_GET["id_element"]?>" method="post">
		<div class='admin_field'>
			<span><?=word_lang("groups")?>:</span>
			<select name="group" style="width:350px" class="form-control">
				<?
				$sql="select id,title from rights_managed_groups order by title";
				$rs->open($sql);
				while(!$rs->eof)
				{
					?>
						<option value="<?=$rs->row["id"]?>"><?=$rs->row["title"]?></option>
					<?
					$rs->movenext();
				}
				?>
			</select>
		</div>
		<div class='admin_field'>
			<input type="submit" class="btn btn-primary" value="<?=word_lang("add")?>">
		</div>
		</form>
	<?
}


if($_GET["events"]=="group_delete")
{
	$sql="select title from rights_managed_structure where types=1 and id=".(int)$_GET["id_element"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>
		<div class="modal_header"><?=word_lang("delete")?></div>
		<form action="step_delete.php?id=<?=$_GET["id"]?>&id_element=<?=$_GET["id_element"]?>" method="post">
		<div class='admin_field'>
			Are you sure that you want to remove <b>"<?=$rs->row["title"]?>" group</b> and all nested elements?
		</div>
		<div class='admin_field'>
			<input type="submit" class="btn btn-danger" value="<?=word_lang("delete")?>">
		</div>
		</form>
		<?
	}
}

if($_GET["events"]=="option_delete")
{
	$sql="select title from rights_managed_structure where types=2 and id=".(int)$_GET["id_element"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		?>
		<div class="modal_header"><?=word_lang("delete")?></div>
		<form action="step_delete.php?id=<?=$_GET["id"]?>&id_element=<?=$_GET["id_element"]?>" method="post">
		<div class='admin_field'>
			Are you sure that you want to remove <b>"<?=$rs->row["title"]?>" option</b> and all nested elements?
		</div>
		<div class='admin_field'>
			<input type="submit" class="btn btn-danger" value="<?=word_lang("delete")?>">
		</div>
		</form>
		<?
	}
}



if($_GET["events"]=="option_edit")
{
	$sql="select title,price,adjust from rights_managed_structure where types=2 and id=".(int)$_GET["id_element"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$options="";
		if($rs->row["adjust"]=="+")
		{
			$options="<option value='+' selected>+</option><option value='-'>-</option><option value='x'>x</option>";
		}
		elseif($rs->row["adjust"]=="-")
		{
			$options="<option value='+'>+</option><option value='-' selected>-</option><option value='x'>x</option>";
		}
		else
		{
			$options="<option value='+'>+</option><option value='-'>-</option><option value='x' selected>x</option>";
		}
		
		?>
		<div class="modal_header"><?=word_lang("edit")?></div>
		<form action="option_edit.php?id=<?=$_GET["id"]?>&id_element=<?=$_GET["id_element"]?>" method="post">
		<div class='admin_field'>
			<span><?=word_lang("title")?>:</span>
			<input type="text" name="title" value="<?=$rs->row["title"]?>" style="width:350px" class="form-control">
		</div>
		<div class='admin_field'>
			<span><?=word_lang("price")?>:</span>
			<select name='adjust' style='width:50px;' class="form-control"><?=$options?></select>&nbsp;<input type="text" name="price" value="<?=float_opt($rs->row["price"],2)?>" style="width:50px" class="form-control">
		</div>
		<div class='admin_field'>
			<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>">
		</div>
		</form>
		<?
	}
}


if($_GET["events"]=="conditions")
{
	$sql="select id,title,conditions,id_parent from rights_managed_structure where types=1 and id=".(int)$_GET["id_element"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$mass_conditions=explode("-",$rs->row["conditions"]);
		?>
		<div class="modal_header"><?=$rs->row["title"]?> &mdash; <?=word_lang("conditions")?></div>
		<form action="conditions_edit.php?id=<?=$_GET["id"]?>&id_element=<?=$_GET["id_element"]?>" method="post">
		
		<div class='admin_field'>
			You can set special conditions when the group is available.<br><br>
			<?
			for($i=0;$i<7;$i++)
			{
			?>
			<span><?if($i>0){echo(" or ");}?>Condition <?=$i+1?>:</span>
			<select style="width:370px;margin-bottom:15px" name="condition<?=$i?>" class="form-control">
				<option value=''></value>
				<?
					$sql="select id,title from rights_managed_groups where id<>".$rs->row["id"]." order by id";
					$ds->open($sql);
					while(!$ds->eof)
					{
						$sel="";
						if(isset($mass_conditions[$i]) and (int)$mass_conditions[$i]==$ds->row["id"])
						{
							$sel="selected";
						}
						?>
							<option value="<?=$ds->row["id"]?>" <?=$sel?>><?=$ds->row["title"]?></option>
						<?
						$sql="select id,title from rights_managed_options where id_parent=".$ds->row["id"]." order by id";
						$dr->open($sql);
						while(!$dr->eof)
						{
							$sel="";
							if(isset($mass_conditions[$i]) and (int)$mass_conditions[$i]==$dr->row["id"])
							{
								$sel="selected";
							}
							?>
								<option value="<?=$dr->row["id"]?>" <?=$sel?>>-&nbsp;&nbsp;<?=$dr->row["title"]?></option>
							<?
							$dr->movenext();
						}
						?>
							<option value=''></value>
						<?
						$ds->movenext();
					}
				?>
			</select>
			<?
			}
			?>
		</div>

		<div class='admin_field'>
			<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>">
		</div>
		</form>
		<?
	}
}




?>


</body>
</html>