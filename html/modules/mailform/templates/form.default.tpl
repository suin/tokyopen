<style type="text/css">

.mailformform { padding: 10px; }

</style>

<div class="mailform">
<h1><{$form_form.title}></h1>
<{if $form_form.header_description}>
	<div style="margin-top:10px;margin-bottom:10px;">
		<{$form_form.header_description|raw}>
	</div>
<{/if}>
<{if $form->hasError()}>
	<ul style="margin-bottom:10px;">
		<{foreach from=$form->getErrors() item="error"}>
			<li><{$error|escape}></li>
		<{/foreach}>
	</ul>
<{/if}>
<{form form=$form}>
  <table class="outer">
   <{foreach from=$form->getProperties() item="property"}>
    <tr>
     <td class="head"><{$property->getLabel()|escape}><{form_require property=$property sign="(required)"|t}></td>
     <td class="<{cycle values="odd,even"}>">
      <div<{if $property->hasError()}> class="penFormError"<{/if}>>
      <{form_input property=$property}>
      <{if $property->getDescription()}>
       <p class="description"><{$property->getDescription()}></p>
      <{/if}>
      </div>
     </td>
    </tr>
   <{/foreach}>
  </table>
  <div>
   <input type="submit" name="confirm" value="<{"Confirm"|t}>" />
  </div>
<input type="hidden" name="id" value="<{$id}>">
<{/form}>
</div>
