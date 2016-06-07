<form name="telladd" id="telladd"  Enctype="multipart/form-data">
<input type="hidden" name="id" value="{ID}">

<div class="xitem">
{YOUR NAME}:<br>
<input class='ibox form-control' type="text" name="name" class="ft" value="" style="width:265px">
</div>

<div class="xitem">
{YOUR EMAIL}:<br>
<input class='ibox form-control' type="text" name="email" class="ft" style="width:265px">
</div>

<div class="xitem">
{FRIEND NAME}:<br>
<input class='ibox form-control' type="text" name="name2" class="ft" style="width:265px">
</div>

<div class="xitem">
{FRIEND EMAIL}:<br>
<input class='ibox form-control' type="text" name="email2" class="ft" style="width:265px">
</div>

<div class="xitem">
<img src="{SITE_ROOT}images/c{RR}.gif" width="80" height="30">
<input class='ibox form-control' name="rn1" type="text" value="" size="20"><input name="rn2" type="hidden" value="{RR}">
</div>

<div class="xitem">
<input class='isubmit' type="button" onClick="tell_add('telladd');" value="{SEND}">
</div>

</form>