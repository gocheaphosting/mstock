<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_customers");

$newsletter=0;
if(isset($_POST["newsletter"]))
{
	$newsletter=1;
}
		
$exam=0;
if(isset($_POST["examination"]))
{
	$exam=1;
}

if(isset($_GET["id"]))
{
		$id=(int)$_GET["id"];
		
		$password="";
		if($_POST["password"]!="********")
		{
			$password=",password='".md5(result($_POST["password"]))."'";
		}
		
		$sql="update users set login='".result($_POST["login"])."'".$password.",name='".result($_POST["name"])."',country='".result($_POST["country"])."',telephone='".result($_POST["telephone"])."',address='".result($_POST["address"])."',email='".result($_POST["email"])."',accessdenied=".(int)$_POST["accessdenied"].",lastname='".result($_POST["lastname"])."',city='".result($_POST["city"])."',state='".result($_POST["state"])."',zipcode='".result($_POST["zipcode"])."',category='".result($_POST["category"])."',website='".result($_POST["website"])."',utype='".result($_POST["utype"])."',company='".result($_POST["company"])."',newsletter=".$newsletter.",examination=".$exam.",aff_commission_buyer=".(int)$_POST["aff_commission_buyer"].",aff_commission_seller=".(int)$_POST["aff_commission_seller"].",description='".result($_POST["description"])."',paypal='".result(@$_POST["paypal"])."',moneybookers='".result(@$_POST["moneybookers"])."',webmoney='".result(@$_POST["webmoney"])."',dwolla='".result(@$_POST["dwolla"])."',qiwi='".result(@$_POST["qiwi"])."',business=".(int)$_POST["business"].",bank_name='".result(@$_POST["bank_name"])."',bank_account='".result(@$_POST["bank_account"])."',payson='".result(@$_POST["payson"])."',vat='".result(@$_POST["vat"])."',payout_limit=".(float)@$_POST["payout_limit"]." where id_parent=".(int)$_GET["id"];
		$db->execute($sql);
}
else
{
		$sql="insert into users (login,password,name,country,telephone,address,email,data1,ip,accessdenied,lastname,city,state,zipcode,category,website,utype,company,newsletter,examination,authorization,aff_commission_buyer,aff_commission_seller,aff_visits,aff_signups,aff_referal,description,paypal,moneybookers,dwolla,qiwi,webmoney,business,bank_name,bank_account,payson,vat,payout_limit) values ('".result($_POST["login"])."','".md5(result($_POST["password"]))."','".result($_POST["name"])."','".result($_POST["country"])."','".result($_POST["telephone"])."','".result($_POST["address"])."','".result($_POST["email"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_SERVER["REMOTE_ADDR"])."',".(int)$_POST["accessdenied"].",'".result($_POST["lastname"])."','".result($_POST["city"])."','".result($_POST["state"])."','".result($_POST["zipcode"])."','".result($_POST["category"])."','".result($_POST["website"])."','".result($_POST["utype"])."','".result($_POST["company"])."',".$newsletter.",".$exam.",'site',".(int)$_POST["aff_commission_buyer"].",".(int)$_POST["aff_commission_seller"].",0,0,0,'".result($_POST["description"])."','".result(@$_POST["paypal"])."','".result(@$_POST["moneybookers"])."','".result(@$_POST["dwolla"])."','".result(@$_POST["qiwi"])."','".result(@$_POST["webmoney"])."',".(int)$_POST["business"].",'".result(@$_POST["bank_name"])."','".result(@$_POST["bank_account"])."','".result(@$_POST["payson"])."','".result(@$_POST["vat"])."',".(float)@$_POST["payout_limit"].")";
		$db->execute($sql);
		
		$sql="select id_parent from users where login='".result($_POST["login"])."'";
		$rs->open($sql);
		$id=$rs->row["id_parent"];
}


//Upload photos
$images_types=array("photo","avatar");


for($i=0;$i<count($images_types);$i++)
{
	$_FILES[$images_types[$i]]['name']=result_file($_FILES[$images_types[$i]]['name']);
	if($_FILES[$images_types[$i]]['size']>0)
	{
		if(preg_match("/jpg$/i",$_FILES[$images_types[$i]]['name']) and !preg_match("/text/i",$_FILES[$images_types[$i]]['type']))
		{
			if($images_types[$i]=="avatar")
			{
				$folder="avatars";
			}
			else
			{
				$folder="users";
			}
			
			$file_name=result($_POST["login"]);
			if($images_types[$i]=="passport")
			{
				$file_name=md5(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));
			}


			$img=site_root."/content/".$folder."/".$images_types[$i]."_".$file_name.".jpg";
			move_uploaded_file($_FILES[$images_types[$i]]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$img);


			if($images_types[$i]=="avatar")
			{
				easyResize($_SERVER["DOCUMENT_ROOT"].$img,$_SERVER["DOCUMENT_ROOT"].$img,100,$global_settings["avatarwidth"]);
			}

			if($images_types[$i]=="photo")
			{
				easyResize($_SERVER["DOCUMENT_ROOT"].$img,$_SERVER["DOCUMENT_ROOT"].$img,100,$global_settings["userphotowidth"]);
			}

			$sql="update users set ".$images_types[$i]."='".result($img)."' where id_parent=".(int)$id;
			$db->execute($sql);
		}
	}
}

$db->close();
	
header("location:index.php");
?>
