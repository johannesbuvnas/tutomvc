<?php
	use tutomvc\MetaBox;

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 25/11/14
	 * Time: 13:55
	 */
	class MetaBoxVO
	{
		protected $_objectID;
		protected $_metaBox;
		protected $_meta;
		protected $_errors  = array();
		protected $_notices = array();

		public function __construct( $objectID, MetaBox $metaBox )
		{
			$this->_objectID = intval( $objectID );
			$this->_metaBox  = $metaBox;
			$meta            = (array)get_post_meta( $objectID, $metaBox->getName() );
			if ( count( $meta ) ) $this->_meta = array_pop( $meta );
		}

		public function test()
		{
			// Clear history
			$this->clearErrors();
			$this->clearNotices();

			return $this;
		}

		protected function addError( $metaFieldName, $body = NULL )
		{
			add_post_meta( $objectID, MetaBox::constructMetaKey( $this->getMetaBox()->getName(), "error" ), $metaFieldName );
			add_post_meta( $objectID, MetaBox::constructMetaKey( $this->getMetaBox()->getName(), "{$metaFieldName}_error" ), $body );

			$this->_errors[ ] = new \tutomvc\Notification( $body, \tutomvc\Notification::TYPE_ERROR );
		}

		public function clearErrors()
		{
			delete_post_meta( $this->getObjectID(), MetaBox::constructMetaKey( $this->getMetaBox()->getName(), "error" ) );
			foreach ( $this->getMetaBox()->getFields() as $metaField )
			{
				delete_post_meta( $this->getObjectID(), MetaBox::constructMetaKey( $this->getMetaBox()->getName(), $metaField->getName() . "_error" ) );
			}
		}

		public function addNotice( $metaFieldName, $body = NULL )
		{
			add_post_meta( $objectID, MetaBox::constructMetaKey( $this->getMetaBox()->getName(), "notice" ), $metaFieldName );
			add_post_meta( $objectID, MetaBox::constructMetaKey( $this->getMetaBox()->getName(), "{$metaFieldName}_notice" ), $body );

			$this->_notices[ ] = new \tutomvc\Notification( $body );
		}

		public function clearNotices()
		{
			delete_post_meta( $this->getObjectID(), MetaBox::constructMetaKey( $this->getMetaBox()->getName(), "notice" ) );
			foreach ( $this->getMetaBox()->getFields() as $metaField )
			{
				delete_post_meta( $this->getObjectID(), MetaBox::constructMetaKey( $this->getMetaBox()->getName(), $metaField->getName() . "_notice" ) );
			}
		}

		/* SET AND GET*/
		public function getMeta( $metaFieldName = NULL )
		{
			if ( $fieldName )
			{
				if ( array_key_exists( $metaFieldName, $this->_meta ) ) return $this->_meta[ $metaFieldName ];
				else return NULL;
			}

			return $this->_meta;
		}

		public function getErrors()
		{
			return $this->_errors;
		}

		public function getNotices()
		{
			return $this->_notices;
		}

		public function getObjectID()
		{
			return $this->_objectID;
		}

		public function getMetaBox()
		{
			return $this->_metaBox;
		}
	}