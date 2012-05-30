/**
 * @constructor
 * @param {SiteNavi_Sitemap_Application} app
 */
var SiteNavi_Sitemap_ContextMenu = function(app) {
	this.app = app;
};

SiteNavi_Sitemap_ContextMenu.prototype = {

	/**
	 * @protected
	 * @type {SiteNavi_Sitemap_Application}
	 */
	app: null,

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
	 * 現在のノードの情報
	 * @public
	 * @type {Object}
	 */
	currentNode: {},

	/**
	 * セットアップする
	 * @public
	 */
	setUp: function() {
		this._setUpActions();
		this._setUpMenu();
		this._registerEventListeners();
		return this;
	},

	/**
	 * イベントリスナを登録する
	 * @public
	 */
	delegate: function(eventName, callback) {
		if ( this.eventListeners[eventName] == undefined ) {
			this.eventListeners[eventName] = [];
		}

		this.eventListeners[eventName].push(callback);
		return this;
	},

	/**
	 * メニューを返す
	 * @public
	 * @param {string} name
	 * @return {Object}
	 */
	get: function(name) {
		return this.menu[name];
	},

	/**
	 * メニューを無効にする
	 * @public
	 * @param {string} name
	 */
	disable: function(name) {
		$(this.menu[name]).addClass(this.app.className.disabled);
		return this;
	},

	/**
	 * メニューを有効にする
	 * @public
	 * @param {string} name
	 */
	enable: function(name) {
		$(this.menu[name]).removeClass(this.app.className.disabled);
		return this;
	},

	/**
	 * メニューを隠す
	 * @public
	 * @param {string} name
	 */
	hide: function(name){
		$(this.menu[name]).hide();
		return this;
	},

	/**
	 * メニューを表示する
	 * @public
	 * @param {string} name
	 */
	show: function(name) {
		$(this.menu[name]).show();
		return this;
	},

	/**
	 * すべてのメニューの状態をリセットする
	 * @public
	 */
	reset: function() {
		for ( var name in this.menu ) {
			this.enable(name);
			this.show(name);
		}
		
		this.currentNode = {};
		return this;
	},

	/**
	 * コンテクストメニューを閉じる
	 * @public
	 */
	close: function() {
		this._lowdarkNodeTitle();
		$(this.app.outlet.contextMenu).hide();
		return this;
	},

	/**
	 * アクションをセットアップする
	 * @protected
	 */
	_setUpActions: function() {
		this.actions.visit = new SiteNavi_Sitemap_ContextMenu_Visit(this.app);
		this.actions.visit.setUp(this);
		
		this.actions.deletePage = new SiteNavi_Sitemap_ContextMenu_Delete(this.app);
		this.actions.deletePage.setUp(this);
		
		this.actions.newPage = new SiteNavi_Sitemap_ContextMenu_NewPage(this.app);
		this.actions.newPage.setUp(this);

		this.actions.newExernalLink = new SiteNavi_Sitemap_ContextMenu_NewExternalLink(this.app);
		this.actions.newExernalLink.setUp(this);

		this.actions.newModule = new SiteNavi_Sitemap_ContextMenu_NewModule(this.app);
		this.actions.newModule.setUp(this);

		this.actions.deleteModule = new SiteNavi_Sitemap_ContextMenu_DeleteModule(this.app);
		this.actions.deleteModule.setUp(this);
		
		this.actions.editPage = new SiteNavi_Sitemap_ContextMenu_EditPage(this.app);
		this.actions.editPage.setUp(this);

	},

	/**
	 * メニューをセットアップする
	 * @protected
	 */
	_setUpMenu: function() {
		var self = this;
		$(this.app.outlet.contextMenu).find('li').each(function(){
			var name = $(this).attr('name');
			self.menu[name] = this;
		});
	},

	/**
	 * イベントハンドラを登録する
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;
		$(this.app.outlet.namespace)
			.delegate(this.app.outlet.nodeTitle,           'click', function(event) { return self._clickTitleEventHandler(this, event); })
			.delegate(this.app.outlet.contextMenuActive,   'click', function(event) { return self._clickActiveMenuEventHandler(this, event); })
			.delegate(this.app.outlet.contextMenuDisabled, 'click', function(event) { return self._clickDisabledMenuEventHandler(this, event); })
		;
		$(document)
			.delegate('body', 'click', function(event){ return self._clickBodyEventHandler(this, event); })
		;
	},
	
	/**
	 * ページのコンテクストメニューイベント
	 * @protected
	 * @param {HTMLLIElement} title
	 * @param {Object} event
	 * @return {boolean}
	 */
	_clickTitleEventHandler: function(title, event) {
		this._showContextMenu(title, event.pageX, event.pageY);
		return false;
	},

	/**
	 * 有効なメニューをクリックしたときのイベント
	 * @protected
	 * @param {HTMLLIElement} title
	 * @param {Object} event
	 * @return {boolean}
	 */
	_clickActiveMenuEventHandler: function(title, event) {
		this.close();

		var eventName = $(title).attr('name');

		if ( this.eventListeners[eventName] == undefined ) {
			return false;
		}

		// イベントをコール
		for ( var i in this.eventListeners[eventName] ) {
			var callback = this.eventListeners[eventName][i];
			callback(this.currentNode);
		}

		// hrefが空ならば、ブラウザのデフォルトイベントを中断する
		if ( $(title).children('a').attr('href').replace('#', '') == '' ) {
			return false;
		}
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
	 * <body />クリックイベント
	 * @protected
	 * @param {HTMLBodyElement} body
	 * @param {Object} event
	 */
	_clickBodyEventHandler: function(body, event) {
		this.close();
	},

	/**
	 * コンテクストメニューを表示する
	 * @protected
	 * @param {HTMLLIElement} title
	 * @param {integer} x
	 * @param {integer} y
	 */
	_showContextMenu: function(title, x, y) {
		this.reset();
		this._setNodeData(title);
		this._setUpContext(this.currentNode);
		this._lowdarkNodeTitle();
		this._highlightNodeTitle(title);
		this._positionContextMenu(x, y);
	},

	/**
	 * コンテクストをセットアップする
	 * @protected
	 * @param {Object} data
	 */
	_setUpContext: function(data) {
		for ( var name in this.actions ) {
			this.actions[name].setUpContext(this, data);
		}
	},

	/**
	 * コンテクストメニューを配置する
	 * @protected
	 */
	_positionContextMenu: function(x, y) {
		$(this.app.outlet.contextMenu)
			.css({
				'position': 'absolute',
				'left': x +'px',
				'top': y +'px',
				'z-index': 999
			})
			.show();
	},

	/**
	 * 現在のノードの情報をセットする
	 * @protected
	 * @param {Object} title
	 * @param {integer} x
	 * @param {integer} y
	 */
	_setNodeData: function(title) {
		var node = $(title).parent()[0];
		this.currentNode = this.app.finder.getDataByNode(node);
	},

	/**
	 * タイトルをハイライトする
	 * @protected
	 * @param {HTMLLIElement} title
	 */
	_highlightNodeTitle: function(title) {
		$(title).addClass(this.app.className.highlight);
	},

	/**
	 * タイトルをタイトルをハイライトのを解除する
	 * @protected
	 */
	_lowdarkNodeTitle: function() {
		$(this.app.outlet.nodeHighlight).removeClass(this.app.className.highlight);
	}
};
