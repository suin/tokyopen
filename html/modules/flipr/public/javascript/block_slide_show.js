jQuery(function($){
	$(function ()
	{
		var i = 1;
	
		$('.FeaturedSlide').each(function(){
		
			var navId = 'FeaturedSliePager' + i;
		
			$(this).after('<div class="FeaturedSlieNav"><ul class="FeaturedSliePager" id="'+navId+'">');
			$(this).cycle({
				timeout: 5000, /* milliseconds between slide transitions (0 to disable auto advance) */
				fx: 'fade', /* choose a transition type, ex: fade, scrollUp, shuffle, etc...*/
				pager: '#'+navId, /* selector for element to use as pager container*/
				pause: 0, /* true to enable "pause on hover"*/
				pauseOnPagerHover: 0 /* true to pause when hovering over pager link*/
			});

			i = i + 1;
		});
	});
});
