<?$site="models";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>


<h1><?=word_lang("models")?></h1>


<script language="javascript">


function anketa(name,pole,nado)
{
this.name=name;
this.pole=pole;
this.nado=nado;
}


ms=new Array(new anketa('<?=word_lang("title")?>','title',true),new anketa('<?=word_lang("description")?>','description',true))



function check()
{
	with(document.uploadform)
	{
		flag=true
		mat="<?=word_lang("please insert")?>: "
		mat2=""
		for(i=0;i<ms.length;i++)
		{
			dd=eval(ms[i].pole+".value");
			if(ms[i].nado==true)
			{
				if(dd=="")
				{
					flag=false;
					mat+="\""+ms[i].name+"\","
				}
			}
	}

	if(flag==false)
	{
		mat=mat.substring(0,mat.length-1)+"."
		if(mat!="<?=word_lang("please insert")?>:.")
		{
			alert(mat+mat2)
		}
		else
		{
			alert(mat2)}
			return false
		}
	}
}

</script>




<?
$sql="select * from models where user='".result($_SESSION["people_login"])."' and id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
?>

<form method="post" Enctype="multipart/form-data" action="models_edit.php?id=<?=$rs->row["id_parent"]?>" name="uploadform" onSubmit="return check();">


<div class="form_field">
	<span><b><?=word_lang("title")?>:</b></span>
	<input class='ibox form-control' name="title" value="<?=$rs->row["name"]?>" type="text" style="width:300px">
</div>


<div class="form_field">
	<span><b><?=word_lang("description")?>:</b></span>
	<textarea name="description"  class='ibox form-control' style="width:400px;height:200px"><?=$rs->row["description"]?>
	</textarea>
</div>


<div class="form_field">
	<span><b><?=word_lang("photo")?>:</b></span>
	<input name="modelphoto" type="file" style="width:300px" class='ibox form-control'> 

	<?if($rs->row["modelphoto"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["modelphoto"])){?>
		&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=$rs->row["modelphoto"]?>"><?=word_lang("preview")?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="models_file_delete.php?id=<?=$rs->row["id_parent"]?>&type=modelphoto"><?=word_lang("delete")?></a>
	<?}?>
	<span class="smalltext">(*.jpg. <?=word_lang("size")?> < 1Mb.)</span>
</div>


<div class="form_field">
	<span><b><?=word_lang("model property release")?>:</b></span>
	<input name="model" type="file" style="width:300px" class='ibox form-control'>
	<?if($rs->row["model"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["model"])){?>
		&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=$rs->row["model"]?>"><?=word_lang("preview")?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="models_file_delete.php?id=<?=$rs->row["id_parent"]?>&type=model"><?=word_lang("delete")?></a>
	<?}?>
	<span class="smalltext">(*.zip or *.pdf or *.jpg.  <?=word_lang("size")?> < 5Mb.)</span>
</div>


<div class="form_field">
	<input class='isubmit' value="<?=word_lang("change")?>" name="subm" type="submit">
</div>

</form>

<?}?>





<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>