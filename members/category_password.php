<?include("../admin/function/db.php");?>
<?
$idt=check_password(0,(int)$_POST["id_parent"],1);

if($idt!=0)
{

$sql="select id_parent,password from category where id_parent=".(int)$idt;
$rs->open($sql);
if($rs->row["password"]==$_POST["password"])
{

if(!isset($_SESSION["cprotected"]))
{
$_SESSION["cprotected"]=strval($idt);
}
else
{
$_SESSION["cprotected"].="|".strval($idt);
}
}

}


header("location:".$_SERVER["HTTP_REFERER"]);
?>