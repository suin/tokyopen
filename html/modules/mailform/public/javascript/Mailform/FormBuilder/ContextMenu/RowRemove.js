/**
 * 「行を削除する」メニュー
 */
Mailform.FormBuilder.ContextMenu.RowRemove = Mailform.FormBuilder.ContextMenu.AbstractMenu.extend({

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
		var app = Mailform.FormBuilder.application;
		if ( app.table.getRowCount() <= 1 ) {
			this.disable();
		}
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
		var message = $(this.dom).data('deleteConfirmMessage');
		if ( confirm(message) == true ) {
			row.remove();
		}
	}
});
