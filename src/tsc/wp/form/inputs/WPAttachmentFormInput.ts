export class WPAttachmentFormInput
{
	public $el: JQuery;
	public options: IWPAttachmentFormInputOptions = {
		max:       -1,
		title:     "",
		buttonText:"",
		type:      null,
		frame:     "select"
	};
	public wpMedia: any;
	public template: any;

	constructor( $el: JQuery, options: Object )
	{
		this.$el      = $el;
		// Model
		this.options  = jQuery.extend( {}, this.options, options );
		// View
		this.template = _.template( <string>this.$el.find( "textarea.underscore-template" ).val() );
		this.wpMedia  = wp.media( {
			title:   this.options.title,
			multiple:this.options.max < 0 || this.options.max > 1 ? true : false,
			library: this.options.type ? {type:this.options.type} : undefined,
			button:  {text:this.options.buttonText},
			frame:   this.options.frame
		} );
		// Controller
		this.wpMedia.on( "select", () => this.onWPMediaSelect() );
		this.$el.on( "click", ".btn-add", () => this.open() );
		this.$el.on( "click", ".btn-remove", ( e ) => this.onRemoveClick( e ) );

		this.render();
	}

	public render()
	{
		if( this.count >= this.options.max && this.options.max >= 0 ) this.$el.find( ".btn-add" ).prop( "disabled", "disabled" );
		else this.$el.find( ".btn-add" ).prop( "disabled", null );
	}

	public open()
	{
		this.wpMedia.open();
	};

	public add( attachmentModel: WPMediaAttachmentModel )
	{
		attachmentModel.src = attachmentModel.icon;
		if( !attachmentModel.width ) attachmentModel.width = "";
		if( !attachmentModel.height ) attachmentModel.height = "";
		if( attachmentModel.sizes )
		{
			if( attachmentModel.sizes.thumbnail && attachmentModel.sizes.thumbnail.url )
			{
				attachmentModel.src = attachmentModel.sizes.thumbnail.url;
			}
			else if( attachmentModel.sizes.full && attachmentModel.sizes.full.url )
			{
				attachmentModel.src = attachmentModel.sizes.full.url;
			}
		}
		var $el = jQuery( this.template( attachmentModel ) );
		this.$el.find( ".list-group" ).append( $el );
	};

	/* SET AND GET */
	public get count()
	{
		return this.$el.find( ".list-group-item" ).length;
	}

	/* EVENT HANDLERS */
	private onWPMediaSelect()
	{
		var selection = this.wpMedia.state().get( 'selection' );
		var __this    = this;
		selection.each( function( attachment )
		{
			if( __this.options.max < 0 || __this.count < __this.options.max )
			{
				__this.add( attachment.toJSON() );
			}
		} );

		this.render();
	};

	private onRemoveClick( e )
	{
		e.preventDefault();
		var $el = jQuery( e.currentTarget );
		$el.closest( ".list-group-item" ).remove();
		this.render();
	}
}

export interface IWPAttachmentFormInputOptions
{
	max: number;
	title: string;
	buttonText: string;
	type: string;
	frame: string;
}
