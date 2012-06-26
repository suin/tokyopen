/**
 * 「行を追加する」メニュー
 */
Mailform.FormBuilder.ContextMenu.RowAdd = Mailform.FormBuilder.ContextMenu.AbstractMenu.extend({

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
	 */
	update: function(item) {
	},

	/**
	 * イベントリスナを登録する
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;
		this.contextMenu.delegate(this.name, function(row){ self._handleClickMenuEvent(row); });
	},

	/**
	 * メニューをクリックしたときのイベント
	 * @protected
	 * @param {Mailform.FormBuilder.TableRow} row
	 */
	_handleClickMenuEvent: function(row) {
		var app = Mailform.FormBuilder.application;
		app.table.insertNewRowAfter(null, row);
	}
});
