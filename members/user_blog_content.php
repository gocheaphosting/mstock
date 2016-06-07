<?if(!defined("site_root")){exit();}?>
<?
$paging_string="";

//Search
$com="";
if(isset($blogsearch))
{
	$paging_string.="&blogsearch=".$blogsearch;
	$sch=explode(" ",result($blogsearch));
	if(count($sch)>0)
	{
		$com=" and (";
		$search="";
		for($i=0;$i<count($sch);$i++)
		{
			if($i!=0){$com.=" and ";}
			$com.=" (title like '%".result3($sch[$i])."%' or content like '%".result3($sch[$i])."%') ";
		}
		$com.=") ";
	}
}




//category
$com2="";
if(isset($_GET["category"]))
{
	$paging_string.="&category=".$_GET["category"];
	
	if($_GET["category"]!=0)
	{
		$sql="select title from blog_categories where id_parent=".(int)$_GET["category"];
		$dn->open($sql);
		if(!$dn->eof)
		{
			$com2.=" and categories like '%".$dn->row["title"]."%' ";
		}
	}
	else
	{
		$com2.=" and categories='' ";
	}
}


//date
$com3="";
if(isset($_GET["ayear"]) and isset($_GET["amonth"]))
{
	$paging_string.="&ayear=".$_GET["ayear"]."&amonth=".$_GET["amonth"];
	$com3.=" and data>".(mktime(0,0,0,$_GET["amonth"],1,$_GET["ayear"])-1)." and data<".(mktime(0,0,0,$_GET["amonth"]+1,1,$_GET["ayear"])+1);
}


//user
$com4="";
if($site=="user_blog"){$com4=" and user='".result3(user_url_back($nameuser))."'";}
if($site=="user_friends")
{
	$sql="select friend1,friend2 from friends where friend1='".result3(user_url_back($nameuser))."'";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$com4.="and (";
		$k=0;
		while(!$dr->eof)
		{
			if($k!=0){$com4.=" or ";}
			$com4.=" user='".$dr->row["friend2"]."'";
			$k++;
			$dr->movenext();
		}
		$com4.=") ";
	}
}




//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;





$sql="select id_parent,title,content,data,user,published,photo,categories,comments from blog where published=1 ".$com.$com2.$com3.$com4." order by data desc";
//echo($sql);
$rs->open($sql);
$n=0;
while(!$rs->eof)
{
	if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
	{
		?>
		<div style="clear:both">
		<div><a href="<?=site_root?>/post/<?=user_url($rs->row["user"])?>/<?=$rs->row["id_parent"]?>.html" class="blog"><?=$rs->row["title"]?></a></div>
		<?
		$boxuser="<div style='margin-top:4px'>".show_user_avatar($rs->row["user"],"login")." - <span class='datenews'>".show_time_ago($rs->row['data'])."</span></div>";
		echo($boxuser);


		if($rs->row["photo"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"]))
		{
			?>
			<img src="<?=$rs->row["photo"]?>" align="left" style="margin-top:10px;margin-bottom:5px;margin-left:0px;margin-right:10px">
			<?
		}
		?>
		<div style="margin-top:8px"><p>
		<?
		echo($rs->row["content"]);
		?></p></div>
		<div style="margin-top:5px" class="grayfont"><?=word_lang("posted in")?>&nbsp;
		<?
		if($rs->row["categories"]=="")
		{
			?><a href="<?=site_root?>/blog/0/<?=user_url($rs->row["user"])?>.html"><?=word_lang("uncategorized")?></a><?
		}
		else
		{
			$cat=explode(",",$rs->row["categories"]);
			for($i=0;$i<count($cat);$i++)
			{
				if($cat[$i]!="")
				{
					$sql="select id_parent from blog_categories where user='".result3(user_url_back($nameuser))."' and title='".result($cat[$i])."'";
					$dn->open($sql);
					if(!$dn->eof)
					{
					
						if($i!=0){echo(" ");}
						?><a href="<?=site_root?>/blog/<?=$dn->row["id_parent"]?>/<?=user_url($rs->row["user"])?>.html"><?=$cat[$i]?></a><?
					}
				}
			}
		}
		if($rs->row["comments"]==1)
		{
			?>&nbsp;|&nbsp;<a href="<?=site_root?>/post/<?=user_url($rs->row["user"])?>/<?=$rs->row["id_parent"]?>.html"><?=word_lang("comments")?>: 
			<?
			$kcount=0;
			$sql="select count(id_parent) as kcount from blog_comments where postid=".$rs->row["id_parent"]." group by id_parent";
			$dr->open($sql);
			if(!$dr->eof){$kcount=$dr->rc;}
			?>(<?=$kcount?>)</a>
	<?}?>
	</div>
	</div>
	<div style="clear:both;height:30px"></div>
	<?
	}
	$n++;
	$rs->movenext();
}


echo(paging($n,$str,$kolvo,$kolvo2,site_root."/members/".$site.".php","&user=".$nameuser.$paging_string));
?>