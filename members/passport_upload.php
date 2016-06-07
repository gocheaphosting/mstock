<?include("../admin/function/db.php");?>
<?

$img="";
$_FILES["passport"]['name']=result_file($_FILES["passport"]['name']);
$nf=explode(".",strtolower($_FILES["passport"]['name']));
if($_FILES["passport"]['size']>0 and $_FILES["passport"]['size']<2*1024*1024)
{
	if($nf[count($nf)-1]=="jpg" and !preg_match("/text/i",$_FILES["passport"]['type']))
	{
		$ff="users";

		$img=site_root."/content/".$ff."/passport_".(int)$_SESSION["reguser"].mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).".".$nf[count($nf)-1];
		move_uploaded_file($_FILES["passport"]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$img);
		$sql="update users set ".result3("passport")."='".result($img)."' where id_parent=".(int)$_SESSION["people_id"];
		$db->execute($sql);

		$sql="update users set passport='".result($img)."' where id_parent=".(int)$_SESSION["reguser"];
		$db->execute($sql);
	}
}


//Define type of the activation
$accessdenied=0;
$activation="on";
$sql="select svalue from users_settings where activ=1";
$rs->open($sql);
if(!$rs->eof)
{
	$activation=$rs->row["svalue"];
	if($activation=="on"){$accessdenied=0;}
	else{$accessdenied=1;}
}
$db->close();

header("location:thanks.php?activation=".$activation);





?>