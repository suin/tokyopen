/**
 * @constructor
 * @param {HTMLDivElement} contextMenu
 * @param {string} clickable
 */
Mailform.FormBuilder.ContextMenu = function(contextMenu, item) {
	this.dom = contextMenu;
	this.item = item;
	this.cancelArea = '';
	this.actions = {};
	this.menu = {};
	this.eventListeners = {};
	this.currentItem = null;
};

Mailform.FormBuilder.ContextMenu.prototype = {

	/**
	 * @protected
	 * @type {HTMLDivElement}
	 */
	dom: null,

	/**
	 * アウトレット
	 * @enum
	 * @type {Object}
	 */
	outlet: {
		menu: 'li[name]',
		activeMenu: 'li:not(.disabled)',
		disabledMenu: 'li.disabled',
		disableClass: 'disable'
	},

	/**
	 * アクション
	 * @type {Object}
	 */
	actions: {},

	/**
	 * メニュー
	 * @type {Object}
	 */
	menu: {},

	/**
	 * イベントリスナ
	 * @type {Object}
	 */
	eventListeners: {},

	/**
	 * コンテクストメニューを表示する対象
	 * @protected
	 * @type {string}
	 */
	item: null,

	/**
	 * コンテクストメニューを表示しない対象
	 * @protected
	 * @type {string}
	 */
	cancelArea: '',

	/**
	 * 選択中の対象
	 * @public
	 * @type {Object}
	 */
	currentItem: null,

	/**
	 * 開いているかどうか
	 * @protected
	 * @type {boolean}
	 */
	opened: false,

	/**
	 * セットアップする
	 * @public
	 */
	setUp: function() {
		this._setUpContextMenu();
		this._setUpMenu();
		this._registerEventListeners();
		return this;
	},

	/**
	 * メニュークリック時のイベントを追加する
	 * @public
	 * @param {string} menuName
	 * @param {Function} callback
	 */
	delegate: function(menuName, callback) {
		if ( this.eventListeners[menuName] == undefined ) {
			this.eventListeners[menuName] = [];
		}

		this.eventListeners[menuName].push(callback);
		return this;
	},

	/**
	 * メニューを返す
	 * @public
	 * @param {string} menuName
	 * @return {Mailform.FormBuilder.ContextMenu.AbstractMenu} のサブクラス
	 */
	getMenu: function(menuName) {
		return this.menu[menuName];
	},

	/**
	 * すべてのメニューの状態をリセットする
	 * @public
	 */
	reset: function() {
		for ( var menuName in this.menu ) {
			this.menu[menuName].reset();
		}
		
		this.currentItem = null;
		return this;
	},

	/**
	 * コンテクストメニューを閉じる
	 * @public
	 */
	close: function() {

		if ( this.opened == false ) {
			return this;
		}

		$(this.dom).hide();
		
		if ( this.currentItem ) {
			this._lowdarkItem(this.currentItem);
		}

		this.opened = false;

		return this;
	},

	/**
	 * クリックしてもコンテクストメニューを開かない要素を指定する
	 * @public
	 * @param {string} selector
	 */
	cancel: function(selector) {
		this.cancelArea = selector;
	},

	/**
	 *
	 * @protected
	 */
	_setUpContextMenu: function() {
		// <body />直下に移動
		$(this.dom).remove().appendTo('body').css({position:'absolute'}).hide();

	},

	/**
	 * メニューをセットアップする
	 * @protected
	 */
	_setUpMenu: function() {
		var self = this;
		$(this.dom).find(this.outlet.menu).each(function(){
			var className = $(this).attr('name');
			var menuName  = self._lcFirst(className);
			self.menu[menuName] = new Mailform.FormBuilder.ContextMenu[className](menuName, this, self);
			self.menu[menuName].setUp();
			$(this).data('menu', self.menu[menuName]);
		});
	},

	/**
	 * イベントハンドラを登録する
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;

		$(this.dom)
			.delegate(this.outlet.activeMenu,   'click', function(event) { return self._clickActiveMenuEventHandler(this, event); })
			.delegate(this.outlet.disabledMenu, 'click', function(event) { return self._clickDisabledMenuEventHandler(this, event); })
		;

		$(document)
			.delegate(this.item, 'click', function(event){ return self._handleClickItemEvent(this, event); })
			.delegate('body', 'click', function(event){ return self._handleClickCancelAreaEvent(this, event); })
			.delegate('body > :not(#mailformContextMenu)', 'mousedown', function(event){ return self._handleClickCancelAreaEvent(this, event); })
		;
	},
	
	/**
	 * 対象をクリックしたときのイベント
	 * @protected
	 * @param {HTMLElement} item
	 * @param {Object} event
	 * @return {boolean}
	 */
	_handleClickItemEvent: function(item, event) {

		if ( $(event.target).is(this.cancelArea) ) {
			this.close(); // 開いてた場合を想定
			return false;
		}

		this._showContextMenu(item, event.pageX, event.pageY);
		return false;
	},

	/**
	 * 有効なメニューをクリックしたときのイベント
	 * @protected
	 * @param {HTMLElement} item
	 * @param {Object} event
	 * @return {boolean}
	 */
	_clickActiveMenuEventHandler: function(menu, event) {
		this.close();

		var menuName = this._lcFirst($(menu).attr('name'));

		if ( this.eventListeners[menuName] == undefined ) {
			return false;
		}

		// イベントをコール
		for ( var i in this.eventListeners[menuName] ) {
			var callback = this.eventListeners[menuName][i];
			callback(this.currentItem);
		}

		return false;
	},

	/**
	 * 無効化されたメニューをクリックしたときのイベント
	 * @protected
	 * @param {HTMLLIElement} title
	 * @param {Object} event
	 * @return {boolean}
	 */
	_clickDisabledMenuEventHandler: function(title, event) {
		this.close();
		return false;
	},

	/**
	 * コンテクストメニューを閉じる領域をクリックしたときのイベント
	 * @protected
	 * @param {HTMLBodyElement} body
	 * @param {Object} event
	 */
	_handleClickCancelAreaEvent: function(body, event) {
		this.close();
	},

	/**
	 * コンテクストメニューを表示する
	 * @protected
	 * @param {HTMLElement} item
	 * @param {integer} x
	 * @param {integer} y
	 */
	_showContextMenu: function(item, x, y) {
		this.reset();
		this.opened = true;
		this.currentItem = $(item).data('tableRow');
		this._highlightItem(this.currentItem);
		this._updateContextMenu();
		this._positionContextMenu(x, y);
	},

	/**
	 * コンテクストメニューをアップデートする
	 * @protected
	 * @param {Object} data
	 */
	_updateContextMenu: function() {
		for ( var name in this.menu ) {
			this.menu[name].update(this.currentItem);
		}
	},

	/**
	 * コンテクストメニューを配置する
	 * @protected
	 */
	_positionContextMenu: function(x, y) {
		$(this.dom)
			.css({
				'position': 'absolute',
				'left': x +'px',
				'top': y +'px',
				'z-index': 999
			})
			.show();
	},

	/**
	 * 1文字目を小文字にする
	 * @protected
	 * @param {string} string
	 * @return {string}
	 */
	_lcFirst: function(string) {
		return string.charAt(0).toLowerCase() + string.slice(1);
	},

	/**
	 *
	 * @protected
	 */
	_highlightItem: function(row) {
		row.highlight();
	},

	/**
	 *
	 * @protected
	 */
	_lowdarkItem: function(row) {
		row.lowdark();
	}
};
