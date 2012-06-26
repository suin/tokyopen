/**
 * @constructor
 */
Mailform.FormBuilder.FormDescription = function(dom) {
	this.dom = dom;
	this.nicEditInstance = null;
};

Mailform.FormBuilder.FormDescription.prototype = {
	
	/**
	 * @public
	 */
	dom: null,

	/**
	 * アウトレット
	 * @enum
	 * @type {Object}
	 */
	outlet: {
		preview: '#mailformDescriptionPreview'
	},

	/**
	 * NicEditのインスタンス
	 * @protected
	 * @type {bkClass}
	 */
	nicEditInstance: null,

	/**
	 * セットアップする
	 * @public
	 */
	setUp: function() {
		this._addNicEditInstance();
		this._registerEventListeners();
	},

	/**
	 * NicEditのインスタンスを追加する
	 * @protected
	 */
	_addNicEditInstance: function() {
		var app = Mailform.FormBuilder.application;
		this.nicEditInstance = app.editorPanel.nicEdit.addInstance(this.outlet.preview.substr(1));
	},

	/**
	 * イベントリスナを登録する
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;

		$(this.dom)
			.delegate(this.outlet.preview, 'click', function(event){ return self._handleClickPreview(this, event); })
		;
		
		this.nicEditInstance.addEvent('blur', function(bkClass){ self._handleBlurPreview(bkClass); });
	},

	/**
	 * プレビュー欄をクリックしたときのイベント
	 * @protected
	 */
	_handleClickPreview: function(preview, event) {
		var app = Mailform.FormBuilder.application;
		app.editorPanel.floatBefore(this.outlet.preview);
	},

	/**
	 * プレビュー欄からフォーカスアウトしたときのイベント
	 * @protected
	 */
	_handleBlurPreview: function(bkClass) {
		if ( !bkClass ) {
			return;
		}

		var editorId = $(bkClass.elm).attr('id');
		var thisId   = this.outlet.preview.substr(1);

		if ( editorId != thisId ) {
			return;
		}

		var content = nicEditors.findEditor(thisId).getContent();
		var app = Mailform.FormBuilder.application;
		app.form.set('header_description', content);
		app.editorPanel.hide();
	}
};