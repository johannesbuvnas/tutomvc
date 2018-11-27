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
	}