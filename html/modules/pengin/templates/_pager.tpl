<div class="pengin-pager">
<{foreach from=$pages item="page"}>
<{if $page.name == "current"}>
<strong><{$page.label}></strong>
<{elseif $page.is_link}>
<a href="<{$page.url}>" class="pager-next active"><{$page.label}></a>
<{else}>
<{$page.label}>
<{/if}>
<{/foreach}>
</div>