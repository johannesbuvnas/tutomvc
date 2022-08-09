<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 01/12/15
	 * Time: 09:53
	 */

	namespace TutoMVC\WordPress\System;


	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\TutoMVC;

	class SystemApp
	{
		public static function getInstance()
		{
			return Facade::getInstance( TutoMVC::NAME );
		}
	}
