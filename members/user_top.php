<?if(!defined("site_root")){exit();}?>
<script type="text/javascript" language="JavaScript">

function add_friend(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('friendbox').innerHTML =req.responseText;
        }
    }
    req.open(null, '<?=site_root?>/members/friends_add.php', true);
    req.send( { friend: value } );
}


function delete_friend(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('friendbox').innerHTML =req.responseText;
        }
    }
    req.open(null, '<?=site_root?>/members/friends_delete2.php', true);
    req.send( { friend: value } );
}




</script>

<script type="text/javascript" src="<?=site_root?>/inc/js/raty/jquery.raty.min.js"></script>

<script type="text/javascript">
    $(function() {
      $.fn.raty.defaults.path = '<?=site_root?>/inc/js/raty/img';

      $('.star').raty({ score: 5 });
      
    });
    
    function vote_rating(id,score)
    {
    	<?
    	if(!isset($_SESSION["people_id"]) and $global_settings["users_rating_limited"])
		{
		?>	
			location.href='<?=site_root?>/members/login.php';
		<?
		}
		else
		{
    	?>
    		var req = new JsHttpRequest();
        
    		// Code automatically called on load finishing.
   	 		req.onreadystatechange = function()
    		{
        		if (req.readyState == 4)
        		{
				
        		}
    		}
    		req.open(null, '<?=site_root?>/members/vote_user.php', true);
    		req.send( {id: id,vote:score } );
    	<?
    	}
    	?>
   	}
</script>


<?
$nameuser="";
$utype="";
$sql="select id_parent,login,avatar,photo,name,lastname,city,country,data1,website,company,utype,rating from users where id_parent=".(int)$user_id;
$rs->open($sql);
if(!$rs->eof)
{
	$nameuser=user_url($rs->row["login"]);
	$utype=$rs->row["utype"];
	$userbox=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."user.tpl");


	$boxauthor="";
	
	$user_name=show_user_name($rs->row["login"]);
	
	if($rs->row["avatar"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["avatar"]))
	{
		$boxauthor.="<img src='".$rs->row["avatar"]."' align='absMiddle' width='".$global_settings["avatarwidth"]."' border='0'>&nbsp;".$user_name;
	}
	else
	{
		$boxauthor.=$user_name;
	}
	$userbox=str_replace("{AUTHOR}",$boxauthor,$userbox);

	$userbox=str_replace("{USERID}",$rs->row["id_parent"],$userbox);
	$userbox=str_replace("{SITE_ROOT}",site_root,$userbox);
	$userbox=str_replace("{WORD_NAME}",word_lang("name"),$userbox);
	$userbox=str_replace("{NAME}",$rs->row["name"]." ".$rs->row["lastname"],$userbox);
	$userbox=str_replace("{WORD_ADDRESS}",word_lang("address"),$userbox);
	$userbox=str_replace("{CITY}",$rs->row["city"],$userbox);
	$userbox=str_replace("{COUNTRY}",$rs->row["country"],$userbox);
	$userbox=str_replace("{WORD_WEBSITE}",word_lang("website"),$userbox);
	$userbox=str_replace("{WEBSITE}","<a href='http://".str_replace("http://","",$rs->row["website"])."'>".$rs->row["website"]."</a>",$userbox);
	$userbox=str_replace("{WORD_DATE}",word_lang("date of registration"),$userbox);
	$userbox=str_replace("{DATE}",date(date_short,$rs->row["data1"]),$userbox);
	$userbox=str_replace("{COMPANY}",$rs->row["company"],$userbox);


	if($rs->row["photo"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"]))
	{
		$userbox=str_replace("{IMAGE}","<img src='".$rs->row["photo"]."'>",$userbox);
	}
	else
	{
		$userbox=str_replace("{IMAGE}","",$userbox);
	}

	$userbox=str_replace("{WORD_PERSONAL_INFORMATION}",word_lang("contacts information"),$userbox);
	$userbox=str_replace("{WORD_PORTFOLIO}",word_lang("member portfolio"),$userbox);
	$userbox=str_replace("{WORD_TOOLS}",word_lang("tools"),$userbox);
	$userbox=str_replace("{WORD_PHOTO}",word_lang("photos"),$userbox);
	$userbox=str_replace("{WORD_VIDEO}",word_lang("videos"),$userbox);
	$userbox=str_replace("{WORD_AUDIO}",word_lang("audio"),$userbox);
	$userbox=str_replace("{WORD_VECTOR}",word_lang("vector"),$userbox);
	$userbox=str_replace("{WORD_VIEWED}",word_lang("viewed"),$userbox);
	$userbox=str_replace("{WORD_DOWNLOADS}",word_lang("downloads"),$userbox);
	$userbox=str_replace("{WORD_REVIEWS}",word_lang("reviews"),$userbox);
	$userbox=str_replace("{WORD_MAIL}",word_lang("sitemail to user"),$userbox);
	$userbox=str_replace("{WORD_TESTIMONIAL}",word_lang("add a testimonial"),$userbox);
	$userbox=str_replace("{WORD_COMPANY}",word_lang("company"),$userbox);
	
	$viewed_count=0;
	$downloaded_count=0;

//Photo count
if($global_settings["allow_photo"])
{
	$sql="select id_parent,viewed,downloaded from photos where published=1 and author='".$rs->row["login"]."'";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$userbox=str_replace("{PHOTO}",strval($dr->rc),$userbox);
		while(!$dr->eof)
		{
			$viewed_count+=$dr->row["viewed"];
			$downloaded_count+=$dr->row["downloaded"];
			$dr->movenext();
		}
	}
	else
	{
		$userbox=str_replace("{PHOTO}",strval(0),$userbox);
	}
}

//Video count
if($global_settings["allow_video"])
{
	$sql="select id_parent,viewed,downloaded from videos where published=1 and author='".$rs->row["login"]."'";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$userbox=str_replace("{VIDEO}",strval($dr->rc),$userbox);
		while(!$dr->eof)
		{
			$viewed_count+=$dr->row["viewed"];
			$downloaded_count+=$dr->row["downloaded"];
			$dr->movenext();
		}
	}
	else
	{
		$userbox=str_replace("{VIDEO}",strval(0),$userbox);
	}
}


