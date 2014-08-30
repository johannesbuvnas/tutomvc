<?php namespace tutomvc\modules\termpage;
use \tutomvc\ActionCommand;

class TaxonomyAddFormFieldsAction extends ActionCommand
{
	const NAME = "_edit_form";

	function __construct( $taxonomyName )
	{
		parent::__construct( $taxonomyName . self::NAME, 0, 2 );
	}

	function execute( $term, $taxonomy )
	{
		$page = TermPageModule::getLandingPageForTerm( $term->term_id );
		$selected = $page ? $page->ID : 0;
		?>
			<table class="form-table">
				<tr class="form-field form-required">
					<th scope="row"><label for="name"><?php _e( 'Landing Page', 'tutomvc' ); ?></label></th>
					<td>
						<?php wp_dropdown_pages( array( "selected" => $selected, 'show_option_none' => "-" ) ); ?>
						<p class="description"><?php _e( 'The selected landing page will override the default page for this term.', "tutomvc" ); ?></p>
					</td>
				</tr>
			</table>
		<?php
	}
}
