<?if(!defined("site_root")){exit();}?>
<?
if($site_epoch_account!=""){




if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_epoch_ipn==true)
{


if (eregi("^Y",$_GET['ans']) and eregi("208.236.105.",$_SERVER['HTTP_REFERER']))
{


	if($_GET["product_type"]=="credits")
	{
		credits_approve($_GET["pid"],$_GET['ans']);
							send_notification('credits_to_user',$_GET["pid"]);
send_notification('credits_to_admin',$_GET["pid"]);
	}

	if($_GET["product_type"]=="subscription")
	{
		subscription_approve($_GET["pid"]);
						send_notification('subscription_to_user',$_GET["pid"]);
send_notification('subscription_to_admin',$_GET["pid"]);
	}

header("location:".surl.site_root."/members/payments_result.php?d=1");
exit();
}
else
{
header("location:".surl.site_root."/members/payments_result.php?d=2");
exit();
}

}
}
else
{



$aproduct=0;

if($_POST["tip"]=="credits")
{
$sql="select * from gateway_epoch where credits=".(int)$_POST["credits"];
$ds->open($sql);
if(!$ds->eof)
{
$aproduct=$ds->row["product_id"];
}
}


if($_POST["tip"]=="subscription")
{
$sql="select * from gateway_epoch where subscription=".(int)$_POST["subscription"];
$ds->open($sql);
if(!$ds->eof)
{
$aproduct=$ds->row["product_id"];
}
}
?>















<form  name="process" id="process" action="<?=epoch_url?>" method="post">
	<INPUT type=hidden name=co_code value="<?=$site_epoch_account?>">
	<INPUT type=hidden name=reseller value=a>
	<INPUT type=hidden name=product_description value="<?=$product_name?>">
	<INPUT type=hidden name=pi_returnurl value="<?=surl.site_root."/members/payments_process.php"?>">
	<INPUT type=hidden name="pi_code" value="<?=$aproduct?>">



               

<div style="font:8.4pt Tahoma"><b>Epoch Username:</b></div>
<div style="margin-bottom:15px"><INPUT type=text name=username value="" style="width:150px"></div>

<div style="font:8.4pt Tahoma"><b>Epoch Password:</b></div>
<div style="margin-bottom:15px"><INPUT type=password name=password value="" style="width:150px"></div>



<?
$sql="select email,country,zipcode from users where id_parent=".$_SESSION["people_id"];
$ds->open($sql);
if(!$ds->eof)
{
?>



<div style="font:8.4pt Tahoma"><b>Email:</b></div>
<div style="margin-bottom:15px"><INPUT type=text name=email value="<?=$ds->row["email"]?>" style="width:150px"></div>


<div style="font:8.4pt Tahoma"><b>Country:</b></div>
<div style="margin-bottom:15px">                  <SELECT NAME=country>
                    <OPTION VALUE=4>AFGHANISTAN 
                    <OPTION VALUE=8>ALBANIA 
                    <OPTION VALUE=12>ALGERIA 
                    <OPTION VALUE=16>AMERICAN SAMOA 
                    <OPTION VALUE=20>ANDORRA 
                    <OPTION VALUE=24>ANGOLA 
                    <OPTION VALUE=660>ANGUILLA 
                    <OPTION VALUE=10>ANTARCTICA 
                    <OPTION VALUE=28>ANTIGUA & BARBUDA 
                    <OPTION VALUE=32>ARGENTINA 
                    <OPTION VALUE=51>ARMENIA 
                    <OPTION VALUE=533>ARUBA 
                    <OPTION VALUE=36>AUSTRALIA 
                    <OPTION VALUE=40>AUSTRIA 
                    <OPTION VALUE=31>AZERBAIJAN 
                    <OPTION VALUE=44>BAHAMAS 
                    <OPTION VALUE=48>BAHRAIN 
                    <OPTION VALUE=50>BANGLADESH 
                    <OPTION VALUE=52>BARBADOS 
                    <OPTION VALUE=112>BELARUS 
                    <OPTION VALUE=56>BELGIUM 
                    <OPTION VALUE=84>BELIZE 
                    <OPTION VALUE=204>BENIN 
                    <OPTION VALUE=60>BERMUDA 
                    <OPTION VALUE=64>BHUTAN 
                    <OPTION VALUE=68>BOLIVIA 
                    <OPTION VALUE=70>BOSNIA-HERZEGOVINA 
                    <OPTION VALUE=72>BOTSWANA 
                    <OPTION VALUE=74>BOUVET ISLAND 
                    <OPTION VALUE=76>BRAZIL 
                    <OPTION VALUE=92>BRITISH VIRGIN ISLANDS 
                    <OPTION VALUE=96>BRUNEI DARUSSALAM 
                    <OPTION VALUE=100>BULGARIA 
                    <OPTION VALUE=854>BURKINA FASO 
                    <OPTION VALUE=104>BURMA 
                    <OPTION VALUE=108>BURUNDI 
                    <OPTION VALUE=120>CAMEROON 
                    <OPTION VALUE=124>CANADA 
                    <OPTION VALUE=128>CANTON AND ENDERBURY IS. 
                    <OPTION VALUE=132>CAPE VERDE 
                    <OPTION VALUE=136>CAYMAN ISLANDS 
                    <OPTION VALUE=140>CENTRAL AFRICAN REP. 
                    <OPTION VALUE=148>CHAD 
                    <OPTION VALUE=152>CHILE 
                    <OPTION VALUE=156>CHINA 
                    <OPTION VALUE=162>CHRISTMAS ISLAND 
                    <OPTION VALUE=166>COCOS (KEELING) ISLAND 
                    <OPTION VALUE=170>COLOMBIA 
                    <OPTION VALUE=174>COMOROS 
                    <OPTION VALUE=178>CONGO 
                    <OPTION VALUE=184>COOK ISLANDS 
                    <OPTION VALUE=188>COSTA RICA 
                    <OPTION VALUE=384>COTE D'IVOIRE 
                    <OPTION VALUE=191>CROATIA 
                    <OPTION VALUE=192>CUBA 
                    <OPTION VALUE=196>CYPRUS 
                    <OPTION VALUE=203>CZECH REPUBLIC 
                    <OPTION VALUE=200>CZECHOSLOVAKIA 
                    <OPTION VALUE=116>DEMOCRATIC KAMPUCHEA 
                    <OPTION VALUE=720>DEMOCRATIC YEMEN 
                    <OPTION VALUE=208>DENMARK 
                    <OPTION VALUE=262>DJIBOUTI 
                    <OPTION VALUE=212>DOMINICA 
                    <OPTION VALUE=214>DOMINICAN REPUBLIC 
                    <OPTION VALUE=216>DRONNING MAUD ISLAND 
                    <OPTION VALUE=626>EAST TIMOR 
                    <OPTION VALUE=218>ECUADOR 
                    <OPTION VALUE=818>EGYPT 
                    <OPTION VALUE=222>EL SALVADOR 
                    <OPTION VALUE=226>EQUATORIAL GUINEA 
                    <OPTION VALUE=233>ESTONIA 
                    <OPTION VALUE=231>ETHIOPIA 
                    <OPTION VALUE=234>FAEROE ISLANDS 
                    <OPTION VALUE=238>FALKLAND ISLANDS-MALVINAS 
                    <OPTION VALUE=242>FIJI 
                    <OPTION VALUE=246>FINLAND 
                    <OPTION VALUE=250>FRANCE 
                    <OPTION VALUE=254>FRENCH GUIANA 
                    <OPTION VALUE=258>FRENCH POLYNESIA 
                    <OPTION VALUE=260>FRENCH SOUTHERN TERR 
                    <OPTION VALUE=266>GABON 
                    <OPTION VALUE=270>GAMBIA 
                    <OPTION VALUE=268>GEORGIA 
                    <OPTION VALUE=276>GERMANY 
                    <OPTION VALUE=288>GHANA 
                    <OPTION VALUE=292>GIBRALTAR 
                    <OPTION VALUE=300>GREECE 
                    <OPTION VALUE=304>GREENLAND 
                    <OPTION VALUE=308>GRENADA 
                    <OPTION VALUE=312>GUADALOUPE 
                    <OPTION VALUE=316>GUAM 
                    <OPTION VALUE=320>GUATEMALA 
                    <OPTION VALUE=324>GUINEA 
                    <OPTION VALUE=624>GUINEA-BISSAU 
                    <OPTION VALUE=328>GUYANA 
                    <OPTION VALUE=332>HAITI 
                    <OPTION VALUE=334>HEARD AND MCDONALD IS. 
                    <OPTION VALUE=340>HONDURAS 
                    <OPTION VALUE=344>HONG KONG 
                    <OPTION VALUE=348>HUNGARY 
                    <OPTION VALUE=352>ICELAND 
                    <OPTION VALUE=356>INDIA 
                    <OPTION VALUE=360>INDONESIA 
                    <OPTION VALUE=364>IRAN 
                    <OPTION VALUE=368>IRAQ 
                    <OPTION VALUE=372>IRELAND 
                    <OPTION VALUE=376>ISRAEL 
                    <OPTION VALUE=380>ITALY 
                    <OPTION VALUE=388>JAMAICA 
                    <OPTION VALUE=392 >JAPAN 
                    <OPTION VALUE=396>JOHNSTON ISLAND 
                    <OPTION VALUE=400>JORDAN 
                    <OPTION VALUE=398>KAZAKHSTAN 
                    <OPTION VALUE=404>KENYA 
                    <OPTION VALUE=296>KIRIBATI 
                    <OPTION VALUE=414>KUWAIT 
                    <OPTION VALUE=417>KYRGYZSTAN 
                    <OPTION VALUE=418>LAOS 
                    <OPTION VALUE=428>LATVIA 
                    <OPTION VALUE=422>LEBANON 
                    <OPTION VALUE=426>LESOTHO 
                    <OPTION VALUE=430>LIBERIA 
                    <OPTION VALUE=434>LIBYA 
                    <OPTION VALUE=438>LIECHTENSTEIN 
                    <OPTION VALUE=440>LITHUANIA 
                    <OPTION VALUE=442>LUXEMBOURG 
                    <OPTION VALUE=446>MACAU 
                    <OPTION VALUE=807>MACEDONIA 
                    <OPTION VALUE=450>MADAGASCAR 
                    <OPTION VALUE=454>MALAWI 
                    <OPTION VALUE=458>MALAYSIA 
                    <OPTION VALUE=462>MALDIVES 
                    <OPTION VALUE=466>MALI 
                    <OPTION VALUE=470>MALTA 
                    <OPTION VALUE=474>MARTINIQUE 
                    <OPTION VALUE=478>MAURITANIA 
                    <OPTION VALUE=480>MAURITIUS 
                    <OPTION VALUE=484>MEXICO 
                    <OPTION VALUE=488>MIDWAY ISLANDS 
                    <OPTION VALUE=498>MOLDOVA 
                    <OPTION VALUE=492>MONACO 
                    <OPTION VALUE=496>MONGOLIA 
                    <OPTION VALUE=500>MONTSERRAT 
                    <OPTION VALUE=504>MOROCCO 
                    <OPTION VALUE=508>MOZAMBIQUE 
                    <OPTION VALUE=516>NAMIBIA 
                    <OPTION VALUE=520>NAURU 
                    <OPTION VALUE=524>NEPAL 
                    <OPTION VALUE=528>NETHERLANDS 
                    <OPTION VALUE=530>NETHERLANDS ANTILLES 
                    <OPTION VALUE=536>NEUTRAL ZONE 
                    <OPTION VALUE=540>NEW CALEDONIA 
                    <OPTION VALUE=554>NEW ZEALAND 
                    <OPTION VALUE=558>NICARAGUA 
                    <OPTION VALUE=562>NIGER 
                    <OPTION VALUE=566>NIGERIA 
                    <OPTION VALUE=570>NIUE 
                    <OPTION VALUE=574>NORFOLK ISLAND 
                    <OPTION VALUE=408>NORTH KOREA 
                    <OPTION VALUE=578>NORWAY 
                    <OPTION VALUE=512>OMAN 
                    <OPTION VALUE=582>PACIFIC IS. TRUST TERR. 
                    <OPTION VALUE=586>PAKISTAN 
                    <OPTION VALUE=591>PANAMA 
                    <OPTION VALUE=598>PAPUA NEW GUINEA 
                    <OPTION VALUE=600>PARAGUAY 
                    <OPTION VALUE=604>PERU 
                    <OPTION VALUE=608>PHILIPPINES 
                    <OPTION VALUE=612>PITCAIRN ISLANDS 
                    <OPTION VALUE=616>POLAND 
                    <OPTION VALUE=620>PORTUGAL 
                    <OPTION VALUE=630>PUERTO RICO 
                    <OPTION VALUE=634>QATAR 
                    <OPTION VALUE=638>REUNION 
                    <OPTION VALUE=642>ROMANIA 
                    <OPTION VALUE=643>RUSSIA 
                    <OPTION VALUE=646>RWANDA 
                    <OPTION VALUE=882>SAMOA 
                    <OPTION VALUE=674>SAN MARINO 
                    <OPTION VALUE=678>SAO TOME AND PRINCIPE 
                    <OPTION VALUE=682>SAUDI ARABIA 
                    <OPTION VALUE=686>SENEGAL 
                    <OPTION VALUE=690>SEYCHELLES 
                    <OPTION VALUE=694>SIERRA LEONE 
                    <OPTION VALUE=702>SINGAPORE 
                    <OPTION VALUE=703>SLOVAKIA 
                    <OPTION VALUE=705>SLOVENIA 
                    <OPTION VALUE=90>SOLOMON ISLANDS 
                    <OPTION VALUE=706>SOMALIA 
                    <OPTION VALUE=710>SOUTH AFRICA 
                    <OPTION VALUE=410>SOUTH KOREA 
                    <OPTION VALUE=724>SPAIN 
                    <OPTION VALUE=144>SRI LANKA 
                    <OPTION VALUE=654>ST. HELENA 
                    <OPTION VALUE=659>ST. KITTS-NEVIS-ANGUILLA 
                    <OPTION VALUE=662>ST. LUCIA 
                    <OPTION VALUE=666>ST. PIERRE ET MIQUELON 
                    <OPTION VALUE=736>SUDAN 
                    <OPTION VALUE=740>SURINAME 
                    <OPTION VALUE=744>SVALBARD & JAN MAYEN IS. 
                    <OPTION VALUE=748>SWAZILAND 
                    <OPTION VALUE=752>SWEDEN 
                    <OPTION VALUE=756>SWITZERLAND 
                    <OPTION VALUE=760>SYRIA 
                    <OPTION VALUE=158>TAIWAN 
                    <OPTION VALUE=762>TAJIKISTAN 
                    <OPTION VALUE=834>TANZANIA 
                    <OPTION VALUE=764>THAILAND 
                    <OPTION VALUE=768>TOGO 
                    <OPTION VALUE=772>TOKELAU 
                    <OPTION VALUE=776>TONGA 
                    <OPTION VALUE=780>TRINIDAD AND TOBAGO 
                    <OPTION VALUE=788>TUNISIA 
                    <OPTION VALUE=792>TURKEY 
                    <OPTION VALUE=795>TURKMENISTAN 
                    <OPTION VALUE=796>TURKS AND CAICOS IS. 
                    <OPTION VALUE=798>TUVALU 
                    <OPTION VALUE=849>U.S. PACIFIC ISLANDS 
                    <OPTION VALUE=850>U.S. VIRGIN ISLANDS 
                    <OPTION VALUE=800>UGANDA 
                    <OPTION VALUE=804>UKRAINE 
                    <OPTION VALUE=784>UNITED ARAB EMIRATES 
                    <OPTION VALUE=826>UNITED KINGDOM 
                    <OPTION VALUE=840 SELECTED>UNITED STATES OF AMERICA 
                    <OPTION VALUE=858>URUGUAY 
                    <OPTION VALUE=860>UZBEKISTAN 
                    <OPTION VALUE=548>VANUATU 
                    <OPTION VALUE=336>VATICAN CITY STATE 
                    <OPTION VALUE=862>VENEZUELA 
                    <OPTION VALUE=704>VIETNAM 
                    <OPTION VALUE=872>WAKE ISLAND 
                    <OPTION VALUE=876>WALLIS AND FUTUNA IS. 
                    <OPTION VALUE=732>WESTERN SAHARA 
                    <OPTION VALUE=887>YEMEN ARAB REPUBLIC 
                    <OPTION VALUE=891>YUGOSLAVIA 
                    <OPTION VALUE=180>ZAIRE 
                    <OPTION VALUE=894>ZAMBIA 
                    <OPTION VALUE=716>ZIMBABWE</OPTION>
                  </SELECT></div>


<div style="font:8.4pt Tahoma"><b>Zip:</b></div>
<div style="margin-bottom:15px"><INPUT type=text name=zip value="<?=$ds->row["zipcode"]?>" style="width:150px"></div>


 
<?
}
?> 



<INPUT type=hidden name=mode value="notification">

<INPUT type=hidden name=pid value="<?=$product_id?>">

<INPUT type=hidden name=product_type value="<?=$product_type?>">

<INPUT type=hidden name=processor value="epoch">

<input type="submit" value="Buy">
</FORM>

















<?

}



}
?>