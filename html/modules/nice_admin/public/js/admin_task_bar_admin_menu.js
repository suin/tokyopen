/**
 * 管理画面をモーダルダイアログで表示するクラス
 */

/**
 * @constructor
 */
var NiceAdmin_AdminTaskBar_AdminMenu = function() {
	this._registerEventListeners(this);
};

NiceAdmin_AdminTaskBar_AdminMenu.prototype = {

	/**
	 * @constant
	 */
	AJAXMODE_QUERYSTRING: 'ajaxmode=2',

	/**
	 * 親窓に対するモーダルウィンドウの幅のパーセンテージ
	 * @constant
	 */
	MODAL_WINDOW_WIDTH_RATE: 0.9,

	/**
	 * 親窓に対するモーダルウィンドウの縦のパーセンテージ
	 * @constant
	 */
	MODAL_WINDOW_HEIGHT_RATE: 0.85,

	/**
	 * アウトレット
	 * @enum
	 */
	outlet: {
		LIST: '[id^=tpModal]',
		MODAL_WINDOW: '#adminTaskBarModalWindow',
		MODAL_TITLE: '#ui-dialog-title-adminTaskBarModalWindow',
		IFRAME: '#adminTaskBarIframe',
		HIDDEN_CONTENTS: '#header, #leftcolumn, #footer' // iframe内で非表示にするDOM
	},

	/**
	 * メイン関数
	 * @public
	 */
	run: function() {
	},

	/**
	 * イベントリスナを登録
	 * @protected
	 * @params NiceAdmin_AdminTaskBar_AdminMenu self
	 */
	_registerEventListeners: function(self) {
		$('body')
			.delegate(this.outlet.LIST, 'click', function(event){ return self._listClickEventHandler(this, event); })
		;
	},

	/**
	 * リストをクリックしたときのイベント
	 * @protected
	 */
	_listClickEventHandler: function(list, event) {

		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		this._setUpModalWindow();

		var url = $(list).find('a').attr('href');
		var title = $(list).text();

		url = this._addAjaxModeQueryString(url);

		var modalWindow = $(this.outlet.MODAL_WINDOW).html('');
		
		// set title
		$(this.outlet.MODAL_TITLE).text(title);
		modalWindow.attr('title', title);

		var _self = this;

		$('<iframe />')
			.hide()
			.attr('id', this.outlet.IFRAME.substr(1))
			.appendTo($(this.outlet.MODAL_WINDOW))
			.attr('src', url)
			.load(function(){
				$(this).contents().find(_self.outlet.HIDDEN_CONTENTS).hide(); // メインコンテンツ以外非表示にする
				$(this).show();
				var myIframeWin = this.contentWindow || myIframe.contentDocument;
				$(myIframeWin).unload(function(){
					$(_self.outlet.IFRAME).hide(); // ページ遷移中は非表示にする
				});
			})
		;

		var windowWidth  = Math.round($(window).width()  * this.MODAL_WINDOW_WIDTH_RATE);
		var windowHeight = Math.round($(window).height() * this.MODAL_WINDOW_HEIGHT_RATE);

		modalWindow.dialog({
			width: windowWidth, 
			height: windowHeight,
			modal: true,
			sticky: true,
			open: function(event, ui){
				/*
				* Scrollbar fix http://bugs.jqueryui.com/ticket/3623
				*/
				$('.ui-widget-overlay').css('width','100%');
				$('body').addClass('adminTaskBarOverflowHidden');
			},
			close: function(event, ui) {
				$('body').removeClass('adminTaskBarOverflowHidden');
			},
			buttons: {
				"Close": function() {
					$(this).dialog('close');
				}
			}
		});

		return false;
	},

	/**
	 * モーダルウィンドウ用のキャンバスをセットアップする
	 */
	_setUpModalWindow: function() {
		if ( $(this.outlet.MODAL_WINDOW).length > 0 ) {
			return;
		}

		$('<div />')
			.attr('id', this.outlet.MODAL_WINDOW.substr(1))
			.appendTo('body')
		;
	},

	/**
	 * URLにajaxmodeクエリーを付ける
	 */
	_addAjaxModeQueryString: function(url) {
		if ( url.indexOf('?') == -1 ) {
			return url + '?' + this.AJAXMODE_QUERYSTRING;
		} else {
			return url + '&' + this.AJAXMODE_QUERYSTRING;
		}
	}
};

jQuery(function(){
	var ins = new NiceAdmin_AdminTaskBar_AdminMenu();
	ins.run();

	$("ul.adminTaskBarNav li.tpNoModalBlocksAdmin").mouseover(
		function(){
			 $(this).children("ul").show();
		});

	$("ul.adminTaskBarNav li.tpNoModalBlocksAdmin").mouseout(
		function(){
			$(this).children("ul").hide();
		}
	);
});


