<div class="Filpr">
<h2><{$page_title}></h2>

<ul class="warning">
<li><{"Are you sure you want to delete your album?"|t}></li>
<li><{"All photos in this album will be deleted."|t}></li>
</ul>

<{if $errors}>
<ul class="error">
<{foreach from=$errors item="error"}>
<li><{$error}></li>
<{/foreach}>
</ul>
<{/if}>

<form action="" method="post">

<div class="text-center"><input type="submit" name="delete" value="<{"Delete"|t}>" /></div>

<input type="hidden" name="token" value="<{$token}>" />

</form>
</div>