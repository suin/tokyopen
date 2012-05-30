jQuery(function($){

	var columns = [
			{ id: 0, name: '#LeftColumn' },
			{ id: 1, name: '#RightColumn' },
			{ id: 3, name: '#CenterLColumn' },
			{ id: 4, name: '#CenterRColumn' },
			{ id: 5, name: '#CenterCColumn' }
	];

	var linkDrugDrop = '#tpNoModalDrugDrop';

	var main = function()
	{
		stortableBlock();
		save();
	}

	var stortableBlock = function()
	{
		$(columns).each(function()
		{
			if ( this.name )
			{
				$(this.name).addClass("connectedSortable").sortable({
					connectWith: '.connectedSortable',
					placeholder: 'ui-state-highlight',
					start: function (e, ui) {
						var height = ui.item.height();
						ui.placeholder.css({'height':height+'px'});
						ui.item.addClass('drugging');
					},
					stop: function (e, ui) {
						ui.item.removeClass('drugging');
					}
				});
			}
		});
	}

	var save = function()
	{
		$(linkDrugDrop).click(eventClickLinkDrugDrop);
	}

	var eventClickLinkDrugDrop = function(event)
	{
		event.preventDefault();
	
		var data = {};

		$(columns).each(function()
		{
			var sideid = this.id;
			data[sideid] = {};
            var i = 0;
			if ( this.name )
			{
				$(this.name).find("> [blockid]").each(function(i)
				{
					var blockid = $(this).attr('blockid');
					
					if ( blockid == '' )
					{
						return;
					}
                    
					data[sideid][i] = blockid;
					i++;
				});
			}
		});

		var url = XOOPS_URL+'/modules/nice_block/admin/index.php?controller=api_drop';

		$.ajax({
		  type: 'POST',
		  url: url,
		  data: {'sides':data, 'url':location.href},
		  success: postSuccess,
		  error: postFailed,
		  dataType: 'json'
		});

		return false;
	}

	var postSuccess = function(result)
	{
		alert(result.message);
		redirect(result.url);
	}

	var postFailed = function(XMLHttpRequest, textStatus, errorThrown)
	{
		alert('ERROR');
	}

	var redirect = function(url)
	{
		location.href = url;
	}

	main();
});
