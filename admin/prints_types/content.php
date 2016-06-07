<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prints");
?>
<? include("../inc/begin.php");?>


<?
$user_fields=array();
$user_fields["title"]="";
$user_fields["description"]="";
$user_fields["price"]=1;
$user_fields["priority"]=1;
$user_fields["weight"]=0.001;
$user_fields["option1"]=0;
$user_fields["option2"]=0;
$user_fields["option3"]=0;
$user_fields["option4"]=0;
$user_fields["option5"]=0;
$user_fields["option6"]=0;
$user_fields["option7"]=0;
$user_fields["option8"]=0;
$user_fields["option9"]=0;
$user_fields["option10"]=0;
$user_fields["photo"]=1;
$user_fields["printslab"]=1;



if(isset($_GET["id"]))
{
	$sql="select * from prints where id_parent=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$user_fields["title"]=$rs->row["title"];
		$user_fields["description"]=$rs->row["description"];
		$user_fields["price"]=$rs->row["price"];
		$user_fields["priority"]=$rs->row["priority"];
		$user_fields["weight"]=$rs->row["weight"];
		
		$user_fields["option1"]=$rs->row["option1"];
		$user_fields["option2"]=$rs->row["option2"];
		$user_fields["option3"]=$rs->row["option3"];
		$user_fields["option4"]=$rs->row["option4"];
		$user_fields["option5"]=$rs->row["option5"];
		$user_fields["option6"]=$rs->row["option6"];
		$user_fields["option7"]=$rs->row["option7"];
		$user_fields["option8"]=$rs->row["option8"];
		$user_fields["option9"]=$rs->row["option9"];
		$user_fields["option10"]=$rs->row["option10"];
		$user_fields["photo"]=$rs->row["photo"];
		$user_fields["printslab"]=$rs->row["printslab"];
	}
}




?>

<div class="back"><a href="index.php" class="btn btn-primary btn-sm btn-mini"><b><i class="fa fa-arrow-left"></i>&nbsp; <?=word_lang("back")?></a></div>




<h1><?=word_lang("prints and products")?> &mdash; <?
if(!isset($_GET["id"]))
{
	echo(word_lang("add")." ");
}
else
{
	echo(word_lang("edit")." ");
}
?></h1>





<div class="box box_padding">






<form method="post" action="add.php<?if(isset($_GET["id"])){echo("?id=".$_GET["id"]);}?>"  Enctype="multipart/form-data">

<div class="subheader"><?=word_lang("common information")?></div>
<div class="subheader_text">

	<div class='admin_field'>
		<span><?=word_lang("title")?>:</span>
		<input type="text" name="title" value="<?=$user_fields["title"]?>" style="width:350px">
	</div>
	
	<div class='admin_field'>
		<span><?=word_lang("description")?>:</span>
		<textarea name="description" style="width:350px;height:80px"><?=$user_fields["description"]?></textarea>
	</div>
	
	<div class='admin_field'>
		<span><?=word_lang("price")?>:</span>
		<input type="text" name="price" value="<?=$user_fields["price"]?>" style="width:150px">
	</div>	
	
	<div class='admin_field'>
		<span><?=word_lang("weight")?> (<?=$global_settings["weight"]?>):</span>
		<input type="text" name="weight" value="<?=$user_fields["weight"]?>" style="width:150px">
	</div>	
	
	<div class='admin_field'>
		<span><?=word_lang("priority")?>:</span>
		<input type="text" name="priority" value="<?=$user_fields["priority"]?>" style="width:150px">
	</div>	
	
	<div class='admin_field'>
		<span><?=word_lang("photo")?>:</span>
		<input type="checkbox" name="photo" value="1" <?if($user_fields["photo"]==1){echo("checked");}?>>
	</div>	
	
	<div class='admin_field'>
		<span><?=word_lang("prints lab")?>:</span>
		<input type="checkbox" name="printslab" value="1" <?if($user_fields["printslab"]==1){echo("checked");}?>>
	</div>	


</div>
	

<div class="subheader"><?=word_lang("products options")?></div>
<div class="subheader_text">

	<?for($i=1;$i<11;$i++){?>
	<div class='admin_field'>
		<span><?=word_lang("property")?> <?=$i?>:</span>
		<select name="option<?=$i?>" style="width:150px">
			<option value="0"></option>
			<?
				$sql="select id,title from products_options order by title";
				$ds->open($sql);
				while(!$ds->eof)
				{
					$sel="";
					if($user_fields["option".$i]==$ds->row["id"])
					{
						$sel="selected";
					}
					?>
					<option value="<?=$ds->row["id"]?>" <?=$sel?>><?=$ds->row["title"]?></option>
					<?
					$ds->movenext();
				}
			?>
		</select>
	</div>	
	<?}?>
	

	
</div>

<div class="subheader"><?=word_lang("previews")?></div>
<div class="subheader_text">
<?
for($i=1;$i<6;$i++)
{
?>
	<div class='admin_field'>
		<span><?=word_lang("photo")?> <?=$i?>:</span>
		<input type="file" style="width:250px" name="photo<?=$i?>">
		<?
		if(isset($_GET["id"]) and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".(int)$_GET["id"]."_".$i."_big.jpg"))
		{
		?>
			<div style='padding-top:3px'><div id='preview' style='display:inline'><a href="<?=site_root."/content/prints/product".(int)$_GET["id"]."_".$i."_big.jpg"?>"><?=word_lang("preview")?></a></div>&nbsp;&nbsp;&nbsp;
			<a href='delete_photo.php?id=<?=$_GET["id"]?>&type=<?=$i?>'><?=word_lang("delete")?></a></div>
		<?
		}
		?>
	</div>
<?
}
?>	

</div>


<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">
			<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>">
		</div>
	</div>




</form>

</div>


<? include("../inc/end.php");?>