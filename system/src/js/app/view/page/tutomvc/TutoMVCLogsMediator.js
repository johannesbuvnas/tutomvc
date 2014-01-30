define([
	"tutomvc",
	"jquery"
],
function(tutomvc, $)
{
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

			_selector = new tutomvc.components.form.input.TagSelector( $("<div></div>") );
			_selector.freeTagEnabled = false;

			_proxy = new tutomvc.components.model.proxy.Proxy();
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
				nonce : Tuto.nonce,
				file : file,
				title : title
			};

			$.ajax({
				type: "post",
				dataType: "html",
				url: Tuto.ajaxURL,
				data: data,
				success: onAjaxResult,
				error: onAjaxError
			});
		};

		/* EVENTS */
		var onSelect = function(e)
		{
			_selections = e.getBody();
			
			adjustUI();
		};

		var onAjaxResult = function(e)
		{
			var element = $(e).addClass( "Log" );
			_container.prepend( element );
		};
		var onAjaxError = function(e)
		{
			console.log(e);
		};
	}

	function ClassConstructor()
	{
		TutoMVCLogsMediator.prototype = new tutomvc.core.view.mediator.Mediator( "TutoMVCLogsMediator" );
		TutoMVCLogsMediator.prototype.constructor = TutoMVCLogsMediator;

		return new TutoMVCLogsMediator();
	};

	ClassConstructor.VIEW_COMPONENT_NAME = "logsViewComponent";

	return ClassConstructor;
});