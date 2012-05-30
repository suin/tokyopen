<{strip}>
<div class="adminTaskBarBox">
	<div class="adminTaskBarControls">
		<ul class="adminTaskBarNav">
			<{foreach from=$links item="link"}>
			        <{if $link.is_modal}>
					<{*if $link.menu == 0 && $link.name != "DrugDrop" && $link.name != "DrugDropCancel"*}>
						<li id="tpModal<{$link.name}>">
					<{else}>
						<li id="tpNoModal<{$link.name}>" class="tpNoModalBlocksAdmin">
					<{/if}>
					
					<{if $link.name == "DrugDrop"}>
						<a href=""><{$link.label}></a>
					<{elseif $link.url == ""}>
						<span class="menu"><{$link.label}></span>
					<{else}>
						<a href="<{$link.url}>"><{$link.label}></a>
					<{/if}>
					
					<{if $link.menu == 1}>
					<ul class="subBlocksAdmin">
					<{foreach from=$subLinks[$link.name] item="sublink"}>
						<li id="<{$sublink.name}>">
						<a href="<{$sublink.url}>"><{$sublink.label}></a>
						</li>
					<{/foreach}>
					</ul>
					<{/if}>
				</li>
			<{/foreach}>
		</ul>
	</div>
</div>
<{/strip}>
