<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 05/01/15
	 * Time: 09:41
	 */

	namespace tutomvc\modules\termpage;

	use tutomvc\FilterCommand;

	/**
	 * Filter the term link.
	 *
	 * @since 2.5.0
	 *
	 * @param string $termlink Term link URL.
	 * @param object $term Term object.
	 * @param string $taxonomy Taxonomy slug.
	 */
	class GetTermLinkFilter extends FilterCommand
	{

		const NAME = "term_link";

		function __construct()
		{
			parent::__construct( self::NAME, 0, 3 );
		}

		function execute( $termLink, $term, $taxonomy )
		{
			$landingPage = TermPageModule::getLandingPageForTerm( $term->term_taxonomy_id );
			if ( $landingPage )
			{
				$termLink = get_permalink( $landingPage->ID );
			}

			return $termLink;
		}

	}