//Audio count
if($global_settings["allow_audio"])
{
	$sql="select id_parent,viewed,downloaded from audio where published=1 and author='".$rs->row["login"]."'";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$userbox=str_replace("{AUDIO}",strval($dr->rc),$userbox);
		while(!$dr->eof)
		{
			$viewed_count+=$dr->row["viewed"];
			$downloaded_count+=$dr->row["downloaded"];
			$dr->movenext();
		}
	}
	else
	{
		$userbox=str_replace("{AUDIO}",strval(0),$userbox);
	}
}


//Vector count
if($global_settings["allow_vector"])
{
	$sql="select id_parent,viewed,downloaded from vector where published=1 and author='".$rs->row["login"]."'";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$userbox=str_replace("{VECTOR}",strval($dr->rc),$userbox);
		while(!$dr->eof)
		{
			$viewed_count+=$dr->row["viewed"];
			$downloaded_count+=$dr->row["downloaded"];
			$dr->movenext();
		}
	}
	else
	{
		$userbox=str_replace("{VECTOR}",strval(0),$userbox);
	}
}

	$userbox=str_replace("{VIEWED}",strval($viewed_count),$userbox);
	$userbox=str_replace("{DOWNLOADS}",strval($downloaded_count),$userbox);



	//Reviews count
	$reviews_count=0;

	$userbox=str_replace("{REVIEWS}",strval($reviews_count),$userbox);


	//Mail link
	if(isset($_SESSION["people_id"]))
	{
		$userbox=str_replace("{MAIL_LINK}",site_root."/members/messages_new.php?user=".$rs->row["login"],$userbox);
	}
	else
	{
		$userbox=str_replace("{MAIL_LINK}",site_root."/members/login.php",$userbox);
	}


	$userbox=format_layout($userbox,"sitephoto",$global_settings["allow_photo"]);
	$userbox=format_layout($userbox,"sitevideo",$global_settings["allow_video"]);
	$userbox=format_layout($userbox,"siteaudio",$global_settings["allow_audio"]);
	$userbox=format_layout($userbox,"sitevector",$global_settings["allow_vector"]);
	$userbox=format_layout($userbox,"messages",$global_settings["messages"]);
	$userbox=format_layout($userbox,"reviews",$global_settings["reviews"]);
	$userbox=format_layout($userbox,"testimonials",$global_settings["testimonials"]);
	$userbox=format_layout($userbox,"friends",$global_settings["friends"]);
	
	$userbox=format_layout($userbox,"rating",$global_settings["users_rating"]);
	
	
	$rating_text="<script>
    			$(function() {
      				$('#star".$rs->row["id_parent"]."').raty({
      				score: ".(float)$rs->row["rating"].",
 					half: true,
  					number: 5,
  					click: function(score, evt) {
    					vote_rating(".$rs->row["id_parent"].",score);
  					}
				});
    			});
				</script>
				<div id='star".$rs->row["id_parent"]."' class='users_rating' style='display:inline'></div>";
	
	$userbox=str_replace("{RATING}",$rating_text,$userbox);

	

	$flag_seller=false;
	if($utype=="seller" or $utype=="common")
	{
		$flag_seller=true;
	}
	$userbox=format_layout($userbox,"seller",$flag_seller);

	$flag_notuser=true;
	if(isset($_SESSION["people_login"]) and $rs->row["login"]==$_SESSION["people_login"])
	{
		$flag_notuser=false;
	}
	$userbox=format_layout($userbox,"notuser",$flag_notuser);

