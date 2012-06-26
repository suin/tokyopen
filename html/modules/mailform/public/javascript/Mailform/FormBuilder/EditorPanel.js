/**
 * @constructor
 */
Mailform.FormBuilder.EditorPanel = function(dom, nicEdit) {
	this.dom = dom;
	this.nicEdit = nicEdit;
};

Mailform.FormBuilder.EditorPanel.prototype = {
	
	/**
	 * @public
	 */
	dom: null,

	nicEdit: null,

	/**
	 *
	 * @public
	 */
	setUp: function() {
		this.nicEdit.setPanel('mailformNicEditPanel');
	},

	/**
	 *
	 * @public
	 */
	show: function() {
		$(this.dom).show();
	},

	/**
	 *
	 * @public
	 */
	hide: function() {
		$(this.dom).hide();
	},

	/**
	 *
	 * @public
	 */
	floatBefore: function(selector) {
		var targetWidth = $(selector).width();
		$(this.dom).width(targetWidth);
		this.show();
		this.position({
			of: selector,
			my: 'center bottom',
			at: 'center top',
			offset: '0 -5'
		});
	},

	/**
	 * パレットを配置する
	 * @public
	 */
	position: function(option) {
		var defaultOption = {
			of: 'body',
			my: 'center center',
			at: 'center center',
			offset: '0 0'
		};

		option = $.extend(defaultOption, option);

		$(this.dom).position({
			of: $(option.of),
			my: option.my,
			at: option.at,
			offset: option.offset,
			collision: 'none'
		});
	}
};