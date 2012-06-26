/**
 * @constructor
 * @param {HTMLTableRowElement} row
 */
Mailform.FormBuilder.TableRow = function(row) {
	this.dom = row;
	this.data = {
		id: 0,
		label: '',
		type: '',
		required: 0,
		description: '',
		options: {}
	};
	this.nicEditInstance = null;
	this._regenerateDescriptionDomId();
};

Mailform.FormBuilder.TableRow.prototype = {

	/**
	 * @public
	 * @type {HTMLTableRowElement}
	 */
	dom: null,

	/**
	 * アウトレット
	 * @enum
	 * @type {Object}
	 */
	outlet: {
		labelArea: '.mailformFieldLabelArea',
		labelPreview: '.mailformFieldLabelPreview',
		labelEdit: '.mailformFieldLabelEdit',
		inputPlaceholder: '.mailformFieldInputPlaceholder',
		inputPlaceholderObjectMock: '.mailformObjectGraphicMock',
		grabTab: '.mailformGrabTab',
		highlight: '.mailformFieldHighlight',
		objectGraphicMask: '.mailformObjectGraphicMask',
		required: '.mailformFieldRequired',
		requiredTrue: '.mailformFieldRequiredTrue',
		requiredFlase: '.mailformFieldRequiredFalse',
		description: '.mailformFieldDescription'
	},

	/**
	 * データ
	 * @protected
	 * @type {Object}
	 */
	data: {},

	/**
	 * 仮追加行フラグ
	 * @protected
	 * @type {Object}
	 */
	prepared: false,

	/**
	 * NicEditのインスタンス
	 * @protected
	 * @type {bkClass}
	 */
	nicEditInstance: null,

	/**
	 * 説明文編集エリアID
	 * @protected
	 * @type {int}
	 */
	descriptionDomId: null,

	/**
	 * 
	 * @protected
	 * @static
	 * @type {int}
	 */
	descriptionDomIdIndex: 0,

	/**
	 * @public
	 */
	setUp: function() {
		$(this.dom).data('tableRow', this);
		this._registerEventListeners();
		this._setUpInputPlaceholder();
		this._setUpGrabTab();
		this._setUpDescription();
	},

	/**
	 * データをセットする
	 * @public
	 * @param {string} name
	 * @param {mixed} value
	 */
	set: function(name, value) {
		if ( this.data[name] == undefined ) {
			return;
		}

		this.data[name] = value;

		if ( name == 'label' ) {
			this._changeLabel(value);
		}

		if ( name == 'required' ) {
			this._changeRequired(value);
		}

		if ( name == 'type' ) {
			this._changeType(value);
		}

		if ( name == 'description' ) {
			this._changeDescription(value);
		}
	},

	/**
	 * データをセットする
	 * @public
	 * @param {Object<string, mixed>} data
	 */
	setData: function(data) {
		for ( name in data ) {
			this.set(name, data[name]);
		}
	},

	/**
	 * データを取得する
	 * @public
	 * @param {string} name
	 */
	get: function(name) {
		return this.data[name];
	},

	/**
	 * データを返す
	 * @public
	 */
	getData: function() {
		return this.data;
	},

	/**
	 * 行を削除する
	 * @public
	 */
	remove: function() {
		$(this.dom).remove();
	},

	/**
	 * 行をハイライトする
	 * @public
	 */
	highlight: function() {
		$(this.dom).addClass(this.outlet.highlight.substr(1));
	},

	/**
	 * 行のハイライトを解除する
	 * @public
	 */
	lowdark: function() {
		$(this.dom).removeClass(this.outlet.highlight.substr(1));
	},

	/**
	 * フィールドオブジェクトを持っているかを返す
	 * @public
	 * @return {boolean}
	 */
	hasObject: function() {
		return ( this.data.type != '' );
	},

	/**
	 * フィールドを削除する
	 * @public
	 */
	removeField: function() {
		this.set('type', '');
	},

	/**
	 * 仮追加行として現れる
	 * @public
	 */
	appearAsPreparedRow: function() {
		this.prepared = true;
		$(this.dom)
			.children()
				.children()
					.hide()
					.slideDown();
	},

	/**
	 * 仮追加業として隠れる
	 * @public
	 */
	hideAsPreparedRow: function() {
		if ( this.prepared == false ) {
			return;
		}

		if ( this.data.type != '' ) {
			this.prepared = false;
			return;
		}

		var row = this.dom;

		$(this.dom)
			.find(this.outlet.grabTab)
				.hide()
				.end()
			.children()
				.children()
					.stop()
					.slideUp(500, function(){
						$(row).remove();
					});
	},

	/**
	 * NicEditのインスタンスを追加する
	 * @public
	 */
	addNicEditInstance: function() {
		var app = Mailform.FormBuilder.application;
		var nicEdit = app.editorPanel.nicEdit;

		if ( nicEdit.instanceById(this.descriptionDomId) == undefined && $('#'+this.descriptionDomId).length > 0 ) {
			var self = this;
			this.nicEditInstance = nicEdit.addInstance(this.descriptionDomId);
			this.nicEditInstance.addEvent('blur', function(bkClass){ self._hadleBlurDescriptionEvent(bkClass); });
		}
	},

	/**
	 * イベントリスナを登録する
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;
		$(this.dom)
			.delegate(this.outlet.labelPreview, 'click', function(event){ return self._handleClickLabelPreviewEvent(this, event); })
			.delegate(this.outlet.labelEdit, 'blur', function(event){ return self._handleBlurLabelEditEvent(this, event); })
			.delegate(this.outlet.labelEdit, 'keydown', function(event){ return self._handleKeydownLabelEditEvent(this, event); })
			.delegate(this.outlet.required, 'click', function(event){ return self._handleClickRequiredEvent(this, event); })
			.delegate(this.outlet.description, 'click', function(event){ return self._hadleClickDescriptionEvent(this, event); })
//			.delegate(this.outlet.description, 'blur', function(event){ return self._hadleBlurDescriptionEvent(this, event); })
		;
	},

	/**
	 * 入力欄プレースホルダをセットアップする
	 * @protected
	 */
	_setUpInputPlaceholder: function() {
		var self = this;

		// ドロッパブルにする
		$(this.dom)
			.find(this.outlet.inputPlaceholderObjectMock)
				.droppable({
					accept: ".mailformObject",
					hoverClass: "ui-state-active",
					activeClass: "ui-state-hover",
					drop: function(event, ui) { self._handleDropInputEvent(this, event, ui); }
				});
	},

	/**
	 * 並び替えのつまみをセットアップする
	 * @protected
	 */
	_setUpGrabTab: function() {
		$(this.dom).find(this.outlet.grabTab).button({
			icons: {
				primary: 'ui-icon-arrowthick-2-n-s'
			},
			text: false
		});
	},

	/**
	 * 説明欄をセットアップする
	 * @public
	 */
	_setUpDescription: function() {
		$(this.dom).find(this.outlet.description).attr('id', this.descriptionDomId);
	},

	/**
	 * 入力欄をドロップしたときのイベント
	 * @protected
	 */
	_handleDropInputEvent: function(inputPlaceholderObjctGraphicMock, event, ui) {
		var object = $(ui.draggable).data('model');
		$('.ui-state-hover').removeClass('ui-state-hover'); // ui-state-hoverが自動ではなくならない。jqueryのバグっぽい
		this.set('type', object.get('name'));
	},

	/**
	 * タイプを変更する
	 * @protected
	 */
	_changeType: function(type) {
		var app = Mailform.FormBuilder.application;
		var object = app.objectPalette.getObject(type);

		if ( object == undefined ) {
			$(this.dom)
				.find(this.outlet.inputPlaceholderObjectMock)
					.html('&nbsp;')
					.droppable('enable');
			this.set('options', {});
		} else {
			field = $.extend(true, {}, object); // deep copy
			var mockHTML = field.get('mockHTML');
			$(mockHTML).removeAttr('name');
			$(this.dom)
				.find(this.outlet.inputPlaceholderObjectMock)
					.html(mockHTML)
					.droppable('disable'); // ドロップを無効化
			this.set('options', field.get('options'));
		}
	},

	/**
	 * ラベルプレビューをクリックしたときのイベント
	 * @protected
	 */
	_handleClickLabelPreviewEvent: function(labelPreview, event) {
		var width = $(this.dom).find(this.outlet.labelArea).width();
		$(this.dom).find(this.outlet.labelArea).width(width);
		$(this.dom).find(this.outlet.labelPreview).hide();
		$(this.dom).find(this.outlet.labelEdit).show().focus().select();
	},

	/**
	 * ラベル編集からフォーカスアウトしたときのイベント
	 * @protected
	 */
	_handleBlurLabelEditEvent: function(labelEdit, event) {
		$(this.dom).find(this.outlet.labelArea).width('auto');
		var label = $(this.dom).find(this.outlet.labelEdit).val();
		var $labelEdit    = $(this.dom).find(this.outlet.labelEdit);
		var $labelPreview = $(this.dom).find(this.outlet.labelPreview);

		$labelEdit.hide();
		this.set('label', label);
		$labelPreview.show();
	},

	/**
	 * ラベル編集でキーを押した時のイベント
	 * @protected
	 */
	_handleKeydownLabelEditEvent: function(labelEdit, event) {
		if ( event.keyCode == 13 ) {
			// Enter
			$(labelEdit).blur();
		}
	},

	/**
	 * 必須マークをクリックしたときのイベント
	 * @protected
	 */
	_handleClickRequiredEvent: function(required, event) {
		if ( $(required).is(this.outlet.requiredTrue) == true ) {
			this.set('required', 0);
		} else {
			this.set('required', 1);
		}
	},

	/**
	 * 必須を変更する
	 * @public
	 */
	_changeRequired: function(value) {
		if ( value == 1 ) {
			this._required();
		} else {
			this._unrequired();
		}
	},

	/**
	 * 必須にする
	 * @protected
	 */
	_required: function() {
		var classRequiredFalse = this.outlet.requiredFlase.substr(1);
		var classRequiredTrue  = this.outlet.requiredTrue.substr(1);
		var $required = $(this.dom).find(this.outlet.required);
		var label = $required.data('requiredLabel');
		$required.removeClass(classRequiredFalse).addClass(classRequiredTrue).text(label);
	},

	/**
	 * 任意にする
	 * @protected
	 */
	_unrequired: function() {
		var classRequiredFalse = this.outlet.requiredFlase.substr(1);
		var classRequiredTrue  = this.outlet.requiredTrue.substr(1);
		var $required = $(this.dom).find(this.outlet.required);
		var label = $required.data('optionalLabel');
		$required.removeClass(classRequiredTrue).addClass(classRequiredFalse).text(label);
	},

	/**
	 * ラベルを変更する
	 * @protected
	 */
	_changeLabel: function(label) {

		var $labelEdit    = $(this.dom).find(this.outlet.labelEdit);
		var $labelPreview = $(this.dom).find(this.outlet.labelPreview);

		if ( label == '' ) {
			$labelPreview.addClass('blank');
			label = $labelEdit.attr('defaultValue');
		} else {
			$labelPreview.removeClass('blank');
			$labelEdit.val(label);
		}

		$labelPreview.text(label);
	},

	/**
	 * 説明文を変更する
	 * @protected
	 */
	_changeDescription: function(description) {
		this.addNicEditInstance();

		if ( editor.instanceById(this.descriptionDomId) == undefined ) {
			$(this.dom).find(this.outlet.description).html(description);
		} else {
			nicEditors.findEditor(this.descriptionDomId).setContent(description);
		}
	},

	/**
	 * 説明文編集エリアのIDを生成する(NicEditがIDを参照するので必要)
	 * @protected
	 */
	_regenerateDescriptionDomId: function() {
		var id = Mailform.FormBuilder.TableRow.prototype.descriptionDomIdIndex;
		this.descriptionDomId = 'mailformDescription_' + id;
		Mailform.FormBuilder.TableRow.prototype.descriptionDomIdIndex += 1;
	},

	/**
	 * 説明文編集エリアをクリックしたときのイベント
	 * @protected
	 */
	_hadleClickDescriptionEvent: function() {
		var app = Mailform.FormBuilder.application;
		var description = $(this.dom).find(this.outlet.description);
		app.editorPanel.floatBefore(description);
	},

	/**
	 * 説明文編集エリアからフォーカスが外れたときのイベント
	 * @protected
	 */
	_hadleBlurDescriptionEvent: function(bkClass) {
		if ( !bkClass ) {
			return;
		}

		var editorId = $(bkClass.elm).attr('id');

		if ( editorId != this.descriptionDomId ) {
			return;
		}

		var content = nicEditors.findEditor(this.descriptionDomId).getContent();
		var app = Mailform.FormBuilder.application;
		this.set('description', content);
		app.editorPanel.hide();
	}
};