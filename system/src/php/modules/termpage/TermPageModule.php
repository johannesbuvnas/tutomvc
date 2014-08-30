<?php namespace tutomvc\modules\termpage;
use \tutomvc\Facade;
////////////////////////////////////////////////////////////////////////////////////////////////////////////
class TermPageModule extends Facade
{
	/* CONSTANTS */
	const KEY = "tutomvc/modules/termpage/facade";
	const TERM_PAGE_META_KEY_TERM_TAXONOMY_ID = "tutomvc_term_landing_page_termTaxonomyID";

	public static $PRODUCTION_MODE = false;

	protected $_supportedTaxonomies;

	function __construct( $supportedTaxonomies = array( "category", "post_tag" ) )
	{
		parent::__construct( self::KEY );

		if(is_array($supportedTaxonomies)) $this->_supportedTaxonomies = $supportedTaxonomies;
		else $this->_supportedTaxonomies = array();
	}

	public function onRegister()
	{
		$this->prepModel();
		$this->prepView();
		$this->prepController();
	}

	private function prepModel()
	{
	}

	private function prepView()
	{
	}
	private function prepController()
	{
		$this->controller->registerCommand( new InitCommand() );
		$this->controller->registerCommand( new AdminInitCommand() );
	}

	public static function setLandingPageForTerm( $termTaxonomyID, $pageID )
	{
		$pages = get_pages(array(
			"meta_key" => self::TERM_PAGE_META_KEY_TERM_TAXONOMY_ID,
			"meta_value" => $termTaxonomyID,
			"hierarchical" => FALSE
		));

		foreach($pages as $page)
		{
			delete_post_meta( $page->ID, self::TERM_PAGE_META_KEY_TERM_TAXONOMY_ID );
		}

		return add_post_meta(  $pageID, self::TERM_PAGE_META_KEY_TERM_TAXONOMY_ID, $termTaxonomyID );
	}
	public static function getLandingPageForTerm( $termTaxonomyID )
	{
		$page = get_pages(array(
			"meta_key" => self::TERM_PAGE_META_KEY_TERM_TAXONOMY_ID,
			"meta_value" => $termTaxonomyID,
			"hierarchical" => FALSE
		));
		if(is_array($page)) $page = array_pop($page);
		if(is_array($page) && !count($page)) return NULL;

		return $page;
	}

	public static function getTermForLandingPage( $pageID )
	{
		global $wpdb;

		$termTaxonomyID = get_post_meta( $pageID, self::TERM_PAGE_META_KEY_TERM_TAXONOMY_ID, TRUE );

		if($termTaxonomyID)
		{
			$taxonomy = $wpdb->get_row(
			            $wpdb->prepare(
			                "SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = %d LIMIT 1",
			                $termTaxonomyID
			            )
			        );
			if($taxonomy) return get_term_by( "term_taxonomy_id", $termTaxonomyID, $taxonomy->taxonomy );
		}

		return NULL;
	}

	public function addTaxonomy( $taxonomyName )
	{
		if(!$this->hasTaxonomy( $taxonomyName )) $this->_supportedTaxonomies[$taxonomyName] = $taxonomyName;

		return $this;
	}
	public function removeTaxonomy( $taxonomyName )
	{
		if($this->hasTaxonomy( $taxonomyName )) unset( $this->_supportedTaxonomies[$taxonomyName] );

		return $this;
	}
	public function hasTaxonomy( $taxonomyName )
	{
		return in_array( $taxonomyName, $this->_supportedTaxonomies );
	}
	public function getTaxonomies()
	{
		return $this->_supportedTaxonomies;
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
