<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 14:02
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\core\form\groups\FissileFormGroup;

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
		 * @see https://codex.wordpress.org/Function_Reference/add_meta_box
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
			$this->validate();
			$meta = MetaBoxModule::getProxy()->getPostMetaByMetaKey( $post->ID, $this->getName(), TRUE );
			$this->setFissions( $meta );
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
			$int = MetaBoxModule::getProxy()->getPostMetaFromDB( $postID, $this->getName() );

			return intval( $int );
		}

		public function update( $postID )
		{
			$this->clear( $postID );

			$map = $this->getFlattenFissions();

			// get_metadata is executed when update_post_meta
			add_post_meta( $postID, $this->getName(), count( $map ) );

			if ( count( $map ) )
			{
				foreach ( $map as $fission )
				{
					foreach ( $fission as $key => $value )
					{
						if ( !empty( $value ) )
						{
							if ( is_array( $value ) )
							{
								foreach ( $value as $nestedValue )
								{
									add_post_meta( $postID, $key, $nestedValue );
								}
							}
							else
							{
								add_post_meta( $postID, $key, $value );
							}
						}
					}
				}
			}

			return $this;
		}

		public function clear( $postID )
		{
			$prevValue = $this->_value;

			$int = $this->countFissions( $postID );
			$this->setFissions( $int );
			delete_post_meta( $postID, $this->getName() );
			$map = $this->getFlattenFissions();
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