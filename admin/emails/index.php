<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_emails");
?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("e-mail")?></h1>


<script language="javascript">

function show_fields(types)
{
	if(types=="phpmail" || types=="sendmail")
	{
		document.getElementById('field_smtp_user').style.display="none";
		document.getElementById('field_smtp_password').style.display="none";
		document.getElementById('field_smtp_port').style.display="none";
		document.getElementById('field_smtp_server').style.display="none";
										
	}
	if(types=="smtp")
	{
		document.getElementById('field_smtp_user').style.display="block";
		document.getElementById('field_smtp_password').style.display="block";
		document.getElementById('field_smtp_port').style.display="block";
		document.getElementById('field_smtp_server').style.display="block";
	}
}

</script>

<div class="box box_padding">

<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">
	<form method="post" action="change.php">
	<p>The script sends all emails by <b>PHP mail()</b> function by default. If the php mail() is disabled on the server you have to select Sendmail or set SMTP authorization.</p>

	<div class='admin_field'>
	<span><?=word_lang("method")?>:</span>
	<input type="radio" name="mailtype" <?if($global_settings["mailtype"]=="phpmail"){echo("checked");}?> onClick="show_fields('phpmail')" value="phpmail"> PHP Mail()&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="mailtype" <?if($global_settings["mailtype"]=="sendmail"){echo("checked");}?> onClick="show_fields('sendmail')" value="sendmail"> Sendmail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="mailtype" <?if($global_settings["mailtype"]=="smtp"){echo("checked");}?> onClick="show_fields('smtp')" value="smtp"> SMTP
	</div>
	
	<div class='admin_field'>
	<span>Admin email:</span>
	<input type="text" name="admin_email" value="<?=$global_settings["admin_email"]?>" style="width:250px"><br>
	<small>This is an email of the site admin. The administrator will receive all messages to the email.</small>
	</div>
	
	<div class='admin_field'>
	<span>'From' email:</span>
	<input type="text" name="from_email" value="<?=$global_settings["from_email"]?>" style="width:250px"><br>
	<small>The users will receive site's messages from the email. The admin and 'from' emails must be different.</small>
	</div>
	
	<div class='admin_field' id="field_smtp_server">
	<span>SMTP server:</span>
	<input type="text" name="smtp_server" value="<?=$global_settings["smtp_server"]?>" style="width:250px">
	</div>
	
	<div class='admin_field' id="field_smtp_port">
	<span>SMTP port:</span>
	<input type="text" name="smtp_port" value="<?=$global_settings["smtp_port"]?>" style="width:50px">
	</div>
	
	<div class='admin_field' id="field_smtp_user">
	<span>SMTP user:</span>
	<input type="text" name="smtp_user" value="<?=$global_settings["smtp_user"]?>" style="width:250px">
	</div>
	
	<div class='admin_field' id="field_smtp_password">
	<span>SMTP password:</span>
	<input type="text" name="smtp_password" value="<?=$global_settings["smtp_password"]?>" style="width:250px">
	</div>
	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>
	
	<script>
		show_fields('<?=$global_settings["mailtype"]?>');
	</script>
	</form>
</div>



<div class="subheader"><?=word_lang("test")?></div>
<div class="subheader_text">

	<?
		if(isset($_GET["action"]))
		{
			echo("<div class='alert'><button type='button' class='close' data-dismiss='alert'>&times;</button>The script is sending a test message. If you receive the email everything works correct.</b></div>");
			
			$test_text="Hi everyone!";
			$test_subject="Test message from ".$global_settings["site_name"];
			
			$mail = new PHPMailer(true); //New instance, with exceptions enabled

			$body             = $test_text;
			
			if($global_settings["mailtype"]=="smtp")
			{
				$mail->IsSMTP();                           // tell the class to use SMTP
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->Port       = $global_settings["smtp_port"];                    // set the SMTP server port
				$mail->Host       = $global_settings["smtp_server"]; // SMTP server
				$mail->Username   = $global_settings["smtp_user"];     // SMTP server username
				$mail->Password   = $global_settings["smtp_password"];            // SMTP server password
			}

			if($global_settings["mailtype"]=="sendmail")
			{
				$mail->IsSendmail();  // tell the class to use Sendmail
			}

			$mail->From       = $global_settings["from_email"];
			$mail->FromName   = "";

			$to = $global_settings["admin_email"];

			$mail->AddAddress($to);

			$mail->Subject  = $test_subject;

			//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			$mail->WordWrap   = 80; // set word wrap
	
			$mail->MsgHTML($body);

			$mail->IsHTML(false); // send as HTML

			if(!$mail->Send()) {
 			 	echo "Mailer Error: " . $mail->ErrorInfo;
			}
		}
	?>

	<p>We advice you to test the mail function. The message will be sent:</p>
	<ul>
		<li><b>To:</b> <?=$global_settings["admin_email"]?></li>
		<li><b>From:</b> <?=$global_settings["from_email"]?></li>
		<li><b>Subject:</b> Test message from <?=$global_settings["site_name"]?></li>
		<li><b>Body:</b> Hi everyone!</li>
	</ul>
	
	<input  class="btn btn-primary" type="button" value="<?=word_lang("send")?>" onClick="location.href='index.php?action=send'">
</div>



</div>











<? include("../inc/end.php");?>