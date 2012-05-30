/**
 * @constructor
 * @param {SiteNavi_Sitemap_Application} app
 */
var SiteNavi_Sitemap_ContextMenu_Visit = function(app) {
	this.app = app;
};

SiteNavi_Sitemap_ContextMenu_Visit.prototype = {

	/**
	 * @protected
	 * @type {SiteNavi_Sitemap_Application}
	 */
	app: null,

	/**
	 * セットアップする
	 * @public
	 * @param {SiteNavi_Sitemap_ContextMenu} contextMenu
	 */
	setUp: function(contextMenu) {
		this._registerEventListeners(contextMenu);
	},

	/**
	 * @public
	 * @param {SiteNavi_Sitemap_ContextMenu} contextMenu
	 * @param {Object} data
	 */
	setUpContext: function(contextMenu, data) {
		var menu = contextMenu.get('visit');
		$(menu).find('a').attr('href', data.url);
		$(menu).find('a').attr('target', '_top');
	},

	/**
	 * @protected
	 * @param {SiteNavi_Sitemap_ContextMenu} contextMenu
	 */
	_registerEventListeners: function(contextMenu) {
		var self = this;
		contextMenu.delegate('visit', function(data){ self._clickMenuEventHandler(data); });
	},

	/**
	 * コンテクストメニューの「外部リンクを追加」をクリックしたとき
	 * @protected
	 * @param {Object} data
	 */
	_clickMenuEventHandler: function(data) {
		// 何もしない
	}
};
