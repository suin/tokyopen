<div class="Flipr">
<h2><{$page_title}></h2>

<{if $user_roles.photo_new}>
<a href="<{url controller=$controller action="upload"}>"><{"Upload Photos"|t}></a>
<{/if}>

<div>
<{foreach from=$albums item="album"}>
<div class="Thumbnails">
<a href="<{url controller="photo_list" album=$album.id}>">
<span class="Thumbnail" style="width:<{$configs.thumb_width}>px; height:<{$configs.thumb_height}>px; <{if $album.cover.thumb_url}>background-image:url(<{$album.cover.thumb_url}>);<{/if}>">
<span class="Total"><{$album.total}></span>
</span>
<span class="Caption"><{$album.name}></span>
</a>
</div>
<{/foreach}>
</div>

<div class="clear"></div>

<{include file="pen:`$dirname`._pager.tpl"}>

</div>