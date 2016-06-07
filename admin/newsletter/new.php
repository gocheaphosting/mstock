<?
//Check access
admin_panel_access("users_newsletter");

if(!defined("site_root")){exit();}
?>





<script type='text/javascript' src='../plugins/tiny_mce/tiny_mce.js'></script>

<script>

tinyMCE.init({
		// General options
		mode : "exact",
		elements : "message_html",
		theme : 'advanced',
		plugins : 'autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks',
		document_base_url : '<?=surl?><?=site_root?>/',
		convert_urls : false,
		relative_urls : false,
		// Theme options
		theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect',
		theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor',
		theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen',
		theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		theme_advanced_statusbar_location : 'bottom',
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : '../plugins/tiny_mce/css/content.css',

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : '../plugins/tiny_mce/lists/template_list.js',
		external_link_list_url : '../plugins/tiny_mce/lists/link_list.js',
		external_image_list_url : '../plugins/tiny_mce/lists/image_list.js',
		media_external_list_url : '../plugins/tiny_mce/lists/media_list.js',

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'}
		],


	});

function open_content(param)
{
	if(param==0)
	{
		document.getElementById('content_text').style.display='block';
		document.getElementById('content_html').style.display='none';
	}
	else
	{
		document.getElementById('content_html').style.display='block';
		document.getElementById('content_text').style.display='none';	
	}
}


</script>



<?
$users_fields=array();
$user_fields["to"]="admin";
$user_fields["types"]="message";
$user_fields["subject"]="";
$user_fields["html"]=0;
$user_fields["message_text"]="";
$user_fields["message_html"]="";

if(isset($_GET["id"]) and (int)$_GET["id"]!=0)
{
	$sql="select touser,types,subject,content,html from newsletter where id=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$user_fields["to"]=$rs->row["touser"];
		$user_fields["types"]=$rs->row["types"];;
		$user_fields["subject"]=$rs->row["subject"];
		$user_fields["html"]=$rs->row["html"];
		$user_fields["message_text"]=strip_tags($rs->row["content"]);
		$user_fields["message_html"]=$rs->row["content"];
	}
}
?>





<form method=post Enctype="multipart/form-data" name="uploadform" action="send.php">



<div class="form_field">
<span><b><?=word_lang("to")?></b></span>
<select name="to"  style="width:550px" >
<option value="admin" <?if($user_fields["to"]=="admin"){echo("selected");}?>>Admin email for testing (only emails)</option>
<option value="newsletter" <?if($user_fields["to"]=="newsletter"){echo("selected");}?>>Users with approved newsletter</option>
<option value="buyer_newsletter" <?if($user_fields["to"]=="buyer_newsletter"){echo("selected");}?>>Buyers with approved newsletter</option>
<option value="seller_newsletter" <?if($user_fields["to"]=="seller_newsletter"){echo("selected");}?>>Sellers with approved newsletter</option>
<option value="affiliate_newsletter" <?if($user_fields["to"]=="affiliate_newsletter"){echo("selected");}?>>Affiliates with approved newsletter</option>
<option value="common_newsletter" <?if($user_fields["to"]=="common_newsletter"){echo("selected");}?>>Common users with approved newsletter</option>
<option value="marketing" <?if($user_fields["to"]=="marketing"){echo("selected");}?>>Marketing emails (only emails)</option>
<option value="all" <?if($user_fields["to"]=="all"){echo("selected");}?>>All users</option>
</select>
</div>

<div class="form_field">
<span><b><?=word_lang("type")?></b></span>
<select name="types"  style="width:550px">
<option value="message" <?if($user_fields["types"]=="message"){echo("selected");}?>>Site messages</option>
<option value="email" <?if($user_fields["types"]=="email"){echo("selected");}?>>Emails</option>
<option value="all" <?if($user_fields["types"]=="all"){echo("selected");}?>>Site messages & Emails (Only when the message is in text format)</option>
</select>
</div>

<div class="form_field">
<span><b><?=word_lang("subject")?></b></span>
<input name="subject" type="text" style="width:550px" value="<?=$user_fields["subject"]?>">
</div>

<div class="form_field">
	<span><b><?=word_lang("type")?>:</b></span>
	<input type="radio" name="html" value="0" checked onClick="open_content(0);" <?if($user_fields["html"]==0){echo("checked");}?>>&nbsp;Text&nbsp;&nbsp;&nbsp;
	<input type="radio" name="html" value="1" onClick="open_content(1);" <?if($user_fields["html"]==1){echo("checked");}?>>&nbsp;HTML
</div>

<div class="form_field">
	<span><b><?=word_lang("content")?>:</b></span>
	<div id="content_text">
		<textarea name="message_text" id="message_text" style="width:700px;height:400px"><?=$user_fields["message_text"]?></textarea>
	</div>
	<div id="content_html" style='display:none'>
		<textarea name="message_html" id="message_html" style="width:700px;height:400px"><?=$user_fields["message_html"]?></textarea>
	</div>
</div>


<script>
function add_condition(value)
{
	document.getElementById('condition').value=value;
}
</script>
<input type="hidden" id="condition" name="condition" value="0">

	<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">
			<input type="submit" value="<?=word_lang("send")?>" class="btn btn-primary" style="margin-top:20px" onClick="add_condition(0);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="<?=word_lang("save as draft")?>" class="btn btn-success" style="margin-top:20px" onClick="add_condition(1);">
		</div>
	</div>

<div id="actions"></div>
</form>

	<script>
	open_content(<?=$user_fields["html"]?>);
	</script>