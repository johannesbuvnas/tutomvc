<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/08/15
	 * Time: 15:47
	 */

	namespace tutomvc\wp\taxonomy;

	use tutomvc\core\form\inputs\Select2FormInput;
	use tutomvc\core\model\NameObject;
	use tutomvc\wp\core\model\vo\WPAdminColumn;

	class Taxonomy extends NameObject
	{
		const CAPABILITY_CREATE_TERMS = "cap_create_terms";
		protected $_objectTypes = array();
		protected $_args        = array();
		protected $_columnsMap  = array();

		function __construct( $name, $objectTypes, $args )
		{
			parent::__construct( $name );
			$this->setObjectTypes( is_string( $objectTypes ) ? array($objectTypes) : $objectTypes );
			$this->setArgs( $args );
		}

		public function wp_register()
		{
			if ( (is_null( $this->getArg( "hierarchical" ) ) || $this->getArg( "hierarchical" ) == FALSE) && is_null( $this->getArg( "meta_box_cb" ) ) )
			{
				$this->setArg( "meta_box_cb", array($this, "wp_render") );
			}

			register_taxonomy( $this->getName(), $this->getObjectTypes(), $this->getArgs() );
			add_filter( "manage_edit-" . $this->getName() . "_columns", array($this, "wp_filter_manage_columns") );
			add_action( "manage_" . $this->getName() . "_custom_column", array(
				$this,
				"wp_action_manage_custom_column"
			), 1, 3 );

			return $this;
		}

		public function wp_render( $post, $taxonomy )
		{
			$taxonomyVO  = get_taxonomy( $this->getName() );
			$tagSelector = new Select2FormInput( $this->getName(), $taxonomyVO->labels->menu_name, NULL, FALSE, !current_user_can( $taxonomyVO->cap->assign_terms ) );
			$tagSelector->setParentName( "tax_input" );
			if ( property_exists( $taxonomyVO->cap, Taxonomy::CAPABILITY_CREATE_TERMS ) ? current_user_can( Taxonomy::CAPABILITY_CREATE_TERMS ) : current_user_can( $taxonomyVO->cap->manage_terms ) )
			{
				$tagSelector->setSelect2Options( array(
					"tags" => TRUE
				) );
			}
			$terms = get_terms( $this->getName(), array(
				"hide_empty" => FALSE
			) );
			foreach ( $terms as $termVO )
			{
				$tagSelector->addOption( $termVO->name, $termVO->name );
			}

			$terms = wp_get_post_terms( $post->ID, $this->getName() );
			$value = array();
			foreach ( $terms as $termVO )
			{
				$value[] = (string)$termVO->name;
			}
			$tagSelector->setValue( $value );

			echo $tagSelector->getFormElement();
		}

		public function wp_filter_manage_columns( $columns )
		{
			/** @var TaxonomyColumn $taxonomyColumn */
			foreach ( $this->_columnsMap as $taxonomyColumn )
			{
				$columns[ $taxonomyColumn->getName() ] = $taxonomyColumn->getTitle();
			}

			return $columns;
		}

		public function wp_action_manage_custom_column( $out, $columnName, $termID )
		{
			if ( $this->getColumn( $columnName ) )
			{
				$this->getColumn( $columnName )->render( $this->getName(), $termID );
			}
		}

		public function addColumn( WPAdminTaxonomyColumn $column )
		{
			$this->_columnsMap[ $column->getName() ] = $column;

			return $this;
		}

		/**
		 * @param $columnName
		 *
		 * @return null|WPAdminColumn
		 */
		public function getColumn( $columnName )
		{
			if ( array_key_exists( $columnName, $this->_columnsMap ) ) return $this->_columnsMap[ $columnName ];

			return NULL;
		}

		/**
		 * @return array
		 */
		public function getObjectTypes()
		{
			return $this->_objectTypes;
		}

		/**
		 * @param array $objectTypes
		 */
		public function setObjectTypes( $objectTypes )
		{
			$this->_objectTypes = $objectTypes;
		}

		/**
		 * @return array
		 */
		public function getArgs()
		{
			return $this->_args;
		}

		/**
		 * @param array $args
		 *
		 * @see https://codex.wordpress.org/Function_Reference/register_taxonomy
		 * @return $this
		 */
		public function setArgs( $args )
		{
			$this->_args = $args;

			return $this;
		}

		/**
		 * @param string $arg
		 * @param mixed $value
		 *
		 * @return $this
		 */
		public function setArg( $arg, $value )
		{
			$this->_args[ $arg ] = $value;

			return $this;
		}

		/**
		 * @param $arg
		 *
		 * @return mixed
		 */
		public function getArg( $arg )
		{
			return isset($this->_args[ $arg ]) ? $this->_args[ $arg ] : NULL;
		}
	}