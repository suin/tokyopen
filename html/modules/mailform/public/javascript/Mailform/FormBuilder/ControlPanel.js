/**
 * @constructor
 * @param {HTMLDivElement} controlPannel
 */
Mailform.FormBuilder.ControlPanel = function(controlPanel) {
	this.dom = controlPanel;
};

Mailform.FormBuilder.ControlPanel.prototype = {

	/**
	 * 
	 * @protected
	 * @type {HTMLDivElement}
	 */
	dom: null,

	/**
	 * アウトレット
	 * @enum
	 * @type {Object}
	 */
	outlet: {
		saveButton: 'input[name=save]',
		errorMessages: '#mailformErrorMessages',
		successMesssage: '#mailformSuccessMessage'
	},

	dialog: null,

	/**
	 * セットアップする
	 * @public
	 */
	setUp: function() {
		this._registerEventListeners();
	},

	/**
	 * 保存する
	 * @public
	 */
	save: function() {
		$(this.outlet.errorMessages).hide();

		var app = Mailform.FormBuilder.application;
		var data = app.form.getData();
		data.fields = [];
		var rows = app.table.getRows();

		$(rows).each(function(){
			var fieldData = this.getData();
			data.fields.push(fieldData);
		});

		$form = $('#mailformForm').empty();
		$input = $('<input />').attr('type', 'hidden');
		$input.clone().attr('name', 'save').val('1').appendTo($form);
		$input.clone().attr('name', 'title').val(data.title).appendTo($form);
		$input.clone().attr('name', 'header_description').val(data.header_description).appendTo($form);

		for ( var i in data.fields ) {

			var field = data.fields[i];
			
			for ( var name in field ) {
				var value = field[name];
				var inputName = 'fields['+i+']['+name+']';

				if ( name == 'options' ) {
					for ( var optionName in value ) {
						var optionValue = value[optionName];
						$input.clone().attr('name', inputName+'['+optionName+']').val(optionValue).appendTo($form);
					}
					
				} else {
					$input.clone().attr('name', inputName).val(value).appendTo($form);
				}
			}
		}

		$form.submit();
	},

	/**
	 * イベントリスナを登録する
	 * @protected
	 */
	_registerEventListeners: function() {
		var self = this;
		$(this.dom)
			.delegate(this.outlet.saveButton, 'click', function(event){ return self._handleClickSaveButtonEvent(this, event); });
		;
	},

	/**
	 * 保存ボタンクリックイベント
	 * @protected
	 */
	_handleClickSaveButtonEvent: function(button, event) {
		$(button).attr("disabled", "disabled");
		this.save();
	}
};