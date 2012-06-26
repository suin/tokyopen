/**
 * 「フィールドオプション」のメニュー
 */
Mailform.FormBuilder.ContextMenu.FieldOption = Mailform.FormBuilder.ContextMenu.AbstractMenu.extend({

	/**
	 * ダイアログ
	 * @protected
	 * @type {Mailform.FormBuilder.Dialog}
	 */
	dialog: null,

	/**
	 * アウトレット
	 * @protected
	 * @type {Object}
	 */
	outlet: {
		form: '.mailformFieldOptionEditForm'
	},

	/**
	 * 現在のフィールド
	 * @protected
	 * @type {Object}
	 */
	currentField: null,

	/**
	 * 現在の入力値
	 * @protected
	 * @type {Object}
	 */
	currentInput: {},

	/**
	 * セットアップする
	 * @public
	 */
	setUp: function() {
		this._registerEventListeners();
	},

	/**
	 * コンテクストに応じて状態を更新する
	 * @public
	 * @param {Mailform.FormBuilder.TableRow} tableRow
	 */
	update: function(tableRow) {
		if ( tableRow.hasObject() == false ) {
			this.disable();
		}
	},

	/**
	 * イベントリスナを登録する
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;
		this.contextMenu.delegate(this.name, function(item){ self._handleClickMenuEvent(item); });
	},

	/**
	 *
	 * @protected
	 */
	_createDialog: function() {
		this.dialog = new Mailform.FormBuilder.Dialog({
			width: 600
		});
	},

	/**
	 * メニューをクリックしたときのイベント
	 * @protected
	 * @param {Mailform.FormBuilder.TableRow} tableRow
	 */
	_handleClickMenuEvent: function(tableRow) {
		this.currentField = tableRow;

		this._createDialog();
		this.dialog.contents("Loading...").open();

		var queryString = $.param({
			controller: 'field_option_edit',
			name: this.currentField.get('type')
		});

		var options = $.extend(true, {}, this.currentField.get('options'));

		var data = {
			params: options
		};

		data.params['_'] = 1; // これがないとparams空っぽのときPOSTにわたらない

		var self = this;

		$.ajax({
			type: 'POST',
			url: location.pathname + '?' + queryString,
			data: data,
			success: function(data){ self._ajaxSuccess(data); },
			error: function(data){ self._ajaxError(data); },
			dataType: 'json'
		});
	},

	/**
	 * Ajax成功時
	 * @protected
	 */
	_ajaxSuccess: function(data) {
		if ( data.error == true ) {
			this._ajaxFail("Error");
			return;
		}

		var self = this;
		this.dialog.title(data.pageTitle);
		this.dialog.contents(data.html);
		this.dialog.addButton("OK", function(){ self._validate(this); });
	},

	/**
	 *
	 * @protected
	 */
	_validate: function(dialogContent) {
		var queryString = $.param({
			controller: 'field_option_edit',
			name: this.currentField.get('type')
		});

		var params = $(dialogContent).find(this.outlet.form).serializeArray();

		this.currentInput = params;
		var data = {
			validate: 1,
			params: {}
		};
		$(params).each(function(){
			data.params[this.name] = this.value;
		});

		data.params['_'] = 1; // これがないとparams空っぽのときPOSTにわたらない

		this.dialog.destroy();

		this._createDialog();
		this.dialog.contents("Loading...").open();

		var self = this;
		$.ajax({
			type: 'POST',
			url: location.pathname + '?' + queryString,
			data: data,
			success: function(data){ self._ajaxValidationSuccess(data); },
			error: function(data){ self._ajaxError(data); },
			dataType: 'json'
		});
	},

	/**
	 *
	 * @protected
	 */
	_ajaxValidationSuccess: function(data) {
		if ( data.error == true ) {
			this._ajaxFail("Error");
			return;
		}
		if ( data.validationError == true ) {
			var self = this;
			this.dialog.title(data.pageTitle);
			this.dialog.contents(data.html);
			this.dialog.addButton("OK", function(){ self._validate(this); });
		} else {
			this.dialog.close();
			
			var options = this.currentField.get('options');
	
			$(this.currentInput).each(function(){
				options[this.name] = this.value;
			});
		}
	},

	/**
	 *
	 * @protected
	 */
	_ajaxFail: function(message) {
		var self = this;
		this.dialog.contents(message);
		this.dialog.addButton("OK", function(){ self.dialog.close(); });
	},

	/**
	 * Ajaxエラー時
	 * @protected
	 */
	_ajaxError: function(data) {
		this._ajaxFail("Error: Unexpected format returned.");
	}
});
