<?if(!defined("site_root")){exit();}?>

<?
$user_bottom="</div></div>
</div>
</td>
</tr>
</table>";

if(file_exists($DOCUMENT_ROOT."/".$site_template_url."user_footer.tpl"))
{
	$user_bottom=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."user_footer.tpl");
}


echo($user_bottom);
?>