<?$site="orders";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"]) and !isset($_SESSION["entry_admin"])){header("location:login.php");}?>

<html>
<head>
<title><?=word_lang("print version")?></title>
<style>
A:link,A:visited {color: #2C78B5;text-decoration: underline;}
A:active,A:hover {color: #2C78B5;text-decoration: underline;}


body,td,p,ul {color: #000000; font: 11px Arial;}
body{margin:20px;}
b,strong{font-weight:bold;}
h1{color: #000000; font: 15px Arial; font-weight: bold}
h2{color: #000000; font: 13px Arial; font-weight: bold}


table.profile_table th
{
padding:5px;
border-top: 1px #e2e2e2 solid;
background-color:#f2f2f2;
margin:0px;
font: 12px Arial;
text-align:left;
}

table.profile_table tr
{
background-color:#ffffff;
}


table.profile_table td
{
padding:10px 5px 10px 5px;
margin:0px;
}


.payment_table
{
width:100%;
}

.payment_table td
{
padding: 5px;
}

.payment_table th
{
background-color:#eeeeee;
color:#42433e;
font: 13px Arial;
font-weight:bold;
padding: 5px;
text-align:left;
}

.payment_table tr
{
vertical-align: top;
}

.payment_table2
{
width:100%;
}

.payment_table2 td
{
paddingt: 5px 20px 20px 0px;
font: 12px Arial;
}

</style>
</head>
<body>
<div style="width:1000px">
<?
include("payments_statement.php");
?>
</div>
</body>
</html>
<?
$db->close();
?>





