/**
 * @constructor
 */
var SiteNavi_Sitemap_Application = function() {
};

SiteNavi_Sitemap_Application.prototype = {

	/**
	 * @public
	 * @string
	 */
	baseUrl: '',

	/**
	 * @protected
	 * @SiteNavi_Sitemap_ContextMenu
	 */
	contextMenu: null,

	/**
	 * @protected
	 * @SiteNavi_Sitemap_Application
	 */
	finder: null,

	/**
	 * アウトレット
	 * @enum
	 */
	outlet: {
		namespace: '.SiteNavi',
		canvas: '#siteNaviCanvas',
		node: '.node',
		nodeToggle: '.toggle',
		nodeToggleMinus: '.toggleMinus',
		nodeTogglePlus: '.togglePlus',
		nodeToggleZero: '.toggleZero',
		nodeTogglable: '.toggleMinus,.togglePlus',
		nodeHiglight: '.highlight',
		nodeIcon: '.icon',
		nodeTitle: '.title',
		nodeChildrenTotal: '.childrenTotal',
		nodeHighlight: '.highlight',
		contextMenu: '.contextMenu',
		contextMenuActive: '.contextMenu li:not(.disabled)',
		contextMenuDisabled: '.contextMenu li.disabled',
		dialog: '#SiteNaviDialog',
		dialogTitle: '#ui-dialog-title-SiteNaviDialog',
		nodesTemplate: '#siteNaviNodesTemplate',
		deleteConfirmDialog: '#siteNaviDeleteConfirmDialog'
	},

	/**
	 * クラス
	 * @enum
	 */
	className: {
		highlight: 'highlight',
		iconLoading: 'iconLoading',
		iconFolder: 'iconFolder',
		iconPage: 'iconPage',
		toggleMinus: 'toggleMinus',
		togglePlus: 'togglePlus',
		toggleZero: 'toggleZero',
		childrenZero: 'childrenZero',
		childrenExists: 'childrenExists',
		disabled: 'disabled'
	},

	/**
	 * メイン処理
	 * @public
	 */
	run: function(parent, children) {
		this._setUpContextMenu();
		this._setUpFinder();
		this._registerEventListeners();
		this._renderNodes(parent, children);
	},

	/**
	 * コンテクストメニューをセットアップする
	 * @protected
	 */ 
	_setUpContextMenu: function() {
		this.contextMenu = new SiteNavi_Sitemap_ContextMenu(this);
		this.contextMenu.setUp();
	},

	/**
	 * ファインダーをセットアップする
	 * @protected
	 */ 
	_setUpFinder: function() {
		this.finder = new SiteNavi_Sitemap_Finder(this);
		this.finder.setUp();
	},

	/**
	 * ノードを描画する
	 * @protected
	 */ 
	_renderNodes: function(parent, children) {
		var node = new SiteNavi_Sitemap_Node();
		node.applyData(parent);
		node.addChildrenByData(children);
		$(node.asHTML()).appendTo(this.outlet.canvas);
	},

	/**
	 * イベントハンドラーを登録する
	 * @protected
	 */
	_registerEventListeners: function() {

	}
};
