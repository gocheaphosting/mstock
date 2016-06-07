<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$search=result($_REQUEST["search"]);

if($search!="")
{
	$count_lite=8;
	$count_limit=0;
	
	
	$mass_types=array();
	if($global_settings["allow_photo"]){$mass_types["photo"]=1;}
	if($global_settings["allow_video"]){$mass_types["video"]=1;}
	if($global_settings["allow_audio"]){$mass_types["audio"]=1;}
	if($global_settings["allow_vector"]){$mass_types["vector"]=1;}
	
	$sch=explode(" ",trim(remove_words(result($search))));
	
	$com2="";
	$com_multilangual = "";
	
	if(count($sch)>0)
	{
		$com2.="(";
		$com_multilangual = "(";
		
		for($i=0;$i<count($sch);$i++)
		{
			if($i!=0){$com2.=" and ";}
			if($i!=0){$com_multilangual.=" and ";}
			
			//Slow query. It finds words exactly.
			//$com2.=" (b.title rlike '[[:<:]]".$sch[$i]."[[:>:]]' or b.description rlike '[[:<:]]".$sch[$i]."[[:>:]]' or b.keywords rlike '[[:<:]]".$sch[$i]."[[:>:]]') ";
			
			//Fast query
			//$com2.=" (b.title like '%".$sch[$i]."%' or b.description like '%".$sch[$i]."%' or b.keywords like '%".$sch[$i]."%') ";
			
			//Fast query. It searches only in the titles
			$com2.=" (title like '%".$sch[$i]."%') ";
			$com_multilangual.=" (title like '%".$sch[$i]."%') ";
			
			//Cirillic
			//$com2.=" (UCASE(b.title) rlike UCASE('[[:<:]]".$sch[$i]."[[:>:]]') or UCASE(b.description) rlike UCASE('[[:<:]]".$sch[$i]."[[:>:]]') or UCASE(b.keywords) rlike UCASE('[[:<:]]".$sch[$i]."[[:>:]]')) ";
		}
		$com_multilangual.=")";
		
		//Multilangual
		if($global_settings["multilingual_publications"] and $com_multilangual != '')
		{
			$sql="select id from translations where types=1 and ".$com_multilangual." group by id order by id";
			$dr->open($sql);
			if(!$dr->eof)
			{
				$multi_ids = "(";
				
				while(!$dr->eof)
				{
					if($multi_ids != '(')
					{
						$multi_ids .= " or ";
					}
					
					$multi_ids .= "b.id_parent=".$dr->row["id"];
					
					$dr->movenext();
				}
				
				$multi_ids .= ")";
				
				$com2.=" or ".$multi_ids;
			}
		}
		//End. Multilingual
		
		$com2.=")";
	}
	
	$sql_mass=array();

	//$sql_password_protected=get_password_protected();

	$sql_mass["photo"]="select id_parent,title from photos b where b.published=1 and ".$com2." limit 0,".$count_lite;

	$sql_mass["video"]="select id_parent,title from videos b where b.published=1 and ".$com2." limit 0,".$count_lite;

	$sql_mass["audio"]="select id_parent,title from audio b where b.published=1 and ".$com2." limit 0,".$count_lite;

	$sql_mass["vector"]="select id_parent,title from vector b where b.published=1 and ".$com2." limit 0,".$count_lite;
	

	

	$mass_was=array();

	foreach ($mass_types as $key => $value)
	{
		if($value==1 and $count_limit<$count_lite+1)
		{	
			$rs->open($sql_mass[$key]);
			while(!$rs->eof)
			{
				if(!isset($mass_was[$rs->row["title"]]) and $count_limit<$count_lite+1)
				{
					$count_limit++;
					
					echo("<div class='instant_search_result' onClick=\"search_go('".$rs->row["title"]."')\">");
					//echo("<div class='instant_search_result' onClick=\"search_go('".$search."')\">");
		
					$title="";
					
					$translate_results=translate_publication($rs->row["id_parent"],$rs->row["title"],"","");

					$titles = array();
					preg_match_all("|(.*)(".$search.")(.*)|Uis",$translate_results["title"], $titles);
					if(isset($titles[1][0]))
					{
						$title=$titles[1][0];
					}
					if(isset($titles[2][0]))
					{
						$title="<span>".$titles[2][0]."</span>";
					}
					if(isset($titles[3][0]))
					{
						$title=$titles[3][0];
					}
		
					if($title=="")
					{
						$title=$translate_results["title"];
					}
					echo($title);

					echo("</div>");
					$mass_was[$rs->row["title"]]=1;
				}
				$rs->movenext();
			}
		}
	}
}

$db->close();
?>