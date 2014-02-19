define([
	"com/tutomvc/core/view/mediator/Mediator",
	"jquery",
	"com/tutomvc/component/form/input/TagSelector",
	"com/tutomvc/component/model/proxy/Proxy"
],
function(Mediator, $, TagSelector, Proxy)
{
	TutoMVCLogsMediator.VIEW_COMPONENT_NAME = "logsViewComponent";
	TutoMVCLogsMediator.NAME = "TutoMVCLogsMediator";
	function TutoMVCLogsMediator()
	{
		/* VARS */
		var _this = this;
		var _json;
		var _totalLogs = 0;
		var _selector;
		var _selections = [];
		var _proxy;
		var _container;

		this.onRegister = function()
		{
			_json = JSON.parse( _this.getViewComponent().attr("data-provider") );
			
			draw();
		};

		var draw = function()
		{
			_container = _this.getViewComponent().find(".Logs");

			_selector = new TagSelector( $("<div></div>") );
			_selector.freeTagEnabled = false;

			_proxy = new Proxy();
			for(var key in _json)
			{
				_totalLogs++;
				_proxy.addVO( key, _json[key] );
			}

			_selector.model.addProxy( _proxy );
			_selector.reset();
			_selector.setLabel( _totalLogs + " logs" );
			_selector.addEventListener( "change", onSelect );

			_this.getViewComponent().prepend( _selector.getElement() );
		};

		var adjustUI = function()
		{
			$(_selector.getFilteredModel().getMap()).each(function()
				{
					var proxy = this;
					var proxyMap = proxy.getMap();

					_container.find(".Log").each(function()
					{
						if(!proxy.getVOByValue( $(this).attr("data-key") )) $(this).remove();
					});

					for(var key in proxyMap)
					{
						if(!_container.find(".Log[data-key='"+key+"']").length)
						{
							requestLog( proxyMap[key].getName(), key );
							// _container.prepend( $("<h1 class='Log' data-key='"+key+"'>"+proxyMap[key].getName()+"</h1>") );
						}
					}
				});
		};

		var requestLog = function( title, file )
		{
			var data = 
			{
				action : "tutomvc/ajax/render/log",
				nonce : TutoMVC.nonce,
				file : file,
				title : title
			};

			$.ajax({
				type: "post",
				dataType: "html",
				url: TutoMVC.ajaxURL,
				data: data,
				success: function(e)
				{
					var element = $(e).addClass( "Log" ).attr( "data-key", file );
					_container.prepend( element );
				},
				error: onAjaxError
			});
		};

		/* EVENTS */
		var onSelect = function(e)
		{
			_selections = e.getBody();
			
			adjustUI();
		};
		var onAjaxError = function(e)
		{
			console.log(e);
		};

		this.super( TutoMVCLogsMediator.NAME );
	};

	return Mediator.superOf( TutoMVCLogsMediator );
});