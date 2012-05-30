<div class="Profile">
	<h2><{$set->get('title')|escape}></h2>
	<table class="outer">
	<{include file="pen:profile._user_info.tpl"}>
	<{* from profile *}>
		<{foreach from=$properties item="property"}>
				<tr>
					<td class="head"><{$property->get('label')|escape}></td>
					<td class="<{cycle values="odd,even"}>"><{$property->value}></td>
				</tr>
		<{/foreach}>
	</table>
	<{if $xoops_userid ==  $user->get('uid')}>
	<a href="<{url controller="profile_edit" id=$set->get('id')}>"><{"Edit Profile"|t}></a>
	<{/if}>

</div>
