<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/11/14
	 * Time: 14:03
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Facade;

	class GitModule
	{
		const FACADE_KEY                   = "tutomvc/modules/git/facade";

		public static function getInstance()
		{
			if ( Facade::getInstance( self::FACADE_KEY ) )
			{
				return Facade::getInstance( self::FACADE_KEY );
			}

			$moduleFacade = new GitModuleFacade( self::FACADE_KEY );
			$systemFacade = Facade::getInstance( Facade::KEY_SYSTEM );

			return $systemFacade->registerSubFacade( $moduleFacade );
		}
	}