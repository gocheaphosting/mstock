<?if(!defined("site_root")){exit();}?>


<?
if(isset($_GET["d"]))
{
	if($_GET["d"]==1)
	{
		echo("<p class='error'>".word_lang("serror1")."</p>");
	}
	if($_GET["d"]==2)
	{
		echo("<p class='error'>".word_lang("serror2")."</p>");
	}
	if($_GET["d"]==3)
	{
		echo("<p class='ok'>".word_lang("serror3")."</p>");
	}	
	if($_GET["d"]==4)
	{
		echo("<p class='error'>Error. Incorrect Captcha.</p>");
	}	
}
?>


<?

$sql="select * from pages where link='signup'";
$rs->open($sql);
if(!$rs->eof)
{
	echo(translate_text($rs->row["content"]));
}



$left_column="";
$right_column="";
$required_fields="";

if($ss=="add")
{
	$required_fields="new anketa('".word_lang("login")."','login',true)";
}

if($ss=="add")
{
	$sql="select title,field_name,required,columns from users_fields where signup=1  group by field_name order by columns,priority";
}
else
{
	$sql="select title,field_name,required,columns from users_fields where profile=1  group by field_name order by columns,priority";
}
$rs->open($sql);
while(!$rs->eof)
{
	$field="";
	
	$bold_begin="";
	$bold_end="";
	
	if($rs->row["required"]==1)
	{
		$bold_begin="<b>";
		$bold_end="</b>";
		
		if($rs->row["field_name"]!="login")
		{
			if($required_fields!=""){$required_fields.=",";}
			
			$required_fields.="new anketa('".word_lang($rs->row["title"])."','".$rs->row["field_name"]."',true)";
		}
	}
	
	if($rs->row["field_name"]=="login")
	{
		if($ss=="add")
		{
			$field.="<input type='hidden' id='login_response' name='login_response' value='0'>
			<span>".$bold_begin.word_lang($rs->row["title"]).$bold_end."</span>
			<input type='text' name='login' id='login' style='width:300px' value='".$user_fields[$rs->row["field_name"]]."' onChange='check_login(this.value);' class='ibox form-control'><div id='error_login' name='error_login'></div>";
		}
		else
		{
			$field.="<input type='hidden' id='login_response' name='login_response' value='0'><input type='hidden' name='login' style='width:300px' value='".$user_fields["login"]."'><div id='error_login' name='error_login' class='error'></div><input type='hidden' id='login_ok' name='login_ok' value='1'>";
		}
	}
	elseif($rs->row["field_name"]=="password")
	{
		$field.="<span>".$bold_begin.word_lang($rs->row["title"]).$bold_end."</span>
					<input type='password' name='password' id='password' style='width:300px' class='ibox form-control' onChange='check_password();'>
					<div id='error_password' name='error_password'></div>
					</div>
					<div class='form_field'>
					<span>".$bold_begin.word_lang("confirm password").$bold_end."</span>
					<input type='password' name='password2' id='password2' style='width:300px' class='ibox form-control' onChange='check_password();'>
					<div id='error_password2' name='error_password2'></div>";
	}
	elseif($rs->row["field_name"]=="email")
	{
		$field.="<span>".$bold_begin.word_lang($rs->row["title"]).$bold_end."</span>
		<input type='text' name='email' id='email' style='width:300px' value='".$user_fields["email"]."' "; 
		
		if($ss=="add")
		{
			$field.="onChange='check_email(this.value);'";
		}
		
		$field.=" class='ibox form-control'><div id='error_email' name='error_email' class='error'></div><input type='hidden' id='email_ok' name='email_ok' value='";
		
		if($ss=="add")
		{
			$field.="0";
		}
		else
		{
			$field.="1";
		}
		
		$field.="'>";
	}
	elseif($rs->row["field_name"]=="country")
	{
		$field.="<span>".$bold_begin.word_lang("country").$bold_end."</span>
		<select name='country' id='country' style='width:310px;' class='ibox form-control' onChange=\"check_field('country');\"><option value=''></option>";
		
		$sql="select name from countries where activ=1 order by priority,name";
		$dd->open($sql);
		while(!$dd->eof)
		{
			$sel="";
			if($dd->row["name"]==$user_fields["country"]){$sel="selected";}

			$field.="<option value='".$dd->row["name"]."' ".$sel.">".$dd->row["name"]."</option>";
			
			$dd->movenext();
		}
		
		$field.="</select>
		<div id='error_country' name='error_country'></div>";
	}
	elseif($rs->row["field_name"]=="description" or $rs->row["field_name"]=="address")
	{
		$field.="<span>".$bold_begin.word_lang($rs->row["title"]).$bold_end."</span>
		<textarea name='".$rs->row["field_name"]."' id='".$rs->row["field_name"]."' style='width:300px;height:70px' class='ibox form-control' onChange='check_field('".$rs->row["field_name"]."');'>".$user_fields[$rs->row["field_name"]]."</textarea><div id='error_".$rs->row["field_name"]."' name='error_".$rs->row["field_name"]."'></div>";
	}
	elseif($rs->row["field_name"]=="state")
	{
		$field.="<span>".$bold_begin.word_lang("state").$bold_end."</span>
		<div id='states_content'>
		<input type='text' name='state' id='state' style='width:310px' value='".$user_fields["state"]."' class='ibox form-control' onChange=\"check_field('state');\">
		</div>
		<div id='error_state' name='error_state'></div>
		<script>
			check_state('country');
		</script>";
	}
	elseif($rs->row["field_name"]=="business")
	{
		$field.="<span>".$bold_begin.word_lang("status").$bold_end."</span>
		<select name='business' style='width:310px' class='ibox form-control'>
		<option value='0' ";
		
		if($user_fields["business"]==0)
		{
			$field.="selected";
		}
		
		$field.=">".word_lang("individual")."</option>
		<option value='1' ";
		
		if($user_fields["business"]==1)
		{
			$field.="selected";
		}
		
		$field.=">".word_lang("business")."</option>
		</select>";
	}
	elseif($rs->row["field_name"]=="newsletter")
	{
		$field.="<input type='checkbox' name='newsletter' value='1' ";
		
		if($user_fields["newsletter"]==1)
		{
			$field.="checked";
		}
		
		$field.="> ".$bold_begin.word_lang("newsletter").$bold_end."<div id='error_".$rs->row["field_name"]."' name='error_".$rs->row["field_name"]."'></div>";
	}
	else
	{
		$field.="<span>".$bold_begin.word_lang($rs->row["title"]).$bold_end."</span>
		<input type='text' name='".$rs->row["field_name"]."' id='".$rs->row["field_name"]."' style='width:300px' value='".$user_fields[$rs->row["field_name"]]."' class='ibox form-control' onChange=\"check_field('".$rs->row["field_name"]."');\">
		<div id='error_".$rs->row["field_name"]."' name='error_".$rs->row["field_name"]."'></div>";
	}
	
	$field="<div class='form_field'>".$field."</div>";
	
	if($rs->row["columns"]==0)
	{
		$left_column.=$field;
	}
	else
	{
		$right_column.=$field;
	}
	
	$rs->movenext();
}
?>


