<div class="Flipr">
<h2><{$page_title}></h2>

<form action="" method="post">
<table class="outer">
<{foreach from=$form item="form"}>
<tr>
<td class="head">
<div class="legacy_list_title"><{$form.title|t}></div>
</td>
<td class="<{cycle values="odd,even"}>">
<{if $form.type == "textbox"}>
<input type="text" name="<{$form.name}>" value="<{$input[$form.name]}>" size="100" maxlength="255" />
<{elseif $form.type == "textarea"}>
<textarea name="<{$form.name}>" cols="30" rows="5"><{$input[$form.name]}></textarea>
<{elseif $form.type == "select"}>
<select name="<{$form.name}>">
<{foreach from=$form.options key="value" item="label"}>
<option value="<{$value}>"<{if $input[$form.name] == $value}> selected="selected"<{/if}>><{$label}></option>
<{/foreach}>
</select>
<{elseif $form.type == "yesno"}>
<input type="radio" name="<{$form.name}>" value="1"<{if $input[$form.name] == 1}> checked="checked"<{/if}>><{"Yes"|t}>
<input type="radio" name="<{$form.name}>" value="0"<{if $input[$form.name] == 0}> checked="checked"<{/if}>><{"No"|t}>
<{elseif $form.type == "date"}>
<input type="text" name="<{$form.name}>" value="<{$input[$form.name]}>" size="10" maxlength="10" />
<{elseif $form.type == "datetime"}>
<input type="text" name="<{$form.name}>" value="<{$input[$form.name]}>" size="19" maxlength="19" />
<{/if}>

<{if $form.desc}>
<p class="legacy_list_description"><{$form.desc|t}></p>
<{/if}>

</td>
</tr>
<{/foreach}>

<tr>
<td class="foot" colspan="2">
<input type="submit" value="<{"Save"|t}>" name="save" class="formButton" />
</td>
</tr>

</table>


</div>