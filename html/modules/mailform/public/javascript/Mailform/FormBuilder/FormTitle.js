/**
 * @constructor
 */
Mailform.FormBuilder.FormTitle = function(dom) {
	this.dom = dom;
};

Mailform.FormBuilder.FormTitle.prototype = {
	
	/**
	 * @public
	 */
	dom: null,

	outlet: {
		preview: '#mailformTitlePreview',
		edit: '#mailformTitleEdit'
	},

	/**
	 *
	 * @public
	 */
	setUp: function() {
		this._registerEventListeners();
	},

	/**
	 *
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;
	
		$(this.dom)
			.delegate(this.outlet.preview, 'click', function(event){ return self._handleClickPreview(this, event); })
			.delegate(this.outlet.edit, 'blur', function(event){ return self._handleBlurEdit(this, event); })
			.delegate(this.outlet.edit, 'keydown', function(event){ return self._handleKeydownEdit(this, event); })
		;
	},

	/**
	 * プレビュー欄をクリックしたときのイベント
	 * @protected
	 */
	_handleClickPreview: function(preview, event) {
		var $preview = $(preview);

		var css = {
			'font-size': $preview.css('font-size'),
			'color': $preview.css('color')
		};

		$(this.outlet.preview).hide();
		$(this.outlet.edit).show().focus().css(css).select();
	},

	/**
	 * 編集欄からフォーカスアウトしたときのイベント
	 * @protected
	 */
	_handleBlurEdit: function(edit, event) {
		var title = $(this.outlet.edit).val();
		$(this.outlet.edit).hide();
		$(this.outlet.preview).text(title).show();
		Mailform.FormBuilder.application.form.set('title', title);
	},

	/**
	 * キーを押した時のイベント
	 * @protected
	 */
	_handleKeydownEdit: function(edit, event) {
		if ( event.keyCode == 13 ) {
			// Enter
			$(edit).blur();
		}
	}
};