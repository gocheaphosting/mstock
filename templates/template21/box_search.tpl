<script language="javascript">

function search_go(value)
{
	document.getElementById('search').value=value;
	$('#site_search').submit();
}

function show_search()
{
	var req = new JsHttpRequest();
	
	 req.onreadystatechange = function()
    {
        if (req.readyState == 4)
        {
			search_result=req.responseText
			if(search_result!="")
			{
				$('#instant_search').slideDown("fast");
				document.getElementById('instant_search').innerHTML =search_result;
			}
			else
			{
				document.getElementById('instant_search').style.display='none';
			}
        }
    }
    req.open(null, '{SITE_ROOT}members/search_lite.php', true);
    req.send( { search: document.getElementById('search').value } );
}


</script>
<form method='get' action='{SITE_ROOT}index.php' id='site_search'>
	<div class="ibox_search">
		<input type='text' name='search' id="search" value='{SEARCH}' onClick="this.value='';" autocomplete="off">
	</div>

	<input class="ibox_search_submit" type='submit' value=''>
</form>
<div id="instant_search"></div>