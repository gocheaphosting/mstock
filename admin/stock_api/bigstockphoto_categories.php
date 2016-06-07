<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_stockapi");



//Load categories:
$url="api.bigstockphoto.com/2/".$global_settings["bigstockphoto_id"]."/categories/";


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

$data = curl_exec($ch); 
if (!curl_errno($ch)) 
{
    $results=json_decode($data);
    
    $sql="delete from category_stock where stock = 'bigstockphoto'";
    $db->execute($sql);
    
    
    foreach ($results->data as $key => $value) 
	{
		$sql="insert into category_stock (id,title,stock) values (0,'".$value->name."','bigstockphoto')";
    	$db->execute($sql);
	}
}

$db->close();

header("location:index.php?d=6");
?>
