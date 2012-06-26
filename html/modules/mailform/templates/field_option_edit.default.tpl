<div>
	<{if $errors}>
		<ul>
			<{foreach from=$errors item="error"}>
				<li><{$error}></li>
			<{/foreach}>
		</ul>
	<{/if}>
	<form class="mailformFieldOptionEditForm" onsubmit="return false;">
		<{$editHTML|raw}>
	</form>
</div>
