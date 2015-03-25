<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 11/03/15
	 * Time: 11:58
	 */

	namespace tutomvc\wp\posttype;

	use tutomvc\Facade;

	class PostTypeModule
	{
		public static function getInstance()
		{
			return Facade::getInstance( PostTypeModuleFacade::KEY ) ? Facade::getInstance( PostTypeModuleFacade::KEY ) : new PostTypeModuleFacade();
		}
	}