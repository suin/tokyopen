<div class="Nano">
<script src="<{$smarty.const.TP_JS_VENDOR_URL}>/nicEdit_ja/nicEdit.js" type="text/javascript"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	var TOP_OFFSET = 29;

	var form = '#nanoEditorForm';
	var panel = '#nanoEditorPanel';
	var panelSpace = '#nanoEditorPanelSpace';
	var textarea = '#nanoEditorTextarea';
	var button = '#nanoEditorSaveButton';
	var titleInput = '#nanoEditorForm input[name=title]';
	var contentInput = '#nanoEditorForm input[name=content]';
	var adminTaskBar = '#adminTaskBarLinkNanoSave a';

	var isModified = false;

	var editor = new nicEditor({
	});
	editor.setPanel(panel.substr(1))
	editor.addInstance(textarea.substr(1));
	editor.addEvent('focus', function(a) {
		isModified = true;
	});

	jQuery(function() {

		var panelHomePosition = $(panel).offset();

		$(form).show();
		$(form).submit(function(){
			isModified = false;
			$(contentInput).val(nicEditors.findEditor(textarea.substr(1)).getContent());
		});
		$(button).click(function(){
			$(form).submit();
		});
		$(adminTaskBar).click(function(){
			$(form).submit();
			return false;
		});
		$(titleInput).focus(function(){
			isModified = true;
		});
	
		$(window).bind('beforeunload', function(event) {
			if ( isModified == true ) {
				return $(form).attr('leaveConfirmMassage');
			}
		});

		$(window).scroll(function () {
			var top = $(this).scrollTop() + TOP_OFFSET;

			if ( top >= panelHomePosition.top ) {
				// パネルが画面外に出たら
				var textAreaWidth    = $(textarea).width();
				var textAreaPosition = $(textarea).offset();
				var panelHeight      = $(panel).height();

				$(panel).css({
					'position':'absolute',
					'top': top + 'px',
					'left': textAreaPosition.left + 'px',
					'width': textAreaWidth +'px'
				});
				
				$(panelSpace).show().css({
					'height': panelHeight + 'px'
				});

			} else {
				// パネルが画面内に戻ってきたら
				$(panel).css({
					'position':'relative',
					'top': 'auto',
					'left': 'auto'
				});
				
				$(panelSpace).hide();
			}
		});
	});
});
</script>

	<{if $errors}>
		<ul class="error">
			<{foreach from=$errors item="error"}>
				<li><{$error}></li>
			<{/foreach}>
		</ul>
	<{/if}>

	<div class="text-right">
		<input type="button" value="<{"Save Contents"|t}>" id="nanoEditorSaveButton" />
	</div>

	<div id="nanoEditorPanel" style="z-index: 999; position: relative;"></div>
	<div id="nanoEditorPanelSpace" style="display:none;"></div>
	<form action="" method="post" id="nanoEditorForm" style="display:none;border: 1px solid #efefef; border-top:none;" leaveConfirmMassage="<{"Your changes will be lost if you don't save them."|t}>">
		<input type="hidden" name="token" value="<{$token}>" />
		<input type="hidden" name="save" value="1" />
		<input type="hidden" name="content" value="" />

		<h2>
			<input type="text" name="title" value="<{$input.title}>" id="nanoEditorTitle" title="<{"Title"|t}>" style="width:99%;margin:0 0 10px 0;font-size: 20px; border:none;" />
		</h2>
		<div id="nanoEditorTextarea"><{$input.content|raw}></div>
	</form>
	
	<noscript><{"You must have JavaScript enabled to view this page as intended."|t}></noscript>
</div>