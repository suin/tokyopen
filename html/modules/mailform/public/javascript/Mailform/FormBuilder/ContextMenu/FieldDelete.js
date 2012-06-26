/**
 * 「フィールドを削除する」メニュー
 */
Mailform.FormBuilder.ContextMenu.FieldDelete = Mailform.FormBuilder.ContextMenu.AbstractMenu.extend({
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
	 * メニューをクリックしたときのイベント
	 * @protected
	 * @param {Mailform.FormBuilder.TableRow} tableRow
	 */
	_handleClickMenuEvent: function(tableRow) {
		var message = $(this.dom).data('deleteConfirmMessage');

		if ( confirm(message) == true ) {
			tableRow.removeField();
		}
	}
});
