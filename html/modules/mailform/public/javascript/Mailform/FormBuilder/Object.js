Mailform.FormBuilder.Object = Mailform.Class.extend({

	data: {},

	/**
	 * コンストラクタ
	 * @public
	 */
	construct: function(data) {
		this.data = data;
	},

	/**
	 *
	 * @public
	 */
	set: function(name, value) {
		this.data[name] = value;
	},

	/**
	 *
	 * @public
	 */
	get: function(name) {
		return this.data[name];
	}
});