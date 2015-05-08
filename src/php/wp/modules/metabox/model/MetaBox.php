<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 14:02
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\FissileFormGroup;

	class MetaBox extends FissileFormGroup
	{
		/* CONSTANTS */
		const CONTEXT_NORMAL   = "normal";
		const CONTEXT_ADVANCED = "advanced";
		const CONTEXT_SIDE     = "side";
		const PRIORITY_HIGH    = "high";
		const PRIORITY_CORE    = "core";
		const PRIORITY_DEFAULT = "default";
		const PRIORITY_LOW     = "low";

		/* VARS */
		protected $_postTypes;
		protected $_context;
		protected $_priority;

		/**
		 * @param string $name
		 * @param string $title
		 * @param string $description
		 * @param string|array $postTypes
		 * @param int $min
		 * @param int $max
		 * @param string $context
		 * @param string $priority
		 *
		 * @throws \ErrorException
		 */
		function __construct( $name, $title, $description, $postTypes, $min = 0, $max = - 1, $context = MetaBox::CONTEXT_NORMAL, $priority = MetaBox::PRIORITY_DEFAULT )
		{
			parent::__construct( $name, $title, $description, $min, $max );

			$this->_postTypes = is_string( $postTypes ) ? array($postTypes) : $postTypes;
			if ( !is_array( $this->_postTypes ) ) throw new \ErrorException( 'MetaBox: $postTypes isnt array|string' );
			$this->setContext( $context );
			$this->setPriority( $priority );
		}

		/* PUBLIC METHODS */
		public function render( $post, $metabox )
		{
			$meta = get_post_meta( $post->ID, $this->getName() );
			$this->setValue( $meta );
			echo $this->getElement();

			return $this;
		}

		public function filter( $postID, $metaKey, $metaValue )
		{
			return $metaValue;
		}

		/**
		 * @param $postID
		 *
		 * @return int
		 */
		public function countFissions( $postID )
		{
			$int = GetPostMetadataFilter::getDBMetaValue( $postID, $this->getName() );

			return intval( $int );
		}

		public function update( $postID )
		{
			// Clear old first
			$this->clear( $postID );

			$map = $this->getFlatValue();

			// get_metadata is executed when updating post meta
			GetPostMetadataFilter::$doNotExecute = TRUE;
			update_post_meta( $postID, $this->getName(), count( $map ) );

			if ( count( $map ) )
			{
				foreach ( $map as $clone )
				{
					foreach ( $clone as $key => $value )
					{
						update_post_meta( $postID, $key, $value );
					}
				}
			}
			GetPostMetadataFilter::$doNotExecute = FALSE;

			return $this;
		}

		public function clear( $postID )
		{
			$prevValue = $this->_value;

			$int = $this->countFissions( $postID );
			$this->setValue( $int );
			delete_post_meta( $postID, $this->getName() );
			$map = $this->getFlatValue();
			foreach ( $map as $clone )
			{
				foreach ( $clone as $key => $value )
				{
					delete_post_meta( $postID, $key );
				}
			}

			$this->_value = $prevValue;

			return $this;
		}

		/* SET AND GET */
		/**
		 * @return array
		 */
		public function getPostTypes()
		{
			return $this->_postTypes;
		}

		/**
		 * @return mixed
		 */
		public function getContext()
		{
			return $this->_context;
		}

		/**
		 * @param mixed $context
		 */
		public function setContext( $context )
		{
			$this->_context = $context;
		}

		/**
		 * @return mixed
		 */
		public function getPriority()
		{
			return $this->_priority;
		}

		/**
		 * @param mixed $priority
		 */
		public function setPriority( $priority )
		{
			$this->_priority = $priority;
		}
	}