<div class="penForm">
	<{if $errors}>
	<ul>
		<{foreach from=$errors item="error"}>
			<li><{$error|escape}></li>
		<{/foreach}>
	</ul>
	<{/if}>

	<form action="<{$formAction}>" method="post" id="<{$formId}>">
		<input type="hidden" name="token" value="<{$token}>" />
		<div><{"Are you sure to delete?"|t}></div>
		<div><{$extraMessage}></div>

		<div style="text-align:center;margin: 10px 0;">
			<input type="submit" name="delete" value="<{"Delete"|t}>" />
		</div>
	</form>
</div>
