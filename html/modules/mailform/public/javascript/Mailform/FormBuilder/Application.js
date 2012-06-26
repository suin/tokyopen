/**
 * @constructor
 */
Mailform.FormBuilder.Application = function() {
};

Mailform.FormBuilder.Application.prototype = {

	/**
	 * コントロールパネル
	 * @public
	 * @type {Mailform.FormBuilder.ControlPanel}
	 */
	controlPanel: null,

	/**
	 * NicEditエディターパネル
	 * @public
	 * @type {Mailform.FormBuilder.EditorPanel}
	 */
	editorPanel: null,

	/**
	 * フォームタイトル
	 * @public
	 * @type {Mailform.FormBuilder.FormTitle}
	 */
	formTitle: null,

	/**
	 * フォーム説明文
	 * @public
	 * @type {Mailform_FormBuilder_EditorDescription}
	 */
	formDescription: null,

	/**
	 * フィールドのテーブル
	 * @public
	 * @type {Mailform.FormBuilder.Table}
	 */
	table: null,

	/**
	 * パレット
	 * @public
	 * @type {Mailform.FormBuilder.ObjectPalette}
	 */
	objectPalette: null,

	/**
	 * コンテクストメニュー
	 * @public
	 * @type {Mailform_FormBuilder_ContextMenu}
	 */
	contextMenu: null,

	/**
	 * フォームモデル
	 * @public
	 * @type {Mailform.FormBuilder.FormModel}
	 */
	form: null,

	/**
	 * Outlet
	 * @enum
	 */
	outlet: {
		controlPanel: '#mailformControlPanel',
		editorPanel: '#mailformNicEditPanel',
		formTitleBox: '#mailformTitleBox',
		formDescriptionBox: '#mailformDescriptionBox',
		formTable: '#mailformTable',
		objectPalette: '#mailformObjectPalette',
		contextMenu: '#mailformContextMenu',
		formData: '#mailformFormData'
	},

	/**
	 * セットアップする
	 * @public
	 */
	setUp: function() {
		Mailform.FormBuilder.application = this; // globalにセット

		this._setUpForm();
		this._setUpControlPanel();
		this._setUpEditorPanel();
		this._setUpFormTitle();
		this._setUpFormDescription();
		this._setUpObjectPalette();
		this._setUpTable();
		this._setUpContextMenu();
	},

	/**
	 * 実行する
	 * @public
	 */
	run: function() {
	},

	/**
	 * フォームモデルをセットアップする
	 * @protected
	 */
	_setUpForm: function() {
		var formData = $.parseJSON($(this.outlet.formData).text());
		delete formData.fields;
		this.form = new Mailform.FormBuilder.FormModel(formData);
	},

	/**
	 *
	 * @protected
	 */
	_setUpControlPanel: function() {
		var controlPanel = $(this.outlet.controlPanel).get(0);
		this.controlPanel = new Mailform.FormBuilder.ControlPanel(controlPanel);
		this.controlPanel.setUp();
	},

	/**
	 * NicEditをセットアップする
	 * @protected
	 */
	_setUpEditorPanel: function() {
		var editorPanel = $(this.outlet.editorPanel).get(0);
		this.editorPanel = new Mailform.FormBuilder.EditorPanel(editorPanel, editor);
		this.editorPanel.setUp();
	},

	/**
	 * フォームタイトルをセットアップする
	 * @protected
	 */
	_setUpFormTitle: function() {
		var formTitle = $(this.outlet.formTitleBox).get(0);
		this.formTitle = new Mailform.FormBuilder.FormTitle(formTitle);
		this.formTitle.setUp();
	},

	/**
	 * フォーム説明文をセットアップする
	 * @protected
	 */
	_setUpFormDescription: function() {
		var formDescription = $(this.outlet.formDescriptionBox).get(0);
		this.formDescription = new Mailform.FormBuilder.FormDescription(formDescription);
		this.formDescription.setUp();
	},

	/**
	 * フィールドの表をセットアップする
	 * @protected
	 */
	_setUpTable: function() {
		var table         = $(this.outlet.formTable).get(0);
		var fieldTemplate = $(this.outlet.fieldTemplate).removeAttr('id')[0];
		this.table = new Mailform.FormBuilder.Table(table);
		this.table.setUp();
	},

	/**
	 * オブジェクトパレットをセットアップする
	 * @protected
	 */
	_setUpObjectPalette: function() {
		var objectPalette = $(this.outlet.objectPalette).get(0);
		this.objectPalette = new Mailform.FormBuilder.ObjectPalette(objectPalette);
		this.objectPalette.setUp();
		this.objectPalette.position({
			of: this.outlet.formTable,
			my: 'left top',
			at: 'right top',
			offset: '10 0'
		});
	},

	/**
	 * コンテクストメニューをセットアップする
	 * @protected
	 */
	_setUpContextMenu: function() {
		var contextMenu = $(this.outlet.contextMenu).get(0);
		this.contextMenu = new Mailform.FormBuilder.ContextMenu(contextMenu, '.mailformField');
		this.contextMenu.setUp();
		this.contextMenu.cancel('input, .mailformFieldLabelPreview, .mailformGrabTab *, .mailformFieldRequired, .mailformFieldDescription, .mailformFieldDescription *');
	}
};