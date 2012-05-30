<div class="Flipr">
<h2><{$page_title}></h2>

<{if $user_roles.photo_edit || $xoops_userid == $photo.user_id}>
<a href="<{url controller="photo_edit" album=$album.id photo=$photo.id}>"><{"Edit Photo"|t}></a>
<{/if}>
<{if $user_roles.photo_delete || $xoops_userid == $photo.user_id}>
<a href="<{url controller="photo_delete" photo=$photo.id}>"><{"Delete Photo"|t}></a>
<{/if}>

<div class="PhotoBox">
<a href="<{$photo.url}>" class="FancyBox"><img src="<{$photo.url}>" class="Photo" width="480" /></a></div>
</div>


<table class="outer">

<tr>
<td class="head"><{"Poster"|t}></td>
<td class="<{cycle values="odd,even"}>"><a href="<{$xoops_url}>/userinfo.php?uid=<{$photo.user_id}>"><{$photo.user_id|xoops_user:"uname"}></a></td>
</tr>

<tr>
<td class="head"><{"Created Date"|t}></td>
<td class="<{cycle values="odd,even"}>"><{$photo.created|xoops_formattimestamp:l}></td>
</tr>

<tr>
<td class="head"><{"Modified Date"|t}></td>
<td class="<{cycle values="odd,even"}>"><{$photo.modified|xoops_formattimestamp:l}></td>
</tr>

<tr>
<td class="head"><{"Description"|t}></td>
<td class="<{cycle values="odd,even"}>"><{$photo.desc|nl2br}></td>
</tr>

</table>
