<?php

	namespace tutomvc\wp\metabox;

	use tutomvc\core\form\groups\FissileFormGroup;

	class UserMetaBox extends FissileFormGroup
	{
		/**
		 * @param \WP_User $user
		 *
		 * @throws \ErrorException
		 */
		public function render( $user )
		{
			$this->validate();
			$meta = MetaBoxModule::getUserProxy()->getUserMetaByMetaKey( $user->ID, $this->getName(), TRUE );
			$this->setFissions( $meta );
			$this->output();
		}

		/**
		 * @param $userID
		 *
		 * @return int
		 */
		public function countFissions( $userID )
		{
			$int = MetaBoxModule::getUserProxy()->getUserMetaFromDB( $userID, $this->getName() );

			return intval( $int );
		}

		/**
		 * Saves current fissions to the WordPress usermeta.
		 *
		 * @param $userID
		 *
		 * @return $this
		 * @throws \ErrorException
		 */
		public function update( $userID )
		{
			$this->clear( $userID );

			$map = $this->getFlatFissions();

			add_user_meta( $userID, $this->getName(), $this->count() );

			if ( count( $map ) )
			{
				foreach ( $map as $key => $value )
				{
					if ( !empty( $value ) )
					{
						if ( is_array( $value ) )
						{
							foreach ( $value as $nestedValue )
							{
								add_user_meta( $userID, $key, $nestedValue );
							}
						}
						else
						{
							add_user_meta( $userID, $key, $value );
						}
					}
				}
			}

			return $this;
		}

		public function clear( $userID )
		{
			$prevValue = $this->_value;

			$int = $this->countFissions( $userID );
			$this->setFissions( $int );
			delete_user_meta( $userID, $this->getName() );
			$map = $this->getFlatFissions();
			foreach ( $map as $key => $value )
			{
				delete_user_meta( $userID, $key );
			}

			$this->_value = $prevValue;

			return $this;
		}
	}