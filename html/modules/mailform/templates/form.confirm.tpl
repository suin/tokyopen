<style type="text/css">

.mailformform { padding: 10px; }

</style>

<div class="mailform">
<h1><{$form_form.title}></h1>
<div style="margin-top:10px;margin-bottom:10px;">
<{"Please review the details entered."|t}>
</div>
	<{form form=$form}>
		<table class="outer">
			<{foreach from=$form->getProperties() item="property"}>
				<tr>
					<td class="head" style="width:30%;"><{$property->getLabel()|escape}><{form_require property=$property sign="(required)"|t}></td>
					<td class="<{cycle values="odd,even"}>" style="width:70%;"><{$property->describeValueLocal()|escape|nl2br}></td>
				</tr>
			<{/foreach}>
		</table>
		<div>
			<input type="submit" name="back" value="<{"Back"|t}>" />
			<input type="submit" name="submit" value="<{"Send Mail"|t}>" />
		</div>
<input type="hidden" name="id" value="<{$id}>">
	<{/form}>

</div>
