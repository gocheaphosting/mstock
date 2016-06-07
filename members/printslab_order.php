<?$site="printslab";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?include("../inc/header.php");?>




<?include("profile_top.php");?>


<h1><?=word_lang("prints lab")?> &mdash; 

<?
if(isset($_GET["id"]))
{
	$sql="select title from galleries where id=".(int)$_GET["id"]." and user_id=".(int)$_SESSION["people_id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		echo($rs->row["title"]);
	}
	else
	{
		exit();
	}
}
?>
</h1>

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

function change_prints(prints_id,prints_value)
{	
	if(document.getElementById('thesame').checked)
	{
		$(".printsclass").val(prints_value);
		$(".prints_options").css("display","none");
		$(".prints_options_value"+prints_value).css("display","block");
	}
	else
	{
		$(".prints_options"+prints_id).css("display","none");
		$("#prints_options"+prints_id+"_"+prints_value).css("display","block");
	}
}

function change_prints_option(prints_id,prints_value,option_id,option_value,option_value_id,flag_radio)
{	
	if(document.getElementById('thesame').checked)
	{
		$(".product_option"+option_id).val(option_value);
		
		if(flag_radio)
		{
			$(".product_option"+prints_value).removeAttr("checked");
			$(".product_option_value"+option_value_id).attr('checked',true);		
		}
	}
}


</script>


<form method="post" Enctype="multipart/form-data" action="printslab_add_to_cart.php" name="printslabform">

<input type="hidden" name="gallery_id" value="<?=(int)$_GET["id"]?>">


<?
if(isset($_GET["id"]) and (int)$_GET["id"]>0)
{
	$sql="select * from galleries_photos where id_parent=".(int)$_GET["id"]." order by data desc";
	$rs->open($sql);
	if(!$rs->eof)
	{
	?>
	<input type="checkbox" id="thesame" checked> - <?=word_lang("Use the same prints options for the gallery")?>
	
	<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%" style="margin-top:15px">
	<tr>
	<th style="text-align:center"><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.printslabform);" checked></th>
	<th><?=word_lang("preview")?></th>	
	<th><?=word_lang("title")?></th>
	<th><?=word_lang("size")?></th>
	<th style="width:50%"><?=word_lang("prints and products")?></th>

	</tr>
	<?
	$tr=1;
	while(!$rs->eof)
	{
		?>
		<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
		<td style="text-align:center"><input type="checkbox" name="sel<?=$rs->row["id"]?>" checked></td>
		<td><div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2"><img src="<?=site_root?>/content/galleries/<?=$rs->row["id_parent"]?>/thumb<?=$rs->row["id"]?>.jpg"></div></div></div></div></div></div></div></div></td>		
		<td><span class="label"><?=$rs->row["title"]?></span></td>
		<td>
		<?
			$img=$_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]."/".$rs->row["photo"];
			if(file_exists($img))
			{
				echo(get_exif($img,true));
			}
		?>
		</td>
		<td>
		<?
					$prints_content="<div class='form_field'><span><b>".word_lang("type").":</b></span><select name='prints".$rs->row["id"]."' class='printsclass' onChange='change_prints(".$rs->row["id"].",this.value)'>";
					
					$sql="select id_parent,title,price,priority,option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where printslab=1 order by priority";
					$dd->open($sql);
					while(!$dd->eof)
					{
						$prints_content.="<option value='".$dd->row["id_parent"]."'>".$dd->row["title"]."</option>";
						$dd->movenext();
					}

					
					$prints_content.="</select></div>";
					
					$flag_active=true;
					
					$sql="select id_parent,title,price,priority,option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where printslab=1 order by priority";
					$dd->open($sql);
					while(!$dd->eof)
					{						
						if($flag_active)
						{
							$style="block";
							$flag_active=false;
						}
						else
						{
							$style="none";
						}
						
						$prints_content.="<div class='prints_options prints_options".$rs->row["id"]." prints_options_value".$dd->row["id_parent"]."' id='prints_options".$rs->row["id"]."_".$dd->row["id_parent"]."' style='display:".$style."'>";
						for($i=1;$i<11;$i++)
						{
							if($dd->row["option".$i]!=0)
							{
								$sql="select title,type,activ,required from products_options where activ=1 and id=".$dd->row["option".$i];
								$dn->open($sql);
								if(!$dn->eof)
								{
									$prints_content.="<div class='ttl' style='margin-top:15px'>".$dn->row["title"].":</div><div>";
			
									if($dn->row["type"]=="selectform")
									{
										$prints_content.="<select name='option".$rs->row["id"]."_".$dd->row["id_parent"]."_".$i."_".$dd->row["option".$i]."' onChange=\"change_prints_option(".$rs->row["id"].",".$dd->row["id_parent"].",".$dd->row["option".$i].",this.value,0,false)\" class='ibox  form-control product_option".$dd->row["option".$i]."' style='width:150px'>";
									}
									
									$flag_checked=true;
			
									$sql="select id,title,price,adjust from products_options_items where id_parent=".$dd->row["option".$i]." order by id";
									$ds->open($sql);
									while(!$ds->eof)
									{
										$sel="";
										$sel2="";
				
										if($flag_checked)
										{
											$sel="selected";
											$sel2="checked";
											$flag_checked=false;
										}
				
										if($dn->row["type"]=="selectform")
										{
											$prints_content.="<option value='".$ds->row["title"]."' ".$sel.">".$ds->row["title"]."</option>";
										}
										if($dn->row["type"]=="radio")
										{
											$prints_content.="<input name='option".$rs->row["id"]."_".$dd->row["id_parent"]."_".$i."_".$dd->row["option".$i]."' onClick=\"change_prints_option(".$rs->row["id"].",".$dd->row["id_parent"].",".$dd->row["option".$i].",'".$ds->row["title"]."',".$ds->row["id"].",true)\" class='product_option".$dd->row["id_parent"]." product_option_value".$ds->row["id"]."' type='radio' value='".$ds->row["title"]."' ".$sel2.">&nbsp;".$ds->row["title"]."&nbsp;&nbsp;";
										}
				
										$ds->movenext();
									}
			
									if($dn->row["type"]=="selectform")
									{
										$prints_content.="</select>";
									}
			
									$prints_content.="</div>";
								}
							}
						}
						$prints_content.="</div>";
						$dd->movenext();
					}
					echo($prints_content);
		?>
		</td>
		
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	<?
	}
}
?>



<div class="form_field">
	<input class='isubmit' value="<?=word_lang("add to cart")?>" name="subm" type="submit">
</div>

</form>






<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>