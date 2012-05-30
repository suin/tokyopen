<div class="Profile">
	<div class="adminnavi">
		<a href="./index.php"><{$module_name}></a>
		&raquo;&raquo; <span class="adminnaviTitle"><a href=""><{$page_title}></a></span>
	</div>
	
	<ul class="toptab">
		<li><a href="<{url controller="property_edit"}>"><{"New property"|t}></a></li>
	</ul>

	<table class="outer">
		<tr>
			<th><{"Label"|t}></th>
			<th><{"Type"|t}></th>
			<th><{"Required"|t}></th>
			<th><{"Description"|t}></th>
			<th>&nbsp;</th>
		</tr>
		<{foreach from=$properties item="property"}>
		<tr class="<{cycle values="odd,even"}>">
			<td><{$property->get('label')|escape}></td>
			<td><{$property->get('type')|t}></td>
			<td><{$property->describeRequired()|t}></td>
			<td><{$property->get('description')|escape}></td>
			<td>
				<a href="<{url controller="property_edit" id=$property->get('id')}>"><{"edit"|t}></a>
				<a href="<{url controller="property_delete" id=$property->get('id')}>"><{"delete"|t}></a>
			</td>
		</tr>
		<{/foreach}>
	</table>
</div>
