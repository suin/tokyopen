<div class="Flipr">
<h2><{$page_title}></h2>

<div><{$album.desc}></div>

<{if $user_roles.photo_new}>
<a href="<{url controller="photo_new" album=$album_id}>"><{"Upload Photos"|t}></a>
<{/if}>
<{if $user_roles.album_edit}>
<a href="<{url controller="album_edit" album=$album_id}>"><{"Edit Album"|t}></a>
<{/if}>
<{if $user_roles.album_delete}>
<a href="<{url controller="album_delete" album=$album_id}>"><{"Delete Album"|t}></a>
<{/if}>

<div>
<{foreach from=$photos item="photo"}>
<div class="Thumbnails">
<a href="<{url controller="photo" photo=$photo.id}>">
<span class="Thumbnail" style="width:<{$configs.thumb_width}>px; height:<{$configs.thumb_height}>px; <{if $photo.thumb_url}>background-image:url(<{$photo.thumb_url}>);<{/if}>">&shy;</span>
<span class="Caption"><{$photo.name}></span>
</a>
</div>
<{/foreach}>
</div>

<div class="clear"></div>

<{include file="pen:`$dirname`._pager.tpl"}>

</div>