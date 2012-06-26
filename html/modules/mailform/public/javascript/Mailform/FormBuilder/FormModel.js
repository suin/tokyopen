/**
 * フォームモデル
 * @class
 */
Mailform.FormBuilder.FormModel = Mailform.Class.extend({
	/**
	 * 
	 * @protected
	 * @type {Object}
	 */
	data: {},

	/**
	 * コンストラクタ
	 * @public
	 * @param {Object} data
	 */
	construct: function(data) {
		this.data = data;
	},

	/**
	 * 値を取得する
	 * @public
	 * @param {string} name
	 * @return {string}
	 */
	get: function(name) {
		return this.data[name];
	},

	/**
	 * 値をセットする
	 * @public
	 * @param {string} name
	 * @param {mixed} value
	 */
	set: function(name, value) {
		this.data[name] = value;
		return this;
	},

	/**
	 * 値をすべて返す
	 * @public
	 * @return {Object}
	 */
	getData: function() {
		return $.extend(true, {}, this.data);
	}
});