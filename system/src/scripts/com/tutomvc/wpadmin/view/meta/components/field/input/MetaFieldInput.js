define([
	"com/tutomvc/component/form/input/SingleSelector",
	"com/tutomvc/component/model/proxy/Proxy",
	"com/tutomvc/wpadmin/view/meta/components/field/input/text/TextInput",
	"com/tutomvc/wpadmin/view/meta/components/field/input/text/TextareaWYSIWYGInput",
	"com/tutomvc/wpadmin/view/meta/components/field/input/attachment/AttachmentList"
],
function( SingleSelector, Proxy, TextInput, TextareaWYSIWYGInput, AttachmentList )
{
	return function( attributes )
	{
		var component;

		switch( attributes.type.name )
		{
			case "textarea_wysiwyg":

				component = new TextareaWYSIWYGInput( attributes.value, attributes.id, attributes.type.settings );

			break;
			case "attachment":

				component = new AttachmentList( attributes.type.settings );
				component.setName( attributes.name );
				component.setValue( attributes.value );

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
			default:

				component = new TextInput( attributes.value );

			break;
		}

		if(component) component.setName( attributes.name );

		return component;
	};
});