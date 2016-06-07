<form name="telladd" id="telladd"  Enctype="multipart/form-data">
<input type="hidden" name="id" value="{ID}">

<div class="form_field">
<span>{YOUR NAME}:</span>
<input class='form-control' type="text" name="name" class="ft" value="">
</div>

<div class="form_field">
<span>{YOUR EMAIL}:</span>
<input class='form-control' type="text" name="email" class="ft">
</div>

<div class="form_field">
<span>{FRIEND NAME}:</span>
<input class='form-control' type="text" name="name2" class="ft">
</div>

<div class="form_field">
<span>{FRIEND EMAIL}:</span>
<input class='form-control' type="text" name="email2" class="ft">
</div>

<div class="form_field">
<img src="{SITE_ROOT}images/c{RR}.gif" width="80" height="30">
<input class='form-control' name="rn1" type="text" value=""><input name="rn2" type="hidden" value="{RR}">
</div>

<div class="form_field">
<input class='btn btn-primary' type="button" onClick="tell_add('telladd');" value="{SEND}">
</div>

</form>