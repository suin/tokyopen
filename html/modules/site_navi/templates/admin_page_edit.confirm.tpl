<{strip}>
<div class="penForm">
	<{form form=$form}>
		<table class="outer">
			<{foreach from=$form->getProperties() item="property"}>
				<tr>
					<td class="head"><{$property->getLabelLocal()|escape}><{form_require property=$property sign="(required)"|t}></td>
					<td class="<{cycle values="odd,even"}>"><{$property->describeValueLocal()|escape|nl2br}></td>
				</tr>
			<{/foreach}>
		</table>
		<div>
			<input type="submit" name="back" value="<{"Back"|t}>" />
			<input type="submit" name="submit" value="<{"Submit"|t}>" />
		</div>
	<{/form}>
</div>
<{/strip}>