<script language="javascript">
function anketa(name,pole,nado)
{
	this.name=name;
	this.pole=pole;
	this.nado=nado;
}


ms=new Array(<?=$required_fields?><?if($ss=="add" and !$global_settings["google_captcha"]){?>,new anketa('<?=word_lang("captcha")?>','rn1',true)<?}?>)

function check_state(value)
{
	var req = new JsHttpRequest();
   	req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			document.getElementById('states_content').innerHTML =req.responseText;
        }
    }
    req.open(null, 'states.php', true);
    req.send( {country: document.getElementById(value).value,state:  document.getElementById('state').value} );
}

function check_field(value)
{
	if(document.getElementById(value).value=="")
	{
		$("#"+value).addClass("ibox_error").removeClass("ibox_ok");
		document.getElementById('error_'+value).innerHTML ="<span class='error'><?=word_lang("incorrect field")?></span>";
	}
	else
	{
		$("#"+value).removeClass("ibox_error").addClass("ibox_ok");
		document.getElementById('error_'+value).innerHTML ="";
	}
	
	if(value=="country" &&  document.getElementById('state'))
	{
		check_state(value);
	}
}





function check()
{
	
		flag=true;
		for(i=0;i<ms.length;i++)
		{
			if(ms[i].nado==true)
			{
				if(document.getElementById(ms[i].pole))
				{
					if(document.getElementById(ms[i].pole).value=="")
					{
						flag=false;
						check_field(ms[i].pole);
					}
				}
			}
		}

		if(document.getElementById('login_response').value==-1)
		{
			flag=false;
		}

		if(document.getElementById('email_ok').value==-1)
		{
			flag=false;
		}
	return flag;
}



