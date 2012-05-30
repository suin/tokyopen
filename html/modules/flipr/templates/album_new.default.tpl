<div class="Filpr">
<h2><{$page_title}></h2>

<{if $errors}>
<ul class="error">
<{foreach from=$errors item="error"}>
<li><{$error}></li>
<{/foreach}>
</ul>
<{/if}>

<form action="" method="post">
<table class="outer">
<tr>
<td class="head"><{"Name"|t}></td>
<td class="odd"><input type="text" name="name" value="<{$input.name}>" size="40" /></td>
</tr>
<tr>
<td class="head"><{"Description"|t}></td>
<td class="odd"><textarea name="desc" cols="65" rows="20"><{$input.desc}></textarea></td>
</tr>
</table>
<div class="text-center"><input type="submit" name="save" value="<{"Save"|t}>" /></div>

<input type="hidden" name="token" value="<{$token}>" />

</form>
</div>