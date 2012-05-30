<div class="siteNavi">
	<{if $hasMenu}>
		<ul class="sideMenu">
			<{if $showParent}>
				<li class="parent">
					<a href="<{$parent->get('url')|escape}>"><{$parent->get('title')|escape}></a>
				</li>
			<{/if}>
			<{foreach from=$children item="child" name="children"}>
				<li class="children">
					<a href="<{$child->get('url')|escape}>"><{$child->get('title')|escape}></a>
				</li>
			<{/foreach}>
		</ul>
	<{/if}>
</div>