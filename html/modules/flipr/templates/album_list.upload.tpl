<div class="Flipr">
<h2><{$page_title}></h2>

<p><{"Select album to upload."|t}></p>

<{if $user_roles.album_new}>
<a href="<{url controller="album_new"}>"><{"New Album"|t}></a>
<{/if}>

<div>
<{foreach from=$albums item="album"}>
<div class="Thumbnails">
<a href="<{url controller="photo_new" album=$album.id}>">
<span class="Thumbnail" style="width:<{$configs.thumb_width}>px; height:<{$configs.thumb_height}>px; <{if $album.cover.thumb_url}>background-image:url(<{$album.cover.thumb_url}>);<{/if}>">&shy;</span>
<span class="Caption"><{$album.name}></span>
</a>
</div>
<{/foreach}>
</div>

<div class="clear"></div>

<{include file="pen:`$dirname`._pager.tpl"}>

</div>