/**
 * @constructor
 * @param app SiteNavi_Sitemap_Application
 */
var SiteNavi_Sitemap_Finder = function(app) {
	this.app = app;
};

SiteNavi_Sitemap_Finder.prototype = {

	/**
	 * リストアクションURL
	 * @constant
	 */
	LIST_ACTION_URL: '/index.php?controller=sitemap&action=list',

	/**
	 * @protected
	 * @SiteNavi_Sitemap_Application
	 */
	app: null,

	/**
	 * セットアップする
	 * @public
	 */
	setUp: function() {
		this._registerEventListeners();
	},

	/**
	 * ノードの情報を返す
	 * @public
	 * @param {HTMLDivElement} node
	 */
	getDataByNode: function(node) {
		
		var data = {};

		for ( var i = 0; i < node.attributes.length; i ++ ) {
			var name  = node.attributes[i].nodeName;
			var value = node.attributes[i].nodeValue;

			if ( name.indexOf('data-') === 0 ) {
				name = name.substr(5);
				data[name] = value;
			}
		}
		
		return data;
	},

	/**
	 * ノードを追加する
	 * @public
	 * @param {integer} parentId
	 * @param {Object} data
	 */
	addNode: function(parentId, data) {
		var parentNode = $(this.app.outlet.namespace).find('[data-id='+parentId+']');
		var children = parseInt($(parentNode).attr('data-children')) + 1;

		$(parentNode)
			.attr('data-children', children)
			.children(this.app.outlet.nodeChildrenTotal)
				.text(children)
				.removeClass(this.app.className.childrenZero)
				.addClass(this.app.className.childrenExists)
			.end()
			.children(this.app.outlet.nodeIcon)
				.removeClass(this.app.className.iconPage)
				.addClass(this.app.className.iconFolder)
			.end();

		if ( this._isOpened(parentNode) == true ) {
			var node = new SiteNavi_Sitemap_Node();
			node.applyData(data);
			$(node.asHTML()).appendTo(parentNode);
		} else {
			this._openFolder(parentNode);
		}
	},

	/**
	 * ノードを削除する
	 * @public
	 *
	 */
	removeNode: function(id, data) {
		var node = $(this.app.outlet.namespace).find('[data-id='+id+']');
		var parentNode = $(this.app.outlet.namespace).find('[data-id='+data.parent_id+']');

		var children = parseInt($(parentNode).attr('data-children')) - 1;
		
		$(node).remove();

		$(parentNode)
			.attr('data-children', children)
			.children(this.app.outlet.nodeChildrenTotal)
				.text(children)
			.end();

		if ( children < 1 ) {
			$(parentNode)
				.children(this.app.outlet.nodeChildrenTotal)
					.removeClass(this.app.className.childrenExists)
					.addClass(this.app.className.childrenZero)
				.end()
				.children(this.app.outlet.nodeIcon)
					.removeClass(this.app.className.iconFolder)
					.addClass(this.app.className.iconPage)
				.end();
			
			this._hideToggle(parentNode);
		}
	},

	/**
	 * イベントハンドラを登録する
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;
		$(this.app.outlet.namespace)
			.delegate(this.app.outlet.nodeTogglable, 'click', function(event){ return self._clickPageEventHandler(this, event); })
		;
	},

	/**
	 * ページのクリックイベント
	 * @protected
	 */
	_clickPageEventHandler: function(page, event) {
		var node = $(page).parent();
		var children = node.attr('children');

		if ( children == 0 ) {
			return;
		}

		if ( this._isOpened(node) == true ) {
			this._closeFolder(node);
		} else {
			this._openFolder(node);
		}
	},

	/**
	 * フォルダが開いているかを返す
	 * @protected
	 */
	_isOpened: function(node) {
		return $(node).find(this.app.outlet.nodeToggle).is(this.app.outlet.nodeToggleMinus);
	},

	/**
	 * フォルダを開く
	 * @protected
	 */
	_openFolder: function(node) {
		this._showLoader(node);
		this._loadChidlren(node);
	},

	/**
	 * フォルダを閉じる
	 * @protected
	 */
	_closeFolder: function(node) {
		this._changeMinusToPlus(node);
		this._removeChildren(node);
	},

	/**
	 * ローダーアイコンを表示する
	 * @protected
	 */
	_showLoader: function(node) {
		node.children(this.app.outlet.nodeIcon).addClass(this.app.className.iconLoading);
	},

	/**
	 * ローダーアイコンを隠す
	 * @protected
	 */
	_hideLoader: function(node) {
		node.children(this.app.outlet.nodeIcon).removeClass(this.app.className.iconLoading);
	},

	/**
	 * 子ノードを取得する
	 * @protected
	 */
	_loadChidlren: function(node) {

		var id = node.attr('data-id');
		var self = this;
		var url = this.app.baseUrl + this.LIST_ACTION_URL + '&route_id=' + id;

		$.ajax({
			type: 'GET',
			url: url,
			success: function(data, textStatus, jqXHR) { self._loadChildrenSuccess(data, textStatus, jqXHR, node); },
			error: function(jqXHR, textStatus, errorThrown) { alert('error'); },
			dataType: 'json'
		});
	},

	/**
	 * 子ノード取得成功時
	 * @protected
	 */
	_loadChildrenSuccess: function(data, textStatus, jqXHR, node) {
		this._hideLoader(node);
		this._changePlusToMinus(node);
		this._addChildren(node, data);
	},

	/**
	 * 子ノードを追加する
	 * @protected
	 */
	_addChildren: function(parent, children) {

		for ( i in children ) {	
			var node = new SiteNavi_Sitemap_Node();
			node.applyData(children[i]);
			$(node.asHTML()).appendTo(parent);
		}
	},

	/**
	 * 子ノードを取り除く
	 * @protected
	 */
	_removeChildren: function(node) {
		$(node).children(this.app.outlet.node).remove();
	},

	/**
	 * プラスアイコンをマイナスに変える
	 * @protected
	 */
	_changePlusToMinus: function(node) {
		$(node)
			.find(this.app.outlet.nodeToggle)
			.removeClass(this.app.className.togglePlus)
			.addClass(this.app.className.toggleMinus);
	},

	/**
	 * マイナスアイコンをプラスに変える
	 * @protected
	 */
	_changeMinusToPlus: function(node) {
		$(node)
			.find(this.app.outlet.nodeToggle)
			.removeClass(this.app.className.toggleMinus)
			.addClass(this.app.className.togglePlus);
	},

	/**
	 * 開閉アイコンを非表示にする
	 * @protected
	 */
	_hideToggle: function(node) {
		$(node)
			.find(this.app.outlet.nodeToggle)
			.removeClass(this.app.className.toggleMinus)
			.removeClass(this.app.className.togglePlus);
	}
};
