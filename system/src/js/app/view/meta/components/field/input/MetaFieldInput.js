define([
	"tutomvc",
	"app/view/meta/components/field/input/text/TextInput",
	"app/view/meta/components/field/input/text/TextareaWYSIWYGInput",
	"app/view/meta/components/field/input/attachment/AttachmentList"
],
function(  tutomvc, TextInput, TextareaWYSIWYGInput, AttachmentList )
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

				component = new tutomvc.components.form.input.SingleSelector();
				component.setLabel( attributes.title );

				var proxy = new tutomvc.components.model.proxy.Proxy();

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