<?include("../admin/function/db.php");?>
<?
$width=(int)$_GET["width"];
$height=(int)$_GET["height"];
$width2=round($width/2);
$height2=round($height/2);
$width3=round($width/3);

$flag_zoomer=false;

//Photo
$sql="select id_parent,title,server1 from photos where id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	$flag_storage=false;
	$file_url=site_root.server_url($rs->row["server1"])."/".$rs->row["id_parent"]."/thumb2.jpg";

	$sql="select url,filename2,filename1,item_id from filestorage_files where id_parent=".$rs->row["id_parent"];
	$ds->open($sql);
	while(!$ds->eof)
	{
		if($ds->row["item_id"]==0 and preg_match("/thumb2/",$ds->row["filename1"]))
		{
			$file_url=$ds->row["url"]."/".$ds->row["filename2"];
		}
		$flag_storage=true;
		$ds->movenext();
	}
	$flag_zoomer=true;
}


//Vector
$sql="select id_parent,title,server1 from vector where id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	$flag_storage=false;
	$file_url=site_root.server_url($rs->row["server1"])."/".$rs->row["id_parent"]."/thumb2.jpg";

	$sql="select url,filename2,filename1,item_id from filestorage_files where id_parent=".$rs->row["id_parent"];
	$ds->open($sql);
	while(!$ds->eof)
	{
		if($ds->row["item_id"]==0 and preg_match("/thumb2/",$ds->row["filename1"]))
		{
			$file_url=$ds->row["url"]."/".$ds->row["filename2"];
		}
		$flag_storage=true;
		$ds->movenext();
	}
	$flag_zoomer=true;
}



if(!$flag_zoomer)
{
	exit();
}
?>
<html>
<head>
<title><?=$rs->row["title"]?></title>
<style>
body{cursor:crosshair}

#zoomer_header
{
position:absolute;
bottom:0px;
left:35%;
right:35%;
background-color:#1e1e1e;
padding:1px 0px 1px 0px;
font:10px Verdana;
color:#FFFFFF;
opacity: 0.5;
filter:progid:DXImageTransform.Microsoft.Alpha(opacity=50);
-moz-opacity: 0.5;
-khtml-opacity: 0.5;
display:none;
text-align:center;
}
</style>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<script type="text/javascript" language="JavaScript" src="<?=site_root?>/members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">


function absPosition(obj) 
{ 
      var x = y = 0; 
      while(obj) 
      { 
            x += obj.offsetLeft; 
            y += obj.offsetTop; 
            obj = obj.offsetParent; 
      } 
      return {x:x, y:y}; 
}

count_click=1;

function zoomer_show(width,height,value,event) 
{
   var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() 
    {
    	if (req.readyState == 4) 
    	{
			document.getElementById('zoomer').innerHTML =req.responseText;
        }
    }
    
    	
	if(count_click==4)
	{
		document.getElementById('zoomer_header').style.display='none';
		count_click=0;
	}
	else
	{
		document.getElementById('zoomer_header').style.display='block';
		count_click_p=100;
		if(count_click==1){count_click_p=19}
		if(count_click==2){count_click_p=38}
		if(count_click==3){count_click_p=75}
		document.getElementById('zoomer_header').innerHTML ="#<?=(int)$_GET["id"]?>&nbsp;@&nbsp;"+count_click_p+"%";
	}
	count_click++;	

	zc=document.getElementById("zoomer")

	sx=event.clientX-Math.round(width/4);
	sy=event.clientY-Math.round(height/4);

	if(sx<zc.offsetLeft){sx=zc.offsetLeft}
	if(sx>zc.offsetLeft+Math.round(width/2)){sx=zc.offsetLeft+Math.round(width/2)}
	if(sy<zc.offsetTop){sy=zc.offsetTop}
	if(sy>zc.offsetTop+Math.round(height/2)){sy=zc.offsetTop+Math.round(height/2)}

	zz=document.getElementById("zoom")
	z=zz.value

	zx=document.getElementById("zoomx")
	zxn=new Number(zx.value)
	zy=document.getElementById("zoomy")
	zyn=new Number(zy.value)

	x1=2*(sx-zc.offsetLeft)/zz.value
	y1=2*(sy-zc.offsetTop)/zz.value

    req.open(null, '<?=site_root?>/members/zoomer.php', true);
    req.send( {'id':value,'x1':x1,'x0':zx.value,'y1':y1,'y0':zy.value,'z':z,'width':width,'height':height} );

	if(zz.value<9)
	{
		zx.value=zxn+x1;
		zy.value=zyn+y1;
		zz.value=zz.value*2
	}
	else
	{
		zx.value=0
		zy.value=0
		zz.value=2
	}
	return true;
}


