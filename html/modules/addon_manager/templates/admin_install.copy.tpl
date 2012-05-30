<p><{"Finish Copy"|t}></p>

<{if (true)}>
<a href="<{$next_link.url}>">&raquo;<{$next_link.text}></a>
<{else}>
<script>
location.href="../../legacy/admin/index.php?action=ModuleInstall&dirname=<{$target_key}>";
</script>
<{/if}>