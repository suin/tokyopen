/**
 * @constructor
 * @param {SiteNavi_Sitemap_Application} app
 */
var SiteNavi_Sitemap_Node = function() {
	this.html = $($('#siteNaviNodeTemplate').html())[0];
};

SiteNavi_Sitemap_Node.prototype = {

	/**
	 * @protected
	 */
	html: '',

	/**
	 * @protected
	 * @enum
	 */
	outlet: {
		title: '.title',
		toggle: '.toggle',
		icon: '.icon',
		childrenTotal: '.childrenTotal'
	},

	/**
	 * @protected
	 * @enum
	 */
	className: {
		hasChildren: {
			toggle: 'togglePlus',
			icon: 'iconFolder',
			childrenTotal: 'childrenExists'
		},
		noChildren: {
			toggle: 'toggleZero',
			icon: 'iconPage',
			childrenTotal: 'childrenZero'
		}
	},

	/**
	 * @public
	 */
	asHTML: function() {
		return $('<div>').append( $(this.html).eq(0).clone() ).html();
		// ↑ return this.html.outerHTML; と同じ (FirefoxはouterHtmlがない)
	},

	/**
	 * 
	 * @public
	 * @param {Object} data
	 * @return {Object} this
	 */
	setData: function(data) {
		for ( var name in data ) {
			var value = data[name];
			var name  = 'data-'+name;
			$(this.html).attr(name, value);
		}

		return this;
	},

	/**
	 * @public
	 * @param {string} title
	 */
	setTitle: function(title) {
		$(this.html).children(this.outlet.title).text(title);
		return this;
	},

	/**
	 * @public
	 * @param {string} icon
	 */
	setIcon: function(icon) {
		$(this.html).children(this.outlet.icon).addClass(icon);
		return this;
	},

	/**
	 * @public
	 * @param {integer} total
	 */
	setChildrenTotal: function(total) {
		if ( total > 0 ) {
			$(this.html).attr('data-children', total);
			$(this.html).children(this.outlet.toggle).addClass(this.className.hasChildren.toggle);
			$(this.html).children(this.outlet.childrenTotal).addClass(this.className.hasChildren.childrenTotal);
		} else {
			$(this.html).attr('data-children', 0);
			$(this.html).children(this.outlet.toggle).addClass(this.className.noChildren.toggle);
			$(this.html).children(this.outlet.childrenTotal).addClass(this.className.noChildren.childrenTotal);
		}

		$(this.html).children(this.outlet.childrenTotal).text(total);

		return this;
	},

	/**
	 * @public
	 * @param {Object} data
	 */
	applyData: function(data) {
		this.setData(data)
			.setTitle(data.title)
			.setIcon(data.icon_name)
			.setChildrenTotal(data.children);
		return this;
	},

	/**
	 * @public
	 * @param {string} child HTML
	 */
	addChild: function(child) {
		$(child).appendTo(this.html);
		$(this.html).children(this.outlet.toggle).removeClass('togglePlus').addClass('toggleMinus');
		return this;
	},

	/**
	 * @public
	 * @param {Array} children
	 */
	addChildren: function(children) {
		for ( i in children ) {
			var child = children[i];
			this.addChild(child);
		}
		return this;
	},

	/**
	 * @public
	 * @param {Object} data
	 */
	addChildByData: function(data) {
		var node = new SiteNavi_Sitemap_Node();
		child = node.applyData(data).asHTML();
		this.addChild(child);
		return this;
	},

	/**
	 * @public
	 * @param {Array} childrenData
	 */
	addChildrenByData: function(childrenData) {
		for ( i in childrenData ) {
			var data = childrenData[i];
			this.addChildByData(data);
		}
		
		return this;
	}
};
