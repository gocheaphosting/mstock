<?
//Check access
admin_panel_access("settings_printful");

if(!defined("site_root")){exit();}
?>

<p>The new prints orders are not sent to printful service automatically. You have 2 ways to place them:</p>

<ul>
	<li><b>Manually</b> in <a href="index.php?d=2">Orders</a> section</li>
	<li>Set a <b>Cron task</b> on your server which will move all new orders to printful with some periodicity (once per day for example).</li>
</ul>

<p>
You can find the cron script here: 
<b><?=site_root?>/members/cron_printful.php</b>
</p>


<p><b>Examples of the cron commands:</b></p>

<ul>
<li>/usr/bin/lynx -source <?=surl?><?=site_root?>/members/cron_printful.php</li>
<li>GET <?=surl?><?=site_root?>/members/cron_printful.php > /dev/null</li>
</ul>