//Friend link
	if(isset($_SESSION["people_id"]))
	{
		$sql="select friend1,friend2 from friends where friend1='".result($_SESSION["people_login"])."' and friend2='".$rs->row["login"]."'";
		$dr->open($sql);
		if($dr->eof)
		{
			$userbox=str_replace("{WORD_FRIEND}",word_lang("add to friends"),$userbox);
			$userbox=str_replace("{FRIEND_LINK}","javascript:add_friend('".$rs->row["login"]."')",$userbox);
		}
		else
		{
			$userbox=str_replace("{WORD_FRIEND}",word_lang("delete from friends"),$userbox);
			$userbox=str_replace("{FRIEND_LINK}","javascript:delete_friend('".$rs->row["login"]."')",$userbox);
		}
	}
	else
	{
		$userbox=str_replace("{WORD_FRIEND}",word_lang("add to friends"),$userbox);
		$userbox=str_replace("{FRIEND_LINK}",site_root."/members/login.php",$userbox);
	}


	if(isset($_SESSION["people_id"]))
	{
		$userbox=str_replace("{TESTIMONIAL_LINK}",site_root."/testimonials/".$nameuser.".html",$userbox);
	}
	else
	{
		$userbox=str_replace("{TESTIMONIAL_LINK}",site_root."/members/login.php",$userbox);
	}



	$userbox=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$userbox);
	$userbox=translate_text($userbox);
	echo($userbox);
	$userbox="";
}
?>





















<div class="tabs_border2">

<div id="tabs_menu">
<ul>
<li <?if($site=="user"){echo("class='activno'");}?>><a href="<?=site_root?>/users/<?=$nameuser?>.html"><?=word_lang("about")?></a></li>
<li><a href="<?=site_root?>/index.php?user=<?=$user_id?>&portfolio=1"><?=word_lang("portfolio")?></a></li>
<?if($global_settings["blog"]){?>
<li <?if($site=="user_blog"){echo("class='activno'");}?>><a href="<?=site_root?>/blog/<?=$nameuser?>.html"><?=word_lang("blog")?></a></li>
<?}?>
<?if($global_settings["testimonials"]){?>
<li <?if($site=="user_testimonials"){echo("class='activno'");}?>><a href="<?=site_root?>/testimonials/<?=$nameuser?>.html"><?=word_lang("testimonials")?></a></li>
<?}?>
<?if($global_settings["friends"]){?>
<li <?if($site=="user_friends"){echo("class='activno'");}?>><a href="<?=site_root?>/friends/<?=$nameuser?>.html"><?=word_lang("friends")?></a></li>
<?}?>


</ul>
</div>
<div id="tabs_menu_content">




