define([
	"com/tutomvc/component/form/input/SingleSelector",
	"com/tutomvc/component/model/proxy/Proxy",
	"com/tutomvc/wpadmin/view/meta/components/field/input/text/TextInput",
	"com/tutomvc/wpadmin/view/meta/components/field/input/text/TextareaWYSIWYGInput",
	"com/tutomvc/wpadmin/view/meta/components/field/input/attachment/AttachmentList",
	"com/tutomvc/component/form/TextArea",
	"com/tutomvc/component/form/Input"
],
function( SingleSelector, Proxy, TextInput, TextareaWYSIWYGInput, AttachmentList, TextArea, Input )
{
	return function( attributes )
	{
		var component; 
		var model = new Input.Model( attributes );
		model.set( attributes.type.settings );

		switch( attributes.type.name )
		{
			case "textarea_wysiwyg":

				component = new TextareaWYSIWYGInput({
					model : model,
				});

			break;
			case "attachment":

				component = new AttachmentList( {
					model : model
				} );

			break;
			case "selector_single":

				component = new SingleSelector();
				component.setLabel( attributes.title );

				var proxy = new Proxy();

				for(var key in attributes.type.settings.options)
				{
					proxy.addVO( attributes.type.settings.options[key], key );
					if(key == attributes.value)
					{
						component.setLabel( attributes.type.settings.options[key] );
						component.setValue( attributes.value );
					}
				}

				component.model.addProxy( proxy );
				component.reset();

			break;
			case "textarea":

				component = new TextArea({
					model : model
				});

			break;
			default:

				// component = new TextInput( attributes.value );
				component = new Input({
					model : model,
					attributes : {
						type : "text"
					}
				});

			break;
		}

		if(component) component.setName( attributes.name );

		return component;
	};
});