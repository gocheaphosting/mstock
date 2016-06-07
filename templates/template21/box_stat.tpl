
			<h6>{lang.Stat}</h6>
			<ul>
				<li class="users">{lang.Photographers}: <a href="{SITE_ROOT}members/users_list.php">{USERS}</a></li>
				{if sitephoto}<li class="photo">{lang.Photos}: <a href="{SITE_ROOT}index.php?sphoto=1">{PHOTOS}</a></li>{/if}
				{if sitevideo}<li class="video">{lang.Videos}: <a href="{SITE_ROOT}index.php?svideo=1">{VIDEOS}</a></li>{/if}
				{if siteaudio}<li class="audio">{lang.Audio}: <a href="{SITE_ROOT}index.php?saudio=1">{AUDIO}</a></li>{/if}
				{if sitevector}<li class="vector">{lang.Vector}: <a href="{SITE_ROOT}index.php?svector=1">{VECTOR}</a></li>{/if}
			</ul>
