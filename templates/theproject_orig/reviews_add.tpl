
<form name="reviewsadd" id="reviewsadd"   Enctype="multipart/form-data">
<input type="hidden" name="id" value="{ID}">
<input type="hidden" name="login" value="{LOGIN}">

<div class="form_field">
<span><b>{WORD_NEW}:</b></span>
<textarea name="content" class='form-control'></textarea>
</div>


<div class="form_field">
<input class='btn btn-primary' type="button" onClick="reviews_add('reviewsadd');" value="{WORD_ADD}">
</div>
</form>