/**
 * @constructor
 * @param {HTMLTableElement} table
 * @param {HTMLTableRowElement} fieldTemplate
 */
Mailform.FormBuilder.Table = function(table) {
	var tableRow = $(table).find(this.outlet.fieldTemplate).clone().removeAttr('id').get(0);
	this.row = new Mailform.FormBuilder.TableRow(tableRow);
	this.dom = $(table).find(this.outlet.fieldTemplate).remove().end().get(0);
};

Mailform.FormBuilder.Table.prototype = {

	/**
	 * @protected
	 * @type {HTMLTableElement}
	 */
	dom: null,

	/**
	 * アウトレット
	 * @enum
	 * @type {Object}
	 */
	outlet: {
		fieldTemplate: '#mailformFieldTemplate',
		field: '.mailformField',
		preparedField: '.mailformPreparedField',
		formData: '#mailformFormData'
	},

	/**
	 * 行のテンプレート
	 * @protected
	 * @type {HTMLTableRowElement}
	 */
	row: null,

	/**
	 * @public
	 */
	setUp: function() {
		this._setUpRows();
		this._sortableRows();
		this._registerEventListeners();
	},

	/**
	 * 新しい行を追加する
	 * @public
	 * @param {int} id
	 * @return {Mailform.FormBuilder.TableRow}
	 */
	appendNewRow: function(id) {
		var row = this.createRow(id);
		$(this.dom).append(row.dom);
		row.addNicEditInstance();
		return row;
	},

	/**
	 * 新しい行を特定の行の後に追加する
	 * @public
	 * @param {int} newRowId
	 * @param {Mailform.FormBuilder.TableRow} beforeRow
	 */
	insertNewRowAfter: function(newRowId, beforeRow) {
		var row = this.createRow(newRowId);
		$(beforeRow.dom).after(row.dom);
		row.addNicEditInstance();
		return row;
	},

	/**
	 * 行オブジェクトを生成する
	 * @public
	 * @param {int} id
	 * @return {Mailform.FormBuilder.TableRow}
	 */
	createRow: function(id) {
		var row = $(this.row.dom).clone().get(0);
		row = new Mailform.FormBuilder.TableRow(row);
		row.setUp();

		if ( id ) {
			row.set('id', id);
		}

		return row;
	},

	/**
	 * 行数を返す
	 * @public
	 * @return {int} 
	 */
	getRowCount: function() {
		return $(this.dom).find(this.outlet.field).length;
	},

	/**
	 * 行を返す
	 * @public
	 * @return {Array<Mailform.FormBuilder.TableRow>}
	 */
	getRows: function() {
		var rows = [];

		$(this.dom).find(this.outlet.field).each(function(){
			rows.push($(this).data('tableRow'));
		});

		return rows;
	},

	/**
	 *
	 * @protected
	 */
	_setUpRows: function() {
		var formData = $.parseJSON($(this.outlet.formData).text());
		var self = this;

		for ( i in formData.fields ) {
			var row = self.appendNewRow(formData.fields[i].id);
			row.setData(formData.fields[i]);
		}

		if ( this.getRowCount() == 0 ) {
			this.appendNewRow();
		}
	},

	/**
	 *
	 * @protected
	 */
	_sortableRows: function() {
		$(this.dom).children('tbody').sortable({
			handle: '.mailformGrabTab',
			cancel: 'input',
			axis: 'y',
			forceHelperSize: true,
			forcePlaceholderSize: true,
			helper: function(e, tr)
			{
				// デフォルトでtrの幅が狭まるのの対策
				// http://stackoverflow.com/questions/1307705/jquery-ui-sortable-with-table-and-tr-width
				var $originals = tr.children();
				var $helper = tr.clone();

				$helper.children().each(function(index)
				{
					// Set helper cell sizes to match the original sizes
					$(this).width($originals.eq(index).width());

				});

				return $helper;
			}
		});
	},

	/**
	 *
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;
		$(document)
			.delegate('.mailformObject', 'dragstart', function(event, ui){ self._handleObjectDragStartEvent(this, event, ui); })
			.delegate('.mailformObject', 'dragstop', function(event, ui){ self._handleObjectDragStopEvent(this, event, ui); })
		;
	},

	/**
	 *
	 * @protected
	 */
	_handleObjectDragStartEvent: function(object, event, ui) {
		if ( this._hasEmptyRow() === false ) {
			this._addPreparedField();
		}
	},

	/**
	 *
	 * @protected
	 */
	_handleObjectDragStopEvent: function(object, event, ui) {
		this._removePreparedField();
	},

	/**
	 * 
	 * @protected
	 */
	_hasEmptyRow: function() {
		var hasEmpty = false;
	
		$(this.outlet.field).each(function(){
			var hasObject = $(this).data('tableRow').hasObject();
			if ( hasObject == false ) {
				hasEmpty = true;
			}
		});

		return hasEmpty;
	},

	/**
	 *
	 * @protected
	 */
	_addPreparedField: function() {
		var row = this.appendNewRow();
		row.appearAsPreparedRow();
	},

	/**
	 *
	 * @protected
	 */
	_removePreparedField: function() {
		$(this.outlet.field).each(function(){
			$(this).data('tableRow').hideAsPreparedRow();
		});
	}
};