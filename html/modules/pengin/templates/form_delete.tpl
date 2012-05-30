<div class="penForm">
	<h2><{$page_title}></h2>
	
	<{if $errors}>
	<ul>
		<{foreach from=$errors item="error"}>
			<li><{$error|escape}></li>
		<{/foreach}>
	</ul>
	<{/if}>

	<form action="" method="post">
		<input type="hidden" name="token" value="<{$token}>" />
		<div>
			<{"Are you sure to delete?"|t}>
		</div>

		<div>
			<input type="submit" name="cancel" value="<{"Cancel"|t}>" />
			<input type="submit" name="delete" value="<{"Delete"|t}>" />
		</div>
	</form>
</div>
