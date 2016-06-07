<form name="testimonialsadd" id="testimonialsadd" style="margin-bottom:25px"    Enctype="multipart/form-data">
<input type="hidden" name="login" value="{LOGIN}">

<div class="form_field">
<span><b>{WORD_NEW}:</b></span>
<textarea name="content" class='form-control'></textarea>
</div>

<div class="form_field">
<input class='btn btn-primary' type="button" onClick="testimonials_add('testimonialsadd');" value="{WORD_ADD}">
</div>

</form>