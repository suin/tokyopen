<div class="siteNavi">
	<input type="hidden" name="options[0]" value="<{$options.0}>" />
	<dl>
		<dt><{"Menu"|t}>:</dt>
		<dt>
			<{* 必要リファクタリング: refs #7407 *}>
			<{html_options name="options[1]" options=$routeOptions selected=$options.1}>
		</dt>
		<dt><{"Show parent link"|t}>:</dt>
		<dt>
			<{html_radios name="options[2]" options=$showParentOptions selected=$options.2}>
		</dt>
	</dl>
</div>