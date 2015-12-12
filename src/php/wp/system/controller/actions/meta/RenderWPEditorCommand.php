<?php
	namespace tutomvc\wp;

	class RenderWPEditorCommand extends ActionCommand
	{
		function __construct()
		{
			parent::__construct( ActionCommand::RENDER_WP_EDITOR );
			$this->setExecutionLimit( 1 );
		}

		function execute()
		{
			wp_editor( 'Tuto MVC', 'tutomvc-editor' );
			?>
			<textarea class="hidden tutomvc-wp-editor-html"><?php wp_editor( "", '[ID]', array("quicktags"     => FALSE,
			                                                                                               "textarea_name" => "tutomvc-wp-editor"
					) ); ?></textarea>
		<?php
		}
	}