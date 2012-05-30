<{strip}>
<div class="penForm">
	<{if $form->hasError()}>
	<ul>
		<{foreach from=$form->getErrors() item="error"}>
			<li><{$error|escape}></li>
		<{/foreach}>
	</ul>
	<{/if}>
	<{form form=$form}>
		<table class="outer">
				<tr>
					<td class="head"><{"Title"|t}></td>
					<td class="odd">
					<{$model->getVar('title')}>
					</td>
				</tr>
				<{if $typeName != ""}>
				<tr>
					<td class="head"><{"Type"|t}></td>
					<td class="even">
					<{$typeName}>
					</td>
				</tr>
				<{/if}>
			<{foreach from=$form->getProperties() item="property"}>
				<tr>
					<td class="head"><{$property->getLabelLocal()|escape}><{form_require property=$property sign="(required)"|t}></td>
					<td class="<{cycle values="odd,even"}>">
						<div<{if $property->hasError()}> class="penFormError"<{/if}>>
						<{form_input property=$property}>
						<{if $property->getDescription()}>
							<p class="description"><{$property->getDescriptionLocal()|escape}></p>
						<{/if}>
						</div>
					</td>
				</tr>
			<{/foreach}>
		</table>
		<div>
			<input type="submit" name="confirm" value="<{"Confirm"|t}>" />
		</div>
	<{/form}>
</div>
<{/strip}>
