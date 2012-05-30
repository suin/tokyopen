<{if $errors}>
<ul class="error">
<{foreach from=$errors item="error"}>
<li><{$error}></li>
<{/foreach}>
</ul>
<{/if}>

<input type="hidden" name="options[0]" value="<{$options.0}>" />

<dl>

<dt><{"Album"|t}> :</dt>
<dd>
<select name="options[1]">
<{foreach from=$albums item="album"}>
<option value="<{$album.id}>"<{if $options.1 == $album.id}> selected="selected"<{/if}>><{$album.name}></option>
<{/foreach}>
</select>
</dd>

<{*

<dt><{"Photo Height"|t}> :</dt>
<dd><input type="text" name="options[2]" value="<{$options.2}>" size="5" maxlength="5" />px</dd>

<dt><{"Photo Width"|t}> :</dt>
<dd><input type="text" name="options[3]" value="<{$options.3}>" size="5" maxlength="5" />px</dd>

<dt><{"Keep Aspect Rate"|t}> :</dt>
<dd>
<label><input type="radio" name="options[4]" value="1" <{if $options.4 == 1}> selected="selected"<{/if}>/> <{"Yes"|t}></label>
<label><input type="radio" name="options[4]" value="2" <{if $options.4 == 2}> selected="selected"<{/if}>/> <{"No"|t}></label>
</dd>

*}>

</dl>
