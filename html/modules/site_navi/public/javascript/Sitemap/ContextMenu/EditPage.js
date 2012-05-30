/**
 * @constructor
 * @param {SiteNavi_Sitemap_Application} app
 */
var SiteNavi_Sitemap_ContextMenu_EditPage = function(app) {
	this.app = app;
};

SiteNavi_Sitemap_ContextMenu_EditPage.prototype = {

	/**
	 * @protected
	 * @type {SiteNavi_Sitemap_Application}
	 */
	app: null,

	/**
	 * アウトレット
	 * @enum
	 */
	outlet: {
		form: '#SiteNavi_Form_AdminPageEdit',
		formSubmitButton: '#SiteNavi_Form_AdminPageEdit input[type=submit]'
	},

	/**
	 * フォームのURL
	 * @constant
	 */
	FORM_URL: '/index.php?controller=page_edit&ajaxmode=1',

	/**
	 * ダイアログ
	 * @type {SiteNavi_Sitemap_Dialog}
	 */
	dialog: null,

	/**
	 * セットアップする
	 * @public
	 * @param {SiteNavi_Sitemap_ContextMenu} contextMenu
	 */
	setUp: function(contextMenu) {
		this._setUpDialog();
		this._registerEventListeners(contextMenu);
	},

	/**
	 * コンテクストメニューをセットアップする
	 * @public
	 * @param {SiteNavi_Sitemap_ContextMenu} contextMenu
	 * @param {Object} data
	 */
	setUpContext: function(contextMenu, data) {
		if ( data.parent_id == 0 ) {
			contextMenu.disable('editPage');
		}
	},

	/**
	 * ダイアログをセットアップする
	 * @protected
	 */
	_setUpDialog: function() {
		this.dialog = new SiteNavi_Sitemap_Dialog({
			width: 500, 
			height: 230
		});
	},

	/**
	 * イベントリスナを登録する
	 * @protected
	 * @param {SiteNavi_Sitemap_ContextMenu} contextMenu
	 */
	_registerEventListeners: function(contextMenu) {
		var self = this;
		$(document)
			.delegate(this.outlet.form, 'submit', function(event){ return self._submitFormEventHandler(this, event); })
			.delegate(this.outlet.formSubmitButton, 'click', function(event){ return self._clickFormSubmitButtonEventHandler(this, event); })
		;

		contextMenu.delegate('editPage', function(data){ self._clickMenuEventHandler(data); });
	},

	/**
	 * コンテクストメニューの「修正」をクリックしたとき
	 * @protected
	 * @param {Object} data
	 */
	_clickMenuEventHandler: function(data) {
		this._request({
			url: this.app.baseUrl + this.FORM_URL + '&id=' + data.id
		});
	},

	/**
	 * ダイアログ内のフォームの送信ボタンをクリックしたとき
	 * @protected
	 */
	_clickFormSubmitButtonEventHandler: function(button, event) {
	
		// jQuery.serialize()はクリックしたボタンのvalueを取得できないので
		// ボタンクリック時に input[type=hidden] をつくってあげる必要がある
		var name = $(button).attr('name');
		var value = $(button).attr('value');
	
		$(button.form).find('input[type=hidden][clickedButton]').remove();
	
		$('<input />')
			.attr('clickedButton', name)
			.attr('type', 'hidden')
			.attr('name', name)
			.attr('value', value)
			.appendTo(button.form);

		this._request({
			type: $(button.form).attr('method'),
			url: $(button.form).attr('action'),
			data: $(button.form).serialize()
		});
	},

	/**
	 * ダイアログ内のフォーム送信時
	 * @protected
	 */
	_submitFormEventHandler: function(form, event) {
		return false;
	},

	/**
	 * リクエストする
	 * @protected
	 * @param {Object.<string, *>} options オプション
	 */
	_request: function(options) {
		options = options || {};

		var self = this;
		var defaultOptions = {
			type: 'GET',
			url: '',
			success: function(data, textStatus, jqXHR) { self._requestSuccess(data, textStatus, jqXHR); },
			error: function(jqXHR, textStatus, errorThrown) { self._requestFail(jqXHR, textStatus, errorThrown); },
			dataType: 'json'
		};

		options = $.extend(defaultOptions, options);

		$.ajax(options);
	},

	/**
	 * リクエスト成功時
	 * @protected
	 */
	_requestSuccess: function(data, textStatus, jqXHR) {
		if ( data.end == 0 ) {
			this.dialog.title(data.title);
			this.dialog.contents(data.html);
			this.dialog.open();
		} else {
			this.dialog.close();
		}
	},

	/**
	 * リクエスト失敗時
	 * @protected
	 */
	_requestFail: function(jqXHR, textStatus, errorThrown) {
		alert("Request Error: "+textStatus);
	}
};