function check_login(value) 
{
    var req = new JsHttpRequest();
    req.onreadystatechange = function() 
    {
    	if (req.readyState == 4) 
   		{
			document.getElementById('error_login').innerHTML =req.responseText;

			if(req.responseText=="<span class='error'><?=word_lang("serror1")?></span>" || req.responseText=="<span class='error'><?=word_lang("incorrect")?></span>")
			{
				document.getElementById('login_response').value=-1
				$("#login").removeClass("ibox_ok").addClass("ibox_error");
			}
			else
			{
				document.getElementById('login_response').value=1
				$("#login").removeClass("ibox_error").addClass("ibox_ok");
			}
        }
    }
    req.open(null, 'check_login.php', true);
    req.send(  { login: value }  );
}



function check_email(value) 
{
    var req = new JsHttpRequest();
    req.onreadystatechange = function() 
    {
        if (req.readyState == 4) 
        {
			document.getElementById('error_email').innerHTML =req.responseText;
			if(req.responseText=="<span class='error'><?=word_lang("serror2")?></span>" || req.responseText=="<span class='error'><?=word_lang("incorrect")?></span>")
			{
				document.getElementById('email_ok').value=-1
				$("#email").addClass("ibox_error").removeClass("ibox_ok");
			}
			else
			{
				document.getElementById('email_ok').value=1
				$("#email").addClass("ibox_ok").removeClass("ibox_error");
			}
        }
    }
    req.open(null, 'check_email.php', true);
    req.send(  { email: value }  );
}



function check_password()
{
	if(document.getElementById('password2').value!="")
	{
		if(document.getElementById('password').value!=document.getElementById('password2').value)
		{
			$("#password").addClass("ibox_error").removeClass("ibox_ok");
			$("#password2").addClass("ibox_error").removeClass("ibox_ok");
			document.getElementById('error_password').innerHTML ="<span class='error'><?=word_lang("not equal")?></span>";
		}
		else
		{
			$("#password").addClass("ibox_ok").removeClass("ibox_error");
			$("#password2").addClass("ibox_ok").removeClass("ibox_error");
			document.getElementById('error_password').innerHTML ="";
		}
	}
}

</script>







<form action="<?if($ss=="add"){?>add.php<?}else{?>modify.php<?}?>" method="POST" name="orderform" onsubmit="return check();">






<table border="0" cellpadding="0" cellspacing="0">
<tr valign="top">
<td>











<?if($ss=="add" and ($global_settings["userupload"]==1 or $global_settings["affiliates"]) and $global_settings["user_signup"]==1 and !$global_settings["common_account"]){?>
	<div class="form_field">
		<span><b><?=word_lang("who are you")?>?</b></span>
		<select name="utype" id="utype" style="width:200px;" class="ibox form-control">
		<option value="buyer" <?if($user_fields["utype"]=="buyer"){echo("selected");}?>><?=word_lang("buyer")?></option>
		
		<?if($global_settings["userupload"]==1){?>
			<option value="seller" <?if($user_fields["utype"]=="seller"){echo("selected");}?>><?=word_lang("seller")?></option>
		<?}?>
		
		<?if($global_settings["affiliates"]){?>
			<option value="affiliate" <?if($user_fields["utype"]=="affiliate"){echo("selected");}?>><?=word_lang("affiliate")?></option>
		<?}?>
		</select>
	</div>
<?}else{?>
	<input type="hidden" name="utype" id="utype" value="<?=$user_fields["utype"]?>">
<?}?>





<?=$left_column?>








<?if($ss=="add"){?>

<div  class="form_field">
<?
	//Show captcha
	require_once('../admin/function/recaptchalib.php');
	echo(show_captcha());
?>
</div>

<?}?>





<div class="form_field">
	<input type="submit" class="isubmit" value="<?if($ss=="add"){?><?=word_lang("sign up")?><?}else{?><?=word_lang("change")?><?}?>">
</div>

</td>
<td style="padding-left:80px">





<?=$right_column?>






</td>
</tr>
</table>
</form>



