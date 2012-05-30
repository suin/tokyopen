<div class="Profile">
	<div class="adminnavi">
		<a href="./index.php"><{$module_name}></a>
		&raquo;&raquo; <span class="adminnaviTitle"><a href=""><{$page_title}></a></span>
	</div>
	<{*
	<ul class="toptab">
		<li><a href="<{url controller="property_edit"}>"><{"New property"|t}></a></li>
	</ul>
	*}>

	<table class="outer">
		<tr>
			<th><{"UID"|t}></th>
			<th><{"User name"|t}></th>
			<th><{"Real name"|t}></th>
			<th>&nbsp;</th>
		</tr>
		<{foreach from=$users item="user"}>
		<tr class="<{cycle values="odd,even"}>">
			<td><{$user->get('uid')}></td>
			<td><{$user->get('uname')|escape}></td>
			<td><{$user->get('name')|escape}></td>
			<td>
				<a href="<{url controller="user_view" id="1" user_id=$user->get('uid')}>"><{"view"|t}></a>
				<a href="<{url controller="user_edit" id="1" user_id=$user->get('uid')}>"><{"edit"|t}></a>
				<{*
				<a href="<{url controller="user_delete" id=$user->get('uid')}>"><{"delete"|t}></a>
				*}>
			</td>
		</tr>
		<{/foreach}>
	</table>
</div>

<{include file="pen:pengin._pager.tpl"}>
