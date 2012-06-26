Mailform.FormBuilder.Dialog = Mailform.Class.extend({

	/**
	 * ダイアログのオプション
	 * @protected
	 * @type {Object.<string, *>}
	 */
	options: {
		closeOnEscape: false,
		modal: true,
		autoOpen: false
	},

	/**
	 * アウトレット
	 * @protected
	 * @enum
	 * @type {Object.<string, *>}
	 */
	outlet: {
		titleBar: '.ui-dialog-titlebar'
	},

	/**
	 * ダイアログ
	 * @protected
	 * @type {Object}
	 */
	dialog: null,

	/**
	 * <body />のoverflowの初期値
	 * @protected
	 * @type {string}
	 */
	bodyOverflow: '',
	
	/**
	 * スクロールが固定されているか
	 * @protected
	 * @type {boolean}
	 */
	scrollFiexed: false,

	/**
	 *
	 * @public
	 */
	construct: function() {
		if ( arguments[0] ) {
			this.options = $.extend.apply(this.options, arguments);
		}

		this._setUp();
		this._registerEventListeners();
		this.dialog.dialog(this.options);
	},

	/**
	 * タイトルをセットする
	 * @public
	 * @param {string} タイトル
	 * @return {this}
	 */
	title: function(title) {
		this.dialog.dialog('option', 'title', title);
		return this;
	},

	/**
	 * コンテンツをセットする
	 * @public
	 * @param {string} タイトル
	 * @return {this}
	 */
	contents: function(contents) {
		this.dialog.html(contents);
		return this;
	},

	/**
	 * ボタンを追加する
	 * @public
	 * @param {string} name ボタン表示名
	 * @return {this}
	 */
	addButton: function(name, callback) {

		if ( callback == undefined ) {
			var self = this;
			callback = function() { self.close(); };
		}

		var buttons = this.dialog.dialog('option', 'buttons');

		buttons[name] = callback;
		this.dialog.dialog('option', 'buttons', buttons);

		return this;
	},

	/**
	 * ダイアログを開く
	 * @public
	 * @return {this}
	 */
	open: function() {
		this.dialog.dialog('open');
		return this;
	},

	/**
	 * ダイアログを閉じる
	 * @public
	 * @return {this}
	 */
	close: function() {
		this.dialog.dialog('close');
		return this;
	},

	/**
	 * ダイアログを destory する
	 * @public
	 */
	destroy: function() {
		this.dialog.dialog('close');
		this.dialog.dialog('destory');
		this.dialog.remove();
	},

	/**
	 * タイトルバーを非表示にする
	 * @public
	 * @return {this}
	 */
	hideTitleBar: function() {
		var parent = this.dialog.parent();
		$(parent).find(this.outlet.titleBar).hide();
		return this;
	},

	/**
	 * サイズ変更を許可するかどうか
	 * @public
	 * @param {boolean} resizable
	 * @return {this}
	 */
	resizable: function(resizable) {
		this.dialog.dialog('option', 'resizable', resizable);
		return this;
	},

	/**
	 * セットアップする
	 * @protected
	 */
	_setUp: function() {
		this.dialog = $('<div />');
		this.bodyOverflow = $('body').css('overflow');
	},

	/**
	 * イベントハンドラを登録する
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;

		this.dialog
			.bind('dialogopen', function(event, ui){ self._dialogOpenEventHandler(event, ui); })
			.bind('dialogclose', function(event, ui){ self._dialogCloseEventHandler(event, ui); })
		;
	},

	/**
	 * ダイアログを開いたときのイベント
	 * @protected
	 */
	_dialogOpenEventHandler: function(event, ui) {
		if ( this.options.modal == true ) {
			this._fixScrollBar();
		}
	},

	/**
	 * ダイアログを閉じたときのイベント
	 * @protected
	 */
	_dialogCloseEventHandler: function(event, ui) {
		if ( this.scrollFiexed == true ) {
			this._unfixScrollBar();
		}
	},

	/**
	 * スクロールバーを固定する
	 * @protected
	 */
	_fixScrollBar: function() {
		$('body').css('overflow', 'hidden');
		this.scrollFiexed = true;
	},

	/**
	 * スクロールバー固定を解除する
	 * @protected
	 */
	_unfixScrollBar: function() {
		$('body').css('overflow', this.bodyOverflow);
		this.scrollFiexed = false;
	}
});
