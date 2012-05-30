<div class="Profile">
	<div class="adminnavi">
		<a href="./index.php"><{$module_name}></a>
		&raquo;&raquo; <span class="adminnaviTitle"><a href="<{url controller="user_list"}>"><{"User list"|t}></a></span>
		&raquo;&raquo; <span class="adminnaviTitle"><{$page_title}></span>
	</div>

	<h2><{$page_title}></h2>

	<table class="outer">
		<{foreach from=$properties item="property"}>
			<tr>
				<td class="head"><{$property->get('label')|escape}></td>
				<td class="<{cycle values="odd,even"}>"><{$property->value}></td>
			</tr>
		<{/foreach}>
	</table>
</div>
