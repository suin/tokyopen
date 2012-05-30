
<div class="Flipr">
<div class="FeaturedSlide">
<{foreach from=$photos item="photo"}>
<div class="FeaturedBox">
<a href="<{url controller="photo" photo=$photo.id}>"><img src="<{$photo.url}>" alt="" /></a>
<div class="Floater">
<h2><{$photo.name}></h2>
<p class="Description"><{$photo.desc}></p>
<p class="ReadMore"><a href="<{url controller="photo" photo=$photo.id}>"><{"more"|t}> &raquo;</a></p>
</div>
</div>
<{/foreach}>
</div>

</div>
