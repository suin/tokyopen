<div class="SocialMedia">
	<h2><{$page_title}></h2>
<{if $has_error == true}>
	<ul>
		<{foreach from=$errors item="error"}>
			<li><{$error|escape}></li>
		<{/foreach}>
	</ul>
<{/if}>
<table id="jtable">
<tr>
<th width="30%"><{"Title"|t}></th><th width="50%">URL</th><th width="20%"> </th>
</tr>
<tr>
<tbody id="jquery-ui-sortable">
	<{foreach item=menu from=$menuList name=loop}>
		<tr class="ui-state-default">
			<td class="td_title"><input type="text" class="title" name="title" value="<{$menu.title}>" size="30"></td>
			<td class="td_url"><input type="text" class="url" name="url" value="<{$menu.url}>" size="60"></td>
			<td class="td_delete"><img src = "<{$xoops_url}>/modules/footer/public/images/x_button.png" class="delete_line"></td>
		</tr>
	<{/foreach}>
	<{if $smarty.foreach.loop.total == 0}>
<tr class="ui-state-default">
	<td class="td_title">
		<input type="text" class="title" name="title" value="" size="30">
	</td>
	<td class="td_url">
		<input type="text" class="url" name="url" value="" size="60">
	</td>
	<td class="td_delete">
		<img src = "<{$xoops_url}>/modules/footer/public/images/x_button.png" class="delete_line">
	</td>
</tr>
	<{/if}>
</tbody>
<tr>

<td colspan="3">
</td>
</tr>
</table>
<div>
<img src = "<{$xoops_url}>/modules/footer/public/images/plus_button.png" class="add_line">
</div>

<div style="margin-top:10px;">
<input type="button" id="submitSortable" value="<{"register"|t}>">
</div>
</div>

<script>
<!--
jQuery( function() {
	jQuery( '#jquery-ui-sortable' ) . sortable();
	jQuery( '#jquery-ui-sortable' ) . disableSelection();
	jQuery( '#submitSortable' ) . click( function() {
		var itemTitles = '';
		var itemUrls = '';
		var values = '';
		jQuery( '#jquery-ui-sortable .td_title' ) . map( function() {
			itemTitles += jQuery( this ) .children( '.title' ) .attr('value') + ',';
		} );

		jQuery( '#jquery-ui-sortable .td_url' ) . map( function() {
			itemUrls += encodeURIComponent(jQuery( this ) .children( '.url' ) .attr('value')) + ',';
		} );
		location . href = 'index.php?controller=menu_list&update=true&titles=' + itemTitles + '&urls=' + itemUrls;
	} );
} );


$(function(){
$(".add_line").click(function(){
var row_data = "<tr class=\"ui-state-default\"><td class=\"td_title\"><input type=\"text\" class=\"title\" name=\"title\" value=\"\" size=\"30\"></td><td class=\"td_url\"><input type=\"text\" class=\"url\" name=\"url\" value=\"\" size=\"60\"></td><td class=\"td_delete\"><img src = \"<{$xoops_url}>/modules/footer/public/images/x_button.png\" class=\"delete_line\"></td></tr>"
$("#jquery-ui-sortable tr:last").after(row_data);
return false;
});
$(".delete_line").live('click',function(event) { 
	if( confirm( '<{"delete this?"|t}>' ) ){
		$(this).parent().parent().remove();
	}
	return false;
});
});
// -->
</script>



<style>
<!--
#jquery-ui-sortable {
	list-style-type: none;
	margin: 0;
	padding: 0;
	width: 70%;
}
#jquery-ui-sortable tr {
	margin: 0 3px 3px 3px;
	padding: 0.3em;
	padding-left: 1em;
	font-size: 15px;
	font-weight: bold;
	cursor: move;
}
-->
</style>

