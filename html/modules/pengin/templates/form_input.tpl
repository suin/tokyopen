<div class="penForm">
	<{if $form->getTitleLocal()}>
		<h1><{$form->getTitleLocal()|escape}></h1>
	<{else}>
		<h1><{$page_title}></h1>
	<{/if}>
	
	<{if $form->hasError()}>
	<ul class="error">
		<{foreach from=$form->getErrors() item="error"}>
			<li><{$error|escape}></li>
		<{/foreach}>
	</ul>
	<{/if}>
	<{form form=$form}>
		<table class="outer">
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