function zoomeron(width,height,event,id)
{
	document.getElementById("zm1").style.display="block";
	document.getElementById("zm2").style.display="block";
	document.getElementById("zm3").style.display="block";
	document.getElementById("zm4").style.display="block";
}




function zoomermove(width,height,event)
{


	zc=document.getElementById("zoomer")
	sx=event.clientX-Math.round(width/4);
	sy=event.clientY-Math.round(height/4);
	if(sx<zc.offsetLeft){sx=zc.offsetLeft}
	if(sx>zc.offsetLeft+Math.round(width/2)){sx=zc.offsetLeft+Math.round(width/2)}
	if(sy<zc.offsetTop){sy=zc.offsetTop}
	if(sy>zc.offsetTop+Math.round(height/2)){sy=zc.offsetTop+Math.round(height/2)}

	document.getElementById("zm1").style.left=sx;
	document.getElementById("zm1").style.top=sy;
	
	document.getElementById("zm2").style.left=sx;
	document.getElementById("zm2").style.top=sy;
	
	document.getElementById("zm3").style.left=sx;
	document.getElementById("zm3").style.top=sy+<?=$height2?>;
	
	document.getElementById("zm4").style.left=sx+<?=$width2?>;
	document.getElementById("zm4").style.top=sy;

}

function zoomeroff()
{
	document.getElementById("zm1").style.display="none";
	document.getElementById("zm2").style.display="none";
	document.getElementById("zm3").style.display="none";
	document.getElementById("zm4").style.display="none";
}


</script>




<div id="zm1" name="zm1"    style='width:<?=$width2?>px;height:1px;border-top-color:#777777;border-top-width:1px;border-top-style:solid;display:none;position:absolute;top:0;left:0;z-index:2'>&nbsp;</div>

<div id="zm2" name="zm2"    style='width:1px;height:<?=$height2?>px;border-left-color:#777777;border-left-width:1px;border-left-style:solid;display:none;position:absolute;top:0;left:0;z-index:2'>&nbsp;</div>

<div id="zm3" name="zm3"    style='width:<?=$width2?>px;height:1px;border-top-color:#777777;border-top-width:1px;border-top-style:solid;display:none;position:absolute;top:0;left:0;z-index:2'>&nbsp;</div>

<div id="zm4" name="zm4"    style='width:1px;height:<?=$height2?>px;border-right-color:#777777;border-right-width:1px;border-right-style:solid;display:none;position:absolute;top:0;left:0;z-index:2'>&nbsp;</div>

<div id="zoomer_header"></div>


<div id="zoomer" onClick="zoomer_show(<?=$width?>,<?=$height?>,<?=$_GET["id"]?>,event);"  onMouseover="zoomeron(<?=$width?>,<?=$height?>,event,<?=$_GET["id"]?>);" onMousemove="zoomermove(<?=$width?>,<?=$height?>,event,<?=$_GET["id"]?>);" onMouseout="zoomeroff();" name="zoomer" style="width:<?=$width?>;height:<?=$height?>;background-image: url('<?if(site_root==""){echo("/");}else{echo(site_root."/");}?>images/loading.gif'); background-repeat: no-repeat;background-position:center;padding:0px;"><img src="<?=$file_url?>" width="<?=$width?>" height="<?=$height?>" border="0" style="margin:0px"></div><input id="zoom" name="zoom" type="hidden" value="2"><input id="zoomx" name="zoomx" type="hidden" value="0"><input id="zoomy" name="zoomy" type="hidden" value="0">
</body>
</html>
