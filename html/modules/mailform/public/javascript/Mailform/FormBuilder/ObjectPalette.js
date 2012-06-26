/**
 * @constructor
 * @param {HTMLDivElement} objectPalette 
 */
Mailform.FormBuilder.ObjectPalette = function(objectPalette) {
	this.dom = objectPalette;
};

Mailform.FormBuilder.ObjectPalette.prototype = {

	/**
	 * @protected
	 * @type {HTMLDivElement}
	 */
	dom: null,

	/**
	 * オブジェクトのテンプレート
	 * @protected
	 * @type {HTMLDivElement}
	 */
	objectTemplate: null,

	/**
	 * Outlet
	 * @enum
	 */
	outlet: {
		title: '.mailformObjectPaletteHead',
		body: '.mailformObjectPaletteBody',
		objectTitle: '.mailformObjectTitle',
		objectGraphicMock: '.mailformObjectGraphicMock',
		objectData: '#mailformObjectData'
	},

	/**
	 * オブジェクトの配列
	 * @protected
	 * @type {Object}
	 */
	objects: {},

	/**
	 * セットアップする
	 * @public
	 */
	setUp: function() {
		this._setUpTemplate();
		this._setUpObjects();
		this._setUpPalette();
		this._draggableDOM();
	},

	/**
	 * オブジェクトを返す
	 * @public
	 */
	getObject: function(name) {
		return this.objects[name];
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
	},

	/**
	 * パレットオブジェクトのテンプレートをセットアップする
	 * @protected
	 */
	_setUpTemplate: function() {
		this.objectTemplate = $(this.dom).find('[template]').clone().removeAttr('template');
		$(this.dom).find('[template]').remove();
	},

	/**
	 * オブジェクトをセットアップする
	 * @protected
	 */
	_setUpObjects: function() {
		var data = $(this.outlet.objectData).text();
		objects = $.parseJSON(data);

		for ( name in objects ) {
			this.objects[name] = new Mailform.FormBuilder.Object(objects[name]);
		}
	},

	/**
	 * パレットをセットアップする
	 * @protected
	 */
	_setUpPalette: function() {

		// <body />直下に移動
		$(this.dom).remove().appendTo('body').css({position:'absolute'});

		var body = $(this.dom).find(this.outlet.body);
		var self = this;

		for ( name in this.objects ) {
			var object = this.objects[name];
			var objectElement = $(this.objectTemplate).clone();

			// モデル設定
			$(objectElement)
				.data('model', object)
				.draggable({
					cursor: 'move',
					cursorAt: {
						bottom: 10, 
						right: 70
					},
					helper: function( event ) {
						var helper = $('<div />').addClass('mailformObjectGraphicHelper');
						var $mock = $(this).find(self.outlet.objectGraphicMock).children();
						var mockHeight = $mock.height();
						var mockWidth = $mock.width();
						var top = parseInt(mockHeight / 2);
						var left = parseInt(mockWidth / 2);
						$(this).draggable("option", 'cursorAt', {
							top: top,
							left: left
						});
						$mock.clone().css({cursor:'move'}).appendTo(helper);

						return helper;
					}
				});

			// ビュー設定
			$(objectElement)
				.attr('title', object.get('title'))
				.attr('name', object.get('name'))
				.find(this.outlet.objectTitle)
					.text(object.get('title'))
					.end()
				.find(this.outlet.objectGraphicMock)
					.html(object.get('mockHTML'))
					.end()
				.appendTo(body);

			$('<div />')
				.addClass('mailformObjectSeparator')
				.appendTo(body);
		}

		$(body).find('.mailformObjectSeparator:last').remove();
		$(this.dom).show();
	},

	/**
	 * パレットをドラッグで移動できるようにする
	 * @protected
	 */
	_draggableDOM: function() {
		$(this.dom).draggable({
			handle: this.outlet.title
		});
	}
};