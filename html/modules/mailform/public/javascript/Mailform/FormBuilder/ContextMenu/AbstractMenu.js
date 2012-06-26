/**
 * コンテクストメニューのメニュー項目
 * @abstract
 */
Mailform.FormBuilder.ContextMenu.AbstractMenu = Mailform.Class.extend({
	/**
	 * 名前
	 * @protected
	 * @type {string}
	 */
	name: '',

	/**
	 * メニューのDOM
	 * @protected
	 * @type {HTMLLIElement}
	 */
	dom: null,

	/**
	 * コンテクストメニュー
	 * @protected
	 * @type {Mailform.FormBuilder.ContextMenu}
	 */
	contextMenu: null,

	/**
	 * @constructor
	 * @param {string} name
	 * @param {HTMLElement} menu
	 * @param {Mailform.FormBuilder.ContextMenu} contextMenu
	 */
	construct: function(name, menu, contextMenu) {
		this.name = name;
		this.dom = menu;
		this.contextMenu = contextMenu;
	},

	/**
	 * セットアップする
	 * @abstract
	 * @public
	 */
	setUp: function() {
	},

	/**
	 * コンテクストに応じて状態を更新する
	 * @abstract
	 * @public
	 * @param {Mailform.FormBuilder.TableRow} tableRow
	 */
	update: function(tableRow) {
	},

	/**
	 * リセットする
	 * @public
	 */
	reset: function() {
		this.enable(name);
		this.show(name);
	},

	/**
	 * 有効化する
	 * @public
	 */
	enable: function() {
		$(this.dom).removeClass('disabled');
	},

	/**
	 * 無効化する
	 * @public
	 */
	disable: function() {
		$(this.dom).addClass('disabled');
	},

	/**
	 * 表示する
	 * @public
	 */
	show: function() {
		$(this.dom).show();
	},

	/**
	 * 非表示にする
	 * @public
	 */
	hide: function() {
		$(this.dom).hide();
	}
});
