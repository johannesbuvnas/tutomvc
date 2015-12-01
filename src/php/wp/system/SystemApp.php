<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 01/12/15
	 * Time: 09:53
	 */

	namespace tutomvc;

	class SystemApp
	{
		public static function getInstance()
		{
			return Facade::getInstance( TutoMVC::NAME );
		}
	}